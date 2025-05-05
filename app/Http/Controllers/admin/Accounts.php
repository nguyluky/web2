<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class Accounts extends Controller
{

    //8.1 Lấy danh sách tài khoản
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

    //8.2 Tạo tài khoản
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

    //8.3 lấy thông tin tài khoản theo id
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
    //8.4 Cập nhật tài khoản
    public function update(Request $request, $id)
    {
        $account = Account::find($id);

        // Kiểm tra xem tài khoản có tồn tại không
        if (!$account) {
            return response()->json([
                'message' => 'Không tìm thấy tài khoản'
            ], 404);
        }

        $validated = $request->validate([
            'username' => 'string|max:256',
            'password' => 'string|max:256',
            'rule' => 'integer|exists:rule,id',
        ]);

        $account->update($validated);

        return response()->json([
            'message' => 'Cập nhật tài khoản thành công',
            'data' => $account
        ]);
    }
    //8.5 Xóa tài khoản
    public function delete(Request $request, $id)
    {
        $account = Account::find($id);

        // Kiểm tra xem tài khoản có tồn tại không
        if (!$account) {
            return response()->json([
                'message' => 'Không tìm thấy tài khoản'
            ], 404);
        }

        $account->delete();

        return response()->json([
            'message' => 'Xóa tài khoản thành công'
        ]);
    }
}