<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$img_url=$depth."../templates/".$met_skin."/images";
if($action=="add"){
	$newlist = "<tr class='mouse click newlist'>\n";
	$newlist.= "<td class='list-text'><input name='id' type='checkbox' id='id' value='new$lp' checked='checked' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='no_order_new$lp' class='text no_order' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='name_new$lp' class='text max' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='qq_new$lp' class='text max' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='msn_new$lp' class='text max' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='taobao_new$lp' class='text max' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='alibaba_new$lp' class='text max' /></td>\n";
	$newlist.= "<td class='list-text'><input type='text' name='skype_new$lp' class='text max' /></td>\n";
	$newlist.= "<td class='list-text'><a href='javascript:;' style='padding:0px 5px;' onclick='delettr($(this));'><img src='$img_url/12.png' /><span class='none'>{$lang_js49}</span></a></td>\n";
	$newlist.= "</tr>";
	echo $newlist;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>