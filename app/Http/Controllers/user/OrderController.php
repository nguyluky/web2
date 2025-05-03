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
            'account_id' => 'required|integer|exists:accounts,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'products.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'payment_method' => 'required|string',
            'products' => 'required|array',
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
    public function checkOrderStatus(Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);
    
        $order = Order::where('id', $validated['order_id'])
            ->where('account_id', auth()->user()->id)
            ->firstOrFail();
    
        return response()->json(['status' => $order->status]);
    }

    // 3.8. Lấy thông tin vận chuyển
    public function getDeliveryInfo() {
        // TODO
        // Tú chưa hiểu lắm về thông tin vận chuyển
    }

    // 3.9. Mua ngay sản phẩm
    public function buyNow(Request $request) {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'products.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'payment_method' => 'required|string',
            'products' => 'required|array',
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

    // 5.1. Lấy danh sách đơn hàng của người dùng
    public function getAll() {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $orders = Order::where('account_id', $user->id)->get();
    
        return response()->json(['orders' => $orders]);
    }
    // 5.2. Lấy chi tiết đơn hàng
    public function getDetailOrder(Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);
    
        $order = Order::with('orderDetails') // tải hiện chi tiết đơn hàng
            ->where('id', $validated['order_id'])
            ->where('account_id', auth()->user()->id)
            ->firstOrFail();
    
        return response()->json(['order' => $order]);
    }

    // 5.3. Hủy đơn hàng
    public function cancelOrder() {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::where('id', $validated['order_id'])
        ->where('account_id', auth()->user()->id)
        ->firstOrFail();
        if ($order->status !== 'pending') {
            return response()->json(['error' => 'Cannot cancel non-pending order'], 422);
        }
    
        $order->status = 'cancelled';
        $order->save();
    
        return response()->json(['message' => 'Đã hủy đơn hàng thành công']);
    }
}
