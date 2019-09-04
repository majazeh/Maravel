<?php

namespace Maravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Blade;
use Maravel\Providers\Guardio\GuardioRegistration;

class AppServiceProvider extends ServiceProvider
{
    use GuardioRegistration;

    public function boot()
    {

        $this->publishes([maravel_path('assets') => public_path('/')]);

        $router = $this->app['router'];

        $GuardioMiddleware = \Maravel\Middleware\Guardio::class;

        $this->registerGuardio();

        $ResponseMiddleware = \Maravel\Middleware\Response::class;
        $router->pushMiddlewareToGroup('api', $ResponseMiddleware);
        $router->pushMiddlewareToGroup('web', $ResponseMiddleware);

        $this->loadMigrationsFrom(maravel_path('migrations'));

        View::addLocation(maravel_path('views'));

        Blade::directive('sort_icon', function ($key) {
            $query = request()->all();
            $query['order'] = $key;
            $query['sort'] = 'asc';
            $asc = Request::create(url()->current(), 'GET', $query)->getUri();
            $query['sort'] = 'desc';
            $desc = Request::create(url()->current(), 'GET', $query)->getUri();
            return "<?php echo isset(\$_GET['order']) && strtolower(\$_GET['order']) == strtolower('$key') ? (isset(\$_GET['sort']) && strtolower(\$_GET['sort']) == 'asc' ? '<a href=\"'. order_link('$key', 'desc') .'\"><i class=\"fas text-primary fa-sort-up\"></i></a>' : '<a href=\"'. order_link('$key', 'asc') .'\"><i class=\"fas text-primary fa-sort-down\"></i></a>') : '<a href=\"'. order_link('$key', 'desc') .'\"><i class=\"fas fa-sort text-black-50\"></i></a>' ?>";
        });

    }
}
