<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$infofile="../../templates/".$met_skin_user."/info.html";
if(!file_exists($infofile)){
	if($met_skin_user=='default')$met_skin_user='metv5';
	header("location:http://www.metinfo.cn/course/peizhi/{$met_skin_user}-cn.html");exit;
}
$content = file_get_contents($infofile);
$content = str_replace('{$met_skin_user}',$met_skin_user,$content);
$content = str_replace('$met_skin_user',$met_skin_user,$content);
echo $content.'<br/>';
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>