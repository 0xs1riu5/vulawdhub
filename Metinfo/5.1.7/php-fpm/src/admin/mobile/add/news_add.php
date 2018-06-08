<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once $depth.'global.func.php';
$navoks[3]='ui-btn-active';
	$class2x[$class2]="selected='selected'";
	$class3x[$class3]="selected='selected'";
	$news_list[class2]=$class2;
	$news_list[issue]=$metinfo_admin_name;
	$news_list[hits]=0;
	$news_list[no_order]=0;
	$news_list[addtime]=$m_now_date;
	$news_list[access]=0;
	$lang_editinfo=$lang_addinfo;
	$lev=$met_class[$class1][access];
	$list_access['access']=$news_list['access'];
	require '../../content/access.php';
$listjs=listjs();
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/add/news_add');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>