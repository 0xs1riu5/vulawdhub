<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
if($type=='lang'){
	$metinfo.= '<ol>';
foreach($met_langok as $key=>$val){
$cls='';
if($langadminok=="metinfo" or (strstr($langadminok,"-".$val[mark]."-"))){
    $metinfo.='<li title="'.$val[mark].'">'.$val[name].'</li>';
}}
	$metinfo.= '</ol>';
	echo $metinfo;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>