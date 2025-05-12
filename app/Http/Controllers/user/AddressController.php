<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Routing\Controller;

class AddressController extends Controller {
    // 7.1. Thêm địa chỉ mới
    public function addAddress(Request $request) {
        $user = auth() -> user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^(02|03|05|07|08|09)\d{8}$/'],
            'street' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'district' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'ward' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'city' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'name' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'email' => ['required', 'email', 'max:255']
        ]);

        $existingAddress = Address::where('profile_id', $user->id)
            ->where('phone_number', $validated['phone_number'])
            ->where('street', $validated['street'])
            ->where('district', $validated['district'])
            ->where('ward', $validated['ward'])
            ->where('city', $validated['city'])
            ->first();

        if ($existingAddress) {
            return response()->json(['error' => 'Address already exists'], 400);
        }

        $address = Address::create([
            'profile_id' => $user->id,
            'phone_number' => $validated['phone_number'],
            'street' => $validated['street'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'city' => $validated['city'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return response()->json($address, 201);
    }

    // 7.2. Lấy danh sách địa chỉ
    public function getUserAddress() {
        $user = auth() -> user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $address = Address::where('profile_id', $user->id)->get();
        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }
        return response()->json($address, 200);
    }

    // 7.3. Cập nhật địa chỉ
    public function updateAddress(Request $request, int $id) {
        $user = auth() -> user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = Address::find($id);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        if ($address->profile_id !== $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^(02|03|05|07|08|09)\d{8}$/'],
            'street' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'district' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'ward' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255'],
            'city' => ['required', 'string', 'regex:/^[\pL\s\d\-.,]+$/u', 'max:255']
        ]);

        $existingAddress = Address::where('profile_id', $user->id)
            ->where('phone_number', $validated['phone_number'])
            ->where('street', $validated['street'])
            ->where('district', $validated['district'])
            ->where('ward', $validated['ward'])
            ->where('city', $validated['city'])
            ->first();

        if ($existingAddress) {
            return response()->json(['error' => 'Address already exists'], 400);
        }

        $address->update($validated);
        return response()->json($address, 200);
    }

    // 7.4. Xóa địa chỉ
    public function deleteAddress($id) {
        $user = auth() -> user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = Address::find($id);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        if ($address->profile_id !== $user->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $address->delete();
        return response()->json(["Delete sucessfully" => $id], 200);
    }
}
