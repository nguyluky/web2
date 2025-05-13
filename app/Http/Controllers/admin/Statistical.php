<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\ImportDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class Statistical extends Controller
{
    protected function getDateFormat($type, $column, $asAlias = true)
    {
        switch ($type) {
            case 'year':
                return $asAlias
                    ? DB::raw("DATE_FORMAT($column, '%m') as period")
                    : 'period';
            case 'month':
                return $asAlias
                    ? DB::raw("DATE_FORMAT($column, '%d/%m') as period")
                    : 'period';
            case 'day':
                return $asAlias
                    ? DB::raw("DATE_FORMAT($column, '%H:00') as period")
                    : 'period';
            default:
                throw new \Exception('Loại thống kê không hợp lệ');
        }
    }

    protected function getPeriods($type, $year, $month = 1)
    {
        switch ($type) {
            case 'year':
                return [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];
            case 'month':
                $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                return array_map(fn($day) => sprintf("%02d/%02d", $day, $month), range(1, $days));
            case 'day':
                return array_map(fn($hour) => sprintf("%02d:00", $hour), range(0, 23));
            default:
                return [];
        }
    }

    public function revenueCost(Request $request)
    {
        $year = $request->query('year', now()->year);
        $type = $request->query('type', 'month');
        $month = $request->query('month', 1); // Thêm tham số month
        $day = $request->query('day', 1);     // Thêm tham số day

        // Validate parameters
        $request->validate([
            'year' => 'integer|min:2000|max:' . now()->year,
            'type' => 'in:year,month,day',
            'month' => 'integer|min:1|max:12',
            'day' => 'integer|min:1|max:31',
        ]);

        // Base query for revenue (from Order)
        $revenueQuery = Order::select(
            DB::raw('SUM(amount) as thu'),
            $this->getDateFormat($type, 'created_at')
        )
            ->whereYear('created_at', $year);

        // Base query for cost (from ImportDetail)
        $costQuery = ImportDetail::select(
            DB::raw('SUM(import_price * amount) as chi'),
            $this->getDateFormat($type, 'created_at')
        )
            ->whereYear('created_at', $year);

        // Áp dụng bộ lọc tháng và ngày nếu cần
        if ($type === 'month' || $type === 'day') {
            $revenueQuery->whereMonth('created_at', $month);
            $costQuery->whereMonth('created_at', $month);
        }
        if ($type === 'day') {
            $revenueQuery->whereDay('created_at', $day);
            $costQuery->whereDay('created_at', $day);
        }

        $revenueQuery->groupBy($this->getDateFormat($type, 'created_at'));
        $costQuery->groupBy($this->getDateFormat($type, 'created_at'));

        // Execute queries
        $revenues = $revenueQuery->get()->keyBy($this->getDateFormat($type, 'created_at', false));
        $costs = $costQuery->get()->keyBy($this->getDateFormat($type, 'created_at', false));

        // Combine results
        $data = [];
        $periods = $this->getPeriods($type, $year, $month);
        foreach ($periods as $period) {
            $data[] = [
                'name' => $period,
                'thu' => isset($revenues[$period]) ? (float)$revenues[$period]->thu : 0,
                'chi' => isset($costs[$period]) ? (float)$costs[$period]->chi : 0,
            ];
        }

        return response()->json($data);
    }
}