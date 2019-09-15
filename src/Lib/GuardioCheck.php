<?php
namespace Maravel\Lib;
use App\GuardPosition;

class GuardioCheck
{

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function has($access)
    {
        if (in_array($this->user->type, config('guardio.admins', ['admin']))) {
            return true;
        }
        $access = !is_array($access) ? [$access] : $access;
        $guardioConfig = config('guardio');

        $groups = $this->groups();
        if (!isset($this->guardio)) {
            $this->guardio = GuardPosition::whereIn('guard', $groups)->get();
        }

        $permissions = [];
        foreach ($groups as $key => $value) {
            $permissions = array_merge($permissions, config("guardio.groups.{$value}") ?: []);
        }
        $permissions = array_unique($permissions);
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
