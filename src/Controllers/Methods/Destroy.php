<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Destroy
{
    public function _destroy(Request $request, $arg1, $arg2 = null)
    {
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        $result = new $this->resourceClass($model);
        $model->delete();
        $this->statusMessage =  $this->class_name() . " removed";
        return $result;
    }
}
