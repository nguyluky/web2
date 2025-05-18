<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use Illuminate\Routing\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use KitLoong\MigrationsGenerator\Schema\Models\Procedure;

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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:45|unique:categories,name',
                'status' => 'required|in:active,hidden',
                'require_fields' => 'string',
                'small_image' => 'image',
                'large_image' => 'image',
                'description' => 'string|max:255',
                'parent_id' => 'integer|exists:categories,id',
            ]);

            $validated['slug'] = Str::slug($validated['name']); // Tạo slug

            if (isset($validated['require_fields'])) {
                $validated['require_fields'] = json_decode($validated['require_fields'], true);
            }
            if (isset($validated['small_image'])) {
                $validated['small_image'] = $request->file('small_image')->store('images/categories', 'public');
                $validated['small_image'] = asset('storage/' . $validated['small_image']);
            }
            if (isset($validated['large_image'])) {
                $validated['large_image'] = $request->file('large_image')->store('images/categories', 'public');
                $validated['large_image'] = asset('storage/' . $validated['large_image']);
            }

            $category = Category::create($validated);

            return response()->json([
                'message' => 'Tạo danh mục thành công',
                'data' => $category
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lỗi tạo danh mục: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tạo danh mục',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    //2.2.1. Tìm kiếm danh mục sản phẩm
    public function search(Request $request)
    {
        $query = Category::query();

        // Tìm kiếm theo tên danh mục
        if ($request->has('name') && !empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Phân trang
        $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
        $categorys = $query->paginate($perPage);

        return response()->json([
            'message' => 'Tìm kiếm thành công',
            'data' => $categorys
        ], 200);
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
            'status' => 'required|in:active,hidden',
            'require_fields' => 'string',
            'small_image' => 'image',
            'large_image' => 'image',
            'description' => 'string|max:255',
            'parent_id' => 'integer|exists:categories,id',
        ]);

        if (isset($validated['name'])) {
            $validated['require_fields'] = json_decode($validated['require_fields'], true);
        }

        if (isset($validated['small_image'])) {
            $validated['small_image'] = $request->file('small_image')->store('images/categories', 'public');
            $validated['small_image'] = asset('storage/' . $validated['small_image']);
        }
        if (isset($validated['large_image'])) {
            $validated['large_image'] = $request->file('large_image')->store('images/categories', 'public');
            $validated['large_image'] = asset('storage/' . $validated['large_image']);
        }

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