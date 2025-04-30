<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Account;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AccountController
{
    public function register(Request $request)
    {



        $validate = $request->validate([
            'username' => 'required|string|max:256|unique:account',
            'password' => 'required|string|min:8|max:256',
            "email" => 'required|email|max:256',
            "fullname" => 'string|max:256',
            "phone_number" => 'string|max:256'
        ]);


        DB::beginTransaction();
        try {
            $validate['rule'] = 0; // Mặc định là user
            $validate['status'] = 'active'; // Mặc định là active
            $validate['password'] = bcrypt($validate['password']); // Mã hóa mật khẩu
            $account = Account::create($validate);

            // Tạo profile cho tài khoản
            $account->profile()->create([
                'fullname' => $request->input('fullname', ''),
                'phone_number' => $request->input('phone_number', ''),
                'email' => $request->input('email'),
            ]);
            DB::commit();

            // Đăng nhập và tạo token
            $token = JWTAuth::fromUser($account);

            // Trả về token
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:256',
            'password' => 'required|string|min:8|max:256',
        ]);

        $account = Account::where('username', $validate['username'])->first();
        if (!$account) {
            return response()->json(['error' => 'not found user'], 404);
        }

        if (!password_verify($validate['password'], $account->password)) {
            return response()->json(['error' => 'wrong password'], 401);
        }
        if ($account->status !== 'active') {
            return response()->json(['error' => 'user is not active'], 403);
        }
        // Đăng nhập và tạo token
        $token = JWTAuth::fromUser($account);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function getAll()
    {
        $users = Account::all();
        if ($users->isEmpty()) {
            return response()->json(['error' => 'not found users'], 404);
        }
        return response()->json(['users => $users'], 201);
    }
}
