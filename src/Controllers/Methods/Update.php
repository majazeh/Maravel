<?php

namespace Maravel\Controllers\Methods;

use Illuminate\Http\Request;

trait Update
{
    public function _update(Request $request, $arg1, $arg2 = null)
    {
        list($parent, $model) = $this->findArgs($request, $arg1, $arg2);
        $fields = array_keys($this->rules($request, 'update'));
        $changed = [];
        $original = [];
        foreach ($fields as $value) {
            if($request->has($value) && $model->$value != $request->$value)
            {
                $changed[$value] = $request->$value;
                $original[$value] = $model->$value;
            }
        }
        $model->update($changed);
        $result = new $this->resourceClass($model);
        $result->additional([
            'changed' => $original,
        ]);

        if ($this->clientController && $request->webAccess()) {
            $client = new $this->clientController(...fun_get_args());
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
