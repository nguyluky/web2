<?php

namespace App\Http\Controllers\admin;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class OrderControllerAdmin extends Controller
{
    public function getOrders(Request $request)
    {
        $query = Order::query()->with('user');

        // Lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Lọc theo phương thức thanh toán
        if ($paymentMethod = $request->input('payment_method')) {
            $query->where('payment_method', $paymentMethod);
        }

        // Phân trang với limit 10
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'Lấy danh sách đơn hàng thành công',
            'data' => $results
        ]);
    }

    public function getMonthlyStats(Request $request)
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        // Lấy dữ liệu cho năm hiện tại
        $currentYearStats = DB::table('order')
            ->join('order_detail', 'order.id', '=', 'order_detail.order_id')
            ->join('product_variants', 'order_detail.product_variant_id', '=', 'product_variants.id')
            ->select(
                DB::raw('MONTH(order.created_at) as month'),
                DB::raw('SUM(product_variants.price) as total')
            )
            ->whereYear('order.created_at', $currentYear)
            ->where('order.status', 'completed')
            ->groupBy(DB::raw('MONTH(order.created_at)'))
            ->pluck('total', 'month')
            ->toArray();


        // Lấy dữ liệu cho năm trước
        $previousYearStats = DB::table('order')
            ->join('order_detail', 'order.id', '=', 'order_detail.order_id')
            ->join('product_variants', 'order_detail.product_variant_id', '=', 'product_variants.id')
            ->select(
                DB::raw('MONTH(order.created_at) as month'),
                DB::raw('SUM(product_variants.price) as total')
            )
            ->whereYear('order.created_at', $previousYear)
            ->where('order.status', 'completed')
            ->groupBy(DB::raw('MONTH(order.created_at)'))
            ->pluck('total', 'month')
            ->toArray();


        // Tạo dữ liệu cho 12 tháng
        $months = [
            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
            'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = [
                'name' => $months[$i - 1],
                'năm này' => isset($currentYearStats[$i]) ? (int)$currentYearStats[$i] : 0,
                'năm trước' => isset($previousYearStats[$i]) ? (int)$previousYearStats[$i] : 0,
                'amt' => isset($currentYearStats[$i]) ? (int)$currentYearStats[$i] : 0 // Giả sử amt là total của năm này
            ];
        }

        return response()->json([
            'message' => 'Lấy thống kê hàng tháng thành công',
            'data' => $data
        ]);
    }

    public function getStatusStats(Request $request)
{
    $statusStats = Order::select('status', DB::raw('COUNT(*) as count'))
        ->groupBy('status')
        ->get()
        ->map(function ($item) {
            $name = match ($item->status) {
                'completed' => 'Hoàn thành',
                'processing' => 'Đang xử lý',
                'pending' => 'Chờ xử lý',
                'cancelled' => 'Đã hủy',
                default => 'Không xác định'
            };
            return [
                'name' => $name,
                'value' => (int)$item->count,
                'color' => match ($item->status) {
                    'completed' => '#4CAF50',
                    'processing' => '#2196F3',
                    'pending' => '#FFC107',
                    'cancelled' => '#F44336',
                    default => '#999999'
                }
            ];
        });

    return response()->json([
        'message' => 'Lấy thống kê trạng thái đơn hàng thành công',
        'data' => $statusStats
    ]);
}
}