<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class Suppliers extends Controller
{

    public function getAll(Request $request)
    {
        $suppliers = Supplier::all();
        return response()->json([
            'message' => 'Lấy danh sách nhà cung cấp thành công',
            'data' => $suppliers
        ]);
    }


    public function create(Request $request)
    {
        // Xác thực dữ liệu đầu vào dựa trên cấu trúc bảng supplier
        $validated = $request->validate([
            'name' => 'required|string|max:45', // Tên nhà cung cấp, tối đa 45 ký tự
            'tax' => 'required|string|max:45', // Mã số thuế, tối đa 45 ký tự
            'contact_name' => 'required|string|max:45', // Tên người liên hệ, tối đa 45 ký tự
            'phone_number' => 'required|string|max:45|regex:/^[0-9\+]+$/u', // Số điện thoại, chỉ chứa số và dấu +
            'email' => 'required|string|email|max:45', // Email, phải đúng định dạng email
            'status' => 'required|in:active,inactive', // Giả sử các giá trị ENUM
        ]);

        // Tạo nhà cung cấp mới
        $supplier = Supplier::create($validated);

        return response()->json([
            'message' => 'Tạo nhà cung cấp thành công',
            'data' => $supplier
        ], 201);
    }


    public function getById(Request $request, $id)
    {
        // Tìm nhà cung cấp theo ID
        $supplier = Supplier::find($id);

        // Kiểm tra xem nhà cung cấp có tồn tại không
        if (!$supplier) {
            return response()->json([
                'message' => 'Không tìm thấy nhà cung cấp'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy nhà cung cấp thành công',
            'data' => $supplier
        ]);
    }
}