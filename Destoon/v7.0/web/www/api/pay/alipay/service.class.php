<?php
defined('IN_DESTOON') or exit('Access Denied');
/**
	*类名：alipay_service
	*功能：支付宝外部服务接口控制
	*版本：2.0
	*日期：2008-08-01
	*作者：支付宝公司销售部技术支持团队
	*联系：0571-26888888
	*版权：支付宝公司
*/

class alipay_service {

	var $gateway = "https://www.alipay.com/cooperate/gateway.do?";         //支付接口
	var $parameter;       //全部需要传递的参数
	var $security_code;   //安全校验码
	var $mysign;          //签名

	//构造支付宝外部服务接口控制
	function __construct($parameter,$security_code,$sign_type = "MD5",$transport= "https") {
		$this->alipay_service($parameter,$security_code,$sign_type,$transport);
	}
	function alipay_service($parameter,$security_code,$sign_type = "MD5",$transport= "https") {
		$this->parameter      = $this->para_filter($parameter);
		$this->security_code  = $security_code;
		$this->sign_type      = $sign_type;
		$this->mysign         = '';
		$this->transport      = $transport;
		if($parameter['_input_charset'] == "")
		$this->parameter['_input_charset']='GBK';
		if($this->transport == "https") {
			$this->gateway = "https://www.alipay.com/cooperate/gateway.do?";
		} else $this->gateway = "http://www.alipay.com/cooperate/gateway.do?";
		$sort_array = array();
		$arg = "";
		$sort_array = $this->arg_sort($this->parameter);
		while (list ($key, $val) = each ($sort_array)) {
			//$arg.=$key."=".$this->charset_encode($val,$this->parameter['_input_charset'])."&";
			$arg.=$key."=".$val."&";
		}
		$DT_PREstr = substr($arg,0,count($arg)-2);  //去掉最后一个问号
		$this->mysign = $this->sign($DT_PREstr.$this->security_code);
	}


	function create_url() {
		$url        = $this->gateway;
		$sort_array = array();
		$arg        = "";
		$sort_array = $this->arg_sort($this->parameter);
		while (list ($key, $val) = each ($sort_array)) {
			//$arg.=$key."=".urlencode($this->charset_encode($val,$this->parameter['_input_charset']))."&";
			$arg.=$key."=".urlencode($val)."&";
		}
		$url.= $arg."sign=" .$this->mysign ."&sign_type=".$this->sign_type;
		return $url;

	}

	function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;

	}

	function sign($DT_PREstr) {
		$mysign = "";
		if($this->sign_type == 'MD5') {
			$mysign = md5($DT_PREstr);
		}elseif($this->sign_type =='DSA') {
			//DSA 签名方法待后续开发
			die("DSA 签名方法待后续开发，请先使用MD5签名方式");
		}else {
			die("支付宝暂不支持".$this->sign_type."类型的签名方式");
		}
		return $mysign;

	}
	function para_filter($parameter) { //除去数组中的空值和签名模式
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para[$key] = $parameter[$key];
		}
		return $para;
	}
	//实现多种字符编码方式
	function charset_encode($input,$_output_charset ,$_input_charset ="GBK" ) {
		$output = "";
		if(!isset($_output_charset) )$_output_charset  = $this->parameter['_input_charset '];
		if($_input_charset == $_output_charset || $input ==null) {
			$output = $input;
		} elseif (function_exists("mb_convert_encoding")){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists("iconv")) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die("sorry, you have no libs support for charset change.");
		return $output;
	}
}
?>