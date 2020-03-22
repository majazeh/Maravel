<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class ApiLog extends Model
{
    protected $guarded = [];
    protected $casts = [
        'request' => 'array',
        'header_request' => 'array',
        'header_response' => 'array',
    ];
}
