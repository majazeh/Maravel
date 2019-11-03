<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['id'] = $this->serial;
        unset($data['mode']);
        unset($data['post_id']);
        unset($data['dir']);
        return $data;
    }
}
