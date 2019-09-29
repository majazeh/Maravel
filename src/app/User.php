<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Models\Model;
    use Notifiable;
    use HasApiTokens;
    use Models\Serial;

    protected $guarded = [
        'id', 'remember_token'
    ];


    public static $s_prefix = 'IQ';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function sendPasswordResetNotification($token)
    {
        dispatch(new \Majazeh\Dashboard\Jobs\SendEmail('emails.recovery', ['email' => $this->email, 'token' => $token, 'title' => _t('change.password.verify.code')]));
    }
}
