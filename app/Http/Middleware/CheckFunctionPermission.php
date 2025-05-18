<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RuleFunction;

class CheckFunctionPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $functionCode
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $functionCode)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        }
        
        // Nếu là admin (rule = 1), cho phép tất cả các quyền
        if ($user->rule == 1) {
            return $next($request);
        }
        
        // Kiểm tra quyền truy cập chức năng cụ thể
        $hasPermission = RuleFunction::where('rule_id', $user->rule)
            ->whereHas('function', function($query) use ($functionCode) {
                $query->where('code', $functionCode);
            })
            ->exists();
        
        if (!$hasPermission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền thực hiện chức năng này'
            ], 403);
        }
        
        return $next($request);
    }
}
