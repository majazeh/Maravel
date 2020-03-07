<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TermsWoP extends ResourceCollection
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $key => $value) {
            $data[] = new TermWoP($value);
        }
        return $data;
    }
}
