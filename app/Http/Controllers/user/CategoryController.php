<?php

namespace App\Http\Controllers\user;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Category;
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
        $category = Category::find($id);
        $t = Product::where('category_id', $id)->get();

        $require_fields = [];
        foreach ($t as $item) {
            $attributes = json_decode($item->attribute, true);
            foreach ($attributes as $key => $value) {
                if (!isset($require_fields[$key])) {
                    $require_fields[$key] = [];
                }
                if (!in_array($value, $require_fields[$key])) {
                    $require_fields[$key][] = $value;
                }
            }
        }

        return response()->json([
            'data' => $category,
            'products' => $t,
            'require_fields' => $require_fields
        ]); 
    }
}
