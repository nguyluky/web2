<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
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
        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:account,id|unique:profile,id',
                'fullname' => 'required|string|max:256',
                'phone_number' => 'required|string|size:10',
                'email' => 'required|email|max:45|unique:profile,email',
            ]);
    
            $profile = Profile::create($validated);
    
            return response()->json([
                'message' => 'Tạo hồ sơ người dùng thành công',
                'data' => $profile,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Lỗi tạo hồ sơ: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tạo hồ sơ người dùng',
                'message' => $e->getMessage(),
            ], 500);
        }
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