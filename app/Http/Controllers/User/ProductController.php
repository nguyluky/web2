<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getById(String $id) {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    public function getByCategory(String $category_id) {
        $product = Product::where('category_id', $category_id)->get();
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }

    public function searchProduct() {
        $search = $request->$input('search');
        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', '%' . $serach . '%');
        }
    }

    public function getByName(String $name) {
        $product = Product::where('name', $name);
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }
}
