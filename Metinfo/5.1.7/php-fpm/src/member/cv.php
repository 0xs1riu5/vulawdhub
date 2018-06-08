<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
	$serch_sql=" where lang='$lang' and customerid='$metinfo_member_name' ";
    $total_count = $db->counter($met_cv, "$serch_sql", "*");
    require_once '../include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = 20;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();	
	
	$query = "SELECT * FROM $met_job where lang='$lang'";
    $result = $db->query($query);
	while($list = $db->fetch_array($result)){
	$job_list[$list[id]]=$list[position];
	}
    $query = "SELECT id,jobid,addtime,customerid,readok FROM $met_cv $serch_sql order by addtime desc LIMIT $from_record, $list_num";

	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
	$list['customerid']=$list['customerid']=='0'?$lang_cvID:$list['customerid'];
	if(isset($job_list[$list[jobid]])) $list[position]= $job_list[$list[jobid]];
	else $list[position]= "<font color=red>$lang_cvTip4</font>";
    $cv_list[]=$list;
    }
	
$page_list = $rowset->link("cv.php?search=$search&page=");

$mfname='cv';
include template('member');
footermember();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>