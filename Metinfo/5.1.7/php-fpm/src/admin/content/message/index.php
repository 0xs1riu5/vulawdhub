<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
$serch_sql=" where lang='$lang' ";
if($readok!="") $serch_sql.=" and readok='$readok' ";
$order_sql=" order by id desc ";
if($customerid) { $serch_sql .= " and customerid='$customerid' "; }
if($search == "detail_search") {
	if($useinfo) { $serch_sql .= " and useinfo like '%$useinfo%' "; }
	$total_count = $db->counter($met_message, "$serch_sql", "*");
} else {
	$total_count = $db->counter($met_message, "$serch_sql", "*");
}
require_once 'include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$list_num = 20;
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query = "SELECT * FROM {$met_message} {$serch_sql} {$order_sql} LIMIT {$from_record}, {$list_num}";
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
	$list[readok] = $list[readok] ? $lang_yes : $lang_no;
	$message_list[]=$list;
}
$page_list = $rowset->link("index.php?anyid={$anyid}&search={$search}&readok={$readok}&useinfo={$useinfo}&lang={$lang}&page=");
$listclass[1]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('content/message/message');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>