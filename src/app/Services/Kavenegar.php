<?php
namespace App\Services;

class Kavenegar {
    public static function send($method, $arg1)
    {
        switch ($method) {
            case 'verify':
                return Kavenegar\API::VerifyLookup($arg1->bridge, $arg1->pin, null, null, 'verify');
            case 'reset_password' :
                return Kavenegar\API::VerifyLookup($arg1->bridge, $arg1->pin, null, null, 'resetPassword');
        }
    }
}
