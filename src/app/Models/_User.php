<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Guardio;

class _User extends Authenticatable
{
    use Model;
    use Notifiable;
    use HasApiTokens;
    use Serial;
    use _UserScopes;

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

    public function isAdmin(){
        return Guardio::has("#admin");
    }

    public static function statusList()
    {
        return config('guardio.status', ['awaiting', 'active', 'block']);
    }
    public static function typeList()
    {
        return config('guardio.type', ['admin', 'user']);
    }

    public static function defaultType()
    {
        return config('guardio.default_user.type', 'user');
    }

    public static function defaultStatus()
    {
        return config('guardio.default_user.status', 'awaiting');
    }

    public function AuthVerify()
    {
        return  new UserAuthVerify($this);
    }

    public function createVerify()
    {
        $authVerify = $this->AuthVerify();
        if(!$authVerify->whereTypeBridge('mobile', $this->mobile)){
            return $authVerify->createMobileVerify();
        }
        return $authVerify;
    }

    public function resetPassword()
    {
        $authVerify = $this->AuthVerify();
        if (!$authVerify->whereTypeBridge('reset_password', $this->mobile)) {
            return $authVerify->mobileResetPassword();
        }
        return $authVerify;
    }
}
