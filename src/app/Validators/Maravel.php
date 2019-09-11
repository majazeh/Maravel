<?php

namespace App\Validators;

use Illuminate\Validation\Validator;

class Maravel extends Validator
{
    public function validateSerial($attribute, $value, $parameters, $validator)
    {
        $type = ucfirst($parameters[0]);
        $model = "\\App\\$type";

        return $model::serialCheck($value);
    }
    public function validateMobile($attribute, $value, $parameters, $validator)
    {
        list($mobile, $country, $code) = \Maravel\Lib\MobileRV::parse($value, $parameters);
        if(!$mobile)
        {
            return false;
        }
        return true;
    }
}
