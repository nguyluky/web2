<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RuleFunction;
use Illuminate\Http\Request;

class RuleFunctions extends Controller
{

    public function getAll(Request $request)
    {
        $limit = $request->query('limit', 10);
        $query = RuleFunction::query();
        $rule_functions = $query->paginate($limit);
      
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $rule_functions,
            'limit' => $limit
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'rule_id' => 'required|integer|exists:rule,id',
            'funstion_id' => 'required|integer|exists:funstion,id',
        ]);

        $rule_function = RuleFunction::create($validated);

        return response()->json([
            'message' => 'Tạo tài khoản thành công',
            'data' => $rule_function
        ], 201);
    }

    public function getById(Request $request, $rule_id)
    {

        $rule_function = Address::find($rule_id);

        // Kiểm tra xem có tồn tại không
        if (!$rule_function) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy tài khoản thành công',
            'data' => $rule_function
        ]);
    }
}