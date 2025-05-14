<?php

namespace App\Http\Controllers\admin;

use App\Models\ProductVariant;
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

    /**
     * Tạo một biến thể sản phẩm mới
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:product,id',
            'sku' => 'nullable|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive,out_of_stock',
            'specifications' => 'nullable|array',
        ]);

        // Chuyển đổi specifications thành JSON nếu cần
        if (isset($validated['specifications']) && is_array($validated['specifications'])) {
            $validated['specifications'] = json_encode($validated['specifications']);
        }

        // Tạo biến thể sản phẩm mới
        $productVariant = ProductVariant::create($validated);

        return response()->json([
            'message' => 'Tạo biến thể sản phẩm thành công',
            'data' => $productVariant
        ], 201);
    }

    /**
     * Cập nhật thông tin biến thể sản phẩm
     */
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
            'specifications' => 'nullable|array',
        ]);
        
        // Chuyển đổi specifications thành JSON nếu cần
        if (isset($validated['specifications']) && is_array($validated['specifications'])) {
            $validated['specifications'] = json_encode($validated['specifications']);
        }
        
        $productVariant->update($validated);
        
        return response()->json([
            'message' => 'Cập nhật biến thể sản phẩm thành công',
            'data' => $productVariant
        ]);
    }

    /**
     * Xóa một biến thể sản phẩm
     */
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