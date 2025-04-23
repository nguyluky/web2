<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class Products extends Controller
{
    /**
     * Lấy danh sách tất cả sản phẩm
     */
    public function getAll(Request $request)
    {
        // Lấy tất cả sản phẩm từ bảng product
        $products = Product::all();
        return response()->json([
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $products
        ]);
    }

    /**
     * Tạo một sản phẩm mới
     */
    public function create(Request $request)
    {
        // Xác thực dữ liệu đầu vào dựa trên cấu trúc bảng
        $validated = $request->validate([
            'name' => 'required|string|max:45', // Phù hợp với cột name (VARCHAR(45))
            'category_id' => 'required|integer|exists:categories,id', // Kiểm tra category_id tồn tại trong bảng categories
            'attributes' => 'nullable|json' // attributes là JSON, có thể null
        ]);

        // Tạo sản phẩm mới
        $product = Product::create($validated);

        return response()->json([
            'message' => 'Tạo sản phẩm thành công',
            'data' => $product
        ], 201);
    }

    /**
     * Lấy thông tin một sản phẩm theo ID
     */
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
}