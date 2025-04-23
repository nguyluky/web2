<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;

class Rules extends Controller
{

    public function getAll(Request $request)
    {
        $rules = Rule::all();
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $rules
        ]);
    }

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
}