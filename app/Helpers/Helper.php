<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Exception;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use PhpParser\Node\Stmt\ElseIf_;
use Ramsey\Uuid\Type\Integer;

class Helper
{
    public static function idEncode(int $unique_id)
    {
        return strtr(rtrim(base64_encode(pack('i', $unique_id)), '='), '+/', '-_');
    }
    
    public static function idDecode(string $code)
    {
        try{
        $number = unpack('i', base64_decode(str_pad(strtr($code, '-_', '+/'), strlen($code) % 4, '=')));
        return $number[1];
        }
        catch(Exception){
            return http_response_code(404);
        }
    }
    public static function getIP()
    {
        $ip_api = 'https://api.ipify.org';
        return file_get_contents($ip_api);
    }
    public static function getLocationInfoByIp(string $ip){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        $result  = array('country'=>'', 'city'=>'');

        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
        if($ip_data && $ip_data->geoplugin_countryName != null){
            $result['country'] = $ip_data->geoplugin_countryCode;
            $result['city'] = $ip_data->geoplugin_city;
        }
        return $result;
    }
}