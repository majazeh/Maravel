<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class EnterTheory extends JsonResource
{
    public function toArray($request)
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'theory' => $this->resource->getOriginal('theory'),
            'trigger' => $this->resource->getOriginal('trigger'),
            'user' => $this->user,
            'expired_at' => ($this->expired_at instanceof \Carbon\Carbon) ? $this->expired_at->timestamp : $this->expired_at,
            'created_at' => ($this->created_at instanceof \Carbon\Carbon) ? $this->created_at->timestamp : $this->created_at,
            'updated_at' => ($this->updated_at instanceof \Carbon\Carbon) ? $this->updated_at->timestamp : $this->updated_at,
        ];
    }
}
