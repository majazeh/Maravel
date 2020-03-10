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
        'remember_token'
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

    public function getGroupsAttribute()
    {
        return isset($this->original['groups']) ? explode('|', $this->original['groups']) : null;
    }

    public function avatar()
    {
        return $this->hasManyThrough(\App\File::class, \App\Post::class, 'id', 'post_id', 'avatar_id')->where('posts.status', 'publish')->where('posts.type', 'LIKE', "attachment%");
    }

    public function getLocationTextAttribute(){
        return $this->original['location'];
    }

    public function isAdmin(){
        return Guardio::has("#admin");
    }

    public function idIs($id)
    {
        return $id instanceof static ? $this->id == $id->id : $this->id == $id ;
    }

    public static function statusList()
    {
        return config('guardio.status', ['awaiting', 'active', 'blocked']);
    }
    public static function typeList()
    {
        return config('guardio.types', ['admin', 'user']);
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

    public static function guest()
    {
        return new static([
            'id' => 0,
            'type' => 'guest'
        ]);
    }
}
