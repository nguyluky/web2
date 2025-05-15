<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImages extends Controller
{
    /**
     * Lấy danh sách tất cả hình ảnh sản phẩm
     */
    public function getAll(Request $request)
    {


        $product_images = ProductImage::get();
    
        return response()->json([
            'message' => 'Lấy danh sách hình ảnh sản phẩm thành công',
            'data' => $product_images
        ]);
    }

    /**
     * Lấy thông tin chi tiết về một hình ảnh
     */
    public function getById($id)
    {
        $product_image = ProductImage::with(['product', 'product_variant'])->find($id);
        
        if (!$product_image) {
            return response()->json([
                'message' => 'Không tìm thấy hình ảnh'
            ], 404);
        }
        
        return response()->json([
            'message' => 'Lấy thông tin hình ảnh thành công',
            'data' => $product_image
        ]);
    }

    /**
     * Tạo một hình ảnh sản phẩm mới
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:product,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Kiểm tra sản phẩm tồn tại
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
            $imageUrl = asset('storage/' . $imagePath);
            
            // Nếu đánh dấu là hình ảnh chính, cập nhật các hình ảnh khác
            if (isset($validated['is_primary']) && $validated['is_primary']) {
                ProductImage::where('product_id', $validated['product_id'])
                    ->update(['is_primary' => false]);
            }
            
            // Tạo bản ghi mới
            $product_image = ProductImage::create([
                'product_id' => $validated['product_id'],
                'variant_id' => $validated['variant_id'] ?? null,
                'image_url' => $imageUrl,
                'is_primary' => $validated['is_primary'] ?? false,
                'sort_order' => $validated['sort_order'] ?? 0,
                'created_at' => now()
            ]);

            return response()->json([
                'message' => 'Tạo hình ảnh sản phẩm thành công',
                'data' => $product_image
            ], 201);
        }
        
        return response()->json([
            'message' => 'Không thể tạo hình ảnh sản phẩm',
            'error' => 'Không tìm thấy file ảnh'
        ], 400);
    }

    /**
     * Cập nhật thông tin hình ảnh sản phẩm
     */
    public function update(Request $request, $id)
    {
        $product_image = ProductImage::find($id);
        
        if (!$product_image) {
            return response()->json([
                'message' => 'Không tìm thấy hình ảnh'
            ], 404);
        }
        
        $validated = $request->validate([
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        // Xử lý upload hình ảnh mới (nếu có)
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu không phải URL ngoài
            $oldImageUrl = $product_image->image_url;
            if (strpos($oldImageUrl, url('/storage')) === 0) {
                $oldPath = str_replace(url('/storage') . '/', '', $oldImageUrl);
                Storage::disk('public')->delete($oldPath);
            }
            
            $imagePath = $request->file('image')->store('images/products', 'public');
            $validated['image_url'] = asset('storage/' . $imagePath);
        }
        
        // Nếu đánh dấu là hình ảnh chính, cập nhật các hình ảnh khác
        if (isset($validated['is_primary']) && $validated['is_primary']) {
            ProductImage::where('product_id', $product_image->product_id)
                ->where('id', '!=', $id)
                ->update(['is_primary' => false]);
        }
        
        $product_image->update($validated);
        
        return response()->json([
            'message' => 'Cập nhật hình ảnh sản phẩm thành công',
            'data' => $product_image
        ]);
    }

    /**
     * Xóa một hình ảnh sản phẩm
     */
    public function delete($id)
    {
        $product_image = ProductImage::find($id);
        
        if (!$product_image) {
            return response()->json([
                'message' => 'Không tìm thấy hình ảnh'
            ], 404);
        }
        
        // Xóa file hình ảnh từ storage (nếu không phải URL ngoài)
        $imageUrl = $product_image->image_url;
        if (strpos($imageUrl, url('/storage')) === 0) {
            $path = str_replace(url('/storage') . '/', '', $imageUrl);
            Storage::disk('public')->delete($path);
        }
        
        $product_image->delete();
        
        return response()->json([
            'message' => 'Xóa hình ảnh sản phẩm thành công'
        ]);
    }

    /**
     * Tìm kiếm hình ảnh sản phẩm
     */
    public function search(Request $request)
    {
        $query = ProductImage::query();
        
        if ($request->has('product_id') && !empty($request->input('product_id'))) {
            $query->where('product_id', $request->input('product_id'));
        }
        
        if ($request->has('variant_id') && !empty($request->input('variant_id'))) {
            $query->where('variant_id', $request->input('variant_id'));
        }
        
        if ($request->has('is_primary') && $request->input('is_primary') !== null) {
            $query->where('is_primary', $request->input('is_primary'));
        }
        
        // Phân trang
        $perPage = $request->input('per_page', 10);
        $product_images = $query->with(['product', 'product_variant'])->paginate($perPage);
        
        return response()->json([
            'message' => 'Tìm kiếm thành công',
            'data' => $product_images
        ], 200);
    }
}