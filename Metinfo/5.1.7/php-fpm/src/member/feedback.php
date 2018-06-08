<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
$query = "SELECT * FROM $met_list where bigid='$met_fd_class' order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
$selectlist[]=$list;
}
$serch_sql=" where customerid='$metinfo_member_name' and lang='$lang' ";
if($met_fd_classname!="")$serch_sql.=" and exists(select info from $met_flist where listid=$met_feedback.id and paraid=$met_fd_class and info='$met_fd_classname')";
$order_sql=" order by id desc ";
$total_count = $db->counter($met_feedback, "$serch_sql", "*");

require_once '../include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$list_num = 20;
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query = "SELECT * FROM $met_feedback $serch_sql $order_sql  LIMIT $from_record, $list_num";

$result = $db->query($query);
while($list= $db->fetch_array($result)){	
$list[readok] = $list[readok] ? $lang_Yes : $lang_No;
//$list[addtime] = date('Y-m-d',strtotime($list[addtime]));
$feedback_list[]=$list;
}
$page_list = $rowset->link("feedback.php?search=$search&lang=$lang&page=");
$mfname='feedback';
include template('member');
footermember();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>