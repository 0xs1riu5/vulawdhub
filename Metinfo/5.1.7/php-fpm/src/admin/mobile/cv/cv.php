<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$query = "SELECT * FROM $met_job where lang='$lang'";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$job_list[$list[id]]=$list[position];
}
$query = "SELECT id,jobid,addtime,customerid,readok FROM $met_cv where lang='$lang' and readok=0 order by addtime desc";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$list[position]=(isset($job_list[$list[jobid]]))? $job_list[$list[jobid]]:"<font color=red>$lang_cvTip4</font>";
	$list[readok] = $lang_no;
	$list[url] = 'cv_editor.php?lang='.$lang.'&id='.$list[id];
	$cv_list[]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/cv/cv');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>