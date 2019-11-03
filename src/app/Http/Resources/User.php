<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\File;
class User extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['id'] = $this->serial;
        $data['avatar'] = new Files(file::where('post_id', $this->avatar_id)->get());
        unset($data['avatar_id']);
        return $data;
    }
}
