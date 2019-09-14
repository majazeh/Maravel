<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

class Maravel extends FormRequest
{
    public $parseRules;
    protected function validationData()
    {
        $data = parent::validationData();
        $this->numberTypes($data);
        $this->mobileRule($data);
        if (method_exists($this->route()->getController(), 'validationData')) {
            $this->route()->getController()->validationData($this, $this->route()->getActionMethod(), $data, ...$this->route()->parameters());
        }
        $this->merge($data);
        return $data;

    }
    public function numberTypes(&$data){
        $fields = ['mobile', 'phone'];
        foreach ($data as $key => $value) {
            if(in_array($key, $fields) && $this->has($key))
            {
                $data[$key] = strtr($this->$key, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
            }
        }
    }

    public function mobileRule(&$data)
    {
        foreach ($this->parseRules() as $key => $value) {
            foreach ($value as $k => $v) {
                if($k == 'mobile' && isset($data[$key]))
                {
                    list($mobile, $country, $code) = \Maravel\Lib\MobileRV::parse($data[$key]);
                    $data[$key] = "$code$mobile";
                }
            }
        }
    }

    public function authorize()
    {
        if (method_exists($this->route()->getController(), 'authorizations')) {
            $action = $this->route()->getActionMethod();
            switch ($action) {
                case 'index':
                    $action = 'viewAny';
                    break;
                case 'show':
                    $action = 'view';
                    break;
                case 'create':
                case 'store':
                    $action = 'create';
                    break;
                case 'edit':
                case 'update':
                    $action = 'update';
                    break;
                case 'destroy':
                    $action = 'delete';
                    break;
            }
            $action = $this->route()->getController()->class_name(null, true, 2) . "." . $action;
            if(in_array($action, array_keys(Gate::abilities())))
            {
                $args = array_values($this->route()->parameters());
                array_unshift($args, $action);
                array_unshift($args, $this);
                return $this->route()->getController()->authorize('guardio', $args);
            }
        }
        return true;
    }

    public function rules()
    {
        if (method_exists($this->route()->getController(), 'rules')) {
            return $this->route()->getController()->rules($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        }

        return [];
    }
    public function parseRules()
    {
        if(!$this->parseRules)
        {
            $rules = $this->rules();
            $parse = [];
            foreach ($rules as $key => $value) {
                $value = explode('|', trim($value));
                $p_values = [];
                foreach ($value as $k => $v) {
                    $v = explode(':', $v, 2);
                    $p_values[$v[0]] = isset($v[1]) ? $v[1] : null;
                }
                $parse[$key] = $p_values;
            }
            $this->parseRules = $parse;
        }
        return $this->parseRules;
    }
}