<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Files extends ResourceCollection
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $key => $value) {
            $file = new File($value);;
            $data[$file->mode] = $file;
        }
        return $data;
    }
}
