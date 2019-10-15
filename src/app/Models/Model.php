<?php

namespace App\Models;

trait Model
{
	public function getMetaAttribute()
	{
		if(!isset($this->attributes['meta']) || empty($this->attributes['meta']))
		{
			return (object) [];
		}

		return json_decode($this->attributes['meta']);
	}

	public function setMetaAttribute($value)
	{
		return $this->attributes['meta'] = json_encode($value);
    }

    public function getMobileTextAttribute()
    {
        list($mobile, $country, $code) = \Maravel\Lib\MobileRV::parse($this->mobile);
        if(!$mobile) return ;
        return '+' . $code . ' ' . $mobile;
    }
}
