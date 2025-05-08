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
     *   summary="Get the authenticated user's profile",
     *   description="Returns the profile information of the currently authenticated user",
     *   operationId="getUserProfile",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="User profile retrieved successfully",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="account_id", type="integer", example=1),
     *         @OA\Property(property="fullname", type="string", example="John Doe"),
     *         @OA\Property(property="phone_number", type="string", example="0123456789"),
     *         @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *         @OA\Property(property="created_at", type="string", format="date-time"),
     *         @OA\Property(property="updated_at", type="string", format="date-time")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     *   )
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
     *   summary="Update the authenticated user's profile",
     *   description="Updates the profile information of the currently authenticated user",
     *   operationId="updateUserProfile",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Profile data to update",
     *     @OA\JsonContent(
     *       required={"fullname", "phone_number", "email"},
     *       @OA\Property(property="fullname", type="string", example="John Doe", description="User's full name"),
     *       @OA\Property(property="phone_number", type="string", example="0123456789", description="User's phone number"),
     *       @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email address")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Profile updated successfully",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="account_id", type="integer", example=1),
     *         @OA\Property(property="fullname", type="string", example="John Doe"),
     *         @OA\Property(property="phone_number", type="string", example="0123456789"),
     *         @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *         @OA\Property(property="created_at", type="string", format="date-time"),
     *         @OA\Property(property="updated_at", type="string", format="date-time")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         @OA\Property(property="fullname", type="array", @OA\Items(type="string", example="The fullname field is required.")),
     *         @OA\Property(property="phone_number", type="array", @OA\Items(type="string", example="The phone number field is required.")),
     *         @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field must be a valid email address."))
     *       )
     *     )
     *   )
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

    /**
     * @OA\Post(
     *   path="/api/users/profile/avatar",
     *   tags={"Profile"},
     *   summary="Upload user avatar",
     *   description="Upload a new avatar image for the authenticated user",
     *   operationId="uploadUserAvatar",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"avatar"},
     *         @OA\Property(
     *           property="avatar",
     *           type="string",
     *           format="binary",
     *           description="The avatar image to upload"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Avatar uploaded successfully",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="account_id", type="integer", example=1),
     *         @OA\Property(property="fullname", type="string", example="John Doe"),
     *         @OA\Property(property="phone_number", type="string", example="0123456789"),
     *         @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *         @OA\Property(property="avatar", type="string", example="avatars/filename.jpg"),
     *         @OA\Property(property="avatar_url", type="string", example="http://localhost:8000/storage/avatars/filename.jpg")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         @OA\Property(property="avatar", type="array", @OA\Items(type="string", example="The avatar field is required."))
     *       )
     *     )
     *   )
     * )
     */
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