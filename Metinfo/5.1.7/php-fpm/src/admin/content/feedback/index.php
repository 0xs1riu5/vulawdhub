<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
$depth='../';
require_once $depth.'../login/login_check.php';
if($class1){
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$class1){
			$tingname    =$val['name'].'_'.$val['columnid'];
			$$val['name']=$$tingname;
		}
	}
}
$query = "SELECT * FROM $met_list where bigid='$met_fd_class' order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$selectlist[]=$list;
}
$serch_sql=" where lang='$lang' ";
if(!$customerid)$serch_sql.=" and class1='$class1' ";
if($readok!="") $serch_sql.=" and readok='$readok' ";
if($met_fd_classname!="")$serch_sql.=" and exists(select info from $met_flist where listid=$met_feedback.id and paraid=$met_fd_class and info='$met_fd_classname')";
$order_sql=" order by id desc ";
if($customerid) { $serch_sql .= " and customerid='$customerid' "; }
if($search == "detail_search") {
	if($useinfo) { $serch_sql .= " and useinfo like '%$useinfo%' "; }
	$total_count = $db->counter($met_feedback, "$serch_sql", "*");
}else{
	$total_count = $db->counter($met_feedback, "$serch_sql", "*");
}
require_once 'include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$list_num = 20;
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query = "SELECT * FROM $met_feedback $serch_sql $order_sql LIMIT $from_record, $list_num";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$list['customerid']=$list['customerid']=='0'?$lang_feedbackAccess0:$list['customerid'];
	$list[readok] = $list[readok] ? $lang_yes : $lang_no;
	$feedback_list[]=$list;
}
$page_list = $rowset->link("index.php?lang=$lang&class1=$class1&search=$search&readok=$readok&useinfo=$useinfo&met_fd_classname=$met_fd_classname&customerid={$customerid}&page=");
	$cs=3;
	$listclass[$cs]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('content/feedback/feedback');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>