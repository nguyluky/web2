<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Function_;
use Illuminate\Http\Request;

class Functions extends Controller
{

    public function getAll(Request $request)
    {
        $functions = Function_::all();
        return response()->json([
            'message' => 'Lấy danh sách tài khoản thành công',
            'data' => $functions
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45', 
        ]);

        $function = Function_::create($validated);

        return response()->json([
            'message' => 'Tạo thành công',
            'data' => $function
        ], 201);
    }

    public function getById(Request $request, $id)
    {

        $function = Account::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$function) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy thành công',
            'data' => $function
        ]);
    }
}