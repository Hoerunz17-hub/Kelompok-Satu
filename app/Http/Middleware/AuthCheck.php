<?php

namespace App\Http\Middleware;

use Closure;

class AuthCheck
{
    public function handle($request, Closure $next)
    {
        if (!session('login')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
