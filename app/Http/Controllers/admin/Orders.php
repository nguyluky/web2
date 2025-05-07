<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Admin/Orders",
 *   description="API quản lý đơn hàng dành cho Admin"
 * )
 */
class Orders extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/orders",
     *     operationId="adminGetAllOrders",
     *     tags={"Admin/Orders"},
     *     summary="Lấy danh sách đơn hàng",
     *     description="Lấy danh sách tất cả đơn hàng với các tùy chọn lọc và phân trang",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Từ khóa tìm kiếm",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="account_id",
     *         in="query",
     *         description="Lọc theo ID tài khoản",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="employee_id",
     *         in="query",
     *         description="Lọc theo ID nhân viên",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="payment_method",
     *         in="query",
     *         description="Lọc theo phương thức thanh toán",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Lọc theo trạng thái đơn hàng",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "processing", "shipped", "delivered", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="date_start",
     *         in="query",
     *         description="Lọc từ ngày (format: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_end",
     *         in="query",
     *         description="Lọc đến ngày (format: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Số lượng bản ghi trên một trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách đơn hàng được lấy thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy danh sách đơn hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="account_id", type="integer"),
     *                         @OA\Property(property="employee_id", type="integer"),
     *                         @OA\Property(property="status", type="string"),
     *                         @OA\Property(property="payment_method", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="last_page_url", type="string"),
     *                 @OA\Property(property="links", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không được phép truy cập",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/admin/orders/{id}",
     *     operationId="adminGetOrderById",
     *     tags={"Admin/Orders"},
     *     summary="Lấy thông tin đơn hàng theo ID",
     *     description="Lấy thông tin chi tiết của một đơn hàng dựa trên ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần lấy thông tin",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy đơn hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="account_id", type="integer"),
     *                 @OA\Property(property="employee_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="payment_method", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy đơn hàng")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không được phép truy cập",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/admin/orders/{id}/details",
     *     operationId="adminGetOrderDetails",
     *     tags={"Admin/Orders"},
     *     summary="Lấy chi tiết đơn hàng theo ID",
     *     description="Lấy danh sách các sản phẩm trong đơn hàng dựa trên ID đơn hàng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần lấy chi tiết",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy chi tiết đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy chi tiết đơn hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="order_id", type="integer"),
     *                     @OA\Property(property="product_variant_id", type="integer"),
     *                     @OA\Property(property="serial", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy đơn hàng")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không được phép truy cập",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/admin/orders/{id}/status",
     *     operationId="adminUpdateOrderStatus",
     *     tags={"Admin/Orders"},
     *     summary="Cập nhật trạng thái đơn hàng",
     *     description="Cập nhật trạng thái của một đơn hàng dựa trên ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của đơn hàng cần cập nhật trạng thái",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"pending", "processing", "shipped", "delivered", "cancelled"},
     *                 description="Trạng thái mới của đơn hàng"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật trạng thái đơn hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật trạng thái đơn hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="account_id", type="integer"),
     *                 @OA\Property(property="employee_id", type="integer"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="payment_method", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy đơn hàng")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu đầu vào không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không được phép truy cập",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/admin/orders/{id}/cancel",
     *     operationId="adminCancelOrder",
     *     tags={"Admin/Orders"},
     *     summary="Hủy đơn hàng",
     *     description="Cập nhật trạng thái của đơn hàng thành cancelled",
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
     *             @OA\Property(property="message", type="string", example="Hủy đơn hàng thành công"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="account_id", type="integer"),
     *                 @OA\Property(property="employee_id", type="integer"),
     *                 @OA\Property(property="status", type="string", example="cancelled"),
     *                 @OA\Property(property="payment_method", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy đơn hàng",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy đơn hàng")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không được phép truy cập",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
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
