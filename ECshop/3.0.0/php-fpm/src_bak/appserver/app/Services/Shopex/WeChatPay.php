<?php
namespace App\Services\Shopex;

use App\Models\v2\ShopConfig;

/**
* 
*/
class WeChatPay
{
	
	public static function info()
	{
		$value = ShopConfig::findByCode('certificate');

        if (!$value) {
            return false;
        }
		$token = unserialize($value)['token'];
		//获取ecshop授权信息
		$param = array(
		    'act' => 'get_auth_info',//固定方法
		    'return_data' => 'json',//返回类型
		);

		$ac = self::getAc($param, $token);//验证签名
		$param['ac'] = $ac;//签名值放入参数中


        $api = config('app.shop_url') . '/api.php';

        $response = curl_request($api, 'POST', $param);
		return $response;
		if ($response['result'] == 'success' && isset($response['info'])) {
 
        }
        return false;
	}

	/**
	 * 验证方法
	 */
	public static function getAc($params, $token){
	    ksort($params);
	    $tmp_verfy = '';
	    foreach ($params as $key => $value){
	        $params[$key] = stripslashes($value);
	        $tmp_verfy .= $params[$key];
	    }
	    return strtolower(md5(trim($tmp_verfy.$token)));
	}

}
?>