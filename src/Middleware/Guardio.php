<?php

namespace Maravel\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
class Guardio
{
    public function handle($request, Closure $next)
    {
        return $next($request);
        $controller = $request->route()->controller;
        if(method_exists($controller, 'permissions'))
        {
            $access = $controller->permissions($request, $request->route()->getAction('as'), ... array_values($request->route()->parameters()));
            if(!$access)
            {
                abort(403);
            }
        }
        return $next($request);
    }
}
