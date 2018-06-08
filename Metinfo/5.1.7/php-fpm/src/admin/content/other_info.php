<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$infofile="../../templates/".$met_skin_user."/otherinfo.inc.php";
if(file_exists($infofile)){
	require_once($infofile);
	for($i=1;$i<=10;$i++){
		$infonameinfo="infoname".$i;
		$infonameinfo1=$$infonameinfo;
		if($infonameinfo1[0]<>'该字段没有启用' or $infonameinfo1[2])$infoname[$i]=array($infonameinfo1[0],$infonameinfo1[1],'1');
	}
	if($imgurlname1[0]<>'该字段没有启用' or $imgurlname1[2])$imgurlname[1]=array($imgurlname1[0],$imgurlname1[1],'1');
	if($imgurlname2[0]<>'该字段没有启用' or $imgurlname2[2])$imgurlname[2]=array($imgurlname2[0],$imgurlname2[1],'1');
}
if($action=="modify"){
	$query = "update $met_config SET value= '$met_index_content' where name='met_index_content' and lang='$lang'";
	$db->query($query);
	$methtm=indexhtm();
	$query = "update $met_otherinfo SET ";
	if($info1<>"" or $infoname[1][2])$query.= "info1       = '$info1',";
	if($info2<>"" or $infoname[2][2])$query.= "info2       = '$info2',";
	if($info3<>"" or $infoname[3][2])$query.= "info3       = '$info3',";
	if($info4<>"" or $infoname[4][2])$query.= "info4       = '$info4',";
	if($info5<>"" or $infoname[5][2])$query.= "info5       = '$info5',";
	if($info6<>"" or $infoname[6][2])$query.= "info6       = '$info6',";
	if($info7<>"" or $infoname[7][2])$query.= "info7       = '$info7',";
	if($info8<>"" or $infoname[8][2])$query.= "info8       = '$info8',";
	if($info9<>"" or $infoname[9][2])$query.= "info9       = '$info9',";
	if($info10<>"" or $infoname[10][2])$query.= "info10      = '$info10',";
	if($imgurl1<>"" or $imgurlname[1][2])$query.= "imgurl1     = '$imgurl1',";
	if($imgurl2<>"" or $imgurlname[2][2])$query.= "imgurl2     = '$imgurl2',";
				$query.=" lang='$lang' where id=$id";
	if($id=="")	
	$query = "INSERT INTO $met_otherinfo SET 
						  info1       = '$info1',
						  info2       = '$info2',
						  info3       = '$info3',
						  info4       = '$info4',
						  info5       = '$info5',
						  info6       = '$info6',
						  info7       = '$info7',
						  info8       = '$info8',
						  info9       = '$info9',
						  info10      = '$info10',
						  imgurl1     = '$imgurl1',
						  imgurl2     = '$imgurl2',
						  lang='$lang'";				  
	$db->query($query);
	require_once '../../include/cache.func.php';
	if(cache_otherinfo(0)){
		$relang=$lang_jsok;
		$relang.=$met_webhtm==0?'':$lang_otherinfocache1;
		metsave('../content/other_info.php?lang='.$lang.'&anyid='.$anyid,$relang);
	}else{
		metsave('../content/other_info.php?lang='.$lang.'&anyid='.$anyid,$lang_otherinfocache2);
	}
}else{
	$otherinfo = $db->get_one("SELECT * FROM $met_otherinfo where lang='$lang'");
}
if(count($infoname)==0 and count($imgurlname)==0){
	$lang_setotherItemSet=$lang_setotherTip2;
	$lang_setotherTip1="";
}
$rooturl="..";
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('content/otherinfo');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>