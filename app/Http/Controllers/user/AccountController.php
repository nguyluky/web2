<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Account;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountController
{
    public function register(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:256|unique:account',
            'password' => 'required|string|min:8|max:256',
        ]);

        $validate['rule'] = 0; // Mặc định là user
        $validate['status'] = 'active'; // Mặc định là active
        $account = Account::create($validate);


        // Đăng nhập và tạo token
        $token = JWTAuth::fromUser($account);

        // Trả về token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:256',
            'password' => 'required|string|min:8|max:256',
        ]);

        if (!$token = auth()->attempt($validate)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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
