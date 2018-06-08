<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$backurl="../mobile/feedback/index.php?lang={$lang}&class1={$class1}";
if($action=="editor"){
	$query = "update $met_feedback SET
						  useinfo            = '$useinfo',
						  class1             = '$class1',
						  readok             = '1'
						  where id='$id'";
	$db->query($query);
	metsave($backurl,'',$depth);
}else{
	if(!$class1){
		$feedback_list=$db->get_one("select * from $met_feedback where id='$id'");
		$class1=$feedback_list['class1'];
	}
	$query = "update $met_feedback SET
						  class1             = '$class1',
						  readok             = '1'
						  where id='$id'";
	$db->query($query);
	$feedback_list=$db->get_one("select * from $met_feedback where id='$id' and class1 = '$class1'");
	if(!$feedback_list)metsave('-1',$lang_dataerror,$depth);
	$feedback_list['customerid']=metidtype($feedback_list['customerid']);
	$query = "SELECT * FROM {$met_parameter} where module=8 and lang='{$lang}' and class1='$class1' order by no_order";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	$info_list=$db->get_one("select * from $met_flist where listid='$id' and paraid='$list[id]' and lang='$lang'");
	$list[content]=$info_list[info];
	if($list[type]==5)$list[content]="<a href='../../upload/file/".$info_list[info]."' target='_blank'>".$info_list[info]."</a>";
	$feedback_para[]=$list;
	}
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('mobile/feedback/feedback_editor');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>