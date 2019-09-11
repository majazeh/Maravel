<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Show
{
    public function _show(Request $request, $arg1, $arg2 = null)
    {
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        return new $this->resourceClass($model);
    }
}
