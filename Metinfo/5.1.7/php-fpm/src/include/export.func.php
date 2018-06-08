<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
/*发送POST*/
function curl_post($post,$timeout){
global $met_weburl,$met_host,$met_file;
$host=$met_host;
$file=$met_file;
	if(get_extension_funcs('curl')&&function_exists('curl_init')&&function_exists('curl_setopt')&&function_exists('curl_exec')&&function_exists('curl_close')){
		$curlHandle=curl_init(); 
		curl_setopt($curlHandle,CURLOPT_URL,'http://'.$host.$file); 
		curl_setopt($curlHandle,CURLOPT_REFERER,$met_weburl);
		curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($curlHandle,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($curlHandle,CURLOPT_TIMEOUT,$timeout);
		curl_setopt($curlHandle,CURLOPT_POST, 1);	
		curl_setopt($curlHandle,CURLOPT_POSTFIELDS, $post);
		$result=curl_exec($curlHandle); 
		curl_close($curlHandle); 
	}
	else{
		if(function_exists('fsockopen')||function_exists('pfsockopen')){
			$post_data=$post;
			$post='';
			@ini_set("default_socket_timeout",$timeout);
			while (list($k,$v) = each($post_data)) {
				$post .= rawurlencode($k)."=".rawurlencode($v)."&";
			}
			$post = substr( $post , 0 , -1 );
			$len = strlen($post);
			if(function_exists(fsockopen)){
				$fp = @fsockopen($host,80,$errno,$errstr,$timeout);
			}
			else{
				$fp = @pfsockopen($host,80,$errno,$errstr,$timeout);
			}
			if (!$fp) {
				$result='';
			}
			else {
				$result = '';
				$out = "POST $file HTTP/1.0\r\n";
				$out .= "Host: $host\r\n";
				$out .= "Referer: $met_weburl\r\n";
				$out .= "Content-type: application/x-www-form-urlencoded\r\n";
				$out .= "Connection: Close\r\n";
				$out .= "Content-Length: $len\r\n";
				$out .="\r\n";
				$out .= $post."\r\n";
				fwrite($fp, $out);
				$inheader = 1; 	
				while(!feof($fp)){
					$line = fgets($fp,1024); 
						if ($inheader == 0) {    
							$result.=$line;
						}  
						if ($inheader && ($line == "\n" || $line == "\r\n")) {  
							$inheader = 0;  
					}    

				}
			
				while(!feof($fp)){
					$result.=fgets($fp,1024);
				}
				fclose($fp);
				str_replace($out,'',$result);
			}
		}
		else{
			$result='';
		}
	}
	$result=trim($result);
	if(substr($result,0,7)=='metinfo'){
		return substr($result,7);
	}
	else{
		return 'nohost';
	}
}
function link_error($str){
	switch($str){
		case 'Timeout' :
			return -6;
		break;
		case 'NO File' :
			return -5;
		break;
		case 'Please update' :
			return -4;
		break;
		case 'No Permissions' :
			return -3;
		break;
		case 'No filepower' :
			return -2;
		break;	
		case 'nohost' :
			return -1;
		break;	
		Default;
			return 1;
		break;
	}
}
/*远程下载*/
/*/URLFROM 远程文件地址 URLTO 本地文件地址，为空表示直接输出*/
function dlfile($urlfrom,$urlto,$timeout=30){
	global $checksum;
	$post_data = array('urlfrom'=>$urlfrom,'checksum'=>$checksum);
	$result=curl_post($post_data,$timeout);
	$link=link_error($result);
	if($link!=1){
		return $link;
	}
	if($urlto){
		$return=file_put_contents($urlto,$result);
		if(!$return){return link_error('No filepower');}
		else{return 1;}
	}
	else{
		return $result;
	}
}
/*文件下载错误返回*/
function dlerror($error){
	global $lang_dltips1,$lang_dltips2,$lang_dltips3,$lang_dltips4,$lang_dltips5,$lang_dltips6,$lang_dltips7;
	switch($error){
		case -1:
			return $lang_dltips1;
		break;
		case -2:
		    return $lang_dltips2;
		break;
		case -3:
			return $lang_dltips3;
		break;
		case -4:
			return $lang_dltips4;
		break;
		case -5:
			return $lang_dltips5;
		break;
		case -6:
			return $lang_dltips6;
		break;	
		case -7:
			return $lang_dltips7;
		break;		
	}
	return 1;
}
/*权限验证*/
/*$type:应用类型;返回值:re:返回状态;md5:验证码*/
function varcodeb($type){
	global $met_file,$db,$met_otherinfo;
	$blcode = $db->get_one("SELECT * FROM $met_otherinfo where id='1'");
	$authcode = $blcode['authcode'];
	$authpass = $blcode['authpass'];
	$met_file='/test/varcode.php';
	if($authcode&&$authpass){
		$post=array('code'=>$authcode,'pass'=>$authpass,'type'=>$type);
		$md5=curl_post($post,30);
		if(preg_match("/^[a-zA-Z0-9]{32}$/",$md5)){
			if(!is_dir(ROOTPATH.'cache/'))mkdir(ROOTPATH.'cache/','0755');
			if(file_put_contents(ROOTPATH."cache/$md5.txt",$md5)){
				$met_file='/test/check.php';
				$post=array('md5'=>$md5);
				$result=curl_post($post,30);
				if($result=='SUC'){
					return array('re'=>'SUC','md5'=>$md5);
				}else{
					delcodeb($md5);
					return array('re'=>$result,'md5'=>'');
				}
			}else{
				delcodeb($md5);
				return array('re'=>'DISREAD','md5'=>'');
			}
		}
		else{
			return array('re'=>$md5,'md5'=>'');
		}
	}else{
		return array('re'=>'DISBUS','md5'=>'');
	}
	
}
function delcodeb($varcode){
	global $met_file;
	$met_file='/test/delvarcode.php';
	unlink(ROOTPATH."/cache/$varcode.txt");
	$post=array('varcode'=>$varcode);
    curl_post($post,30);
}
function sedsmstype($type){
	global $lang_smstips1,$lang_smstips58,$lang_smstips59,$lang_smstips60,$lang_smstips61;
	switch($type){
		case 1:$metinfo=$lang_smstips1;break;
		case 2:$metinfo=$lang_smstips58;break;
		case 3:$metinfo=$lang_smstips59;break;
		case 4:$metinfo=$lang_smstips60;break;
		case 5:$metinfo=$lang_smstips61;break;
	}
	return $metinfo;
}
function sedsmserrtype($err,$type){
	global $lang_getOK,$lang_smstips66,$lang_smstips67,$lang_smstips68,$lang_smstips69,$lang_smstips70,$lang_smstips71,$lang_smstips72,$lang_smstips73,$lang_smstips74,$lang_smstips75,$lang_smstips76;
	switch($err){
		case 'SUCCESS':$metinfo=$lang_getOK;break;
		case 'ERR_10' :$metinfo=$lang_smstips66;break;
		case 'ERR_11' :$metinfo=$lang_smstips67;break;
		case 'ERR_12' :$metinfo=$lang_smstips68;break;
		case 'ERR_13' :$metinfo=$lang_smstips69;break;
		case 'ERR_14' :$metinfo=$type?$lang_smstips70:$lang_smstips76;break;
		case 'ERR_15' :$metinfo=$lang_smstips71;break;
		case 'ERR_16' :$metinfo=$lang_smstips72;break;
		case 'ERR_17' :$metinfo=$lang_smstips73;break;
		case 'ERR_18' :$metinfo=$lang_smstips74;break;
		case 'ERR_19' :$metinfo=$lang_smstips75;break;
	}
	return $metinfo;
}
/*短信发送*/
function sendsms($phone,$message,$type){
	global $db,$met_otherinfo,$met_sms,$met_file;
	global $code,$lang_smstips66;
	/*验证商业用户*/
	$varcode=varcodeb('sms');
	$varcode=$varcode['re']=='SUC'?$varcode['md5']:'';
	/*发送短信*/
	$total_pass = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
	if($total_pass){
		$met_file='/sms/sendsms.php';
		$post=array(
			'total_pass'=>$total_pass['authpass'],
			'phone'=>$phone,
			'message'=>$message,
			'type'=>$type,
			'varcode'=>$varcode,
			'code'=>$code
		);
		$sms = curl_post($post,30);
		$sms = trim($sms);
		$time=time();
		switch($sms){
			case 'SUCCESS':$qey=1;break;
			case 'ERR_10' :$qey=$type==1?0:1;break;
			case 'ERR_11' :$qey=0;break;
			case 'ERR_12' :$qey=0;break;
			case 'ERR_13' :$qey=$type==1?0:1;break;
			case 'ERR_14' :$qey=$type==1?0:1;break;
			case 'ERR_15' :$qey=0;break;
			case 'ERR_16' :$qey=$type==1?0:1;break;
			case 'ERR_17' :$qey=$type==1?0:1;break;
			case 'ERR_17' :$qey=0;break;
		}
		$metinfo = $type==1?sedsmserrtype($sms):$sms;
		if($qey){
			$query = "INSERT INTO $met_sms SET
				time     ='$time',
				type     ='$type',
				content  ='$message',
				tel      ='$phone',
				remark   ='$sms'";
			$db->query($query);
		}
	}else{
		$metinfo = $lang_smstips66;
	}
	/*删除验证文件*/
	if($varcode!='')delcodeb($varcode);
	return $metinfo;
}
/*验证商业会员*/
function smspreice(){
global $met_file;
	$varcode=varcodeb('sms');
	$code=$varcode['re'];
	$varcode=$varcode['re']=='SUC'?$varcode['md5']:'';
	$met_file='/sms/smsprice.php';
	$post=array('code'=>$code,'varcode'=>$varcode);
	$re = curl_post($post,30);
	$res= explode('|',$re);
	$re='';
	$re['re']=$res[0];
	$re['price']=$res[1];
	delcodeb($varcode);
	return $re;
}
function smsremain(){
global $met_file,$db,$met_otherinfo;
	$total_passok = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
	$met_file='/sms/remain.php';
	$post=array('total_pass'=>$total_passok['authpass']);
	//$balance = $total_passok['authpass']?curl_post($post,30):'0.00';
	if($total_passok['authpass']){
		$balance=curl_post($post,30);
		$balance=trim($balance);
		if(!preg_match("/^[0-9\.]*$/",$balance)){
			$re['re']='nohost';
		}
	}else{
		$balance='0.00';
	}
	$re['balance']=$balance;
	return $re;
}
function powererr($err){
	global $lang_updaterr18,$lang_updaterr19;
	switch($err){
		case 'DISREAD' :$metinfo=$lang_updaterr18;break;
		Default        :$metinfo=$lang_updaterr19;break;
	}
	return $metinfo;
}
function maxnurse(){
	global $db,$met_sms;
	$ct=strtotime(date("Y/m/d 00:00:00",time()));	
	$et=strtotime(date("Y/m/d 23:59:59",time()));	
	$maxnurse = $db->get_all("SELECT * FROM $met_sms WHERE time>={$ct} and time<='{$et}' and type='4' and remark='SUCCESS'");
	return count($maxnurse);
}
function strdomain($url){
	$str       = str_replace("http://","",$url);
	$strdomain = explode("/",$str);
	return $strdomain[0];
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>