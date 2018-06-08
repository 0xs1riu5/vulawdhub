<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$rurl='../interface/online/index.php?anyid='.$anyid.'&lang='.$lang;
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
	$query = "delete from $met_online where id='$val'";
	$db->query($query);
	}
	file_unlink($depth."../../cache/online_$lang.inc.php");
	metsave($rurl,'',$depth);
}elseif($action=="editor"){
	$allidlist=explode(',',$allid);
	$adnum = count($allidlist)-1;
	for($i=0;$i<$adnum;$i++){
		$name = 'name_'.$allidlist[$i];
		$name = $$name;
		$no_order = 'no_order_'.$allidlist[$i];
		$no_order = $$no_order;
		$qq = 'qq_'.$allidlist[$i];
		$qq = $$qq;
		$msn = 'msn_'.$allidlist[$i];
		$msn = $$msn;		
		$taobao = 'taobao_'.$allidlist[$i];
		$taobao = $$taobao;		
		$alibaba = 'alibaba_'.$allidlist[$i];
		$alibaba = $$alibaba;		
		$skype = 'skype_'.$allidlist[$i];
		$skype = $$skype;
		$tpif = is_numeric($allidlist[$i])?1:0;
		$sqly = $tpif?"id='$allidlist[$i]'":'';
		if($sqly!='')$skin_m=$db->get_one("SELECT * FROM $met_online WHERE $sqly");
		if($tpif){
			if(!$skin_m)metsave('-1',$lang_dataerror,$depth);
		}
		$uptp = $tpif?"update":"insert into";
		$upbp = $tpif?"where id='$allidlist[$i]'":",lang='$lang'";
		$query="$uptp $met_online set
                      name           = '$name',
     				  no_order       = '$no_order',
					  qq             = '$qq',
					  msn            = '$msn',
					  taobao         = '$taobao',
					  alibaba        = '$alibaba',
					  skype          = '$skype'
			$upbp";
		$db->query($query);
	}
	file_unlink($depth."../../cache/online_$lang.inc.php");
	metsave($rurl,'',$depth);
}else{
	$query = "delete from $met_online where id='$id'";
	$db->query($query);
	file_unlink($depth."../../cache/online_$lang.inc.php");
	metsave($rurl,'',$depth);
}
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
?>
