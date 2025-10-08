<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  // Add for debugging

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Split roles string into array: "nurse|surgeon" -> ['nurse','surgeon']= explode('|', $roles);
        $allowedRoles = explode('|', $roles);

        if ($user->role === 'admin' || in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        Log::warning("Role check failed for user {$user->id}: {$user->role} not in " . implode(', ', $allowedRoles));

        abort(403, 'Unauthorized Access Attempt.');
    }
}
