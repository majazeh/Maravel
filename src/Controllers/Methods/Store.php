<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Store
{
    public function _store(Request $request, $parent = null)
    {
        if($parent)
        {
            $parent = $this->findOrFail($parent, $this->parentModel);
        }

        $fields = array_keys($this->rules($request, 'store'));
        $data = [];
        foreach ($fields as $value) {
            if ($request->has($value)) {
                $data[$value] = $request->$value;
            }
        }
        $model = $this->model::create($data);
        $model = $this->model::findOrFail($model->id);
        $result = new $this->resourceClass($model);
        $this->statusMessage = $this->class_name() . " created";
        return $result;
    }
}
