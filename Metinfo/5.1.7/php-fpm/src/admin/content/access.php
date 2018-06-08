<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth=$_GET['depth']==1?'':$depth;
require_once $depth.'../login/login_check.php';
$lev2=$lev;
//$query="select * from $met_admin_array where array_type='1' and user_webpower='$lev' and lang='$lang'";
$query="select * from $met_admin_array where id='$lev'";
$levs=$db->get_one($query);
$lev=$levs[user_webpower];
$level='';
$list_access['access']=$acesss_contents?$acesss_contents:$list_access['access'];
if($met_member_use){
	$query="select * from $met_admin_array where array_type='1' and lang='$lang' order by user_webpower asc";
	$admin_array=$db->get_all($query);
	$level=$lev==0&&!$menbermanage?"<option value='0'>$lang_access0</option>":'';
	foreach($admin_array as $key=>$val){
		if($val['user_webpower']>=intval($lev)){
			$accessnow=$val['id']==$list_access['access']?"selected='selected'":'';
			//$webpower=$menbermanage?$val[id]:$val[user_webpower];
			$webpower=$val[id];
			$level.="<option value='$webpower' $accessnow>$val[array_name]</option>";
		}
	}
	$level.=$menbermanage?'':($list_access['access']==3?"<option value='3' selected='selected'>$lang_access3</option>":"<option value='3'>$lang_access3</option>");
	if($action=='js'){echo $level;die();}
}
$lev=$lev2;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>