<?php

namespace App\Http\Controllers\user;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImage;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints quản lý danh mục sản phẩm"
 * )
 */
class CategoryController extends Controller
{

    public function getAll()
    {
        $category = Category::all();
        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }

        /**
         * @status 201
         * @body Category
         */
        return response()->json(['data' => $category]);
    }

    public function getFilter($id)
    {
        // join category and product
        // get all product in category and sub category
        // $category = Category::find($id)->get();
        // $pruducts = Product::where('category_id', $id)->get();

        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        // Get all subcategories
        $subcategories = Category::where('parent_id', $id)->pluck('id')->toArray();
        $categoryIds = array_merge([$id], $subcategories);

        // Get all product in this category and its subcategories
        $pruducts = Product::whereIn('category_id', $categoryIds)->get();

        $data = array();
        $data_response = array();
        foreach ($pruducts as $product) {
            $product_variants = $product->product_variants()->get();

            foreach ($product_variants as $variant) {
                $data[] = array_merge($product->toArray()['specifications'],$variant->toArray()['specifications']);
            }
        }

        if (isset($category->toArray()['require_fields'])) {
            $require_fields = $category->toArray()['require_fields'];
        } else {
            $require_fields = array();

            // lấy những key mà tồn tại trong mọi sản phẩm
            $keys = array();
            foreach ($data as $item) {
                foreach ($item as $key => $value) {
                    if (!isset($keys[$key])) {
                        $keys[$key] = 1;
                    } else {
                        $keys[$key]++;
                    }
                }
            }
            $max = max($keys);
            foreach ($keys as $key => $value) {
                if ($value == $max) {
                    $require_fields[] = $key;
                }
            }
        }

        foreach ($require_fields as $key) {
            if (!isset($data_response[$key])) {
                $data_response[$key] = array();
            }

            foreach ($data as $item) {
                if (isset($item[$key])) {
                    $data_response[$key][] = $item[$key];
                }
            }
            $data_response[$key] = array_unique($data_response[$key]);
        }


        return response()->json([
            'data' => $data_response,
            // 'd' => $pruducts[0]->product_variants()->get()
        ]);
    }

    public function getById($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        /**
         * @status 201
         * @body Category
         */
        return response()->json(['data' => $category]);
    }

public function getMainCategories(Request $request)
    {
        try {
            $categories = Category::select('id', 'name')
                ->with(['product' => function ($query) {
                    $query->with(['product_images' => function ($q) {
                        $q->where('is_primary', 1)->orWhereNull('is_primary')->first();
                    }])->first();
                }])
                ->take(6)
                ->get()
                ->map(function ($categories) {
                    // Lấy ảnh chính từ sản phẩm đầu tiên
                    $firstImage = $categories->product->first()?->productImages->first()?->image_url ?? 'https://placehold.co/400x200';

                    return [
                        'id' => $categories->id,
                        'name' => $categories->name,
                        'image_url' => $firstImage,
                        'description' => 'Khám phá các sản phẩm ' . strtolower($categories->name) . ' chất lượng cao từ các thương hiệu hàng đầu.'
                    ];
                });

            return response()->json([
                'message' => 'Lấy danh mục chính thành công',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in getMainCategories: ' . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi khi lấy danh mục chính',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllCategories(Request $request)
    {
        try {
$categories = Category::with(['products.productImages' => function ($query) {
    $query->where('is_primary', 1);
}])->get()->map(function ($category) {
    $image = $category->products->first()?->productImages->first()?->image_url ?? 'https://placehold.co/100x100';
    
    return [
        'id' => $category->id,
        'name' => $category->name,
        'image_url' => $image,
        'product_count' => $category->products->count(),
    ];
});

            return response()->json([
                'message' => 'Lấy tất cả danh mục thành công',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in getAllCategories: ' . $e->getMessage());
            return response()->json([
                'message' => 'Lỗi khi lấy tất cả danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}