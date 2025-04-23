<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class Accounts extends Controller
{

    public function getAll(Request $request)
    {
        $limit = $request->query('limit', 10);
        $query = Account::query();
        $accounts = $query->paginate($limit);

        return response()->json([
            'message' => 'Lấy danh sách tài khoản thành công',
            'data' => $accounts,
            'limit' => $limit
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:256', 
            'password' => 'required|string|max:256', 
            'rule' => 'required|integer|exists:rule,id',
        ]);

        $account = Account::create($validated);

        return response()->json([
            'message' => 'Tạo tài khoản thành công',
            'data' => $account
        ], 201);
    }

    public function getById(Request $request, $id)
    {

        $account = Account::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$account) {
            return response()->json([
                'message' => 'Không tìm thấy tài khoản'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy tài khoản thành công',
            'data' => $account
        ]);
    }
}