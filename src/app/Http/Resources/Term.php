<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class Term extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->serial,
            'title' => $this->title
        ];
        if ($this->resource->relationLoaded('parents')) {
            $data['parents'] = Term::collection($this->parents);
        }
        if($this->resource->relationLoaded('parents'))
        {
            $data['creator'] = new User($this->creator);
        }
        return $data;
    }
}
