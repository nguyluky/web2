<?php

namespace App\Http\Controllers\admin;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderDetails extends Controller
{

    public function getAll(Request $request)
    {   
        $limit = $request->query('limit', 10);
        $query = OrderDetail::query();
        $order_details = $query->paginate($limit);
      
        return response()->json([
            'message' => 'Lấy danh sách chi tiết đơn hàng thành công',
            'data' => $order_details,
            'limit' => $limit
        ]);
    }

    public function create(Request $request)
    {

        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'amount' => 'required|integer|min:1',
        ]);

        // Tạo chi tiết đơn hàng mới
        $order_detail = OrderDetail::create($validated);

        return response()->json([
            'message' => 'Tạo chi tiết đơn hàng thành công',
            'data' => $order_detail
        ], 201);
    }

    public function getById(Request $request, $id)
    {
        // Tìm chi tiết đơn hàng theo ID
        $order_detail = OrderDetail::find($id);

        // Kiểm tra xem chi tiết đơn hàng có tồn tại không
        if (!$order_detail) {
            return response()->json([
                'message' => 'Không tìm thấy chi tiết đơn hàng'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy chi tiết đơn hàng thành công',
            'data' => $order_detail
        ]);
    }
    //3.2 Lấy
}