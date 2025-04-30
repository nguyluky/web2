<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user || !in_array($permission, $user->getPermissionKeys())) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

