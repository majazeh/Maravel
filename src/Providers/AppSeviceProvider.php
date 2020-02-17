<?php

namespace Maravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Blade;
use App\Guardio;
use Maravel\Middleware\Response;
use Illuminate\Routing\Router;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Cache;
use Maravel\Middleware\Authenticate as MaravelAuthenticate;
use Illuminate\Support\Facades\Config;

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
        }
        if($this->app->runningInConsole())
        {
            $this->publishes([
                maravel_path('assets/public') => public_path('/'),
                maravel_path('assets/resources') => resource_path('/'),
                maravel_path('assets/webpack.mix.js') => base_path('/webpack.mix.js')
                ]);
            $this->loadMigrationsFrom(maravel_path('migrations'));
        }
        Router::macro('mResource', function($name, $controller, array $options = []){
            if(!isset($options['except']))
            {
                $options['except'] = ['store', 'update', 'destroy'];
            }
            else
            {
                $options['except'] = array_merge_recursive($options['except'], ['store', 'update', 'destroy']);
            }
            if (!isset($options['as'])) {
                $options['as'] = 'dashboard';
            }
            if ($this->container && $this->container->bound(ResourceRegistrar::class)) {
                $registrar = $this->container->make(ResourceRegistrar::class);
            } else {
                $registrar = new ResourceRegistrar($this);
            }

            return new PendingResourceRegistration(
                $registrar,
                $name,
                $controller,
                $options
            );
        });

        Request::macro('webAccess', function(){
            return $this->headers->get('Web-Access') ? true : false;
        });

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('api', Response::class);
        $router->pushMiddlewareToGroup('web', Response::class);
        $router->aliasMiddleware('auth', MaravelAuthenticate::class);

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

        if (config('app.routes.web', true))
        {
            Config::set('breadcrumbs.view', 'layouts.breadcrumbs');
            $breadcrumbs = [maravel_path('routes/breadcrumbs.php')];
            if(file_exists(base_path('routes/breadcrumbs.php')))
            {
                $breadcrumbs[] = base_path('routes/breadcrumbs.php');
            }
            Config::set('breadcrumbs.files', $breadcrumbs);
        }

        Cache::macro('getJson', function(){
            $get = Cache::get(...func_get_args());
            return $get ? json_decode($get) : $get;
        });

    }
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Guardio', \App\Guardio::class);
        });
    }
}
