<?php
namespace App\Services\Kavenegar;

class API {
    const APIPATH = "https://api.kavenegar.com/v1/%s/%s/%s.json/";
    protected static function get_path($method, $base = 'sms')
    {
        return sprintf(self::APIPATH, config('services.sms.apikey'), $base, $method);
    }

    protected static function execute($url, $data = null)
    {
        $headers       = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'charset: utf-8'
        );
        $fields_string = $data;
        if (!is_null($data)) {
            $fields_string = http_build_query($data);
        }
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $fields_string);

        $response     = curl_exec($handle);
        $code         = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        $curl_errno   = curl_errno($handle);
        $curl_error   = curl_error($handle);
        if ($curl_errno) {
            throw new \Exception($curl_error);
        }
        $json_response = json_decode($response);
        if ($code != 200 && is_null($json_response)) {
            throw new \Exception("Request have errors");
        } else {
            $json_return = $json_response->return;
            if ($json_return->status != 200) {
                throw new \Exception($json_return->message);
            }
            return $json_response->entries;
        }
    }

    public static function Send($sender, $receptor, $message, $date = null, $type = null, $localid = null)
    {
        if (is_array($receptor)) {
            $receptor = implode(",", $receptor);
        }
        if (is_array($localid)) {
            $localid = implode(",", $localid);
        }
        $path   = static::get_path("send");
        $params = array(
            "receptor" => $receptor,
            "sender" => $sender,
            "message" => $message,
            "date" => $date,
            "type" => $type,
            "localid" => $localid
        );
        $split = explode("\n", $message);
        $sms_list = [''];
        $sms_index = 0;
        foreach($split as $sms){
            $new_len = strlen($sms);
            if(strlen($sms_list[$sms_index])  + $new_len  > 1000){
                $sms_index++;
                $sms_list[$sms_index] = '';
            }
            $sms_list[$sms_index] .= $sms . "\n";
        }

        $result = [];
        foreach($sms_list as $sms){
            $params['message'] = trim($sms, "\n");
            $result[] = static::execute($path, $params);
        }
        return $result;
    }

    public static function VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null)
    {
        $path   = static::get_path("lookup", "verify");
        $params = array(
            "receptor" => $receptor,
            "token" => $token,
            "token2" => $token2,
            "token3" => $token3,
            "template" => $template,
            "type" => $type
        );
        if (func_num_args() > 5) {
            $arg_list = func_get_args();
            if (isset($arg_list[6]))
                $params["token10"] = $arg_list[6];
            if (isset($arg_list[7]))
                $params["token20"] = $arg_list[7];
        }
        return static::execute($path, $params);
    }
}
