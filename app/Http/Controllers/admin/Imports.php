<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Imports extends Controller
{
    //5.1. Lấy danh sách phiếu nhập hàng
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $suppiler_id  = $request->query('suppiler_id ');
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
        if ($suppiler_id ) {
            $query->where('suppiler_id ', $suppiler_id );
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
            'suppiler_id ' => $suppiler_id ,
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

// 5.2. Tạo phiếu nhập hàng
public function create(Request $request)
    {
        // $validated = $request->validate([
        //     'suppiler_id ' => 'required|integer|exists:supplier,id',
        //     'employee_id' => 'required|integer|exists:account,id',
        //     'import_detail' => 'required|array',
        //     'import_detail.*.product_id' => 'required|integer|exists:product,id',
        //     'import_detail.*.amount' => 'required|integer|min:1',
        // ]);
        $validated = $request->validate([
            'suppiler_id' => 'required|integer|exists:supplier,id',
            'employee_id' => 'required|integer|exists:account,id',
            'import_details' => 'required|array',
            'import_details.*.product_variant_id' => 'required|integer|exists:product_variants,id',
            'import_details.*.amount' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Create the import record with default status 'pending'
            $import = Import::create([
                'suppiler_id' => $validated['suppiler_id'],
                'employee_id' => $validated['employee_id'],
                'status' => 'pending',
             'created_at' => now()
            ]);
          
            

            // Create import details using product price from products table
            foreach ($validated['import_details'] as $detail) {
                $product = Product::find($detail['product_variant_id']);
                ImportDetail::create([
                    'import_id' => $import->id,
                    'product_variant_id' => $detail['product_variant_id'],
                    'import_price' => $product->base_price, // Use base_price from products
                    'amount' => $detail['amount'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Tạo phiếu nhập hàng thành công',
                'data' => $import->load('importDetails')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Tạo phiếu nhập hàng thất bại: ' . $e->getMessage()
            ], 422);
        }
    }


    // 5.4. Cập nhật trạng thái phiếu nhập hàng
    public function updateStatus(Request $request, $id)
    {
        $import = Import::find($id);

        if (!$import) {
            return response()->json([
                'message' => 'Không tìm thấy phiếu nhập hàng'
            ], 404);
        }

        if ($import->status === 'completed') {
            return response()->json([
                'message' => 'Không thể cập nhật vì phiếu nhập hàng đã ở trạng thái hoàn thành'
            ], 422);
        }
        if ($import->status === 'completed') {
            return response()->json([
                'message' => 'Không thể cập nhật vì phiếu nhập hàng đã ở trạng thái hủy'
            ], 422);
        }

        $validated = $request->validate([
            'status' => 'required|in:processing,completed,cancelled,pending'
        ]);

        $import->update($validated);

        if ($import->status === 'completed') {
            $this->updateProduct($id); // Call the method to update stock
        }

        return response()->json([
            'message' => 'Cập nhật trạng thái phiếu nhập hàng thành công',
            'data' => $import
        ], 200);
    }

public function updateProduct($id)
{
    \Log::info("Bắt đầu cập nhật tồn kho từ phiếu nhập ID: {$id}");

    $importDetails = ImportDetail::where('import_id', $id)->get();

    if ($importDetails->isEmpty()) {
        \Log::info("Không tìm thấy chi tiết nhập hàng với import_id = {$id}");
        return;
    }

    \DB::beginTransaction();
    try {
        $updates = [];
        foreach ($importDetails as $detail) {
            $variantId = $detail->product_variant_id;
            $amount = $detail->amount;

            if ($amount <= 0) {
                \Log::warning("Số lượng không hợp lệ cho variant_id = {$variantId} trong import_id = {$id}, amount = {$amount}");
                continue;
            }

            $updates[$variantId] = ($updates[$variantId] ?? 0) + $amount;
            \Log::info("-> Nhập sản phẩm: variant_id = {$variantId}, số lượng = {$amount}, tổng tạm tính = {$updates[$variantId]}");
        }

        foreach ($updates as $variantId => $totalAmount) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                $currentStock = $variant->stock;
                $newStock = $currentStock + $totalAmount;

                \Log::info("==> Cập nhật tồn kho: variant_id = {$variantId}, tồn hiện tại = {$currentStock}, thêm = {$totalAmount}, tồn mới = {$newStock}");

                ProductVariant::where('id', $variantId)->update([
                    'stock' => \DB::raw("stock + {$totalAmount}")
                ]);
            } else {
                \Log::warning("Không tìm thấy biến thể sản phẩm với ID = {$variantId}");
            }
        }

        \DB::commit();
        \Log::info("✅ Hoàn tất cập nhật tồn kho cho import_id = {$id}");
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error("❌ Lỗi cập nhật tồn kho với import_id = {$id}: " . $e->getMessage());
        throw $e;
    }
}


    //5.5. Xóa phiếu nhập hàng
    public function cancelImport($id)
    {
        $import = Import::with('importDetails')->find($id);

        if (!$import) {
            return response()->json([
                'message' => 'Không tìm thấy phiếu nhập hàng'
            ], 404);
        }

        // Xóa chi tiết phiếu nhập trước
        $import->importDetails()->delete();

        // Sau đó xóa phiếu nhập
        $import->delete();

        return response()->json([
            'message' => 'Xóa phiếu nhập hàng thành công'
        ]);
    }

}