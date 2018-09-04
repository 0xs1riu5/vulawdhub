<?php
//
namespace App\Services\QiNiu;

class QiNiu
{
    public $accessKey;
    public $secretKey;

    public function __construct($accessKey, $secretKey) {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function uploadToken($flags)
    {
        if(!isset($flags['deadline']))
            $flags['deadline'] = 3600 + time();
        $encodedFlags = self::urlsafe_base64_encode(json_encode($flags));
        $sign = hash_hmac('sha1', $encodedFlags, $this->secretKey, true);
        $encodedSign = self::urlsafe_base64_encode($sign);
        $token = $this->accessKey.':'.$encodedSign. ':' . $encodedFlags;
        return $token;
    }

    public function accessToken($url,$body=false){
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];
        $access = $path;
        if (isset($parsed_url['query'])) {
            $access .= "?" . $parsed_url['query'];
        }
        $access .= "\n";
        if($body) $access .= $body;
        $digest = hash_hmac('sha1', $access, $this->secretKey, true);
        return $this->accessKey.':'.self::urlsafe_base64_encode($digest);
    }

    public static function urlsafe_base64_encode($str){
        $find = array("+","/");
        $replace = array("-", "_");
        return str_replace($find, $replace, base64_encode($str));
    }

    public static function resizeImage($filename, $target, $is_parse_filename=false)
    {
        //42a477c5614e0fed_1427805894_w_428_h_640_181.jpg
        //imageView2/1/w/64/h/64/q/85
        if ($is_parse_filename) {
            $parse_filename = explode('_', $filename);
            if (isset($parse_filename[3]) && isset($parse_filename[5])) {
                $origin_width = $parse_filename[3];
                $origin_height = $parse_filename[5];
                return $filename.'?imageView2/1/w/'.$target.'/h/'.ceil($origin_height*($target/$origin_width)).'/q/85';
            }
        }
        return $filename.'?imageView2/1/w/'.$target.'/h/'.$target.'/q/85';
    }
}
