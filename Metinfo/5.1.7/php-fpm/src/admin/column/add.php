<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=='add'){
$img_url="../templates/".$met_skin."/images";
$module=0;
if($id){
$column_list = $db->get_one("SELECT * FROM $met_column WHERE id='$id'");
$module = $column_list['module'];
$foldername = $column_list['foldername'];
}else{
$id=1;
}									
if($type==1){
	$typey = '';
	$bigs = 0;
	$imgs = "<div style='width:22px; height:10px; overflow:hidden; float:left;'></div>";
}else{
	$imgcss = 'padding-left:10px;';
	$imycss = "columnz_$id";
	$typey = $type;
	$bigs = $id;
}
$widthx=$type==3?'style="width:121px;"':($type==2?'style="width:141px;"':'style="width:144px;"');
	if($type==2)$imgs = "<img src='{$img_url}/bg_column.gif' style='position:relative; top:6px; margin-right:4px;' />";
	if($type==3)$imgs = "<img src='{$img_url}/bg_column1.gif' style='position:relative; top:6px; margin-right:3px;' />";
		$newlist = "<tr class='mouse click $imycss newlist column_$type'>";
		$newlist.= "<td class='list-text$typey'><input name='id' type='checkbox' checked='checked' id='id' value='new_$lp' /></td>";
		$newlist.= "<td class='list-text$typey'><input type='text' class='text no_order' value='0' name='no_order_new_$lp' /><input type='hidden' value='$type' name='classtype_new_$lp' /><input type='hidden' value='$bigs' name='bigclass_new_$lp' /><input type='hidden' value='0' name='access_new_$lp' /></td>";
		$newlist.= "<td class='list-text$typey' style='text-align:left; $imgcss'>$imgs<input {$widthx} type='text' class='text namenonull' value='' name='name_new_$lp' />";
		$newlist.= "</td>";
		$newlist.= "<td class='list-text$typey'>";
		$newlist.= "<select name='nav_new_$lp'>";
for($u=0;$u<4;$u++){
		$navtypes = navdisplay($u);
		$newlist.= "<option value='$u'>$navtypes</option>";
}
		$newlist.= "</select>";
		$newlist.= "</td>";
		$newlist.= "<td class='list-text$typey'>";
		$newlist.= "<select name='module_new_$lp' onchange='newmodule($(this),$module,$type)'>";
if($type==2)$newlist.= "<option value='$module'>".module($module)."</option>";			
for($i=1;$i<=14;$i++){
$j=($i<13)?$i:($i+87);
$langmod="lang_mod".$j;
$langmod1=$$langmod;
$pk=1;
if($type==2 && $i==$module)$pk=0;
if($type==3 && $i!=$module)$pk=0;
if($pk){
if(count($met_module[$j])==0 or ($j<=5 || $j==8)){
		$newlist.= "<option value='$j'>{$langmod1}</option>";
}}}
		$newlist.= "<option value='999'>{$lang_modout}</option>";
		$newlist.= "</select>";
		$newlist.= "</td>";
		$newlist.= "<td class='list-text$typey'>";
if($type==1){
$newlist.= "<input type='text' class='text max foldernonull' value='' name='foldername_new_$lp' />";
}else{
$newlist.= "<span>$foldername</span><input type='text' value='' class='text max none foldernonull' name='foldername_new_$lp' />";
}
		$newlist.= "<input type='text' class='text none max nonull out_url_new' style='font-weight:normal;' value='{$lang_columnOutLink}' name='out_url_new_$lp' /><font style='font-size:12px; font-weight:normal;'></font></td>";
		$newlist.= "<td class='list-text$typey'>";
		$newlist.= "<input type='text' class='text no_order' value='0' name='index_num_new_$lp' /></td>";
		$newlist.= "<td class='list-text$typey'><a href='javascript:;' class='hovertips' onclick='delettr($(this));'>{$lang_js49}</a>";
		$newlist.= "</td>";
		$newlist.="</tr>";
		echo $newlist;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>