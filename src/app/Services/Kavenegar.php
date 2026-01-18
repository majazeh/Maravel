<?php
namespace App\Services;

use Exception;

const TOKEN_URL = 'https://safir.bale.ai/api/v2/auth/token';
const OTP_URL = 'https://safir.bale.ai/api/v2/send_otp';

const DATA = [
    'grant_type'    => 'client_credentials',
    'client_id'     => env('BALE_OTP_USERNAME'),
    'client_secret' => env('BALE_OTP_PASSWORD'),
    'scope'         => 'read',
];
class Kavenegar
{

    public static function baleOTP($mobile, $otp)
    {
        $ch = curl_init(TOKEN_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST            => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_POSTFIELDS      => http_build_query(DATA),
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            throw new \Exception('Curl error: ' . $error);
        }

        $result = json_decode($response);
        $accessToken = $result->access_token;
        $data = [
            'phone' => $mobile,
            'otp'   => (int) $otp,
        ];
        
        $ch = curl_init(OTP_URL);
        
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($data),
        ]);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            throw new \Exception('Curl error: ' . $error);
        }
    }

    public static function send($method, $arg1)
    {
        if (env('BALE_OTP_USERNAME') !== '' && env('BALE_OTP_USERNAME') !== null) {
            try{
                self::baleOTP($arg1->bridge, $arg1->pin);
            }catch(Exception $e){}
        }
        switch ($method) {
            case 'verify':
                return Kavenegar\API::VerifyLookup($arg1->bridge, $arg1->pin, null, null, 'verify');
            case 'reset_password':
                return Kavenegar\API::VerifyLookup($arg1->bridge, $arg1->pin, null, null, 'resetPassword');
        }
    }
}
