<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
$mdname = 'download';
$showname = 'showdownload';
$dbname = $met_download;
$listnum = $met_download_list;
$imgproduct = 'download';
require_once '../include/global/showmod.php';
$download = $news;
$download_list_new  = $md_list_new;
$download_class_new = $md_class_new;
$download_list_com  = $md_list_com;
$download_class_com = $md_class_com;
$download_class     = $md_class;
$download_list      = $md_list;
require_once '../public/php/downloadhtml.inc.php';
include template('showdownload');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>