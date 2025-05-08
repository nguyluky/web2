<?php

namespace App\Http\Controllers\admin;

use Illuminate\Routing\Controller;
use App\Models\Order;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Statistical extends Controller
{
    public function revenueCost(Request $request)
    {
        $year = $request->query('year', now()->year);
        $type = $request->query('type', 'month'); // year, month, day

        // Validate parameters
        $request->validate([
            'year' => 'integer|min:2000|max:' . now()->year,
            'type' => 'in:year,month,day',
        ]);

        // Base query for revenue (from Order)
        $revenueQuery = Order::select(
            DB::raw('SUM(amount) as revenue'),
            $this->getDateFormat($type, 'created_at')
        )
            ->whereYear('created_at', $year)
            ->groupBy($this->getDateFormat($type, 'created_at'));

        // Base query for cost (from ImportDetail)
        $costQuery = ImportDetail::select(
            DB::raw('SUM(import_price * amount) as cost'),
            $this->getDateFormat($type, 'created_at')
        )
            ->whereYear('created_at', $year)
            ->groupBy($this->getDateFormat($type, 'created_at'));

        // Execute queries
        $revenues = $revenueQuery->get()->keyBy($this->getDateFormat($type, 'created_at', false));
        $costs = $costQuery->get()->keyBy($this->getDateFormat($type, 'created_at', false));

        // Combine results
        $data = [];
        $periods = $this->getPeriods($type, $year);
        foreach ($periods as $period) {
            $data[] = [
                'period' => $period,
                'revenue' => $revenues[$period]->revenue ?? 0,
                'cost' => $costs[$period]->cost ?? 0,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * 7.2. Thống kê tồn kho
     */
    public function inventory(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $search = $request->query('search');
        $sort = $request->query('sort', 'id'); // id, name, stock

        // Validate parameters
        $request->validate([
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
            'sort' => 'in:id,name,stock',
        ]);

        // Base query
        $query = Product::select('id', 'name')
            ->selectRaw('(SELECT SUM(amount) FROM import_details WHERE product_id = products.id) - (SELECT SUM(amount) FROM order_details WHERE product_id = products.id) as stock');

        // Search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Sorting
        $query->orderBy($sort == 'stock' ? 'stock' : "products.{$sort}");

        // Paginate
        $inventory = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data' => $inventory->items(),
            'pagination' => [
                'current_page' => $inventory->currentPage(),
                'total_pages' => $inventory->lastPage(),
                'total' => $inventory->total(),
            ],
        ]);
    }

    /**
     * 7.3. Thống kê dashboard
     */
    public function dashboard()
    {
        $totalRevenue = Order::sum('amount');
        $totalCost = ImportDetail::sum(DB::raw('import_price * amount'));
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_cost' => $totalCost,
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
            ],
        ]);
    }

    /**
     * 7.4. Thống kê doanh thu theo sản phẩm
     */
    public function revenueByProducts(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month');
        $limit = $request->query('limit', 10);

        // Validate parameters
        $request->validate([
            'year' => 'integer|min:2000|max:' . now()->year,
            'month' => 'nullable|integer|min:1|max:12',
            'limit' => 'integer|min:1|max:100',
        ]);

        // Base query
        $query = \App\Models\OrderDetail::select(
            'products.id',
            'products.name',
            DB::raw('SUM(order_details.amount * order_details.price) as revenue') // Tính doanh thu từ số lượng và giá
        )
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereYear('order_details.created_at', $year)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue');

        // Filter by month if provided
        if ($month) {
            $query->whereMonth('order_details.created_at', $month);
        }

        // Limit results
        $data = $query->take($limit)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * 7.5. Thống kê doanh thu theo danh mục
     * GET /api/admin/statistics/revenue-by-categories
     */
    public function revenueByCategories(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month');
        $limit = $request->query('limit', 10);

        // Validate parameters
        $request->validate([
            'year' => 'integer|min:2000|max:' . now()->year,
            'month' => 'nullable|integer|min:1|max:12',
            'limit' => 'integer|min:1|max:100',
        ]);

        // Base query
        $query = \App\Models\OrderDetail::select(
            'categories.id',
            'categories.name',
            DB::raw('SUM(order_details.amount * order_details.price) as revenue')
        )
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereYear('order_details.created_at', $year)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue');

        // Filter by month if provided
        if ($month) {
            $query->whereMonth('order_details.created_at', $month);
        }

        // Limit results
        $data = $query->take($limit)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * Helper: Get date format for grouping
     */
    private function getDateFormat($type, $column, $raw = true)
    {
        $formats = [
            'year' => 'YEAR',
            'month' => 'DATE_FORMAT(%s, "%%Y-%%m")',
            'day' => 'DATE_FORMAT(%s, "%%Y-%%m-%%d")',
        ];

        if ($raw) {
            return sprintf($formats[$type], $column);
        }

        return $type === 'year' ? 'year' : ($type === 'month' ? 'year_month' : 'date');
    }

    /**
     * Helper: Get periods for revenue-cost
     */
    private function getPeriods($type, $year)
    {
        if ($type === 'year') {
            return [$year];
        }

        if ($type === 'month') {
            return array_map(function ($month) use ($year) {
                return sprintf('%d-%02d', $year, $month);
            }, range(1, 12));
        }

        // For day type
        $daysInYear = (new \DateTime("{$year}-12-31"))->format('z') + 1;
        return array_map(function ($day) use ($year) {
            return (new \DateTime("{$year}-01-01"))->modify("+{$day} days")->format('Y-m-d');
        }, range(0, $daysInYear - 1));
    }
}