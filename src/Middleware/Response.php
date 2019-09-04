<?php

namespace Maravel\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
class Response
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $controller = $request->route()->getController();
        if($response instanceof \Illuminate\Http\JsonResponse)
        {
            $type = is_array($request->route()->getAction('middleware')) ? $request->route()->getAction('middleware') : [$request->route()->getAction('middleware')];
            if(in_array('web', $type)){
                return $controller->toView($request);
            }
        }
        return $response;
    }
}
