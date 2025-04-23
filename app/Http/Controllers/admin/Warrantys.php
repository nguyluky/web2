<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Warranty;
use Illuminate\Http\Request;

class Warrantys extends Controller
{
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