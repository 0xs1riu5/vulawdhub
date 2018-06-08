<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
 $serch_sql=" where array_type='1' and lang='$lang'";
    if($search == "detail_search") {        
        if($array_name) { $serch_sql .= " and array_name like '%$array_name%' "; }
        $total_count = $db->counter($met_admin_array, "$serch_sql", "*");
    } else {
        $total_count = $db->counter($met_admin_array, "$serch_sql", "*");
    }
    require_once 'include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = 16;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
    $query = "SELECT * FROM $met_admin_array $serch_sql ORDER BY user_webpower DESC LIMIT $from_record, $list_num";
    $result = $db->query($query);
	$admin_list=array();
	 while($list = $db->fetch_array($result)) {
		$admin_list[]=$list;
    }
$page_list = $rowset->link("array.php?array_name=$array_name&search=$search&lang=$lang&anyid={$anyid}&page=");
switch($usertype){
	case '1':$user1="selected='selected'";break;
	case '2':$user2="selected='selected'";break;
	default:$user0="selected='selected'";break;
}
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('member/member_array');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>