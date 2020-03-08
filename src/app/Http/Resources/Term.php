<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class Term extends TermWoP
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['parents'] = $this->parents->count() ? new TermsWoP($this->parents) : null;
        if(auth()->user() && auth()->user()->isAdmin())
        {
            $data['cretor'] = new User($this->creator);

        }
        return $data;
    }
}
