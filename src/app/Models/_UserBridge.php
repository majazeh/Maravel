<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;
use App\UserBridge;
use App\User;

class _UserBridge extends Eloquent
{
    protected $guarded = [];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public static function whereToken($token)
    {
        return static::where([
            'token' => $token,
            ['expires_at', '>', Carbon::createFromTimestamp(time())]
        ])
        ->whereNull('verified_at')
        ->first();
    }

    public static function wherePin($type, $bridge, $pin)
    {
        return static::where([
            'type' => $type,
            'bridge' => $bridge,
            'pin' => $pin,
            ['expires_at', '>', Carbon::createFromTimestamp(time())]
        ])
        ->whereNull('verified_at')
        ->first();
    }

    public function verify()
    {
        if ($this->type == 'reset_password') {
            $this->delete();
            return;
        }
        $now = Carbon::now();
        if($this->type == 'mobile' && $this->user->status == 'awaiting')
        {
            $this->user->mobile = $this->bridge;
            $this->user->status = 'active';
            $this->user->update();
        }

        if ($this->type == 'email' && $this->user->status == 'awaiting') {
            $this->user->status = 'active';
            $this->user->email = $this->bridge;
            $this->user->email_verified_at = $now;
            $this->user->update();
        }
        $this->expires_at = null;
        $this->token = null;
        $this->pin = null;
        $this->verified_at = $now;
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
