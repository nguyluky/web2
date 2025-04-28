<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Models\Account;
use Illuminate\Models\Profile;
use Illuminate\Support\Carbon;

class AccountController {
    public function register(Request $request) {
        $validate = $request->validate([
            'username' => 'required|string|max:256|unique:account',
            'password' => 'required|string|min:8|max:256',
            'fullname' => 'required|string|max:256',
            'phone_number' => 'required|string|max:10|unique:profile',
            'email' => 'required|string|max:45|unique:profile'
        ]);

        // if want to hash password: $validate['password'] = Hash::make($validate['password']);
        // remember to `use Illuminate\Support\Facades\Hash;`
        $validate['created'] = Carbon::now();
        $validate['updated'] = Carbon::now();
        $validate['rule'] = 0; // Mặc định là user
        $validate['status'] = 'active'; // Mặc định là active
        
        DB::beginTransaction();
        try {
            $acc = [
                'username' => $validate['username'],
                'password' => $validate['password'],
                'created' => $validate['created'],
                'updated' => $validate['updated'],
                'rule' => $validate['rule'],
                'status' => $validate['status']
            ];

            $account = Account::create($acc);
            
            $user = [
                'id' => $account->id,
                'fullname' => $validate['fullname'],
                'phone_number' => $validate['phone_number'],
                'email' => $validate['email']
            ];
    
            $newUser = Profile::create($user);
            $token = $account->createToken('api_token')->plainTextToken;
            DB::commit();
            return response()->json(['user' => $newUser, 'account' => $account, 'token' => $token], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response() ->json(['error' => 'Could not create new profile'], 500);
        }
    }

    public function login(Request $request) {
        $validate = $request->validate([
            'username' => 'required|string|max:256',
            'password' => 'required|string|min:8|max:256',
        ]);
        $user = Account::where('username', $validate['username'])->first();
        if (!$user) {
            return response()->json(['error' => 'not found user'], 404);
        }
        if ($user['password'] != $validate['password']) {
            return response()->json(['error' => 'password not correct'], 401);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function getById($id) {
        $user = Profile::find($id);
        if (!$user) {
            return response()->json(['error' => 'not found user'], 404);
        }
        return response()->json(['user => $user'], 201);
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
