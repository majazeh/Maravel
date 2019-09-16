<?php

namespace Maravel\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Session;

class Authenticate
{
    public function __construct(Auth $auth)
    {
        // dd($auth);
    }

    public function handle($request, Closure $next)
    {

        // dd($request->session());
        // dd(60);
        // if (Cookie::get('maravel-token')) {
        //     $request->headers->set('Content-Type', 'application/json');
        //     $request->headers->set('Authorization', 'Bearer ' . Cookie::get('maravel-token'));
        // }
        return $next($request);
    }
}
