<?php

namespace Maravel\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function map(Router $router)
    {
        $this->webRoutes($router);
        $this->apiRoutes($router);
    }

    public function webRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\Maravel\Controllers\Dashboard',
            'prefix' => 'dashboard',
            'middleware' => 'web'
        ], function ($router) {
            require_once(__DIR__ . '/../Routes/web.php');
        });
    }
    public function apiRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\Maravel\Controllers\API',
            'prefix' => 'dashboard',
            'middleware' => 'api'
        ], function ($router) {
            require_once(__DIR__ . '/../Routes/api.php');
        });
    }
}
