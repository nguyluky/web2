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
    public function update(Request $request, $id)
{
    try {
        $user = Profile::findOrFail($id);
        $validated = $request->validate([
            'fullname' => 'required|string|max:256',
            'phone_number' => 'required|string|size:10|unique:profile,phone_number,' . $id,
            'email' => 'required|email|max:256|unique:profile,email,' . $id,
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Cập nhật hồ sơ thành công',
            'data' => $user
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'message' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Lỗi cập nhật hồ sơ: ' . $e->getMessage());
        return response()->json([
            'error' => 'Không thể cập nhật hồ sơ',
            'message' => $e->getMessage()
        ], 500);
    }
}
        public function search(Request $request)
        {
            try {
                $query = Profile::query();

                // Tìm kiếm theo fullname
                if ($request->has('fullname') && !empty($request->input('fullname'))) {
                    $query->where('fullname', 'like', '%' . $request->input('fullname') . '%');
                }

                // Lọc theo status (nếu cần join với bảng account)
                if ($request->has('status') && $request->input('status') !== 'all') {
                    $query->join('account', 'profile.id', '=', 'account.id')
                        ->where('account.status', $request->input('status'))
                        ->select('profile.*'); // Chỉ lấy các cột từ profile
                }

                // Phân trang
                $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
                $users = $query->paginate($perPage);

                return response()->json([
                    'message' => 'Tìm kiếm thành công',
                    'data' => $users
                ], 200);
            } catch (\Exception $e) {
                \Log::error('Lỗi khi tìm kiếm người dùng: ' . $e->getMessage());
                return response()->json([
                    'error' => 'Không thể tìm kiếm người dùng',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
}