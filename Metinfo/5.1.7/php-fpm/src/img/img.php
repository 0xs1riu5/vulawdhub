<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
$mdname = 'img';
$showname = 'showimg';
$dbname = $met_img;
$dbname_list = $met_img_list;
$mdmendy = 1;
$imgproduct = 'img';
require_once '../include/global/listmod.php';
$img_listnow = $modlistnow;
$img_list_new  = $md_list_new;
$img_class_new = $md_class_new;
$img_list_com  = $md_list_com;
$img_class_com = $md_class_com;
$img_class     = $md_class;
$img_list      = $md_list;
require_once '../public/php/imghtml.inc.php';
include template('img');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>