<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Profile;
use App\Models\Account;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Statistical extends Controller
{
    public function topCustomersByDateRange(Request $request)
    {
        // Validate input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'sort' => 'in:asc,desc',
        ]);

        // Get query parameters
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $sortOrder = $request->query('sort', 'desc');

        // Adjust end_date to include the full day
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        // Query to get top 5 customers with their orders
        $customers = Order::select(
            'profile.id as profile_id',
            'profile.fullname as customer_name',
            DB::raw('SUM(product_variants.price) as total_purchase')
        )
            ->join('order_detail', 'order.id', '=', 'order_detail.order_id')
            ->join('product_variants', 'order_detail.product_variant_id', '=', 'product_variants.id')
            ->join('account', 'order.account_id', '=', 'account.id')
            ->leftJoin('profile', 'profile.id', '=', 'account.id')
            ->where('order.status', 'completed')
            ->whereBetween('order.created_at', [$startDate, $endDate])
            ->groupBy('profile.id', 'profile.fullname')
            ->orderBy('total_purchase', $sortOrder)
            ->limit(5)
            ->get();

        // Map results to include orders for each customer
        $customerData = $customers->map(function ($customer) use ($startDate, $endDate) {
            // Get orders for this customer
            $orders = Order::select(
                'order.id as order_id',
                'order.created_at',
                DB::raw('SUM(product_variants.price) as order_total')
            )
                ->join('order_detail', 'order.id', '=', 'order_detail.order_id')
                ->join('product_variants', 'order_detail.product_variant_id', '=', 'product_variants.id')
                ->where('order.account_id', $customer->profile_id)
                ->where('order.status', 'completed')
                ->whereBetween('order.created_at', [$startDate, $endDate])
                ->groupBy('order.id', 'order.created_at')
                ->get()
                ->map(function ($order) {
                    return [
                        'order_id' => $order->order_id,
                        'created_at' => $order->created_at, // Sửa lỗi: Trả trực tiếp chuỗi created_at
                        'order_total' => (float) $order->order_total,
                    ];
                });

            return [
                'customer_id' => $customer->profile_id,
                'customer_name' => $customer->customer_name ?? 'Unknown',
                'total_purchase' => (float) $customer->total_purchase ?? 0,
                'orders' => $orders,
            ];
        });

        return response()->json($customerData);
    }
    
    public function getOrdersByCustomer(Request $request)
{
    $request->validate([
        'account_id' => 'required|integer',
        'start_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
    ]);

    $accountId = $request->query('account_id');
    $startDate = $request->query('start_date');
    $endDate = date('Y-m-d 23:59:59', strtotime($request->query('end_date')));

    $orders = Order::select(
        'order.id',
        'order.created_at',
        DB::raw('SUM(product_variants.price) as total_price')
    )
        ->join('order_detail', 'order.id', '=', 'order_detail.order_id')
        ->join('product_variants', 'order_detail.product_variant_id', '=', 'product_variants.id')
        ->where('order.account_id', $accountId)
        ->where('order.status', 'completed')
        ->whereBetween('order.created_at', [$startDate, $endDate])
        ->groupBy('order.id', 'order.created_at')
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'total_price' => (float) $order->total_price,
            ];
        }),
        'message' => $orders->isEmpty() ? 'No orders found' : null
    ]);
}

public function getOrderDetails(Request $request)
{
    $request->validate([
        'order_id' => 'required|integer|exists:order,id',
    ]);

    $orderId = $request->query('order_id');

    $orderDetails = OrderDetail::select(
        'order_detail.id',
        'product.name as product_name',
        'product_variants.price',
        'product_variants.attributes'
    )
        ->join('product_variants', 'order_detail.product_variant_id', '=', 'product_variants.id')
        ->join('product', 'product_variants.product_id', '=', 'product.id')
        ->where('order_detail.order_id', $orderId)
        ->get();

    return response()->json([
        'status' => 'completed',
        'data' => $orderDetails->map(function ($detail) {
            $attributes = is_string($detail->attributes) ? json_decode($detail->attributes, true) : [];
            if (json_last_error() !== JSON_ERROR_NONE) {
                $attributes = [];
            }

            return [
                'detail_id' => $detail->id,
                'product_name' => $detail->product_name,
                'price' => (float) ($detail->price ?? 0),
                'attributes' => $attributes,
            ];
        }),
        'message' => $orderDetails->isEmpty() ? 'No order details found' : null
    ]);
}

}