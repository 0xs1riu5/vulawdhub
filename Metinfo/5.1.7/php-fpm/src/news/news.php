<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
$mdname = 'news';
$showname = 'shownews';
$dbname = $met_news;
$dbname_list = $met_news_list;
require_once '../include/global/listmod.php';
$news_listnow = $modlistnow;
$news_list_new  = $md_list_new;
$news_class_new = $md_class_new;
$news_list_com  = $md_list_com;
$news_class_com = $md_class_com;
$news_class     = $md_class;
$news_list      = $md_list;
require_once '../public/php/newshtml.inc.php';
include template('news');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>