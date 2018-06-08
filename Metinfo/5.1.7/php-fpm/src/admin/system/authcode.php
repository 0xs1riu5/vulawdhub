<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
$authurlself=$met_weburl;
$authcode=trim($authcode);
$authpass=trim($authpass);
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$rurls='../system/authcode.php?anyid='.$anyid.'&cs='.$cs.'&lang='.$lang;
if($action=="modify"){
	$authurl=authcode($authcode, 'DECODE', $authpass);
	$authurl=explode("|",$authurl);
	foreach($authurl as $val){
		if(strstr($met_weburl,$val)){
			$db->query("update $met_otherinfo set authpass='$authpass',authcode='$authcode' where id=1");
			$re=varcodeb('sys');
			if($re['md5'])delcodeb($re['md5']);
			if($re['re']=='SUC'){
				$query ="update $met_otherinfo set 
					  authpass    ='$authpass',
					  authcode    ='$authcode',
					  authtext    ='{$lang_authTip3}'
					  where id='1'";
				$db->query($query);
				$db->query("update $met_config set value='0.06' where name='met_smsprice'");
				$db->query("update $met_otherinfo set info1='',info2='' where id=1");
				echo "<script type=\"text/javascript\">location.href='{$rurls}';parent.window.location.reload();</script>";
				die();
			}else{
				$db->query("update $met_otherinfo set info1='',info2='',authpass='',authcode='',authtext='' where id=1");
				if($re['re']=='DISREAD'){metsave($rurls,$lang_updaterr18);}
				elseif($re['re']=='nohost'){metsave($rurls,$lang_updaterr20);}
				else{metsave($rurls,$lang_authTip2);}
			}
		}
	}
	$db->query("update $met_otherinfo set info1='',info2='',authpass='',authcode='',authtext='' where id=1");
	metsave($rurls,$lang_authTip2);
}else{
	$authinfo = $db->get_one("SELECT * FROM $met_otherinfo where id=1");
	if(!$authinfo){
		metsave('-1',$lang_dataerror);
	}
	if($authinfo[authcode]=='')$authinfo[authcode]="{$lang_authTip4}";
}
if($cs==1){
	if($met_agents_type>1){
		echo '&nbsp';
		die();
	}
	$time=time();
	$met_file='/authorize.php';
	$authinfo=$db->get_one("SELECT * FROM $met_otherinfo where id=1");
	if($authinfo['info1']&&$autcod){
		if($authinfo['info1']=='NOUSER'){
			echo '';
			die;
		}
		else{
			$authinfo['info2']=is_numeric($authinfo['info2'])?$authinfo['info2']:2147483647;
			if($time<=$authinfo['info2']){
				echo $authinfo['info1'];
				die();
			}
		}		
	}
	if($authinfo['authcode']&&$authinfo['authpass']){
		$post_data = array('met_code'=>$authinfo['authcode'],'met_key'=>$authinfo['authpass']);
		$info=curl_post($post_data,30);
		if($info=='no host'){
			$user['domain']=$lang_hosterror;
			$user['webname']=$lang_hosterror;
			$user['type']=$lang_hosterror;
			$user['buytime']=$lang_hosterror;
			$user['lifetime']=$lang_hosterror;
			$user['service']=$lang_hosterror;
		}else{
			$usertemp=explode('|',$info);
			if($usertemp[0]!='NOUSER'){
				$user['domain']=$usertemp[1];
				$user['webname']=$usertemp[3];
				$user['type']=$usertemp[2];
				$user['buytime']=date('Y-m-d',$usertemp[4]);
				$user['lifetime']=$user['lifetime']?date('Y-m-d',$usertemp[5]):'永久';
				$user['service']=$usertemp[6];
			}
		}
	}
	else{
		$usertemp[0]='NOUSER';
	}
	$info1=$usertemp[0]=='NOUSER'?$usertemp[0]:$user['type'];
	$info2=$time<=$usertemp[5]?$usertemp[5]:2147483647;
	$db->query("update $met_otherinfo set info1='$info1',info2='$info2' where id=1");
	if($autcod){
		echo $user['type'];
		die;
	}
}
$rooturl="..";
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('system/authcode');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>