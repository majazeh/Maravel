<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use Str;
class Maravel extends Validator
{
    public $validate = [];
    public function validateSerial($attribute, $value, $parameters, $validator)
    {
        if(!is_null($value)){
            $value = is_array($value) ? $value : [$value];
            foreach ($value as $v) {
                $model = '\App\\' . ucfirst($parameters[0]);
                if (!class_exists($model)) {
                    $table = $parameters[0];
                    $model = '\App\\' . ucfirst(Str::singular($table));
                }
                if(!$model::idCheck($v)) return false;
            }
        }
        return true;
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

    public function validateDouble($attribute, $value, $parameters, $validator)
    {
        $parameters = ['/^\d*(\.\d{1,2})?$/'];
        return $this->validateRegex($attribute, $value, $parameters);
    }

    public function validateExistsSerial($attribute, $value, $parameters)
    {
        if(!$this->validateSerial(...func_get_args()))
        {
            return false;
        }
        return $this->validateExists($attribute, $value, $parameters);
    }
}
