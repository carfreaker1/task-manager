<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // Super Admin bypass
        if ($user->role->name == 'Super Admin') {
            return $next($request);
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'Unauthorized Access');
        }
        return $next($request);
    }
}
