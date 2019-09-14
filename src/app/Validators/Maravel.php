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

    public function validateOneOf($attribute, $value, $parameters, $validator)
    {
        array_push($parameters, $attribute);
        $one_of = false;
        foreach ($parameters as $key => $value) {
            if(isset($this->data[$value]) && $this->data[$value])
            {
                $one_of = true;
                break;
            }
        }
        return $one_of;
    }
}
