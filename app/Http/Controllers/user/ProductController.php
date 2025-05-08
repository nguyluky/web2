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
     *                 @OA\Property(property="sku", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="base_price", type="number", format="float"),
     *                 @OA\Property(property="base_original_price", type="number", format="float"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(
     *                     property="specifications",
     *                     type="object",
     *                     description="Thông số kỹ thuật của sản phẩm",
     *                     example={"processor":"Intel Core i9 13900K","graphics":"NVIDIA RTX 4080","display":"17.3\" QHD 240Hz","memory":"32GB DDR5"}
     *                 ),
     *                 @OA\Property(
     *                     property="features", 
     *                     type="array", 
     *                     description="Tính năng nổi bật của sản phẩm",
     *                     @OA\Items(type="string"),
     *                     example={"RGB Keyboard", "Thunderbolt 4", "Wi-Fi 6E"}
     *                 ),
     *                 @OA\Property(
     *                     property="meta_data", 
     *                     type="object", 
     *                     nullable=true,
     *                     description="Dữ liệu bổ sung của sản phẩm"
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="product_variants",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="product_id", type="integer"),
     *                         @OA\Property(property="sku", type="string"),
     *                         @OA\Property(property="price", type="number", format="float"),
     *                         @OA\Property(property="original_price", type="number", format="float"),
     *                         @OA\Property(property="stock", type="integer"),
     *                         @OA\Property(property="status", type="string"),
     *                         @OA\Property(
     *                             property="specifications",
     *                             type="object",
     *                             description="Thông số kỹ thuật của biến thể sản phẩm",
     *                             example={"color":"Black","ram":"32GB","storage":"1TB"}
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="product_images",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="product_id", type="integer"),
     *                         @OA\Property(property="variant_id", type="integer", nullable=true),
     *                         @OA\Property(property="image_url", type="string"),
     *                         @OA\Property(property="is_primary", type="boolean"),
     *                         @OA\Property(property="sort_order", type="integer"),
     *                         @OA\Property(property="created_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(property="parent_id", type="integer", nullable=true),
     *                     @OA\Property(
     *                         property="require_fields", 
     *                         type="object", 
     *                         description="Các trường bắt buộc cho danh mục",
     *                         example={"color":true,"size":true,"material":false}
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 ),
     *                 @OA\Property(
     *                     property="product_reviews",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="product_id", type="integer"),
     *                         @OA\Property(property="user_id", type="integer"),
     *                         @OA\Property(property="rating", type="integer"),
     *                         @OA\Property(property="comment", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(
     *                             property="account",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="username", type="string"),
     *                             @OA\Property(property="profile", type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="fullname", type="string"),
     *                                 @OA\Property(property="avatar", type="string", nullable=true)
     *                             )
     *                         )
     *                     )
     *                 )
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

        // get product by id including variants and images and category and reviews, sell count, and user reviews
        $product = Product::with(['product_variants', 'product_images'])
            ->where('id', $id)
            ->with(['category'])
            ->with(['product_reviews'])
            ->with(['product_reviews.account.profile'])
            ->first();
        
        // $product['product_reviews'] = $product->product_reviews->map(function ($review) {
        //     return $review->with(['account.profile'])->first();
        // });

        // if (!$product) {
        //     return response()->json(['error' => 'product not found'], 404);
        // }
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
     *                     @OA\Property(property="sku", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="category_id", type="integer"),
     *                     @OA\Property(property="base_price", type="number", format="float"),
     *                     @OA\Property(property="base_original_price", type="number", format="float"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(
     *                         property="specifications",
     *                         type="object",
     *                         description="Thông số kỹ thuật của sản phẩm",
     *                         example={"processor":"Intel Core i9 13900K","graphics":"NVIDIA RTX 4080","display":"17.3\" QHD 240Hz","memory":"32GB DDR5"}
     *                     ),
     *                     @OA\Property(
     *                         property="features", 
     *                         type="array", 
     *                         description="Tính năng nổi bật của sản phẩm",
     *                         @OA\Items(type="string"),
     *                         example={"RGB Keyboard", "Thunderbolt 4", "Wi-Fi 6E"}
     *                     ),
     *                     @OA\Property(
     *                         property="meta_data", 
     *                         type="object", 
     *                         nullable=true,
     *                         description="Dữ liệu bổ sung của sản phẩm"
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="product_images",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="product_id", type="integer"),
     *                             @OA\Property(property="variant_id", type="integer", nullable=true),
     *                             @OA\Property(property="image_url", type="string"),
     *                             @OA\Property(property="is_primary", type="boolean"),
     *                             @OA\Property(property="sort_order", type="integer"),
     *                             @OA\Property(property="created_at", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getNewProduct() {
        $limit = request()->input('limit', 10);
        $data = Product::orderBy('created_at', 'desc')->with(['product_images'])->limit($limit)->get();

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
     *         name="category_id",
     *         in="query",
     *         required=false,
     *         description="Lọc theo ID danh mục",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Lọc theo trạng thái sản phẩm (active, inactive)",
     *         @OA\Schema(type="string", enum={"active", "inactive"})
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         required=false,
     *         description="Lọc theo giá tối thiểu",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         required=false,
     *         description="Lọc theo giá tối đa",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         description="Số lượng sản phẩm trên một trang (mặc định: 10)",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Số trang",
     *         @OA\Schema(type="integer", default=1)
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
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="sku", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="slug", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="category_id", type="integer"),
     *                     @OA\Property(property="base_price", type="number", format="float"),
     *                     @OA\Property(property="base_original_price", type="number", format="float"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(
     *                         property="specifications",
     *                         type="object",
     *                         description="Thông số kỹ thuật của sản phẩm",
     *                         example={"processor":"Intel Core i9 13900K","graphics":"NVIDIA RTX 4080","display":"17.3\" QHD 240Hz","memory":"32GB DDR5"}
     *                     ),
     *                     @OA\Property(
     *                         property="features", 
     *                         type="array", 
     *                         description="Tính năng nổi bật của sản phẩm",
     *                         @OA\Items(type="string"),
     *                         example={"RGB Keyboard", "Thunderbolt 4", "Wi-Fi 6E"}
     *                     ),
     *                     @OA\Property(
     *                         property="meta_data", 
     *                         type="object", 
     *                         nullable=true,
     *                         description="Dữ liệu bổ sung của sản phẩm"
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="product_images",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="product_id", type="integer"),
     *                             @OA\Property(property="variant_id", type="integer", nullable=true),
     *                             @OA\Property(property="image_url", type="string"),
     *                             @OA\Property(property="is_primary", type="boolean"),
     *                             @OA\Property(property="sort_order", type="integer"),
     *                             @OA\Property(property="created_at", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string"),
     *             @OA\Property(property="from", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="last_page_url", type="string"),
     *             @OA\Property(property="links", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="url", type="string", nullable=true),
     *                     @OA\Property(property="label", type="string"),
     *                     @OA\Property(property="active", type="boolean")
     *                 )
     *             ),
     *             @OA\Property(property="next_page_url", type="string", nullable=true),
     *             @OA\Property(property="path", type="string"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="prev_page_url", type="string", nullable=true),
     *             @OA\Property(property="to", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */
    public function searchProduct(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'created_at_desc');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        // take the rest of the filters
        $filters = $request->except(['query', 'limit', 'sort']);
        $filters = array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $products = Product::query();

        $products->with(['product_images']);

        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            });
        }
        
        // apply filters
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $products->whereIn($key, $value);
            } else {
                $products->where($key, $value);
            }
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
