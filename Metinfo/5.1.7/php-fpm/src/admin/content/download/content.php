<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once $depth.'global.func.php';
if($action=="editor"){
	$download_list=$db->get_one("select * from $met_download where id='$id'");
	$download_list['title']=str_replace('"', '&#34;', str_replace("'", '&#39;',$download_list['title']));
	$download_list['ctitle']=str_replace('"', '&#34;', str_replace("'", '&#39;',$download_list['ctitle']));
	if($met_member_use){
		$lev=$download_list['class3']?$met_class[$download_list['class3']][access]:($download_list['class2']?$met_class[$download_list['class2']][access]:$met_class[$download_list['class1']][access]);
	}
	if(!$download_list)metsave('-1',$lang_dataerror,$depth);
	$query = "select * from $met_plist where module='4' and listid='$id'";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
		$nowpara="para".$list[paraid];
		$download_list[$nowpara]=$list[info];
		$nowparaname="";
		if($list[imgname]<>"")$nowparaname=$nowpara."name";$download_list[$nowparaname]=$list[imgname];
	}
	$class1=$download_list[class1];
	if($download_list[new_ok]==1)$new_ok="checked";
	if($download_list[com_ok]==1)$com_ok="checked";
	if($download_list[top_ok]==1)$top_ok="checked";
	if($download_list[wap_ok]==1)$wap_ok="checked";	
	$class1x[$download_list[class1]]='selected="selected"';
	$class2x[$download_list[class2]]="selected='selected'";
	$class3x[$download_list[class3]]="selected='selected'";	
	$class1=$download_list[class1];
	$class2=$download_list[class2];
	$class3=$download_list[class3];
}else{
	$class1x[$class1]="selected='selected'";
	$class2x[$class2]="selected";
	$class3x[$class3]="selected";
	$download_list[class2]=$class2;
	$download_list[issue]=$metinfo_admin_name;
	$download_list[hits]=0;
	$download_list[no_order]=0;
	$download_list[addtime]=$m_now_date;
	$download_list[access]="0";
	$download_list[downloadaccess]="0";
	$lang_editinfo=$lang_addinfo;
	$lev=$class3?$met_class[$class1][access]:($class2?$met_class[$class2][access]:$met_class[$class1][access]);
}
$list_access['access']=$download_list['access'];
require '../access.php';
$lev_temp=$level;
$list_access['access']=$download_list['downloadaccess'];
require '../access.php';
$leve2=$level;
$level=$lev_temp;
$module=4;
$listjs=listjs($module);
if($met_class[$class1][releclass]){
	$met_class22=$met_class3;
	$met_class3=array();
}
if(!count($met_class22[$class1])||!$class1)$class2style="style='display:none'";
if(!count($met_class3[$class2])||!$class2)$class3style="style='display:none'";
$para_list=para_list_with($download_list);
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('content/download/content');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>