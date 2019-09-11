<?php

namespace Maravel\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();
    }
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
            require_once(__DIR__ . '/../routes/web.php');
        });
    }
    public function apiRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\Maravel\Controllers\API',
            'prefix' => 'api',
            'middleware' => 'api'
        ], function ($router) {
            require_once(__DIR__ . '/../routes/api.php');
        });
    }
}
