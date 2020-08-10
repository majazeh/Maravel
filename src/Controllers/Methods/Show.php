<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Show
{
    public function _show(Request $request, $arg1, $arg2 = null)
    {
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        $result = new $this->resourceClass($model);
        $additional = [];
        if ($parent) {
            $parentModel = isset($this->parentModel) ? $this->parentModel : get_class($parent);
            $additional[$this->class_name($parentModel, null, 2)] = new $this->parentResourceCollectionClass($parent);
            $additional['meta'] = [
                'parent' => $this->class_name($parentModel, null, 2)
            ];
        }
        $result->additional($additional);

        return $result;
    }
}
