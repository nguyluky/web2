<?php

namespace App\Http\Controllers\user;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Profile",
 *   description="User profile endpoints"
 * )
 */
class ProfileController extends Controller
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

        Profile::where('id', $user->profile->id)->update([
            'fullname' => $validate['fullname'],
            'phone_number' => $validate['phone_number'],
            'email' => $validate['email'],
        ]);

        // get new profile data
        $user->profile = $user->profile()->first();

        return response()->json([
            'status' => 'success',
            'data' => $user->profile
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();
        $validate = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->profile()->update(['avatar' => $avatarPath]);
            
            // Update the profile with the full URL to the avatar
            $user->profile->avatar_url = asset('storage/' . $avatarPath);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user->profile
        ]);
    }
}