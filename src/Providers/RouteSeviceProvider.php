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
        $this->authRoutes($router);
        require_once(maravel_path('routes/‌breadcrumbs.php'));

    }

    public function webRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\Maravel\Controllers\Dashboard',
            'prefix' => 'dashboard',
            'middleware' => 'web'
        ], function ($router) {
            require_once(maravel_path('routes/web.php'));
        });
    }
    public function apiRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\Maravel\Controllers\API',
            'prefix' => 'api',
            'middleware' => 'api'
        ], function ($router) {
            require_once(maravel_path('routes/api.php'));
        });
    }
    public function authRoutes(Router $router)
    {
        $router->group([
            'namespace' => '\Maravel\Controllers\Auth',
            'prefix' => '',
            'middleware' => 'web'
        ], function ($router) {
            require_once(maravel_path('routes/auth.php'));
        });
    }
}
