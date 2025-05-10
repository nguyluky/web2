<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;

class Rules extends Controller
{

    //8.6. Lấy danh sách quyền
    public function getAll(Request $request)
    {
        $limit = $request->query('limit', 10);
        $query = Rule::query();
        $rules = $query->paginate($limit);
       
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $rules,
            'limit' => $limit
        ]);
    }




    //8.9. Xóa quyền
    public function delete(Request $request, $id)
    {
        $rule = Rule::find($id);

        if (!$rule) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        $rule->delete();

        return response()->json([
            'message' => 'Xóa thành công',
        ]);
    }

    // search rule
    public function search(Request $request)
    {
        try {
            $query = Rule::query();

            // Tìm kiếm theo name
            if ($request->has('name') && !empty($request->input('name'))) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }

           
            if ($request->has('status') && $request->input('status') !== 'all') {
                $query->where('status', $request->input('status'));
            }

            // Phân trang
            $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
            $rules = $query->paginate($perPage);

            return response()->json([
                'message' => 'Tìm kiếm thành công',
                'data' => $rules
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tìm kiếm quyền: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tìm kiếm quyền',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    


        //8.7. Tạo quyền
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:256|unique:rule,name',
                'status' => 'required|in:1,0',

            ]);
            $rule = Rule::create($validated);

            return response()->json([
                'message' => 'Tạo quyền thành công',
                'data' => $rule
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lỗi tạo quyền: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tạo quyền',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    //8.8. Cập nhật quyền
    public function update(Request $request, $id)
    {
        try {
            $rule = Rule::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|max:256|unique:rule,name,' . $id,
                'status' => 'required|in:1,0',
            ]);

            $rule->update($validated);

            return response()->json([
                'message' => 'Cập nhật quyền thành công',
                'data' => $rule
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lỗi cập nhật quyền: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể cập nhật quyền',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}