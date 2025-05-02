<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController
{
    public function createOrders(Request $request) {
        $validated = $request->validate([
            'account_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'payment_method' => 'required|string',
            'products' => 'required|array',
            'products.*.product_variant_id' => 'required|integer',
            'products.*.serial' => 'required|integer',
        ]);

        DB::transaction(function () use ($validated) {
            $order_data = [
                'account_id' => $validated['account_id'],
                'employee_id' => $validated['employee_id'],
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'created_at' => Carbon::now(),
                'id' => Order::max('id') + 1,
            ];
    
            $order = Order::create($order_data);
    
            foreach($validated['products'] as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $product['product_variant_id'],
                    'serial' => $product['serial'],
                ]);
            }    
            return response()->json(['order' => $order], 201);
        });
    }

    // 3.7. Kiểm tra tình trạng thanh toán
    public function checkOrderStatus() {
        // TODO
    }

    // 3.8. Lấy thông tin vận chuyển
    public function getDeliveryInfo() {
        // TODO
    }

    // 3.9. Mua ngay sản phẩm
    public function buyNow() {
        // TODO
    }

    // 5.1. Lấy danh sách đơn hàng
    public function getUserOrders(Request $request) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $page = $request->query('page', 1);         
        $limit = $request->query('limit', 10);
        $status = $request->query('status'); 

        $query = Order::where('account_id', $user->id);
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate($limit, ['*'], 'page', $page);
        return response()->json($orders, 200);
    }

    // 5.2. Lấy chi tiết đơn hàng
    public function getOrderDetail(int $id) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $orderDetail = OrderDetail::where('order_id', $id)->get();

        return response()->json(['orderDetail' => $orderDetail], 200);
    }

    // 5.3. Hủy đơn hàng 
    public function cancelOrder(int $id) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->account_id !== $user->id || $order->status == 'completed') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $order->update([
            'status' => 'cancelled'
        ]);
    
        return response()->json($order, 200);
    }
}
