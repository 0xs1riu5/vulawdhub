<?php
/* *
 * 支付宝接口RSA函数
 * 详细：RSA签名、验签、解密
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

/**
 * RSA签名
 * @param $data 待签名数据
 * @param $private_key 商户私钥字符串
 * return 签名结果
 */
function rsaSign($data, $private_key) {
    //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
    $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
	$private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
	$private_key=str_replace("\n","",$private_key);

	$private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

    $res=openssl_get_privatekey($private_key);

    if($res)
    {
        openssl_sign($data, $sign,$res);
    }
    else {
        echo "您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!";
        exit();
    }
    openssl_free_key($res);
	//base64编码
    $sign = base64_encode($sign);
    return $sign;
}

/**
 * RSA验签
 * @param $data 待签名数据
 * @param $alipay_public_key 支付宝的公钥字符串
 * @param $sign 要校对的的签名结果
 * return 验证结果
 */
function rsaVerify($data, $alipay_public_key, $sign)  {
    //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
	$alipay_public_key=str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
	$alipay_public_key=str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
	$alipay_public_key=str_replace("\n","",$alipay_public_key);

    $alipay_public_key='-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
    $res=openssl_get_publickey($alipay_public_key);
    if($res)
    {
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
    }
    else {
        echo "您的支付宝公钥格式不正确!"."<br/>"."The format of your alipay_public_key is incorrect!";
        exit();
    }
    openssl_free_key($res);    
    return $result;
}

?>