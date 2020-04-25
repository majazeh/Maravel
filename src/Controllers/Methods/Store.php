<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Store
{
    public function _store(Request $request, $parent = null, ...$args)
    {
        $callback = null;
        if (last($args) instanceof \Closure) {
            $callback = last($args);
            array_pop($args);
        } elseif ($parent instanceof \Closure) {
            $callback = $parent;
            $parent = null;
        }
        if($parent)
        {
            $parent = $this->findOrFail($parent, isset($this->parentModel) ? $this->parentModel : get_class($parent));
        }

        if ($callback) {
            $args = [$request];
            if($parent)
            {
                $args[] = $parent;
            }
            $args[] = $this->store_data($request, $parent, ...$args);
            $model = call_user_func_array($callback, $args);
        } else {

            $model = $this->model::create($this->store_data($request, $parent, ...$args));
        }

        return $this->additionalStore($request,$this->resultStore($request, $model, $parent), $parent);
    }

    public function resultStore($request, $model, $parent)
    {
        $model = get_class($model)::findOrFail($model->id);
        return new $this->resourceClass($model);
    }

    public function additionalStore($request, $result, $parent)
    {
        if ($parent) {
            $parentModel = isset($this->parentModel) ? $this->parentModel : get_class($parent);
            $additional[$this->class_name($parentModel, null, 2)] = new $this->parentResourceCollectionClass($parent::find($parent->id));
            $additional['meta'] = [
                'parent' => $this->class_name($parentModel, null, 2)
            ];
            $result->additional($additional);
        }
        if (isset($this->clientController) && $request->webAccess()) {
            $client = new $this->clientController(...func_get_args());
            $client->webStore($request, $result);
        }
        $this->statusMessage = $this->class_name() . " created";
        return $result;
    }

    public function store_data(Request $request, $parent = null, ...$args)
    {
        $data = [];
        if (method_exists($this, 'fields')) {
            $data = $this->fields($request, 'store', $parent, ...$args);
        } else {
            $fields = $this->fillable('store') ?: array_keys($this->rules($request, 'store', $parent, ...$args));
            $except = method_exists($this, 'except') ? $this->except($request, 'store', $parent, ...$args) : [];
            foreach ($except as $key => $value) {
                $index = array_search($value, $fields);
                if ($index !== -1) {
                    unset($fields[$index]);
                }
            }
            foreach ($fields as $value) {
                if ($request->has($value)) {
                    $data[$value] = $request->$value;
                }
            }
        }
        return $data;
    }
}
