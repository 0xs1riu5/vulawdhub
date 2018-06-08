<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
/*时间变量*/
$dtimet=statime("Y/m/d 00:00:00");	
$dtimew=statime("Y/m/d 23:59:59");	
$ztimea=statime("Y-m-d 00:00:00","-1 day");
$ztimeb=statime("Y-m-d 23:59:59","-1 day");
$xtime=statime("Y-m-d 00:00:00","-6 day");	
$timeq30=statime("Y-m-d 00:00:00","-29 day");
$timed1=strtotime(date('Y-m-d 00:00:00', mktime(0,0,0,date('n'),1,date('Y'))));
$st=isset($st)?$st:$dtimet;
$et=isset($et)?$et:$dtimew;
if($stt)$st=strtotime($stt);
if($ett)$et=strtotime($ett);
if($st>$et){
	$st=strtotime($ett);
	$et=strtotime($stt);
}
/*初始变量*/
$cs=isset($cs)?$cs:0;
$dancs[$cs]='class="dday round"';
$tmst=date("Y-m-d",$st);
$tmet=date("Y-m-d",$et);
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";

$total_count = $db->counter($met_visit_day, " acctime >='{$st}' and acctime<='{$et}'", "*");
$list_num = 15;
require_once $depth.'../include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query="select * from {$met_visit_day} WHERE acctime >='{$st}' and acctime<='{$et}' order by acctime desc LIMIT {$from_record},{$list_num}";
$result= $db->query($query);
while($list = $db->fetch_array($result)){
	$valmet=acceptun($list['columnid'],$list['listid'],$list['lang']);
	$list['title']=$valmet['title']?$valmet['title']:$list['visitpage'];
	if($list['dizhi']==''){
		$cop=explode('-',ipdizhi($list['ip']));
		$list['dizhi']=$cop[0];
		$list['network']=$cop[1];
		$dayquery ="update {$met_visit_day} SET dizhi = '{$cop[0]}',network = '{$cop[1]}' where id = '{$list['id']}'";
		$db->query($dayquery);
	}
	$visit_day[]=$list;
}
function browseryext($browser){
global $lang_statbrowser1,$lang_statbrowser2,$lang_statbrowser3,$lang_statbrowser4,$lang_statbrowser5,$lang_statbrowser6;
	switch($browser){
		case 'se360':$metinfo=$lang_statbrowser1;break;
		case 'se':$metinfo=$lang_statbrowser7;break;
		case 'maxthon':$metinfo=$lang_statbrowser2;break;
		case 'qq':$metinfo=$lang_statbrowser3;break;
		case 'tt':$metinfo=$lang_statbrowser4;break;
		case 'theworld':$metinfo=$lang_statbrowser5;break;
		case 'chrome':$metinfo=$lang_statbrowser6;break;
		default:$metinfo=$browser;break;
	}
	return $metinfo;
}
function ipdizhi($ip){
	global $met_file;
	$met_file='/ipku.php';
	$post=array('ip'=>$ip);
	$metinfo = curl_post($post,30);
	return $metinfo;
}
$page_list = $rowset->link("details.php?lang={$lang}&anyid={$anyid}&st={$st}&et={$et}&cs={$cs}&page=");
include template('app/stat/details');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>