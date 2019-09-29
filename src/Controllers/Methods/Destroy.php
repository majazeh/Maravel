<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Destroy
{
    public function _destroy(Request $request, $arg1, $arg2 = null)
    {
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        $result = new $this->resourceClass($model);
        if ($parent) {
            $additional[$this->class_name($this->parentModel, null, 2)] = new $this->parentResourceCollectionClass($parent);
            $additional['meta'] = [
                'parent' => $this->class_name($this->parentModel, null, 2)
            ];
            $result->additional($additional);
        }
        if ($this->clientController && $request->webAccess()) {
            $client = new $this->clientController(...func_get_args());
            $client->webDestroy($request, $result, $arg1, $arg2);
        }
        $model->delete();
        $this->statusMessage =  $this->class_name() . " removed";
        return $result;
    }
}
