<?php

namespace App\Http\Controllers\admin;

// use Illuminate\Routing\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Addresss extends Controller
{

    public function getAll(Request $request)
    {
        $limit = $request->query('limit', 10);
        $query = Address::query();
        $addresss = $query->paginate($limit);
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $addresss,
            'limit' => $limit
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:profile,id',
            'phone_number' => 'required|string|max:10', 
            'street' => 'required|string|max:45', 
            'ward' => 'required|string|max:45', 
            'district' => 'required|string|max:45', 
            'city' => 'required|string|max:45', 
        ]);

        $address = Address::create($validated);

        return response()->json([
            'message' => 'Tạo tài khoản thành công',
            'data' => $address
        ], 201);
    }

    public function getById(Request $request, $id)
    {

        $address = Address::find($id);

        // Kiểm tra xem có tồn tại không
        if (!$address) {
            return response()->json([
                'message' => 'Không tìm thấy tài khoản'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy tài khoản thành công',
            'data' => $address
        ]);
    }
}