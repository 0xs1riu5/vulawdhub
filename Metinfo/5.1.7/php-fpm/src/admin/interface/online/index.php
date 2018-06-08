<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$query = "SELECT * FROM $met_online where lang='$lang' order by no_order";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	if(strlen($list[qq])>30){
		$list[qq1a]=explode('http://wpa.qq.com/pa',$list[qq]);
		$list[qq2a]=explode(':',$list[qq1a][1]);
		$list[qq]=$list[qq2a][1];
	}
	$online_list[]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('interface/online/online');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>