<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Models\Account;
use Illuminate\Support\Carbon;

class AccountController
{
    public function register(Request $request) {
        $validate = $request->validate([
            'username' => 'required|string|max:256|unique:account',
            'password' => 'required|string|min:8|max:256',
        ]);

        // if want to hash password: $validate['password'] = Hash::make($validate['password']);
        // remember to `use Illuminate\Support\Facades\Hash;`
        $validate['created'] = Carbon::now();
        $validate['updated'] = Carbon::now();
        $validate['rule'] = 0; // Mặc định là user
        $validate['status'] = 'active'; // Mặc định là active
        $account = Account::create($validate);
        $token = $account->createToken('api_token')->plainTextToken;
        return response()->json(['user' => $account, 'token' => $token], 201);
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

    public function getAll() {
        $users = Account::all();
        if ($users->isEmpty()) {
            return response()->json(['error' => 'not found users'], 404);
        }
        return response()->json(['users => $users'], 201);
    }
}
