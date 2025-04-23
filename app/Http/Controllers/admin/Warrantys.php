<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Warranty;
use Illuminate\Http\Request;

class Warrantys extends Controller
{
    /**
     * Lấy danh sách tất cả bảo hành
     */
    public function getAll(Request $request)
    {
        $warranties = Warranty::all();
        return response()->json([
            'message' => 'Lấy danh sách bảo hành thành công',
            'data' => $warranties
        ]);
    }


    public function create(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:accounts,id',
            'product_id' => 'required|integer|exists:products,id', 
            'supplier_id' => 'required|integer|exists:suppliers,id', 
            'issue_date' => 'required|date_format:Y-m-d',
            'expiration_date' => 'required|date_format:Y-m-d|after_or_equal:issue_date',
            'status' => 'required|in:pending,approved,rejected', // lưu các trạng thái tú
            'note' => 'nullable|string', // Ghi chú có thể để trống
        ]);

        // Tạo bảo hành mới
        $warranty = Warranty::create($validated);

        return response()->json([
            'message' => 'Tạo bảo hành thành công',
            'data' => $warranty
        ], 201);
    }

    /**
     * Lấy thông tin một bảo hành theo ID
     */
    public function getById(Request $request, $id)
    {
        // Tìm bảo hành theo ID
        $warranty = Warranty::find($id);

        // Kiểm tra xem bảo hành có tồn tại không
        if (!$warranty) {
            return response()->json([
                'message' => 'Không tìm thấy bảo hành'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy bảo hành thành công',
            'data' => $warranty
        ]);
    }
}