<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['id'] = $this->serial;
        return $data;
    }
}
