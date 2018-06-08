<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="do"){
	$class=$class3?$class3:($class2?$class2:$class1);
	$classimg=$db->get_one("select * from $met_column where id='$class'");
	$class1title=$db->get_one("select * from $met_column where id='$class1'");
	$classtitle=$class1title['name'];
	if($class2){$class2title=$db->get_one("select * from $met_column where id='$class2'");$classtitle.="->$class2title[name]";}
	if($class3){$class3title=$db->get_one("select * from $met_column where id='$class3'");$classtitle.="->$class3title[name]";}
	$table=moduledb($classimg['module']);
	$sql_query="where lang='$lang' and class1='$class1'";
	$sql_query.=$class2?" and class2='$class2'":"";
	$sql_query.=$class2?" and class3='$class3'":"";
	if($fileup==1){
		$list_num=5;
		$from_record=($page-1)*$list_num;
		$page=$page==0?1:$page;
		if(($page*$list_num)>$numcsv){$list_num=$numcsv-($page-1)*$list_num;}
		$img_list=$db->get_all("select * from $table $sql_query and id>='$fid' and id<='$lid' ORDER BY id ASC LIMIT $from_record, $list_num");
		$page_type=1;
		$numtotal=$numcsv;
	}else{
		$sql_query.=" and (recycle='0' or recycle='-1')";
		$total_count = $db->counter($table,$sql_query,"*");
		require_once 'include/pager.class.php';
		$page = (int)$page;
		if($page_input){$page=$page_input;}
		$list_num = 5;
		$rowset = new Pager($total_count,$list_num,$page);
		$from_record = $rowset->_offset();
		$img_list=$db->get_all("select * from $table $sql_query and (recycle='0' or recycle='-1') order by top_ok desc,no_order desc,updatetime desc,id desc LIMIT $from_record, $list_num");
		$page=$page==0?1:$page;
		if(($page*$list_num)>$total_count){$list_num=$total_count-($page-1)*$list_num;}
		$page_list = $rowset->link("fileup.php?anyid={$anyid}&class1=$class1&class2=$class2&class3=$class3&action=do&lang=$lang&page=");
		$numtotal=$total_count;
		$numcsv=$total_count;
		if(!$img_list)$listnots=$lang_listno;
	}
	$listid='';
	foreach($img_list as $key=>$val){
		$listid.=$val['id'].',';
	}
	$from_record_1=$from_record+1;
	$from_record_2=$from_record+$list_num;
}
require_once 'index.php';
$listclass[2]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/batch/fileup');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>