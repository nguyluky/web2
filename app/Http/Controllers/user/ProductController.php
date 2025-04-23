<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getById(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    public function getByCategory(string $category_id)
    {
        $product = Product::where('category_id', $category_id)->get();
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    public function searchProduct(Request $request)
    {
        $search = $request->query('search');
        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
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
