<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Get the role value - handle both Enum and string cases
        $userRole = $request->user()->role;
        $userRoleValue = $userRole instanceof \App\Enums\UserRole 
            ? $userRole->value 
            : $userRole;

        if (!in_array($userRoleValue, $roles)) {
            abort(403, 'Unauthorized. You do not have permission to access this page.');
        }

        return $next($request);
    }
}
