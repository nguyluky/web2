<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\ImportDetail;
use Illuminate\Http\Request;

class ImportDetails extends Controller
{
    /**
     * Lấy danh sách tất cả chi tiết nhập hàng
     */

    public function getAll(Request $request)
    {
        $query = ImportDetail::query()->get();
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $query
        ]);
    }


    /**
     * Tạo một chi tiết nhập hàng mới
     */
    public function create(Request $request)
    {
        // Xác thực dữ liệu đầu vào dựa trên cấu trúc bảng import_detail
        $validated = $request->validate([
            'import_id' => 'required|integer|exists:imports,id', // Kiểm tra import_id tồn tại trong bảng imports
            'product_id' => 'required|integer|exists:products,id', // Kiểm tra product_id tồn tại trong bảng products
            'import_price' => 'required|integer|min:0', // Giá nhập phải là số nguyên, không âm
        ]);

        // Tạo chi tiết nhập hàng mới
        $import_detail = ImportDetail::create($validated);

        return response()->json([
            'message' => 'Tạo chi tiết nhập hàng thành công',
            'data' => $import_detail
        ], 201);
    }


    public function getById(Request $request, $id)
    {
        // Tìm chi tiết nhập hàng theo ID
        $import_detail = ImportDetail::find($id);

        // Kiểm tra xem chi tiết nhập hàng có tồn tại không
        if (!$import_detail) {
            return response()->json([
                'message' => 'Không tìm thấy chi tiết nhập hàng'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy chi tiết nhập hàng thành công',
            'data' => $import_detail
        ]);
    }
}