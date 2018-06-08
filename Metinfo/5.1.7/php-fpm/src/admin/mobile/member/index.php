<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$sql=$met_member_login==3?'checkid=0':'admin_approval_date is null';
$query = "SELECT * FROM $met_admin_table where {$sql} and usertype <> 3 and lang='$lang' ORDER BY admin_modify_date DESC";
$result = $db->query($query);
$admin_list=array();
while($list = $db->fetch_array($result)){
	switch($list['usertype']){
		case '1':$list['usertype']=$lang_access1;break;
		case '2':$list['usertype']=$lang_access2;break;
	}
	$list['checked']=$list['checkid']==1?$lang_memberChecked:$lang_memberUnChecked;
	$list[url] = 'editor.php?lang='.$lang.'&id='.$list[id];
	$admin_list[]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/member/member');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>