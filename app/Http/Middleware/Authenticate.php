<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Auth::guard($guards)->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}