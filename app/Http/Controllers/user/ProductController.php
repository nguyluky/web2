<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getById(string $id)
    {

        // get product by id including variants and images
        $product = Product::with(['product_variants', 'product_images'])->where('id', $id)->first();

        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    public function getByCategory(string $category_id)
    {
        $product = Product::where('category_id', $category_id)->get();
        if ($product->isEmpty()) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

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
