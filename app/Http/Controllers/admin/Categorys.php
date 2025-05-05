<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class Categorys extends Controller
{
    //2.1. Danh sách danh mục sản phẩm
    public function getAll(Request $request)
    {
        $limit = $request->query('limit', 10);
        $query = Category::query();
        $categorys = $query->paginate($limit);
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $categorys,
            'limit' => $limit
        ]);
    }
    //2.2 . Thêm danh mục sản phẩm
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
    //2.3. Cập nhật danh mục sản phẩm
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$category) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:45', 
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'data' => $category
        ]);
    }
    //2.4. Xóa danh mục sản phẩm
    public function delete($id)
    {
        $category = Category::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$category) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Xóa thành công',
            'data' => $category
        ]);
    }
}