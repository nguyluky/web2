<?php

namespace App\Http\Controllers\admin;

use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductVariants extends Controller
{
    public function getAll(Request $request)
    {
        $query = ProductVariant::query()->get();
        return response()->json([
            'message' => 'Lấy danh sách thành công',
            'data' => $query
        ]);
    }

    public function search(Request $request)
    {
        $query = ProductVariant::query()->with('product');

        // Lọc theo từ khóa
        if ($keyword = $request->input('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })->orWhere('attributes', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Lọc theo ID biến thể
        if ($variantId = $request->input('variant_id')) {
            $query->where('id', $variantId);
        }

        // Phân trang với limit 10
        $perPage = 10;
        $page = $request->input('page', 1);
        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'Tìm kiếm biến thể sản phẩm thành công',
            'data' => $results
        ]);
    }

    public function getById($id)
    {
        $query = ProductVariant::query()->find($id);
        if (!$query) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }
        return response()->json([
            'message' => 'Lấy thông tin thành công',
            'data' => $query
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'sku' => 'nullable|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive,out_of_stock',
            'attributes' => 'nullable|array',
        ]);

        if (isset($validated['attributes']) && is_array($validated['attributes'])) {
            $validated['attributes'] = json_encode($validated['attributes']);
        }

        $productVariant = ProductVariant::create($validated);

        return response()->json([
            'message' => 'Tạo biến thể sản phẩm thành công',
            'data' => $productVariant
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $productVariant = ProductVariant::find($id);
        
        if (!$productVariant) {
            return response()->json([
                'message' => 'Không tìm thấy biến thể sản phẩm'
            ], 404);
        }
        
        $validated = $request->validate([
            'sku' => 'nullable|string|max:255|unique:product_variants,sku,' . $id,
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive,out_of_stock',
            'attributes' => 'nullable|array',
        ]);
        
        if (isset($validated['attributes']) && is_array($validated['attributes'])) {
            $validated['attributes'] = json_encode($validated['attributes']);
        }
        
        $productVariant->update($validated);
        
        return response()->json([
            'message' => 'Cập nhật biến thể sản phẩm thành công',
            'data' => $productVariant
        ]);
    }

    public function delete($id)
    {
        $productVariant = ProductVariant::find($id);
        
        if (!$productVariant) {
            return response()->json([
                'message' => 'Không tìm thấy biến thể sản phẩm'
            ], 404);
        }
        
        $productVariant->delete();
        
        return response()->json([
            'message' => 'Xóa biến thể sản phẩm thành công'
        ]);
    }
}