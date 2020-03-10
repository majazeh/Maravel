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
            'parents' => $this->relationLoaded('parents') ? new Terms($this->parents) : null,
            'creator' => $this->relationLoaded('creator') ? new User($this->creator) : null
        ];
    }
}
