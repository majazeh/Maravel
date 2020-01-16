<?php

namespace Maravel\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    use WebMethods;

    public function __construct(Request $request)
    {
        if (!$request->route()) return;
        parent::__construct($request);
        self::$result = new \StdClass;
        $this->designConstruct($request);
    }

    public function designConstruct(Request $request)
    {
        $as = preg_replace('/^api\./', 'dashboard.', $request->route()->getAction('as'));
        $paths = explode('.', $as);
        $route_resource = preg_replace('/\.[^\.]*$/', '', $as);
        self::$result->module = new \stdClass;
        self::$result->layouts = new \stdClass;
        self::$result->global =  new \stdClass;

        self::$result->module->name = $as;
        if (!isset($this->resource)) {
            $this->resource = join('.', array_splice($paths, 0, -1));
        }

        if (!isset($this->apiResource)) {
            $this->apiResource = 'api.' . preg_replace('/^dashboard\./', '', $this->resource);
        }
        self::$result->module->resource = $this->resource;
        self::$result->module->apiResource = $this->apiResource;
        self::$result->module->action = last($paths);
        self::$result->module->header = _t($as);
        self::$result->module->desc = _t($as . '.desc');
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
            self::$result->layouts->mode = $request->header('data-xhr-base') ?: 'xhr';
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
            $view = self::$result->layouts->mode == 'html' ? $views[$as] : (view()->exists($views[$as] . '-'. self::$result->layouts->mode) ? $views[$as] . '-'. self::$result->layouts->mode : $views[$as]);
        }
        $response = response(view()->make($view, (array) self::$result));
        if(self::$result->layouts->mode == 'xhr')
        {
            $content = $response->getContent();
            $data = json_encode(self::$result->global);
            $content = "$data\n$content";
            $response->setContent($content);
        }
        return $response;
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
        if(!isset($this->endpoint))
        {
            return false;
        }
        $endpoint = $this->endpoint;
        if(gettype($this->endpoint) !== 'object')
        {
            $endpoint = $this->endpoint = new $this->endpoint($request);
        }
        return $endpoint;
    }
}
