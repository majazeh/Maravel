<?php
namespace App;

class GuardioCheck
{
    protected $permissions = null;
    protected $user = null;

    public function __construct($user)
    {
        $this->user = $user;

        $groups = $this->groups();
        $guardio = Guard::select('guard_positions.*')
        ->whereIn('guards.title', $groups)
        ->join('guard_positions', 'guards.id', 'guard_positions.guard_id')->get();

        $permissions = [];
        foreach ($groups as $key => $value) {
            $default = config("guardio.groups.{$value}", []);
            foreach ($default as $k => $v) {
                if(ctype_digit($k) || is_integer($k))
                {
                    $permissions[$v] = null;
                }
                else
                {
                    $permissions[$k] = $v;
                }
            }
        }
        foreach ($guardio as $key => $value) {
            $permissions[$value->gate] = $value->value;
        }
        $this->permissions = $permissions;
    }

    public function permissions($keys = false)
    {
        return $keys ? array_keys($this->permissions) : $this->permissions;
    }

    public function get($key)
    {
        return $this->has($key) ? (isset($this->permissions[$key]) ? $this->permissions[$key] : null) : false;
    }

    public function has($access)
    {
        if (in_array($this->user->type, config('guardio.admins', ['admin']))) {
            return true;
        }
        $access = !is_array($access) ? [$access] : $access;
        $permissions = $this->permissions(true);
        foreach ($access as $key => $value) {
            $value = str_replace(" ", "", $value);
            if (strpos($value, '|')) {
                $OrValue = explode('|', $value);
                $check = false;
                foreach ($OrValue as $okey => $ovalue) {
                    if (substr($ovalue, 0, 1) == '@') {
                        if ($this->inGroup(substr($ovalue, 1))) {
                            $check = true;
                            break;
                        }
                    } else {
                        if (in_array($ovalue, $permissions)) {
                            $check = true;
                            break;
                        }
                    }
                }
                if (!$check) {
                    return false;
                }
                continue;
            } elseif (substr($value, 0, 1) == '@') {
                if (!$this->inGroup(substr($value, 1))) {
                    return false;
                }
            } elseif (!in_array($value, $permissions)) {
                return false;
            }
        }
        return true;
    }
    public function type($search = null)
    {
        if (!auth()->check()) {
            return false;
        }
        if ($search) {
            return $this->user->type == $search;
        }
        return $this->user->type;
    }

    public function groups($search = null)
    {
        if (!auth()->check()) {
            return false;
        }
        $groups = $this->user->groups ? explode('|', $this->user->groups) : [];
        if (!in_array($this->user->type, $groups)) {
            array_push($groups, $this->user->type);
        }
        return $groups;
    }
    public function inGroup($search)
    {
        if (!auth()->check()) {
            return false;
        }
        if (in_array($search, $this->groups())) {
            return true;
        }
        return false;
    }
}
