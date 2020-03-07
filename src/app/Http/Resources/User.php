<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Guardio;
class User extends JsonResource
{
    public function toArray($request)
    {
        $user = auth()->check() ? auth()->user()->id : null;
        $data = parent::toArray($request);
        $data['id'] = $this->serial;
        $data['avatar'] = $this->avatar ? new Files($this->avatar) : null;
        unset($data['avatar_id']);
        $data['created_at'] = ($this->created_at instanceof \Carbon\Carbon) ? $this->created_at->timestamp : $this->created_at;
        $data['updated_at'] = ($this->updated_at instanceof \Carbon\Carbon) ? $this->updated_at->timestamp : $this->updated_at;
        if (!Guardio::has('view-user-admin-variables') && $user != $this->id) {
            foreach (['status', 'type', 'groups', 'created_at', 'updated_at', 'email_verified_at', 'email', 'mobile'] as $key) {
                if (key_exists($key, $data)) {
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }
}
