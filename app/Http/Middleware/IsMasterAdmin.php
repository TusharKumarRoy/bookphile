<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMasterAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isMasterAdmin()) {
            abort(403, 'Access denied. Master admin privileges required.');
        }

        return $next($request);
    }
}