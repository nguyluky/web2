<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\CreateProductsRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Products extends Controller
{

    //1.1 Lấy danh sách sản phẩm
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limit = $request->query('limit', 10);
    
        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
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
    
        $products = $query->paginate($limit);
        $products->appends([
            'search' => $search,
            'category_id' => $categoryId,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limit
        ]);
    
        return response()->json([
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $products
        ]);
    }

    //1.2 Tạo sản phẩm
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

    //1.3 Lấy sản phẩm theo ID
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

    //1.4 Cập nhật sản phẩm
    public function update(CreateProductsRequest $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Cập nhật thông tin sản phẩm
        $validatedData = $request->validated();
        $product->update($validatedData);

        return response()->json([
            'message' => 'Cập nhật sản phẩm thành công',
            'data' => $product
        ]);
    }

//1.5 Xóa sản phẩm
    public function delete($id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Xóa sản phẩm
        $product->delete();

        return response()->json([
            'message' => 'Xóa sản phẩm thành công'
        ]);
    }
    //1.6 Thêm trạng thái sản phẩm
    public function addStatus(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Thêm trạng thái sản phẩm
        $product->status = $request->input('status');
        $product->save();

        return response()->json([
            'message' => 'Thêm trạng thái sản phẩm thành công',
            'data' => $product
        ]);
    }
//1.7 Thay đổi trạng thái sản phẩm
    public function updateStatus(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Thay đổi trạng thái sản phẩm
        $product->status = $request->input('status');
        $product->save();

        return response()->json([
            'message' => 'Thay đổi trạng thái sản phẩm thành công',
            'data' => $product
        ]);
    }
    //1.8 Xóa trạng thái sản phẩm
    public function deleteStatus($id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Xóa trạng thái sản phẩm
        $product->status = null;
        $product->save();

        return response()->json([
            'message' => 'Xóa trạng thái sản phẩm thành công',
            'data' => $product
        ]);
    }


}