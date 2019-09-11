<?php

namespace Maravel\Controllers;

use Illuminate\Http\Request;

trait WebMethods
{

    public function _index(Request $request, $arg1 = null, $arg2 = null)
    {
        self::$result->{$this->class_name(null, true, 2)} = $this->endpoint($request)->index($request, $arg1, $arg2);
        return $this->view($request);
    }

    public function _show(Request $request, $arg1 = null, $arg2 = null)
    {
        self::$result->{$this->class_name(null, false, 2)} = $this->endpoint($request)->show($request, $arg1, $arg2);
        return $this->view($request);
    }

    public function _create(Request $request)
    {
        self::$result->module->post_action = route(self::$result->module->resource . '.store');
        return $this->view($request);
    }

    public function _edit(Request $request, $arg1, $arg2 = null)
    {
        $model = self::$result->{$this->class_name(null, false, 2)} = $this->endpoint($request)->show($request, $arg1, $arg2);
        self::$result->module->post_action = route(self::$result->module->resource . '.update', $model->serial ?: $model->id);
        return $this->view($request);
    }

    public function _update(Request $request, $arg1 = null, $arg2 = null)
    {
        $result = tap($this->endpoint($request)->update($request, $arg1, $arg2), function ($user) use($request) {
            if(!$request->no_redirect || $request->redirect)
            {
                $redirect = $request->redirect;
                if(!$redirect)
                {
                    $redirect = \Route::has($this->resource . '.edit')
                    ? route($this->resource . '.edit', $user->serial ?: $user->id)
                    : (\Route::has($this->resource . '.show') ? route($this->resource . '.show', $user->serial ?: $user->id) : null);
                }
                if($redirect)
                {
                    $user->additional(
                        array_replace_recursive($user->additional, [
                            'redirect' => $redirect,
                        ])
                    );
                }
            }
            $this->statusMessage = $this->endpoint($request)->statusMessage;
        });
        return $result;
    }

    public function _store(Request $request, $arg1 = null, $arg2 = null)
    {
        $result = tap($this->endpoint($request)->store($request, $arg1, $arg2), function ($user) use ($request) {
            if (!$request->no_redirect || $request->redirect) {
                $redirect = $request->redirect;
                if (!$redirect) {
                    $redirect = \Route::has($this->resource . '.create')
                        ? route($this->resource . '.create')
                        : (\Route::has($this->resource . '.show') ? route($this->resource . '.show', $user->serial ?: $user->id) : null);
                }
                if ($redirect) {
                    $user->additional(
                        array_replace_recursive($user->additional, [
                            'redirect' => $redirect,
                        ])
                    );
                }
            }
            $this->statusMessage = $this->endpoint($request)->statusMessage;
        });
        return $result;
    }

    public function _destroy(Request $request, $arg1 = null, $arg2 = null)
    {
        $result = tap($this->endpoint($request)->destroy($request, $arg1, $arg2), function ($user) use ($request) {
            if (!$request->no_redirect || $request->redirect) {
                $redirect = $request->redirect;
                if (!$redirect) {
                    $redirect = \Route::has($this->resource . '.index')
                        ? route($this->resource . '.index')
                        : null;
                }
                if ($redirect) {
                    $user->additional(
                        array_replace_recursive($user->additional, [
                            'redirect' => $redirect,
                        ])
                    );
                }
            }
            $this->statusMessage = $this->endpoint($request)->statusMessage;
        });
        return $result;
    }
}
