<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
if($class1){
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$class1){
			$tingname    =$val['name'].'_'.$val['columnid'];
			$$val['name']=$$tingname;
		}
	}
}
$query = "SELECT * FROM $met_feedback where lang='$lang' and readok='0' and class1='$class1' order by id desc";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$list[readok] = $lang_no;
	$list[url] = 'editor.php?lang='.$lang.'&id='.$list[id].'&class1='.$class1;
	$feedback_list[]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/feedback/feedback');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>