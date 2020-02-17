<?php

namespace Maravel\Middleware;

use Closure;
use App\Http\Middleware\Authenticate as Middleware;
class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        if (!in_array('apiIf', $guards) || $request->server('HTTP_AUTHORIZATION'))
        {
            return parent::authenticate($request, $guards);
        }
    }
}
