<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Guardio;
use Illuminate\Support\Facades\Gate;

class User extends JsonResource
{
    public function toArray($request)
    {
        $user = auth()->check() ? auth()->user()->id : null;
        $data = parent::toArray($request);
        $data['id'] = $this->serial;
        $data['avatar'] = $this->avatar ? new Files($this->avatar) : null;
        $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
        unset($data['avatar_id']);
        unset($data['email_verified_at']);
        $data['created_at'] = ($this->created_at instanceof \Carbon\Carbon) ? $this->created_at->timestamp : $this->created_at;
        $data['updated_at'] = ($this->updated_at instanceof \Carbon\Carbon) ? $this->updated_at->timestamp : $this->updated_at;
        if (!Guardio::has('view-user-admin-variables') && $user != $this->id) {
            foreach (['status', 'type', 'groups', 'created_at', 'updated_at', 'email_verified_at', 'email', 'mobile'] as $key) {
                if (key_exists($key, $data)) {
                    unset($data[$key]);
                }
            }
        }
        $data['can'] = [];
        if (Gate::allows('api.users.update', [$request, $this->resource])) {
            $data['can'][] = 'edit';
        }
        if (Gate::allows('api.users.delete', [$request, $this->resource])) {
            $data['can'][] = 'delete';
        }
        return $data;
    }
}
