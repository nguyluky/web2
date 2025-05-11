<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Order;
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
        
        $query = Order::query();
        if ($search) {
            $query->where('id', 'like', '%' . $search . '%')
                ->orWhere('payment_method', 'like', '%' . $search . '%');
        }
        if ($account_id) {
            $query->where('account_id', $account_id);
        }
        if ($employee_id) {
            $query->where('employee_id', $employee_id);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($date_start && $date_end) {
            $query->whereBetween('created_at', [$date_start, $date_end]);
        }
        if ($date_start && !$date_end) {
            $query->where('created_at', '>=', $date_start);
        }
        if (!$date_start && $date_end) {
            $query->where('created_at', '<=', $date_end);
        }

        $orders = $query->paginate($limit);
        $orders->appends([
            'search' => $search,
            'account_id' => $account_id,
            'employee_id' => $employee_id,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);

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
    public function updateStatus(Request $request, $id)
    {
        try {
            \Log::info('Received update status request', [
                'order_id' => $id,
                'status_input' => $request->status
            ]);
    
            $order = Order::findOrFail($id);
    
            // Validate the status field
            $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
            ]);
    
            // Update the order status
            $order->status = $request->status;
            $order->save();
    
            \Log::info('Order status updated successfully', [
                'order_id' => $order->id,
                'new_status' => $order->status
            ]);
    
            return response()->json([
                'message' => 'Status updated successfully',
                'order' => $order,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed when updating order status', [
                'order_id' => $id,
                'errors' => $e->errors(),
            ]);
    
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to update order status', [
                'order_id' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'message' => 'Failed to update status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
