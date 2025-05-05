<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class Suppliers extends Controller
{
    //4.1 Lấy danh sách nhà cung cấp
    public function getAll(Request $request)
    {


        $search = $request->query('search');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limit = $request->query('limit', 10);

        $query = Supplier::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('tax', 'like', '%' . $search . '%');
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

        $suppliers = $query->paginate($limit);

     
        $suppliers->appends([
            'search' => $search,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);

 
        return response()->json([
            'message' => 'Lấy danh sách nhà cung cấp thành công',
            'data' => $suppliers
        ]);
    }

    //4.2 Tạo nhà cung cấp mới
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

    //4.3 lấy thông tin nhà cung cấp theo ID
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
    //4.4 Cập nhật thông tin nhà cung cấp theo ID
    public function update(Request $request, $id)
    {
        // Tìm nhà cung cấp theo ID
        $supplier = Supplier::find($id);

        // Kiểm tra xem nhà cung cấp có tồn tại không
        if (!$supplier) {
            return response()->json([
                'message' => 'Không tìm thấy nhà cung cấp'
            ], 404);
        }

        // Xác thực dữ liệu đầu vào dựa trên cấu trúc bảng supplier
        $validated = $request->validate([
            'name' => 'required|string|max:45', // Tên nhà cung cấp, tối đa 45 ký tự
            'tax' => 'required|string|max:45', // Mã số thuế, tối đa 45 ký tự
            'contact_name' => 'required|string|max:45', // Tên người liên hệ, tối đa 45 ký tự
            'phone_number' => 'required|string|max:45|regex:/^[0-9\+]+$/u', // Số điện thoại, chỉ chứa số và dấu +
            'email' => 'required|string|email|max:45', // Email, phải đúng định dạng email
            'status' => 'required|in:active,inactive', // Giả sử các giá trị ENUM
        ]);

        // Cập nhật thông tin nhà cung cấp
        $supplier->update($validated);

        return response()->json([
            'message' => 'Cập nhật nhà cung cấp thành công',
            'data' => $supplier
        ]);
    }
    //4.5 Xóa nhà cung cấp theo ID
    public function delete($id)
    {
        // Tìm nhà cung cấp theo ID
        $supplier = Supplier::find($id);

        // Kiểm tra xem nhà cung cấp có tồn tại không
        if (!$supplier) {
            return response()->json([
                'message' => 'Không tìm thấy nhà cung cấp'
            ], 404);
        }

        // Xóa nhà cung cấp
        $supplier->delete();

        return response()->json([
            'message' => 'Xóa nhà cung cấp thành công'
        ]);
    }

}