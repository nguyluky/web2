<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;

class ProfileController
{
    public function getProfile(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'status' => 'success',
            'data' => $user->profile
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validate = $request->validate([
            'fullname' => 'required|string|max:256',
            'phone_number' => 'required|string|max:256',
            'email' => 'required|email|max:256',
        ]);

        $user->profile()->update($validate);

        return response()->json([
            'status' => 'success',
            'data' => $user->profile
        ]);
    }
}
