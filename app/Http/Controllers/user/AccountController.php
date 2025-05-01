<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Account;
use App\Models\Profile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AccountController {
    public function register(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:256|unique:account',
            'password' => 'required|string|min:8|max:256',
            "email" => 'required|email|max:256',
            "fullname" => 'required|string|max:256',
            "phone_number" => 'required|string|max:256'
        ]);


        DB::beginTransaction();
        try {
            $validate['rule'] = 4; // Mặc định là user
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
            return response()->json(['message' => 'not found user'], 404);
        }

        if (!password_verify($validate['password'], $account->password)) {
            return response()->json(['message' => 'wrong password'], 401);
        }
        if ($account->status !== 'active') {
            return response()->json(['message' => 'user is not active'], 403);
        }
        // Đăng nhập và tạo token
        $token = JWTAuth::fromUser($account);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function getById() {
        $user = auth()->user();
        // dd($user);
        // if (!$user) {
        //     return response()->json(['error' => 'not found user'], 404);
        // }
        // return response()->json(['user' => $user], 200);
        $profile = $user->profile;
        return response()->json(['data' => $profile], 200);
    }

    public function update(Request $request) {
        $validate = $request->validate([
            // 'id' => 'required|integer|exists:profile,id',
            'fullname' => 'required|string|max:256',
            'phone_number' => 'required|string|max:10|unique:profile,phone_number,' . $request->id . ',id', // unique for phone_number, except record for id = $request->id
            'email' => 'required|string|max:45|unique:profile,email,' . $request->id . ',id' // unique for email, except record for id = $request->id
        ]);
        $user = Profile::where('id', $validate['id'])->first();
        $user->fullname = $validate['fullname'];
        $user->phone_number = $validate['phone_number'];
        $user->email = $validate['email'];
        $user->save();
        return response()->json(['user' => $user], 201);
    }

    public function changePassword(Request $request) {
        $validate = $request->validate([
            'username' => 'required|string|max:256|exists:account,username',
            'old_password' => 'required|string|min:8|max:256',
            'new_password' => 'required|string|min:8|max:256'
        ]);
        $account = Account::where('username', $validate['username'])->first();
        if ($account->password != $validate['old_password']) {
            return response()->json(['error' => 'Wrong old password'], 404);    
        }
        $account->password = $validate['new_password'];
        $account->save();
        return response()->json(['Update password' => $account], 201);
    }

    // 4.6. Quên mật khẩu
    public function forgetPassword() {
        // TODO
    }

    // 4.7. Đặt lại mật kh
    public function resetPassword() {
        // TODO
    }
}
