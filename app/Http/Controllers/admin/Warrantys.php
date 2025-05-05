<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Warranty;
use Illuminate\Http\Request;

class Warrantys extends Controller
{   
    //6.1 Lấy danh sách bảo hành
    public function getAll(Request $request)
    {

        // Lấy các tham số query
        $search = $request->query('search');
        $accountId = $request->query('account_id');
        $productId = $request->query('product_id');
        $supplierId = $request->query('supplier_id');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limit = $request->query('limit', 10);

        $query = Warranty::query();
        if ($search) {
            $query->where('note', 'like', '%' . $search . '%');
        }

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($date_start && $date_end) {
            $query->whereBetween('issue_date', [$date_start, $date_end]);
        }
        if ($date_start && !$date_end) {
            $query->where('issue_date', '>=', $date_start);
        }
        if (!$date_start && $date_end) {
            $query->where('issue_date', '<=', $date_end);
        }

        $warranties = $query->paginate($limit);

        $warranties->appends([
            'search' => $search,
            'account_id' => $accountId,
            'product_id' => $productId,
            'supplier_id' => $supplierId,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);

        return response()->json([
            'message' => 'Lấy danh sách bảo hành thành công',
            'data' => $warranties
        ]);
    }

    //6.2 Tạo bảo hành mới
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

    //6.3 Lấy thông tin bảo hành theo ID
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

    //6.4 Cập nhật thông tin bảo hành theo ID
    public function update(Request $request, $id)
    {
        // Tìm bảo hành theo ID
        $warranty = Warranty::find($id);

        // Kiểm tra xem bảo hành có tồn tại không
        if (!$warranty) {
            return response()->json([
                'message' => 'Không tìm thấy bảo hành'
            ], 404);
        }

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'account_id' => 'sometimes|required|integer|exists:accounts,id',
            'product_id' => 'sometimes|required|integer|exists:products,id', 
            'supplier_id' => 'sometimes|required|integer|exists:suppliers,id', 
            'issue_date' => 'sometimes|required|date_format:Y-m-d',
            'expiration_date' => 'sometimes|required|date_format:Y-m-d|after_or_equal:issue_date',
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'note' => 'nullable|string',
        ]);

        // Cập nhật thông tin bảo hành
        $warranty->update($validated);

        return response()->json([
            'message' => 'Cập nhật bảo hành thành công',
            'data' => $warranty
        ]);
    }
    //6.5 Xóa bảo hành theo ID
    public function delete($id)
    {
        // Tìm bảo hành theo ID
        $warranty = Warranty::find($id);

        // Kiểm tra xem bảo hành có tồn tại không
        if (!$warranty) {
            return response()->json([
                'message' => 'Không tìm thấy bảo hành'
            ], 404);
        }

        // Xóa bảo hành
        $warranty->delete();

        return response()->json([
            'message' => 'Xóa bảo hành thành công'
        ]);
    }
}