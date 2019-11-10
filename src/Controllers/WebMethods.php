<?php

namespace Maravel\Controllers;

use Illuminate\Http\Request;

trait WebMethods
{

    public function _index(Request $request, $arg1 = null, $arg2 = null)
    {
        self::$result->{$this->class_name(null, true, 2)} = $this->endpoint($request)->index($request, $arg1, $arg2);
        if(isset(self::$result->{$this->class_name(null, true, 2)}->additional['meta']['parent']))
        {
            $parent_name = self::$result->{$this->class_name(null, true, 2)}->additional['meta']['parent'];
            self::$result->parent = self::$result->{$this->class_name(null, true, 2)}->additional[$parent_name];
        }
        return $this->view($request);
    }

    public function _show(Request $request, $arg1 = null, $arg2 = null)
    {
        $response = $this->endpoint($request)->show($request, $arg1, $arg2);
        self::$result->{$this->class_name(null, false, 2)} = $response;
        self::$result->id = $response->serial ?: $response->id;
        self::$result->resultName = $this->class_name(null, false, 2);
        return $this->view($request);
    }

    public function _create(Request $request)
    {
        self::$result->module->post_action =
        \Route::has(self::$result->module->apiResource . '.store')
        ? route(self::$result->module->apiResource . '.store')
        : route(self::$result->module->resource . '.store');
        return $this->view($request);
    }

    public function _edit(Request $request, $arg1, $arg2 = null)
    {
        $response = $this->endpoint($request)->show($request, $arg1, $arg2);
        $model = self::$result->{$this->class_name(null, false, 2)} = $response;
        self::$result->id = $response->serial ?: $response->id;
        self::$result->resultName = $this->class_name(null, false, 2);
        self::$result->module->post_action =
            \Route::has(self::$result->module->apiResource . '.update')
            ? route(self::$result->module->apiResource . '.update', $model->serial ?: $model->id)
            : route(self::$result->module->resource . '.update', $model->serial ?: $model->id);
        return $this->view($request);
    }

    public function webUpdate(Request $request, $resource)
    {
        if(!$request->no_redirect || $request->redirect)
        {
            $redirect = $request->redirect;
            if(!$redirect)
            {
                $redirect = \Route::has($this->resource . '.edit')
                ? route($this->resource . '.edit', $resource->serial ?: $resource->id)
                : (\Route::has($this->resource . '.show') ? route($this->resource . '.show', $resource->serial ?: $resource->id) : null);
            }
            if($redirect)
            {
                $resource->additional(
                    array_replace_recursive($resource->additional, [
                        'redirect' => $redirect,
                    ])
                );
            }
        }
    }

    public function webStore(Request $request, $resource)
    {
        if (!$request->no_redirect || $request->redirect) {
            $redirect = $request->redirect;
            if (!$redirect) {
                $redirect = \Route::has($this->resource . '.create')
                    ? route($this->resource . '.create')
                    : (\Route::has($this->resource . '.show') ? route($this->resource . '.show', $resource->serial ?: $resource->id) : null);
            }
            if ($redirect) {
                $resource->additional(
                    array_replace_recursive($resource->additional, [
                        'redirect' => $redirect,
                    ])
                );
            }
        }
    }

    public function webDestroy(Request $request, $resource, $arg1, $arg2 = null)
    {
        $parent = null;
        if (isset($resource->additional['meta']['parent'])) {
            $parent_name = $resource->additional['meta']['parent'];
            $parent = $resource->additional[$parent_name];
        }
        if (!$request->no_redirect || $request->redirect) {
            $redirect = $request->redirect;
            if (!$redirect) {
                $redirect = \Route::has($this->resource . '.index')
                    ? route($this->resource . '.index', $parent ? ($parent->serial ?: $parent->id) : null)
                    : null;
            }
            if ($redirect) {
                $resource->additional(
                    array_replace_recursive($resource->additional, [
                        'redirect' => $redirect,
                    ])
                );
            }
        }
    }
}
