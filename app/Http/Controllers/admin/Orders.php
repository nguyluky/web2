<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
                ->orWhere('account_id', 'like', '%' . $search . '%')
                ->orWhere('employee_id', 'like', '%' . $search . '%');
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


    // public function create(Request $request)
    // {
    //     $validated = $request->validate([
    //         'account_id' => 'required|integer|exists:accounts,id', // Kiểm tra account_id tồn tại trong bảng accounts
    //         'status' => 'required|in:pending,processing,shipped,delivered,cancelled', // Giả sử các giá trị ENUM
    //         'employee_id' => 'nullable|integer|exists:employees,id', // employee_id có thể null, nếu có thì phải tồn tại
    //         'payment_method' => 'required|in:cash,credit_card,paypal', // Giả sử các giá trị ENUM
    //     ]);


    //     $order = Order::create($validated);

    //     return response()->json([
    //         'message' => 'Tạo đơn hàng thành công',
    //         'data' => $order
    //     ], 201);
    // }


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

    //3.3 Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled', // Giả sử các giá trị ENUM
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        $order->update($validated);

        return response()->json([
            'message' => 'Cập nhật trạng thái đơn hàng thành công',
            'data' => $order
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
}
