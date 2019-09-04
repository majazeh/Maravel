<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Show
{
    public function _show(Request $request, $arg1, $arg2 = null)
    {
        if ($arg2) {
            $model = $this->findOrFail($arg2, $this->model);
            $parent = $this->findOrFail($arg1, $this->parentModel);
            self::$result->{$this->class_name($this->parent, false, 2)} = $parent;
        } else {
            $model = $this->findOrFail($arg1, $this->model);
            $parent = null;
        }
        return $this->response(new $this->resourceClass($model), $this->class_name(null, false, 2));
    }
}
