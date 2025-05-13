<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Import;
use Illuminate\Http\Request;

class Imports extends Controller
{
    //5.1. Lấy danh sách phiếu nhập hàng
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $supplier_id = $request->query('supplier_id');
        $employee_id = $request->query('employee_id');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limit = $request->query('limit', 10);

        $query = Import::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%');
            });
        }
        if ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        }
        if ($employee_id) {
            $query->where('employee_id', $employee_id);
        }
        // Xử lý lọc theo ngày
        if ($date_start && $date_end) {
            if ($date_start === $date_end) {
                // Lọc đúng trong 1 ngày
                $query->whereBetween('created_at', [
                    $date_start . ' 00:00:00',
                    $date_end . ' 23:59:59'
                ]);
            } else {
                // Khoảng nhiều ngày
                $query->whereBetween('created_at', [
                    $date_start . ' 00:00:00',
                    $date_end . ' 23:59:59'
                ]);
            }
        } elseif ($date_start && !$date_end) {
            $query->where('created_at', '>=', $date_start . ' 00:00:00');
        } elseif (!$date_start && $date_end) {
            $query->where('created_at', '<=', $date_end . ' 23:59:59');
        }

        $imports = $query->paginate($limit);
        $imports->appends([
            'search' => $search,
            'supplier_id' => $supplier_id,
            'employee_id' => $employee_id,
            // 'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);
        return response()->json([
            'message' => 'Lấy danh sách phiếu nhập hàng thành công',
            'data' => $imports
        ]);
    }

    //5.2. Tạo phiếu nhập hàng
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

    //5.3. Cập nhật phiếu nhập hàng
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


    // 5.4. Cập nhật trạng thái phiếu nhập hàng giống với đơn hàng
    public function updateStatus(Request $request, $id)
    {
        // Tìm phiếu nhập hàng theo ID
        $import = Import::find($id);

        // Kiểm tra xem phiếu nhập hàng có tồn tại không
        if (!$import) {
            return response()->json([
                'message' => 'Không tìm thấy phiếu nhập hàng'
            ], 404);
        }

        // Cập nhật trạng thái phiếu nhập hàng
        $import->update($request->only('status'));

        return response()->json([
            'message' => 'Cập nhật trạng thái phiếu nhập hàng thành công',
            'data' => $import
        ]);
    }

    //5.5. Xóa phiếu nhập hàng
    public function cancelImport($id)
    {
        // Tìm phiếu nhập hàng theo ID
        $import = Import::find($id);

        // Kiểm tra xem phiếu nhập hàng có tồn tại không
        if (!$import) {
            return response()->json([
                'message' => 'Không tìm thấy phiếu nhập hàng'
            ], 404);
        }

        // Xóa phiếu nhập hàng
        $import->delete();

        return response()->json([
            'message' => 'Xóa phiếu nhập hàng thành công'
        ]);
    }
}