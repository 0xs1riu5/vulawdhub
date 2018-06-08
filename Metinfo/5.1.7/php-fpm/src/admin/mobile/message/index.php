<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$query = "SELECT * FROM {$met_message} where lang='$lang' and readok=0 {$order_sql}";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$list['customerid']=$list['customerid']=='0'?$lang_feedbackAccess0:$list['customerid'];
	if($met_member_use){
		switch($list['access']){
			case '1':$list['access']=$lang_access1;break;
			case '2':$list['access']=$lang_access2;break;
			case '3':$list['access']=$lang_access3;break;
			default: $list['access']=$lang_access0;break;
		}
	}	
	$list[readok] = $lang_no;
	$list[url] = 'editor.php?lang='.$lang.'&id='.$list[id];
	$message_list[]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/message/message');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>