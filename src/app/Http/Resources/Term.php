<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class Term extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->serial,
            'title' => $this->title,
            'cat' => $this->cat,
            'type' => $this->type,
        ];
    }
}
