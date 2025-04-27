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
}
