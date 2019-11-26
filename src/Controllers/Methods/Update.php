<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Update
{
    public function _update(Request $request, $arg1, $arg2 = null)
    {
        $callback = null;
        if($arg2 instanceof \Closure)
        {
            $callback = $arg2;
            $arg2 = null;
        }
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        $args = [$model];
        if($parent)
        {
            array_unshift($args, $parent);
        }
        if (method_exists($this, 'fields')) {
            $fields = $this->fields($request, 'update', $parent, ...$args);
        }
        else
        {
            $fields = array_keys($this->rules($request, 'update', ...$args));
            $except = method_exists($this, 'except') ? $this->except($request, 'update', ...$args) : [];
            foreach ($except as $key => $value) {
                $index = array_search($value, $fields);
                if($index !== -1)
                {
                    unset($fields[$index]);
                }
            }
        }
        $changed = [];
        $original = [];
        foreach ($fields as $value) {
            if($request->has($value) && $model->$value != $request->$value)
            {
                $changed[$value] = $request->$value;
                $original[$value] = $model->$value;
            }
        }
        if($callback)
        {
            array_push($args, $changed);
            array_unshift($args, $request);
            $func_changed = call_user_func_array($callback, $args);
            if(is_array($func_changed))
            {
                $changed = $func_changed;
            }
        }
        else
        {
            $model->update($changed);
        }
        $result = new $this->resourceClass($model);
        $result->additional([
            'changed' => $original,
        ]);

        if ($this->clientController && $request->webAccess()) {
            $client = new $this->clientController(...func_get_args());
            $client->webUpdate($request, $result);
        }

        if(!empty($changed))
        {
            $this->statusMessage = $this->class_name() . " changed";
        }
        else
        {
            $this->statusMessage = "unchanged";
        }
        return $result;
    }
}
