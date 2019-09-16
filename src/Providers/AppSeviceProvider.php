<?php

namespace Maravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Blade;
use App\Guardio;
use Maravel\Middleware\Authenticate;
use Maravel\Middleware\Response;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->request->is('api/*') || $this->app->request->ajax()) {
            if($this->app->request->is('api/*'))
            {
                $this->app->request->headers->set('Accept', 'application/json');
            }
            $this->app->bind(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                \Maravel\Exceptions\ExceptionHandler::class
            );
            if($this->app->request->cookie('maravel-token'))
            {
                $this->app->request->headers->set('Authorization', 'Bearer ' . $this->app->request->cookie('maravel-token'));
            }
        }

        if($this->app->runningInConsole())
        {
            $this->publishes([
                maravel_path('assets/public') => public_path('/'),
                maravel_path('assets/resources') => resource_path('/')
                ]);
            $this->loadMigrationsFrom(maravel_path('migrations'));
        }
        $router = $this->app['router'];
        $router->prependMiddlewareToGroup('api', Authenticate::class);
        // dd(get_class_methods($router));
        \Illuminate\Auth\SessionGuard::macro(
                'guardio', function($access)
                {
                    return Guardio::has($access);
                }
        );

        $ResponseMiddleware = Response::class;
        // $this->app['router']->aliasMiddleware('maravel-auth', Authenticate::class);
        $router->pushMiddlewareToGroup('api', $ResponseMiddleware);
        $router->pushMiddlewareToGroup('web', $ResponseMiddleware);


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

        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            return new \App\Validators\Maravel($translator, $data, $rules, $messages);
        });
    }
}
