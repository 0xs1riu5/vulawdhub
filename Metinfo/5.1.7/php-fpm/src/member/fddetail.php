<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
$feedback_list=$db->get_one("select * from $met_feedback where id='$id'");
if(!$feedback_list){
okinfo('index.php?lang='.$lang,$lang_dataerror);
}
$feedback_list['customerid']=$feedback_list['customerid']==0?$lang_feedbackAccess0:$list['customerid'];
$query = "SELECT * FROM $met_parameter where module=8 and lang='$lang' and class1='$feedback_list[class1]' order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
$info_list=$db->get_one("select * from $met_flist where listid='$id' and paraid='$list[id]' and lang='$lang'");
$list[content]=$info_list[info];
if($list[type]==5)$list[content]="<a href='../upload/file/".$info_list[info]."' target='_blank'>".$info_list[info]."</a>";
$feedback_para[]=$list;
}
$mfname='feedback_detail';
include template('member');
footermember();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>