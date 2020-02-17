<?php
namespace App;
use Laravel\Passport\Token as Passport;
use App\Casts\Json;

class Token extends Passport
{
    protected $casts = [
        "scopes" => "array",
        "meta" => 'array',
        "revoked" => "bool"
    ];
}
