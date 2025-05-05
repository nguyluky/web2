<?php

namespace App\Http\Controllers\user;

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
    /**
     * @OA\Get(
     *   path="/api/users/profile",
     *   tags={"Profile"},
     *   @OA\Response(response=200, description="User profile retrieved"),
     *   security={{"sanctum":{}}}
     * )
     */
    public function getProfile(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'status' => 'success',
            'data' => $user->profile
        ]);
    }

    /**
     * @OA\Put(
     *   path="/api/users/profile",
     *   tags={"Profile"},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       @OA\Property(property="fullname", type="string", example="John Doe"),
     *       @OA\Property(property="phone_number", type="string", example="0123456789"),
     *       @OA\Property(property="email", type="string", format="email", example="john@example.com")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Profile updated"),
     *   security={{"sanctum":{}}}
     * )
     */
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