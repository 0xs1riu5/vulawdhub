<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
$serch_sql=" where customerid='$metinfo_member_name' and lang='$lang' ";
	$order_sql=" order by id desc ";
	$total_count = $db->counter($met_message, "$serch_sql", "*");
    require_once '../include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = 20;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
    $query = "SELECT * FROM $met_message $serch_sql $order_sql LIMIT $from_record, $list_num";
    $result = $db->query($query);
	while($list= $db->fetch_array($result)){
	$list[readok]=$list[readok]==1?$lang_YES:$lang_NO;
    $message_list[]=$list;
    }
$page_list = $rowset->link("message.php?search=$search&lang=$lang&page=");

$mfname='message';
include template('member');
footermember();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>