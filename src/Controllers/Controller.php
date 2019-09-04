<?php

namespace Maravel\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use \Maravel\Lib\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Methods;
    public static $result;
    public function __construct(Request $request)
    {
        static::$result = new \StdClass;
        $namespace = explode('\\',get_class($this));
        $class_name = substr(end($namespace), 0, -10);
        if(!isset($this->model))
        {
            $this->model = '\\App\\'.$class_name;
        }
        if(!isset($this->resourceClass))
        {
            $this->resourceClass = '\\App\\Http\\Resources\\'.$class_name;
        }
        if (!isset($this->resourceCollectionClass)) {
            $this->resourceCollectionClass = '\\App\\Http\\Resources\\' . str_plural($class_name);
        }
    }

    /**
     * 0 : unchange
     * 1 : first upper
     * 2 : lower
     * 3 : upper
     */
    public function class_name($class_name = null, $plural = false, $lower = 0)
    {
        $namespace = explode('\\', $class_name ?: get_class($this));
        $class_name = substr(end($namespace), -10, 10) == 'Controller' ? substr(end($namespace), 0, -10) : end($namespace);
        $class_name = $plural ? str_plural($class_name) : $class_name;
        switch ($lower) {
            case 1: return ucfirst($class_name);
            case 2: return strtolower($class_name);
            case 3: return strtoupper($class_name);
            default: return $class_name;
        }
    }

    public function toView($request)
    {
        $as = $request->route()->getAction('as');
        $paths = explode('.', $as);
        $route_resource = preg_replace('/\.[^\.]*$/', '', $as);
        static::$result->module = isset(static::$result->module) ? static::$result->module : new \stdClass;
        static::$result->layouts = isset(static::$result->layouts) ? static::$result->layouts : new \stdClass;
        static::$result->global = isset(static::$result->global) ? static::$result->global : new \stdClass;

        static::$result->module->name = $as;
        static::$result->module->resource = join('.', array_splice($paths, 0, -1));
        static::$result->module->action = last($paths);
        static::$result->module->header = _t($as);
        static::$result->module->desc = _t($as, '.desc');
        static::$result->module->icons = [
            'index' => 'fas fa-list-alt',
            'create' => 'fas fa-plus-square',
            'edit' => 'fas fa-edit',
            'show' => 'fas fa-atom'
        ];

        static::$result->global->title = _t('Maravel');


        static::$result->layouts->mode = 'html';
        if (request()->ajax() && !strstr(request()->header('accept'), 'application/json')) {
            static::$result->layouts->mode = 'template';
        }
        elseif (request()->ajax() && strstr(request()->header('accept'), 'application/json')) {
            static::$result->layouts->mode = 'json';
        }
        // dd(self::$result);
        // dd($request->route());
        // dd($request->route()->getController());
        // dd(get_class_methods($request->route()));
        $view = $as;
        $views = method_exists($this, 'views') ? $this->views() : (isset($this->views) ? $this->views : []);
        if(array_key_exists($as, $views))
        {
            $view = $views[$as];
        }
        return response(\View::make($view, (array) self::$result));
    }

    public function response($result, $title = 'data')
    {
        static::$result->$title = $result;
        return new Response($result, $title);
    }

    public function findOrFail($id, $model = null)
    {
        if (!$model) {
            $model = $this->model;
        }
        if (gettype($id) !== 'object') {
            $query = new $model;
            $model = $query->resolveRouteBinding($id);
            if (!$model) {
                $name = explode('\\', $model);
                $name = end($name);
                abort('404', $name . ' no found');
            }
            return $model;
        } else {
            return $id;
        }
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, '_' . $method))
        {
            return $this->{'_' . $method}(...$parameters);
        }
        parent::__call($method, $parameters);
    }
}
