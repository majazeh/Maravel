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
        if ($this->app->request->is('api/*') || $this->app->request->ajax()) {
            if($this->app->request->is('api/*'))
            {
                $this->app->request->headers->set('Accept', 'application/json');
            }
            $this->app->bind(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                \Maravel\Exceptions\ExceptionHandler::class
            );
        }
        $this->publishes([
            maravel_path('assets/public') => public_path('/'),
            maravel_path('assets/resources') => resource_path('/')
            ]);
        $router = $this->app['router'];
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

        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            return new \App\Validators\Maravel($translator, $data, $rules, $messages);
        });

    }
}
