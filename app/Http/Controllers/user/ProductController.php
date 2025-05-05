<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints của sản phẩm (user)"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     summary="Lấy chi tiết sản phẩm theo ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="product", type="object",
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
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="product not found")
     *         )
     *     )
     * )
     */
    public function getById(string $id)
    {

        // get product by id including variants and images and category and reviews, sell count
        $product = Product::where('id', $id)->first();

        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}/products",
     *     operationId="getProductsByCategory",
     *     tags={"Products"},
     *     summary="Lấy sản phẩm theo danh mục",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của danh mục",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="product", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="category_id", type="integer"),
     *                     @OA\Property(property="supplier_id", type="integer"),
     *                     @OA\Property(property="status", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy sản phẩm",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="product not found")
     *         )
     *     )
     * )
     */
    public function getByCategory(string $category_id)
    {
        $product = Product::where('category_id', $category_id)->get();
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    // 1.4. Lấy sản phẩm nổi bật
    public function getFeaturedProduct() {
        // TODO
    }

    /**
     * @OA\Get(
     *     path="/api/products/new",
     *     operationId="getNewProducts",
     *     tags={"Products"},
     *     summary="Lấy danh sách sản phẩm mới nhất",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Số lượng sản phẩm (mặc định: 10)",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="category_id", type="integer"),
     *                     @OA\Property(property="supplier_id", type="integer"),
     *                     @OA\Property(property="status", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getNewProduct() {
        $limit = request()->input('limit', 10);
        $data = Product::orderBy('created_at', 'desc')->limit($limit)->get();

        return response()->json([
            'message' => 'Lấy sản phẩm mới thành công',
            'data' => $data
        ]);
    }

    // 1.5. Lấy sản phẩm liên quan _KHÓ_
    public function getRelatedProduct() {
        // TODO
    }

    /**
     * @OA\Get(
     *     path="/api/products/search",
     *     operationId="searchProducts",
     *     tags={"Products"},
     *     summary="Tìm kiếm sản phẩm",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=false,
     *         description="Từ khóa tìm kiếm",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Số lượng sản phẩm trên một trang (mặc định: 10)",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         required=false,
     *         description="Sắp xếp kết quả (created_at_desc, created_at_asc, price_asc, price_desc)",
     *         @OA\Schema(type="string", default="created_at_desc", 
     *                   enum={"created_at_desc", "created_at_asc", "price_asc", "price_desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="category_id", type="integer"),
     *                     @OA\Property(property="supplier_id", type="integer"),
     *                     @OA\Property(property="status", type="integer"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             ),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer")
     *         )
     *     )
     * )
     */
    public function searchProduct(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'created_at_desc');

        $products = Product::query();

        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            });
        }

        switch ($sort) {
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            case 'created_at_asc':
                $products->orderBy('created_at', 'asc');
                break;
            default:
                $products->orderBy('created_at', 'desc');
        }

        $results = $products->paginate($limit);
        $results->appends([
            'query' => $query,
            'limit' => $limit,
            'sort' => $sort
        ]);

        return response()->json($results);
    }

    public function getByName(string $name)
    {
        $product = Product::where('name', $name);
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }
}
