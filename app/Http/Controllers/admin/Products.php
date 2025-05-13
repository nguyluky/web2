<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\CreateProductsRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Admin Products",
 *     description="API Endpoints quản lý sản phẩm (admin)"
 * )
 */
class Products extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/admin/products",
     *     operationId="adminGetAllProducts",
     *     tags={"Admin Products"},
     *     summary="Lấy danh sách sản phẩm",
     *     description="Lấy danh sách sản phẩm có phân trang và bộ lọc",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên hoặc ID sản phẩm",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Lọc theo danh mục",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Lọc theo trạng thái",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date_start",
     *         in="query",
     *         description="Ngày bắt đầu (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_end",
     *         in="query",
     *         description="Ngày kết thúc (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Số lượng sản phẩm trên một trang",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="data", type="array", 
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="sku", type="string"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="slug", type="string"),
     *                         @OA\Property(property="description", type="string"),
     *                         @OA\Property(property="category_id", type="integer"),
     *                         @OA\Property(property="base_price", type="number"),
     *                         @OA\Property(property="base_original_price", type="number"),
     *                         @OA\Property(property="status", type="string"),
     *                         @OA\Property(property="specifications", type="string"),
     *                         @OA\Property(property="features", type="string"),
     *                         @OA\Property(property="meta_data", type="object", nullable=true),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="last_page_url", type="string"),
     *                 @OA\Property(property="links", type="array", 
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true),
     *                         @OA\Property(property="label", type="string"),
     *                         @OA\Property(property="active", type="boolean")
     *                     )
     *                 ),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    //1.1 Lấy danh sách sản phẩm
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limit = $request->query('limit', 10);
    
        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
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
    
        $products = $query->paginate($limit);
        $products->appends([
            'search' => $search,
            'category_id' => $categoryId,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);
    
        return response()->json([
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $products
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/products",
     *     operationId="createProduct",
     *     tags={"Admin Products"},
     *     summary="Tạo sản phẩm mới",
     *     description="Tạo một sản phẩm mới với thông tin cơ bản",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="iPhone 12"),
     *             @OA\Property(property="description", type="string", example="New iPhone 12"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="supplier_id", type="integer", example=1),
     *             @OA\Property(property="status", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tạo sản phẩm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tạo sản phẩm thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="supplier_id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tạo sản phẩm thất bại")
     *         )
     *     )
     * )
     */
    //1.2 Tạo sản phẩm
    public function create(CreateProductsRequest $request)
    {

        $validatedData = $request->validated();
        $success = Product::insert($validatedData);
        if ($success) {
            return response()->json([
                'message' => 'Tạo sản phẩm thành công',
                'data' => $validatedData
            ], 201);
        } else {
            return response()->json([
                'message' => 'Tạo sản phẩm thất bại'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/admin/products/{id}",
     *     operationId="adminGetProductById",
     *     tags={"Admin Products"},
     *     summary="Lấy chi tiết sản phẩm theo ID",
     *     description="Lấy thông tin chi tiết sản phẩm dựa trên ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lấy sản phẩm thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="supplier_id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm")
     *         )
     *     )
     * )
     */
    //1.3 Lấy sản phẩm theo ID
    public function getById(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy sản phẩm thành công',
            'data' => $product
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/products/{id}",
     *     operationId="updateProduct",
     *     tags={"Admin Products"},
     *     summary="Cập nhật thông tin sản phẩm",
     *     description="Cập nhật thông tin sản phẩm theo ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="iPhone 12"),
     *             @OA\Property(property="description", type="string", example="New iPhone 12"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="supplier_id", type="integer", example=1),
     *             @OA\Property(property="status", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật sản phẩm thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="supplier_id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    //1.4 Cập nhật sản phẩm
    public function update(CreateProductsRequest $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Cập nhật thông tin sản phẩm
        $validatedData = $request->validated();
        $product->update($validatedData);

        return response()->json([
            'message' => 'Cập nhật sản phẩm thành công',
            'data' => $product
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/products/{id}",
     *     operationId="deleteProduct",
     *     tags={"Admin Products"},
     *     summary="Xóa sản phẩm",
     *     description="Xóa sản phẩm theo ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa sản phẩm thành công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm")
     *         )
     *     )
     * )
     */
    //1.5 Xóa sản phẩm
    public function delete($id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Xóa sản phẩm
        $product->delete();

        return response()->json([
            'message' => 'Xóa sản phẩm thành công'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/products/{id}",
     *     operationId="addProductStatus",
     *     tags={"Admin Products"},
     *     summary="Thêm trạng thái sản phẩm",
     *     description="Thêm trạng thái cho sản phẩm theo ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thêm trạng thái thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thêm trạng thái sản phẩm thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="supplier_id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm")
     *         )
     *     )
     * )
     */
    //1.6 Thêm trạng thái sản phẩm
    public function addStatus(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Thêm trạng thái sản phẩm
        $product->status = $request->input('status');
        $product->save();

        return response()->json([
            'message' => 'Thêm trạng thái sản phẩm thành công',
            'data' => $product
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/products/{id}/status",
     *     operationId="updateProductStatus",
     *     tags={"Admin Products"},
     *     summary="Cập nhật trạng thái sản phẩm",
     *     description="Cập nhật trạng thái cho sản phẩm theo ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="inactive")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật trạng thái thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Thay đổi trạng thái sản phẩm thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="supplier_id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm")
     *         )
     *     )
     * )
     */
    //1.7 Thay đổi trạng thái sản phẩm
    public function updateStatus(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Thay đổi trạng thái sản phẩm
        $product->status = $request->input('status');
        $product->save();

        return response()->json([
            'message' => 'Thay đổi trạng thái sản phẩm thành công',
            'data' => $product
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/products/{id}/status",
     *     operationId="deleteProductStatus",
     *     tags={"Admin Products"},
     *     summary="Xóa trạng thái sản phẩm",
     *     description="Đặt trạng thái sản phẩm về null",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa trạng thái thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa trạng thái sản phẩm thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="supplier_id", type="integer"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Không tìm thấy sản phẩm")
     *         )
     *     )
     * )
     */
    //1.8 Xóa trạng thái sản phẩm
    public function deleteStatus($id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Xóa trạng thái sản phẩm
        $product->status = null;
        $product->save();

        return response()->json([
            'message' => 'Xóa trạng thái sản phẩm thành công',
            'data' => $product
        ]);
    }

        public function search(Request $request)
    {
        try {
            $query = Product::query();
            if ($request->filled('keyword')) {
                $keyword = $request->input('keyword');
            
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%")
                      ->orWhere('base_original', 'like', "%$keyword%")
                    ->orWhereHas('category', function ($q1) use ($keyword) {
                        $q1->where('name', 'like', "%$keyword%");
                    })
                    ->orWhereHas('product_variant', function ($q2) use ($keyword) {
                        $q2->where('stock', 'like', "%$keyword%");
                    });
                });
            }
            // Lọc theo status
            if ($request->has('status') && $request->input('status') !== 'all') {
                    $query->where('status', $request->input('status'));
            }
            // Lọc theo khoảng thời gian
            if ($request->filled('date_start') && $request->filled('date_end')) {
                $start = $request->input('date_start');
                $end = $request->input('date_end');
            
                $query->whereBetween('created_at', [$start, $end]);
            
            } elseif ($request->filled('date_start')) {
                $start = $request->input('date_start');
                $query->where('created_at', '>=', $start);
            
            } elseif ($request->filled('date_end')) {
                $end = $request->input('date_end');
                $query->where('created_at', '<=', $end);
            }

            // Phân trang
            $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
            $warranties = $query->paginate($perPage);
            return response()->json([
                'message' => 'Tìm kiếm thành công',
                'data' => $warranties
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tìm kiếm sản phẩm: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tìm kiếm sản phẩm',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}