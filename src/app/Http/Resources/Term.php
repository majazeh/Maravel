<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
        if (Gate::allows('api.terms.update', [$request, $this->resource])) {
            $data['can'][] = 'edit';
        }
        if (Gate::allows('api.terms.delete', [$request, $this->resource])) {
            $data['can'][] = 'delete';
        }
        return $data;
    }
}
