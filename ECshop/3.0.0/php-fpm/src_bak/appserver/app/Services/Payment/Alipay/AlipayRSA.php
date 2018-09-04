<?php
//
namespace App\Services\Payment\Alipay;

class AlipayRSA {
/**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    public static function rsaSign($data, $private_key) {
        $res = openssl_get_privatekey($private_key);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    public static function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === self::checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = self::characet($v, "UTF-8");

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    public static function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "UTF-8")
            return true;

        return false;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    public static function characet($data, $targetCharset) {

        if (!empty($data)) {
            $fileType = "";
            if (strcasecmp($fileType, $targetCharset) != 0) {

                $data = mb_convert_encoding($data, $targetCharset);
                //              $data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }


        return $data;
    }
}