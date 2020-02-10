<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Guardio;

trait _UserScopes
{
    public function scopeWhereMobile($query, $mobile)
    {
        $query->where('mobile', $mobile);
    }
}
