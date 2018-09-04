<?php
namespace App\Services\Payment\Teegon;

require_once("core.function.php");

class TeegonService {

    public $base_url;
    public $id;
       
    private $client_id;
    private $client_secret;

    const TEE_SITE_URL      = 'https://teegon.com/';
    const TEE_API_URL       = 'https://api.teegon.com/';

    function __construct($base_url, $client_id, $client_secret){ //, $client_id, $client_secret
        $this->base_url = $base_url;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    function pay($param,$result_decode = true){
        if(empty($param['order_no'])){
            return "订单号错误";
        }

        // if(empty($param['return_url'])){
        //     return "付款成功回调地址错误";
        // }

        if(empty($param['amount'])){
            return "支付金额错误";
        }

        $param['client_id'] = $this->client_id;
        $param['client_secret'] = $this->client_secret;
        $rst = $this->post('v1/charge/', $param);
        if($result_decode == true){
            return json_decode($rst, true);
        }

        return $rst;
    }

    function verify_return(){
        if($_GET['charge_id']){
            if(empty($_GET['sign'])){
                return array('status'=>"1",'error_msg'=>'天工服务端返回签名信息错误!','param'=>$_GET);
            }

            if(!$this->get_sign_veryfy($_GET,$_GET['sign'])){
                return array('status'=>"2",'error_msg'=>'签名验证错误请检查签名算法!','param'=>$_GET);
            }

            return array('status'=>"0",'error_msg'=>'','param'=>$_GET);
        }
    }

    function post($path, $params=array()){
        return $this->call('post', $path, $params);
    }

    function get($path, $params=array()){
        return $this->call('get', $path, $params);
    }

    function delete($path, $params=array()){
        return $this->call('delete', $path, $params);
    }

    function put($path, $params=array()){
        return $this->call('put', $path, $params);
    }

    function call($method, $path, $params=array()){
        $url = $this->base_url.$path;
        $options = array(
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 10,
        );

        $param_string = http_build_query($params);
        switch(strtolower($method)){
            case 'post':
                $options += array(CURLOPT_POST => 1,
                              CURLOPT_POSTFIELDS => $param_string);
                break;
            case 'put':
                $options += array(CURLOPT_PUT => 1,
                              CURLOPT_POSTFIELDS => $param_string);
                break;
            case 'delete':
                $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                if($param_string)
                    $options[CURLOPT_URL] .= '?'.$param_string;
                break;
            default:
                if($param_string)
                    $options[CURLOPT_URL] .= '?'.$param_string;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        if( ! $result = curl_exec($ch))
        {
            $this->on_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function get_sign_veryfy($para_temp, $sign){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->para_filter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->arg_sort($para_filter);
        //生成加密字符串
        $prestr = $this->create_string($para_sort);

        $isSgin = $this->md5_verify($prestr, $sign, $this->client_secret);

        return $isSgin;
    }

    public function sign($para_temp){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->para_filter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->arg_sort($para_filter);
        //生成加密字符串
        $prestr = $this->create_string($para_sort);

        $prestr = $this->client_secret .$prestr . $this->client_secret;
        return strtoupper(md5($prestr));
    }


    private function para_filter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    private function arg_sort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    private function create_string($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.$val;
        }


        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    private function md5_verify($prestr, $sign, $key) {
        $prestr = $key .$prestr . $key;
        $mysgin = strtoupper(md5($prestr));
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }



    private function on_error($err){
        return false;
    }

    
    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function buildRequestForm($para_temp, $method, $button_name) {
        //待请求参数数组
        // $para = $this->buildRequestPara($para_temp);
        
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".self::TEE_API_URL."charge/pay' method='".$method."'>";
        while (list ($key, $val) = each ($para_temp)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit'  value='".$button_name."' style='display:none;'></form>";
        
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        
        return $sHtml;
    }
}

