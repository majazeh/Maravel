<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\User;

class Maravel extends FormRequest
{
    public $parseRules;
    public function validationData()
    {
        $data = parent::validationData();
        if(!$this->route() || !$this->route()->controller) return $data;
        if (method_exists($this->route()->getController(), 'requestData')) {
            $this->route()->getController()->requestData($this, $this->route()->getActionMethod(), $data, ...array_values($this->route()->parameters()));
        }
        $this->numberTypes($data);
        $this->mobileRule($data);
        $this->serialRule($data);
        $this->replace($data);
        return $data;

    }
    public function numberTypes(&$data){
        $fields = ['mobile', 'phone'];
        foreach ($data as $key => $value) {
            if(in_array($key, $fields) && $this->has($key))
            {
                $data[$key] = $this->numberial($this->$key);
            }
        }
    }
    public function numberial($string){
        return strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }

    public function mobileRule(&$data)
    {
        foreach ($this->parseRules() as $key => $value) {
            foreach ($value as $k => $v) {
                if($k == 'mobile' && isset($data[$key]))
                {
                    list($mobile, $country, $code) = \Maravel\Lib\MobileRV::parse($data[$key]);
                    $data[$key] = $mobile ? "$code$mobile" : $data[$key];
                }
            }
        }
    }

    public function serialRule(&$data)
    {
        foreach ($this->parseRules() as $key => $value) {
            foreach ($value as $k => $v) {
                if ($k == 'serial' && isset($data[$key]) && $data[$key]) {
                    $model = '\App\\' . ucfirst($v);
                    $data[$key] = $model::id($data[$key]);
                }
            }
        }
    }

    public function authorize()
    {
        if(!$this->route() || !$this->route()->controller) return true;
        $action = $this->route()->getAction('as');
        $aAaction = explode('.', $action);
        $method = last($aAaction);
        array_pop($aAaction);
        switch ($method) {
            case 'index':
            $method = 'viewAny';
            break;
            case 'show':
            $method = 'view';
            break;
            case 'create':
            case 'store':
            $method = 'create';
            break;
            case 'edit':
            case 'update':
            $method = 'update';
            break;
            case 'destroy':
            $method = 'delete';
            break;
        }
        $aAaction[] = $method;
        $action = join('.', $aAaction);
        $auth = true;
        if(in_array($action, array_keys(Gate::abilities())))
        {
            if(!auth()->check() && in_array('auth:apiIf', $this->route()->getAction('middleware')))
            {
                auth()->login(User::guest());
            }
            $args = array_values($this->route()->parameters());
            array_unshift($args, $action);
            array_unshift($args, $this);
            $auth = $this->route()->getController()->authorize('guardio', $args);
            if(auth()->id() == 0)
            {
                auth()->logout();
            }
        }
        if ($auth && $this->route() && $this->route()->controller && method_exists($this->route()->getController(), 'gate')){
            $auth = $this->route()->getController()->gate($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        };
        return $auth;
    }

    public function rules()
    {
        $rules = $this->getRules();
        if (!$this->route() || !$this->route()->controller) return $rules;
        if (method_exists($this->route()->getController(), 'manipulateData')) {
            $data = $this->all();
            $this->route()->getController()->manipulateData($this, $this->route()->getActionMethod(), $data, ...array_values($this->route()->parameters()));
            $this->replace($data);
        }
        return $rules;
    }

    public function getRules()
    {
        $rules = [];
        if (!$this->route() || !$this->route()->controller) return $rules;
        if (method_exists($this->route()->getController(), 'rules')) {
            $rules = $this->route()->getController()->rules($this, $this->route()->getActionMethod(), ...array_values($this->route()->parameters()));
        }
        return $rules;
    }
    public function parseRules()
    {
        if(!$this->parseRules)
        {
            $rules = $this->getRules() ?: [];
            $parse = [];
            foreach ($rules as $key => $value) {
                $value = is_array($value) ? $value : explode('|', trim($value));
                $p_values = [];
                foreach ($value as $k => $v) {
                    if(is_string($v))
                    {
                        $v = explode(':', $v, 2);
                        $p_values[$v[0]] = isset($v[1]) ? $v[1] : null;
                    }
                }
                $parse[$key] = $p_values;
            }
            $this->parseRules = $parse;
        }
        return $this->parseRules;
    }
    public function isPrefix($prefix)
    {
        return $this->getPrefix() == trim($prefix, '/');
    }
    public function getPrefix()
    {
        return trim($this->route()->getAction('prefix'), '/');
    }
}
