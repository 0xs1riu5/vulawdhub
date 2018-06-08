<?php

!defined('IN_HDWIKI') && exit('Access Denied');

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

require HDWIKI_ROOT.'/config.php';
require HDWIKI_ROOT.'/lib/string.class.php';
require HDWIKI_ROOT.'/model/base.class.php';

class hdwiki {

	var $get = array();
	var $post = array();
	var $querystring;
	
	function hdwiki() {
		$this->init_request();
		$this->load_control();
	}
	
	function init_request(){
		if (!file_exists(HDWIKI_ROOT.'/data/install.lock')) {
			header('location:install/install.php');
			exit();
		}
		header('Content-type: text/html; charset='.WIKI_CHARSET);
		$querystring=str_replace("'","",urldecode($_SERVER['QUERY_STRING']));
		if(strpos($querystring , 'plugin-hdapi-hdapi-default') !== false){
			$querystring=str_replace('plugin-hdapi-', '', $querystring);
		}
		$pos = strpos($querystring , '.');
		if($pos!==false){
			$querystring=substr($querystring,0,$pos);
		}
		$this->get = explode('-' , $querystring);
		
		if (count($this->get) <= 3 && count($_POST) == 0 && substr($querystring, 0, 6) == 'admin_' && substr($querystring, 0, 10) != 'admin_main'){
			$this->querystring = $querystring;
		}
		
		if(empty($this->get[0])){
			$this->get[0]='index';
		}
		if(empty($this->get[1])){
			$this->get[1]='default';
		}

		if(count($this->get)<2){
			exit(' Access Denied !');
		}
		//unset($_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);
		$this->get = string::haddslashes($this->get,1);
		$this->post = string::haddslashes($_POST);
		$_COOKIE = string::haddslashes($_COOKIE);
		$this->checksecurity();
		$remain=array('_SERVER','_FILES','_COOKIE','GLOBALS','starttime','mquerynum');
		foreach ($GLOBALS as $key => $value){
			if ( !in_array($key,$remain) ) {
				unset($GLOBALS[$key]);
			}
		}
	}
	
	function load_control(){
		if($this->get[0]=='plugin'){
			if(empty($this->get[2])){
				$this->get[2]=$this->get[1];
			}
			if(empty($this->get[3])){
				$this->get[3]='default';
			}
			$pluginfile=HDWIKI_ROOT.'/plugins/'.$this->get[1].'/control/'.$this->get[2].'.php';
			if(false===@include($pluginfile)){
				$this->notfound('plugin control "'.$this->get[2].'"  not found!');
			}
			$this->get=array_slice($this->get,2);
		}else{
			$controlfile=HDWIKI_ROOT.'/control/'.$this->get[0].'.php';
			if(false===@include($controlfile)){
				$this->notfound('control "'.$this->get[0].'"  not found!');
			}
		}
	}

	function run(){
		$control = new control($this->get,$this->post);
		if ($this->querystring){
			$control->hsetcookie('querystring',$this->querystring, 3600);
		}
		$method = $this->get[1];
		$exemption=true; //免检方法的标志，免检方法不需要经过权限检测
		if('hd'!= substr($method, 0, 2)){
			$exemption=false;
			$method = 'do'.$this->get[1];
		}
		if ($control->user['uid'] == 0	&& $control->setting['close_website'] === '1'	&& strpos('dologin,dologout,docheckusername,docheckcode,docode',$method) === false
		){
			exit($control->setting['close_website_reason']);
		}
		
		if(method_exists($control, $method)) {
			$regular=$this->get[0].'-'.$this->get[1];
			$querystring=implode('-',$this->get);
			$isadmin= ('admin'==substr($this->get[0],0,5));
			if($exemption || $control->checkable($querystring) || $control->checkable($regular)){
				$control->$method();
			}else{
				$control->message($regular.$control->view->lang['refuseAction'],'', $isadmin);
			}
		}else {
			$this->notfound('method "'.$method.'" not found!');
		}
	}
	
	function notfound($error){
		@header('HTTP/1.0 404 Not Found');
		exit("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1><p> $error </p></body></html>");
	}
	
	function checksecurity() {
		$check_array = array(
			//于get请求 需要检查哪些关键词 
			'get'=>array('cast', 'exec','show ','show/*','alter ','alter/*','create ','create/*','insert ','insert/*', 'select ','select/*','delete ','delete/*','update ', 'update/*','drop ','drop/*','truncate ','truncate/*','replace ','replace/*','union ','union/*','execute', 'from', 'declare', 'varchar', 'script', 'iframe', ';', '0x', '<', '>', '\\', '%27', '%22', '(', ')'),
		);
		foreach ($check_array as $check_key=>$check_val) {
			if(!empty($this->$check_key)) {
				foreach($this->$check_key as $getvalue) {
					foreach ($check_val as $invalue) {
						if(stripos($getvalue, $invalue) !== false){
							$this->notfound('page is not found!');
							//exit('No Aceess!注意敏感词!');
						}
					}
				}
			}
		}
	}
}


?>