<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class Orders extends Controller
{

    public function getAll(Request $request)
    {
        $orders = Order::all();
        return response()->json([
            'message' => 'Lấy danh sách đơn hàng thành công',
            'data' => $orders
        ]);
    }


    public function create(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id', // Kiểm tra account_id tồn tại trong bảng accounts
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled', // Giả sử các giá trị ENUM
            'employee_id' => 'nullable|integer|exists:employees,id', // employee_id có thể null, nếu có thì phải tồn tại
            'payment_method' => 'required|in:cash,credit_card,paypal', // Giả sử các giá trị ENUM
        ]);


        $order = Order::create($validated);

        return response()->json([
            'message' => 'Tạo đơn hàng thành công',
            'data' => $order
        ], 201);
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
}