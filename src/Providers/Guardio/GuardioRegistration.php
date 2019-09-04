<?php

namespace Maravel\Providers\Guardio;

trait GuardioRegistration
{
    public function registerGuardio()
    {
        \Illuminate\Auth\SessionGuard::macro('type', $this->registerGuardioType());
        \Illuminate\Auth\SessionGuard::macro('groups', $this->registerGuardioGroups());
        \Illuminate\Auth\SessionGuard::macro('inGroup', $this->registerGuardioInGroup());
        \Illuminate\Auth\SessionGuard::macro(
            'guardio',
            function ($access) {
                if (!$this->check()) {
                    return false;
                }
                if ($this->user()->type == 'admin') {
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
        );
    }
    public function registerGuardioType()
    {
        return function ($search = null) {
            if (!self::check()) {
                return false;
            }
            if ($search) {
                return self::user()->type == $search;
            }
            return self::user()->type;
        };
    }

    public function registerGuardioGroups()
    {
        return function ($search = null) {
            if (!self::check()) {
                return false;
            }
            $groups = self::user()->groups ? explode('|', self::user()->groups) : [];
            if (!in_array(self::user()->type, $groups)) {
                array_push($groups, self::user()->type);
            }
            return $groups;
        };
    }
    public function registerGuardioInGroup()
    {
        return function ($search) {
            if (!self::check()) {
                return false;
            }
            if (in_array($search, self::groups())) {
                return true;
            }
            return false;
        };
    }
}
