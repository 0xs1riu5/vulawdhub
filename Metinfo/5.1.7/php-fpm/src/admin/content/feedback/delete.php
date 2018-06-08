<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/feedback/index.php?anyid={$anyid}&lang=$lang&class1=$class1&customerid={$customerid}";
$query = "select * from $met_parameter where lang='$lang' and module='8' and type='5' order by no_order";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
$para_list[]=$list;
}
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		/*delete images*/
		if($met_deleteimg){
			foreach($para_list as $key=>$val1){
				$imagelist=$db->get_one("select * from $met_flist where lang='$lang' and  paraid='$val1[id]' and listid='$val'");
				file_unlink($depth."../".$imagelist[info]);
			}
		}
		$query = "delete from $met_flist where listid='$val' and module='8'";
		$db->query($query);
		$query = "delete from $met_feedback where id='$val'";
		$db->query($query);
	}
	metsave($backurl,'',$depth);
}elseif($action=="delall"||$action=="delno"||$action=="delyse"){
	$acsql="";
	$acsql=$action=="delno"?"and readok=0":$acsql;
	$acsql=$action=="delyse"?"and readok=1":$acsql;
	$query = "select * from $met_feedback where lang='$lang' and class1='$class1' $acsql";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
		$allidlist[]=$list;
	}
	foreach($allidlist as $key=>$val){
		/*delete images*/
		if($met_deleteimg){
			foreach($para_list as $key=>$val1){
				$imagelist=$db->get_one("select * from $met_flist where lang='$lang' and  paraid='$val1[id]' and listid='$val[id]'");
				file_unlink($depth."../".$imagelist[info]);
			}
		}
		$query = "delete from $met_flist where listid='$val[id]' and module='8'";
		$db->query($query);
	}
	$query = "delete from $met_feedback where lang='$lang' and class1='$class1' $acsql";
	$db->query($query);
	metsave($backurl,'',$depth);
}else{
	$admin_list = $db->get_one("SELECT * FROM $met_feedback WHERE id='$id'");
	if(!$admin_list)metsave('-1',$lang_dataerror,$depth);
	/*delete images*/
	if($met_deleteimg){
		foreach($para_list as $key=>$val){
			$imagelist=$db->get_one("select * from $met_flist where lang='$lang' and  paraid='$val[id]' and listid='$id'");
			file_unlink($depth."../".$imagelist[info]);
		}
	}
	$query = "delete from {$met_flist} where listid='$id' and module='8'";
	$db->query($query);
	$query = "delete from {$met_feedback} where id='$id'";
	$db->query($query);
	metsave($backurl,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.	
?>
