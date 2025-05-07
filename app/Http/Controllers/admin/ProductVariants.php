<?php

namespace App\Http\Controllers\admin;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductVariants extends Controller
{

    public function getAll(Request $request)
    {
        $limit = $request->query('limit', 10);
        $query = ProductVariant::query();
        $product_variants = $query->paginate($limit);
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $product_variants,
            'limit' => $limit
        ]);
    }
}