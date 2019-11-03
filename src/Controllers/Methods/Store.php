<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Store
{
    public function _store(Request $request, $parent = null, ...$args)
    {
        if($parent)
        {
            $parent = $this->findOrFail($parent, $this->parentModel);
        }

        if(method_exists($this, 'fields'))
        {
            $data = $this->fields($request, 'store', $parent, ...$args);
        }
        else
        {
            $fields = array_keys($this->rules($request, 'store', $parent, ...$args));
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
        $model = $this->model::create($data);
        $model = $this->model::findOrFail($model->id);
        $result = new $this->resourceClass($model);
        if ($this->clientController && $request->webAccess()) {
            $client = new $this->clientController(... func_get_args());
            $client->webStore($request, $result);
        }
        $this->statusMessage = $this->class_name() . " created";
        return $result;
    }
}
