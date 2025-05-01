<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Profile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AccountController {
    public function register(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:256|unique:account,username|regex:/^[^@]+$/',
            'password' => 'required|string|min:8|max:256',
            "email" => 'required|email|max:256',
            "fullname" => 'string|max:256',
            "phone_number" => 'string|max:256'
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

    public function getById($id) {
        $user = Profile::find($id);
        if (!$user) {
            return response()->json(['error' => 'not found user'], 404);
        }
        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request) {
        $validate = $request->validate([
            'id' => 'required|integer|exists:profile,id',
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
        if (!password_verify($validate['old_password'], $account->password)) {
            return response()->json(['error' => 'Wrong old password'], 404);
        }
        if (password_verify($validate['new_password'], $account->password)) {
            return response()->json(['error' => 'New password cannot same as old password'], 404);
        }
        $account->password = bcrypt($validate['new_password']);
        $account->save();
        return response()->json(['Update password' => $account], 201);
   }

    // 4.6. Quên mật khẩu
    public function forgetPassword(Request $request) {
        $profile = null;
        if ($request->has('email')) {
            $validate = $request->validate([
                'email' => 'required|exists:profile,email'
            ]);
            $profile = Profile::where('email', $validate['email'])->first();
        } else if ($request->has('phone_number')) {
            $validate = $request->validate([
                'phone_number' => 'required|exists:profile,phone_number'
            ]);
            $profile = Profile::where('phone_number', $validate['phone_number'])->first();
        } else if ($request->has('username')) {
            $validate = $request->validate([
                'username' => 'required|exists:account,username'
            ]);
            $account = Account::where('username', $validate['username'])->first();
            $profile = Profile::find($account->id);
        } else {
            return response()->json(['error' => 'No valid identifier provided'], 400);
        }
        if (!$profile) {
            return response()->json(['Error' => 'User not found'], 404);
        }
        $content = $profile->id . "|" . now()->timestamp;
        $hash = hash_hmac('sha256', $content, env('APP_KEY'), true);
        $hashEncoded = base64_encode($hash);
        $token = $content . "|" . $hashEncoded;
        return response()->json(['Send recovery email successfully' => $profile, 'token' => $token]);
    }

    // 4.7. Đặt lại mật kh
    public function resetPassword(Request $request) {
        if (!$request->has('token')) {
            return response()->json(['Error' => 'Not found token'], 500);
        }
        $token = $request['token'];
        $parts = explode('|', $token);
        if (count($parts) != 3) {
            return response()->json(['Error' => 'Invalid token format'], 400);
        }
        list($id, $timestamp, $hashEncoded) = $parts;
        $profile = Profile::where('id', $id)->first();
        if (!$profile) {
            return response()->json(['Error' => 'Not found user'], 404);
        }
        if ($timestamp < time() - 900) { // if the token is expired (created more than15 minutes)
            return response()->json(['Error' => 'Token expired'], 404);
        }
        $content = $id . "|" . $timestamp;
        $hash = hash_hmac('sha256', $content, env('APP_KEY'), true);
        $tmp = base64_encode($hash);
        if ($tmp != $hashEncoded) {
            return response()->json(['Error' => 'Invalid token (might be an attack attempt)'], 404);
        }
        if (!$request->has('password')) {
            return response()->json(['error' => 'not found new password'], 404);
        }
        $validate = $request->validate([
            'password' => 'required,min:8,max:256'
        ]);
        $account = Account::where('id', $id)->first();
        if (password_verify($validate['password'], $account->password)) {
            return response()->json(['error' => 'not allow old password'], 400);
        }
        $account->password = bcrypt($validate['password']);
        $account->save();
        return response()->json('Successful', 201);
    }
}

