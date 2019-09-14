<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Users extends ResourceCollection
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $key => $value) {
            $data[] = new User($value);
        }
        return $data;
    }
}
