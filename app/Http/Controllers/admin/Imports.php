<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use Illuminate\Http\Request;

class Imports extends Controller
{

    public function getAll(Request $request)
    {
        $imports = Import::all();
        return response()->json([
            'message' => 'Lấy danh sách phiếu nhập hàng thành công',
            'data' => $imports
        ]);
    }


    public function create(Request $request)
    {
        // Xác thực dữ liệu đầu vào dựa trên cấu trúc bảng import
        $validated = $request->validate([
            'supplier_id' => 'required|integer|exists:suppliers,id', // Kiểm tra supplier_id tồn tại trong bảng suppliers
            'employee_id' => 'required|integer|exists:employees,id', // Kiểm tra employee_id tồn tại trong bảng employees
            'status' => 'required|in:pending,completed,cancelled', // Giả sử các giá trị trạng thái
        ]);

        // Tạo phiếu nhập hàng mới
        $imports = Import::create($validated);

        return response()->json([
            'message' => 'Tạo phiếu nhập hàng thành công',
            'data' => $imports
        ], 201);
    }


    public function getById(Request $request, $id)
    {
        // Tìm phiếu nhập hàng theo ID
        $import = Import::find($id);

        // Kiểm tra xem phiếu nhập hàng có tồn tại không
        if (!$import) {
            return response()->json([
                'message' => 'Không tìm thấy phiếu nhập hàng'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy phiếu nhập hàng thành công',
            'data' => $import
        ]);
    }
}