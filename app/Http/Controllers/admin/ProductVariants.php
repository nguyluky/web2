<?php

namespace App\Http\Controllers\admin;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductVariants extends Controller
{

    public function getAll(Request $request)
    {
        $query = ProductVariant::query()->get();
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $query
        ]);
    }
}