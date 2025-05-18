<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\CreateProductsRequest;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use OpenApi\Annotations as OA;


function str_slug($text) : string {
    return str_replace(' ', '-', strtolower($text));
}

/**
 * @OA\Tag(
 *     name="Admin Products",
 *     description="API Endpoints quản lý sản phẩm (admin)"
 * )
 */
class Products extends Controller
{

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

    public function create(Request $request)
    {

        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            // Product Info
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category_id' => 'nullable|integer|exists:categories,id',

            // Pricing
            'base_price' => 'required|numeric|min:0',
            'base_original_price' => 'nullable|numeric|min:0',

            // Inventory
            'status' => 'nullable|string|in:active,inactive,draft',

            // JSON Data
            'specifications' => 'nullable|array',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'meta_data' => 'nullable|array',
        ]);

        $validatedData['slug'] = str_slug($validatedData['name']);
        while (Product::where('slug', $validatedData['slug'])->exists()) {
            $validatedData['slug'] = str_slug($validatedData['name']) . '-' . uniqid();
        }
        $success = Product::create($validatedData);
        if ($success) {
            return response()->json([
                'message' => 'Tạo sản phẩm thành công',
                'data' => $success
            ], 201);
        } else {
            return response()->json([
                'message' => 'Tạo sản phẩm thất bại'
            ], 500);
        }
    }


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

    public function update(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);

        // Kiểm tra xem sản phẩm có tồn tại không
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            // Product Info
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category_id' => 'nullable|integer|exists:categories,id',

            // Pricing
            'base_price' => 'required|numeric|min:0',
            'base_original_price' => 'nullable|numeric|min:0',

            // Inventory
            'sku' => 'nullable|string|max:100|unique:product,sku,'.$id,
            'status' => 'nullable|string|in:active,inactive,draft',

            // JSON Data
            'specifications' => 'nullable|array',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'meta_data' => 'nullable|array',
        ]);

        // Xử lý slug nếu tên thay đổi
        if (isset($validatedData['name']) && $validatedData['name'] !== $product->name) {
            $validatedData['slug'] = str_slug($validatedData['name']);
            while (Product::where('slug', $validatedData['slug'])->where('id', '!=', $id)->exists()) {
                $validatedData['slug'] = str_slug($validatedData['name']) . '-' . uniqid();
            }
        }

        // Cập nhật thông tin sản phẩm
        $product->update($validatedData);

        return response()->json([
            'message' => 'Cập nhật sản phẩm thành công',
            'data' => $product
        ]);
    }

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

    public function search(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->filled('keyword')) {
                $keyword = $request->input('keyword');
            
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%")
                      ->orWhere('base_original_price', 'like', "%$keyword%")
                      ->orWhereHas('product_variants', function ($q2) use ($keyword) {
                          $q2->whereRaw("CAST(stock AS CHAR) LIKE ?", ["%$keyword%"]);
                      })
                      ->orWhereHas('category', function ($q3) use ($keyword) {
                          $q3->where('name', 'like', "%$keyword%");
                      });
                });
            }

            // Lọc theo status
            if ($request->has('status') && $request->input('status') !== 'all') {
                    $query->where('status', $request->input('status'));
            }
            // Lọc theo khoảng thời gian
            if ($request->filled('date_start') && $request->filled('date_end')) {
                $start = $request->input('date_start');
                $end = $request->input('date_end');
            
                $query->whereBetween('created_at', [$start, $end]);
            
            } elseif ($request->filled('date_start')) {
                $start = $request->input('date_start');
                $query->where('created_at', '>=', $start);
            
            } elseif ($request->filled('date_end')) {
                $end = $request->input('date_end');
                $query->where('created_at', '<=', $end);
            }

            // Phân trang
            $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
            $warranties = $query->paginate($perPage);
            return response()->json([
                'message' => 'Tìm kiếm thành công',
                'data' => $warranties
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tìm kiếm sản phẩm: ' . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tìm kiếm sản phẩm',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function topProducts()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $orders = Order::with(['order_details.product_variant.product'])
            ->where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();

        $productCounts = collect();

        foreach ($orders as $order) {
            foreach ($order->order_details as $detail) {
                $product = $detail->product_variant->product;
                if ($product) {
                    $productCounts[$product->id] = ($productCounts[$product->id] ?? 0) + 1;
                }
            }
        }

        $topProducts = collect($productCounts)
            ->sortDesc()
            ->take(5);

        $products = Product::whereIn('id', $topProducts->keys())->get()->map(function ($product) use ($topProducts) {
            $product->total_sold = $topProducts[$product->id];
            return $product;
        });

        return $products->sortByDesc('total_sold')->values();
    }
}