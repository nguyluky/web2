<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class Profiles extends Controller
{

    public function getAll(Request $request)
    {       
        $limit = $request->query('limit', 10);
        $query = Profile::query();
        $profiles = $query->paginate($limit);
       
        return response()->json([
            'message' => 'Lấy danh sách tài khoản thành công',
            'data' => $profiles,
            'limit' => $limit
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:256', 
            'phone_number' => 'required|string|max:10', 
            'email' => 'required|string|max:45',
        ]);

        $profile = Profile::create($validated);

        return response()->json([
            'message' => 'Tạo tài khoản thành công',
            'data' => $profile
        ], 201);
    }

    public function getById(Request $request, $id)
    {

        $profile = Profile::find($id);

        // Kiểm tra xem có tồn tại không
        if (!$profile) {
            return response()->json([
                'message' => 'Không tìm thấy'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy thành công',
            'data' => $profile
        ]);
    }
}