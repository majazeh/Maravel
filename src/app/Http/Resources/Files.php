<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Files extends ResourceCollection
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $key => $value) {
            $data[] = new File($value);
        }
        return $data;
    }
}
