<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class Attachment extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['id'] = $this->serial;
        $data['attachments'] = new Files($this->resource->attachments);
        return $data;
    }
}
