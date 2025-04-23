<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Import;
use Illuminate\Http\Request;

class Imports extends Controller
{

    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $supplier_id = $request->query('supplier_id');
        $employee_id = $request->query('employee_id');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limt = $request->query('limit', 10);

        $query = Import::query();
        if ($search) {
            $query->where('id', 'like', '%' . $search . '%')
                ->orWhere('supplier_id', 'like', '%' . $search . '%')
                ->orWhere('employee_id', 'like', '%' . $search . '%');     
        }
        if ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        }
        if ($employee_id) {
            $query->where('employee_id', $employee_id);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($date_start && $date_end) {
            $query->whereBetween('created_at', [$date_start, $date_end]);
        }
        if ($date_start && !$date_end) {
            $query->where('created_at', '>=', $date_start);
        }
        if (!$date_start && $date_end) {
            $query->where('created_at', '<=', $date_end);
        }

        $imports = $query->paginate($limit);
        $imports->appends([
            'search' => $search,
            'supplier_id' => $supplier_id,
            'employee_id' => $employee_id,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);
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