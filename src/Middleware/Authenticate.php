<?php

namespace Maravel\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class Authenticate
{
    public function handle($request, Closure $next/*, ...$guards*/)
    {
        if (Cookie::get('maravel-token')) {
            $request->headers->set('Content-Type', 'application/json');
            $request->headers->set('Authorization', 'Bearer ' . Cookie::get('maravel-token'));
        }
        return $next($request);
    }
}
