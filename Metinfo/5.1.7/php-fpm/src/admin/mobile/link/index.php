<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$query = "SELECT * FROM $met_link where lang='$lang' and show_ok=0 order by orderno desc";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$list[show_ok]=$lang_no;
	$list[com_ok]=($list[com_ok])?$lang_yes:$lang_no;
	$list[link_type]=($list[link_type])?$lang_linkType5:$lang_linkType4;
	$list[url] = 'content.php?action=editor&lang='.$lang.'&id='.$list[id];
	$link_list[]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/link/link');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>