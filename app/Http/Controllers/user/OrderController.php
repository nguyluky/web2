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
    /**
     * @OA\Post(
     *     path="/api/orders",
     *     operationId="createOrder",
     *     tags={"Orders"},
     *     summary="Tạo đơn hàng mới",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"profile_id", "products", "payment_method"},
     *             @OA\Property(property="profile_id", type="integer", description="ID của profile người dùng"),
     *             @OA\Property(property="payment_method", type="integer", description="ID của phương thức thanh toán"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="Danh sách sản phẩm trong đơn hàng",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_variant_id"},
     *                     @OA\Property(property="product_variant_id", type="integer", description="ID của biến thể sản phẩm"),
     *                     @OA\Property(property="serial", type="integer", description="Số serial của sản phẩm (sẽ được tự động tạo nếu không có)")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="order", type="object",
     *                 @OA\Property(property="id", type="integer", description="ID của đơn hàng"),
     *                 @OA\Property(property="profile_id", type="integer", description="ID của profile người dùng"),
     *                 @OA\Property(property="status", type="string", example="pending", description="Trạng thái đơn hàng"),
     *                 @OA\Property(property="payment_method", type="integer", description="ID của phương thức thanh toán"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Thời gian tạo đơn hàng"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Thời gian cập nhật đơn hàng gần nhất")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="profile_id", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="payment_method", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="products", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="products.*.product_variant_id", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function createOrders(Request $request) {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:profile,id',
            'products.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'payment_method' => 'required|integer|exists:payment,id',
            'products' => 'required|array',
        ]);

        DB::transaction(function () use ($validated) {
            $order_data = [
                'profile_id' => $validated['profile_id'],
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'created_at' => Carbon::now(),
                'id' => Order::max('id') + 1,
            ];

            $order = Order::create($order_data);

            foreach($validated['products'] as $product) {
                $order_detail = [
                    'id' => OrderDetail::max('id') + 1,
                    'order_id' => $order->id,
                    'product_variant_id' => $product['product_variant_id'],
                    'serial' => rand(),
                ];
                OrderDetail::create($order_detail);
            }
            return response()->json(['order' => $order], 201);
        });
    }

    /**
     * @OA\Post(
     *     path="/api/orders/check-status",
     *     operationId="checkOrderStatus",
     *     tags={"Orders"},
     *     summary="Kiểm tra tình trạng thanh toán của đơn hàng",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_id"},
     *             @OA\Property(property="order_id", type="integer", description="ID của đơn hàng cần kiểm tra")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Not found")
     *         )
     *     )
     * )
     */
    public function checkOrderStatus(Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::where('id', $validated['order_id'])
            ->where('profile_id', auth()->user()->id)
            ->firstOrFail();

        return response()->json(['status' => $order->status]);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/delivery-info",
     *     operationId="getDeliveryInfo",
     *     tags={"Orders"},
     *     summary="Lấy thông tin vận chuyển",
     *     description="API này chưa được triển khai đầy đủ",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Chức năng đang được phát triển")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function getDeliveryInfo() {
        // TODO
        // Tú chưa hiểu lắm về thông tin vận chuyển
    }

    /**
     * @OA\Post(
     *     path="/api/orders/buy-now",
     *     operationId="buyNow",
     *     tags={"Orders"},
     *     summary="Mua ngay sản phẩm",
     *     description="Tạo đơn hàng mới và mua sản phẩm ngay lập tức",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"profile_id", "products", "payment_method"},
     *             @OA\Property(property="profile_id", type="integer"),
     *             @OA\Property(property="payment_method", type="integer"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_variant_id", type="integer"),
     *                     @OA\Property(property="serial", type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đặt hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="order", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="profile_id", type="integer"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_method", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function buyNow(Request $request) {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:profile,id',
            'products.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'payment_method' => 'required|integer|exists:payment,id',
            'products' => 'required|array',
        ]);

        DB::transaction(function () use ($validated) {
            $order_data = [
                'profile_id' => $validated['profile_id'],
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
                    'serial' => rand(),
                ]);
            }
            return response()->json(['order' => $order], 201);
        });
    }

    /**
     * @OA\Get(
     *     path="/api/user/orders",
     *     operationId="getUserOrders",
     *     tags={"Orders"},
     *     summary="Lấy danh sách đơn hàng của người dùng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Số trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Số lượng đơn hàng mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Lọc theo trạng thái đơn hàng",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "processing", "completed", "cancelled"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="profile_id", type="integer"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(property="payment_method", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function getUserOrders(Request $request) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $status = $request->query('status');

        $query = Order::where('profile_od', $user->id);
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate($limit, ['*'], 'page', $page);
        return response()->json($orders, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     operationId="getOrderDetail",
     *     tags={"Orders"},
     *     summary="Lấy chi tiết đơn hàng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="orderDetail",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="order_id", type="integer"),
     *                     @OA\Property(property="product_id", type="integer"),
     *                     @OA\Property(property="variant_id", type="integer"),
     *                     @OA\Property(property="amount", type="integer"),
     *                     @OA\Property(property="price", type="number", format="float"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function getOrderDetail(int $id) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $orderDetail = OrderDetail::where('order_id', $id)->get();

        return response()->json(['orderDetail' => $orderDetail], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}/cancel",
     *     operationId="cancelOrder",
     *     tags={"Orders"},
     *     summary="Hủy đơn hàng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần hủy",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hủy đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="profile_id", type="integer"),
     *             @OA\Property(property="status", type="string", example="cancelled"),
     *             @OA\Property(property="payment_method", type="integer"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Chưa xác thực",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền hủy đơn hàng này",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Order not found")
     *         )
     *     )
     * )
     */
    public function cancelOrder(int $id) {
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
