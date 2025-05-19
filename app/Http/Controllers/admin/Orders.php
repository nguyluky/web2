<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class Orders extends Controller
{

    //3.1 Lấy danh sách đơn hàng
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $account_id = $request->query('account_id');
        $employee_id = $request->query('employee_id');
        $payment_method = $request->query('payment_method');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limit = $request->query('limit', 10);
    
        $query = Order::query()->with('profile');
    
        // Tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('profile', function ($q2) use ($search) {
                    $q2->where('fullname', 'like', '%' . $search . '%')
                       ->orWhere('email', 'like', '%' . $search . '%')
                       ->orWhere('phone_number', 'like', '%' . $search . '%');
                })
                ->orWhere('payment_method', 'like', '%' . $search . '%');
            });
        }
    
        // Lọc theo các trường
        if ($account_id) {
            $query->where('account_id', $account_id);
        }
        if ($employee_id) {
            $query->where('employee_id', $employee_id);
        }
        if ($status) {
            $query->where('status', $status);
        }
    
        // Xử lý lọc theo ngày
        if ($date_start && $date_end) {
            if ($date_start === $date_end) {
                // Lọc đúng trong 1 ngày
                $query->whereBetween('created_at', [
                    $date_start . ' 00:00:00',
                    $date_end . ' 23:59:59'
                ]);
            } else {
                // Khoảng nhiều ngày
                $query->whereBetween('created_at', [
                    $date_start . ' 00:00:00',
                    $date_end . ' 23:59:59'
                ]);
            }
        } elseif ($date_start && !$date_end) {
            $query->where('created_at', '>=', $date_start . ' 00:00:00');
        } elseif (!$date_start && $date_end) {
            $query->where('created_at', '<=', $date_end . ' 23:59:59');
        }
    
        // Phân trang và trả về kết quả
        $orders = $query->paginate($limit);
        $orders->appends($request->all());
    
        return response()->json([
            'message' => 'Lấy danh sách đơn hàng thành công',
            'data' => $orders
        ]);
    }
    

    public function getById(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy đơn hàng thành công',
            'data' => $order
        ]);
    }


    //3.2 Lấy chi tiết đơn hàng theo id
    public function getOrderDetails(Request $request, $id)
    {
        $order = Order::with('order_details')->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy chi tiết đơn hàng thành công',
            'data' => $order->order_details
        ]);
    }

 


    //3.4 Hủy đơn hàng
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Hủy đơn hàng thành công',
            'data' => $order
        ]);
    }



    // update order 
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($orderId);
        $newStatus = $request->status;

        // Không cho phép cập nhật nếu đơn hàng đã completed hoặc cancelled
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return response()->json([
                'error' => 'Cannot update status. Order is already completed or cancelled.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Nếu status mới là cancelled thì hoàn lại stock
            if ($newStatus === 'cancelled') {
                $orderDetails = OrderDetail::where('order_id', $orderId)->get();
            
                $productVariantCounts = [];
            
                foreach ($orderDetails as $detail) {
                    $productId = $detail->product_variant_id;
                    $productVariantCounts[$productId] = ($productVariantCounts[$productId] ?? 0) + 1;
                }
            
                foreach ($productVariantCounts as $productId => $qty) {
                    $variant = ProductVariant::findOrFail($productId);
                    $newStock = $variant->stock + $qty;
                
                    // Dùng update() thay vì save()
                    $variant->update(['stock' => $newStock]);
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => $newStatus]);

            DB::commit();

            return response()->json(['message' => 'Order status updated successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to update order status.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

      public function getCurrentMonthOrders(Request $request)
    {
        // Get current month's start and end dates
        $startOfMonth = Carbon::now()->startOfMonth()->toDateTimeString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateTimeString();
    
        $query = Order::query()->with('profile')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
    
        // Apply filters if provided
        if ($account_id = $request->query('account_id')) {
            $query->where('account_id', $account_id);
        }
        if ($employee_id = $request->query('employee_id')) {
            $query->where('employee_id', $employee_id);
        }
    
        // Get all results without pagination
        $orders = $query->get();
    
        return response()->json([
            'message' => 'Lấy danh sách đơn hàng hoàn thành tháng hiện tại thành công',
            'data' => $orders
        ]);
    }
    
    /**
     * Get all completed orders for the previous month
     */
    public function getPreviousMonthOrders(Request $request)
    {
        // Get previous month's start and end dates
        $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth()->toDateTimeString();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();
    
        $query = Order::query()->with('profile')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth]);
    
        // Apply filters if provided
        if ($account_id = $request->query('account_id')) {
            $query->where('account_id', $account_id);
        }
        if ($employee_id = $request->query('employee_id')) {
            $query->where('employee_id', $employee_id);
        }
    
        // Get all results without pagination
        $orders = $query->get();
    
        return response()->json([
            'message' => 'Lấy danh sách đơn hàng hoàn thành tháng trước thành công',
            'data' => $orders
        ]);
    }
}
