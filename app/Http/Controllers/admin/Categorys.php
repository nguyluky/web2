<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class Categorys extends Controller
{

    public function getAll(Request $request)
    {
        $categorys = Category::all();
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $categorys
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45', 
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Tạo thành công',
            'data' => $category
        ], 201);
    }

    public function getById(Request $request, $id)
    {

        $category = Category::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$category) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy thành công',
            'data' => $category
        ]);
    }
}