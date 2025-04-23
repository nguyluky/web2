<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductsRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Products extends Controller
{
    /**
     * Lấy danh sách tất cả sản phẩm
     */
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limt = $request->query('limit', 10);

        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%');
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
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

        $request = $query->paginate($limt);
        $request->appends([
            'search' => $search,
            'category_id' => $categoryId,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limt
        ]);
        return $request;
    }

    public function create(CreateProductsRequest $request)
    {

        $validatedData = $request->validated();
        $success = Product::insert($validatedData);
        if ($success) {
            return response()->json([
                'message' => 'Tạo sản phẩm thành công',
                'data' => $validatedData
            ], 201);
        } else {
            return response()->json([
                'message' => 'Tạo sản phẩm thất bại'
            ], 500);
        }
    }

    /**
     * Lấy thông tin một sản phẩm theo ID
     */
    public function getById(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        return response()->json([
            'message' => 'Lấy sản phẩm thành công',
            'data' => $product
        ]);
    }



}