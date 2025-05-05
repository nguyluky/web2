<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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

    //8.7. Tạo quyền
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:24', 
            'rule' => 'required|integer',
        ]);

        $rule = Rule::create($validated);

        return response()->json([
            'message' => 'Tạo tài khoản thành công',
            'data' => $rule
        ], 201);
    }

    //8.8. Cập nhật quyền
    public function getById(Request $request, $id)
    {

        $rule = Account::find($id);

        if (!$rule) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy thành công',
            'data' => $rule
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
}