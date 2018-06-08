<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
	if($module=='')$module=10000;
    $serch_sql="where lang='$lang'";
    if($search == "detail_search"&&$title_search) {        
		$serch_sql .= " and title like '%$title_search%' "; 
	}
	if($module==10000){
		$total_count = $db->counter($met_news, "$serch_sql and recycle=2", "*");
		$total_count += $db->counter($met_product, "$serch_sql and recycle=3", "*");
		$total_count += $db->counter($met_download, "$serch_sql and recycle=4", "*");
		$total_count += $db->counter($met_img, "$serch_sql and recycle=5", "*");
		require_once 'include/pager.class.php';
		$page = (int)$page;
		if($page_input){$page=$page_input;}
		$list_num = 16;
		$rowset = new Pager($total_count,$list_num,$page);
		$from_record = $rowset->_offset();
		$query = "SELECT id,title,class1,class2,class3,updatetime,recycle FROM $met_product $serch_sql and recycle=3";
		$query .=" UNION SELECT id,title,class1,class2,class3,updatetime,recycle FROM $met_news $serch_sql and recycle=2";
		$query .=" UNION SELECT id,title,class1,class2,class3,updatetime,recycle FROM $met_download $serch_sql and recycle=4";
		$query .=" UNION SELECT id,title,class1,class2,class3,updatetime,recycle FROM $met_img $serch_sql and recycle=5";
		$query .=" ORDER BY updatetime DESC LIMIT $from_record, $list_num";
	}
	else{
		$table=moduledb($module);
		$total_count = $db->counter($table, "$serch_sql and recycle=$module", "*");
		require_once 'include/pager.class.php';
		$page = (int)$page;
		if($page_input){$page=$page_input;}
		$list_num = 16;
		$rowset = new Pager($total_count,$list_num,$page);
		$from_record = $rowset->_offset();
		$query = "SELECT id,title,class1,class2,class3,updatetime,recycle FROM $table $serch_sql and recycle=$module ORDER BY updatetime DESC LIMIT $from_record, $list_num";
	}
    $result = $db->query($query);
	while($list = $db->fetch_array($result)) {
		$recycle_list[]=$list;
    }
	$query="select * from $met_column where module>=2 and module<=5 and lang='$lang'";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {
		$c_list[$list['id']]=$list;
    }
$page_list = $rowset->link("index.php?anyid={$anyid}&title_search=$title_search&module=$module&search=$search&lang=$lang&page=");
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('content/recycle/recycle');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>