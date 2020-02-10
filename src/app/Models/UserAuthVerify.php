<?php

namespace App\Models;
use App\User;
use App\UserBridge;
use Carbon\Carbon;
use Illuminate\Support\Str;


use Illuminate\Validation\ValidationException;

class UserAuthVerify
{
    use UserAuthVerify\Mobile;
    protected $user, $messenger, $bridge;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function crateBridge($type, $bridge, $expires_at = 5 * 60)
    {
        $exists = UserBridge::where([
            'type' => $type,
            'bridge' => $bridge
        ])->first();
        if($exists && time() >= strtotime($exists->expires_at))
        {
            $exists->delete();
        }
        elseif($exists)
        {
            if($exists->user_id !== $this->user->id)
            {
                throw ValidationException::withMessages([
                    'bridge' => __('this bridge is for other user')
                ]);
            }
            return $exists;
        }

        return UserBridge::create([
            'user_id' => $this->user->id,
            'type' => $type,
            'bridge' => $bridge,
            'token' => Str::random(40),
            'pin' => rand(100000, 999999),
            'expires_at' => Carbon::createFromTimestamp(time() + $expires_at)
        ]);
    }

    public function hasToken($token)
    {
        return UserBridge::where([
            'token' => $token,
            'user_id' => $this->user->id,
            ['expires_at', '>', Carbon::createFromTimestamp(time())]
        ])
        ->whereNull('verified_at')
        ->first();
    }

    public function hasPin($type, $bridge, $pin)
    {
        return UserBridge::where([
            'user_id' => $this->user->id,
            'type' => $type,
            'bridge' => $bridge,
            'pin' => $pin,
            ['expires_at', '>', Carbon::createFromTimestamp(time())]
        ])
            ->whereNull('verified_at')
            ->first();
    }

    public function whereTypeBridge($type, $bridge, $verified_at = false)
    {
        $bridge = UserBridge::where([
            'user_id' => $this->user->id,
            'type' => $type,
            'bridge' => $bridge,
            ['expires_at', '>', Carbon::createFromTimestamp(time())]
        ]);
        if(!$verified_at)
        {
            $bridge->whereNull('verified_at');
        }
        $bridge = $bridge->first();
        if($bridge)
        {
            $this->bridge = $bridge;
        }
        return $bridge;
    }

    public function user()
    {
        return $this->user;
    }

    public function messenger()
    {
        return $this->messenger;
    }

    public function bridge()
    {
        return $this->bridge;
    }
}
