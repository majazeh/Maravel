<?php

namespace Maravel\Lib;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;


class Response implements Responsable
{

    public $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        $return = $this->result;
        if($this->result instanceof Responsable)
        {
            $return = $this->result->toResponse($request);
        }
        $return->original = $this->result;
        return $return;
    }
}
