<?php

namespace Maravel\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    use WebMethods;

    public function __construct(Request $request)
    {
        self::$result = new \StdClass;
        parent::__construct($request);
        $this->designConstruct($request);
    }

    public function designConstruct(Request $request)
    {
        $as = $request->route()->getAction('as');
        $paths = explode('.', $as);
        $route_resource = preg_replace('/\.[^\.]*$/', '', $as);
        self::$result->module = new \stdClass;
        self::$result->layouts = new \stdClass;
        self::$result->global =  new \stdClass;

        self::$result->module->name = $as;
        if (!isset($this->resource)) {
            $this->resource = join('.', array_splice($paths, 0, -1));
        }
        self::$result->module->resource = $this->resource;
        self::$result->module->action = last($paths);
        self::$result->module->header = _t($as);
        self::$result->module->desc = _t($as, '.desc');
        self::$result->module->icons = [
            'index' => 'fas fa-list-alt',
            'create' => 'fas fa-plus-square',
            'edit' => 'fas fa-edit',
            'show' => 'fas fa-atom'
        ];

        self::$result->global->title = _t('Maravel');
        self::$result->layouts->mode = 'html';
    }
    public function view($request)
    {
        if ($request->ajax() && !strstr($request->header('accept'), 'application/json')) {
            self::$result->layouts->mode = 'template';
        } elseif ($request->ajax() && strstr($request->header('accept'), 'application/json')) {
            self::$result->layouts->mode = 'json';
        }

        $as = $request->route()->getAction('as');
        $view = $as;
        $views = method_exists($this, 'views') ? $this->views() : (isset($this->views) ? $this->views : [
            self::$result->module->resource . '.index' => self::$result->module->resource . '.index',
            self::$result->module->resource . '.show' => self::$result->module->resource . '.show',
            self::$result->module->resource . '.create' => self::$result->module->resource . '.create',
            self::$result->module->resource . '.edit' => self::$result->module->resource . '.create'
        ]);
        if (array_key_exists($as, $views)) {
            $view = $views[$as];
        }
        return response(view()->make($view, (array) self::$result));
    }

    public function rules(Request $request, $action)
    {
        if(method_exists($this->endpoint($request), 'rules'))
        {
            return $this->endpoint($request)->rules(...func_get_args());
        }
        return [];
    }

    public function authorizations(Request $request, $action)
    {
        if (method_exists($this->endpoint($request), 'authorizations')) {
            return $this->endpoint($request)->authorizations(...func_get_args());
        }
        return true;
    }

    public function endpoint(Request $request)
    {
        $endpoint = $this->endpoint;
        if(gettype($this->endpoint) !== 'object')
        {
            $endpoint = $this->endpoint = new $this->endpoint($request);
        }
        return $endpoint;
    }
}
