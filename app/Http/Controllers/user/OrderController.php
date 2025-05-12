<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Routing\Controller;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints quản lý đơn hàng"
 * )
 */
class OrderController extends Controller
{
    public function createOrders(Request $request)
    {
        $validated = $request->validate([
            'products.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'payment_method' => 'required|integer',
            'products' => 'required|array',
            'address_id' => 'required|integer|exists:address,id',
        ]);

        $profile_id = auth()->user()->id;
        $validated['profile_id'] = $profile_id;

        // try {
        $order = DB::transaction(function () use ($validated) {
            $order_data = [
                'profile_id' => $validated['profile_id'],
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                // 'created_at' => Carbon::now(),
                'address_id' => $validated['address_id'],
            ];

            $order = Order::create($order_data);

            foreach ($validated['products'] as $product) {
                $order_detail = [
                    'order_id' => $order->id,
                    'product_variant_id' => $product['product_variant_id'],
                    'serial' => rand(),
                ];
                OrderDetail::create($order_detail);
            }
            return $order;
        });
        return response()->json(['order' => $order], 201);
    }

    public function checkOrderStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::where('id', $validated['order_id'])
            ->where('profile_id', auth()->user()->id)
            ->firstOrFail();

        return response()->json(['status' => $order->status]);
    }

    public function getDeliveryInfo()
    {
        // TODO
        // Tú chưa hiểu lắm về thông tin vận chuyển
    }

    public function buyNow(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:profile,id',
            'products.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'payment_method' => 'required|integer|exists:payment,id',
            'products' => 'required|array',
            'address_id' => 'required|integer|exists:address,id',
        ]);

        DB::transaction(function () use ($validated) {
            $order_data = [
                'profile_id' => $validated['profile_id'],
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'created_at' => Carbon::now(),
                'id' => Order::max('id') + 1,
                'address_id' => $validated['address_id'],
            ];

            $order = Order::create($order_data);

            foreach ($validated['products'] as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $product['product_variant_id'],
                    'serial' => rand(),
                ]);
            }
            return response()->json(['order' => $order], 201);
        });
    }

    public function getUserOrders(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $status = $request->query('status');

        $query = Order::where('profile_id', $user->id);
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
        return response()->json($orders, 200);
    }

    public function getOrderDetail(int $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Check if order belongs to the user
        $order = Order::where('id', $id)->where('profile_id', $user->id)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found or unauthorized'], 404);
        }
        
        $orderDetails = OrderDetail::where('order_id', $id)
            ->with(['product_variant.product', 'product_variant.product.product_images'])
            ->get()
            ->map(function ($detail) use ($order) {
                return [
                    'id' => $detail->id,
                    'order_id' => $detail->order_id,
                    'product' => $detail->product_variant->product,
                    'variant' => $detail->product_variant,
                    'amount' => 1, // Since there's no amount field, defaulting to 1
                    'price' => $detail->product_variant->price,
                    'created_at' => $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                    'updated_at' => $order->updated_at ? $order->updated_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json(['orderDetail' => $orderDetails], 200);
    }

    public function cancelOrder(int $id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->profile_id !== $user->id || $order->status == 'completed') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return response()->json($order, 200);
    }
}
