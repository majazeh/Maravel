<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Str;
class EnterTheory extends Model
{
    protected $_theory, $_trigger;
    protected $guarded = [];
    protected $casts = [
        'expired_at' => 'datetime',
        'meta' => 'array'
    ];

    public static function tokenGenerator()
    {
        return Str::random(69);
    }

    public static function random()
    {
        return config('app.debug') ? 130171 : rand(130171, 999999);
    }

    public function parent()
    {
        return $this->hasOne(static::class, 'id', 'parent_id');
    }
    public function getTheoryAttribute()
    {
        if(!isset($this->attributes['theory']))
        {
            return null;
        }
        if(!$this->_theory)
        {
            $this->_theory = $this->findTheory($this->attributes['theory']);
        }
        return $this->_theory;
    }
    public function getTriggerAttribute()
    {
        if (!isset($this->attributes['trigger'])) {
            return null;
        }
        if (!$this->_trigger) {
            $this->_trigger = $this->findTheory($this->attributes['trigger']);
        }
        return $this->_trigger;
    }

    public function findTheory($theory)
    {
        if (!($plan = config('auth.theories.' . $theory .'.model'))) {
            throw new Exception("$theory Theory not found!");
        }
        return new $plan($this);
    }
    public function resolveRouteBinding($value)
    {
        return $this->where('key', $value)->where('expired_at', '>', Carbon::now())->first();
    }

    public function toArray()
    {
        return [
            'key' => $this->attributes['key'],
            'theory' => $this->attributes['theory'],
        ];
    }
}
