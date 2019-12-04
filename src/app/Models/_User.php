<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class _User extends Authenticatable
{
    use Model;
    use Notifiable;
    use HasApiTokens;
    use Serial;

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

    public function getGroupsAttribute()
    {
        return isset($this->original['groups']) ? explode('|', $this->original['groups']) : null;
    }

    public function getAvatarAttribute()
    {
        return \App\File::where('post_id', $this->original['avatar_id'])->get()->keyBy('mode');
    }

    public function getLocationTextAttribute(){
        return $this->original['location'];
    }
}
