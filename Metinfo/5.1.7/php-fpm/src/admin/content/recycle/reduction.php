<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="editor"){
$allidlist=explode(',',$allid);
foreach($allidlist as $key=>$val){
$rid=explode('|',$val);
switch($rid[1]){
	case 2:
		$recycle=$met_news;
		$defilename='shownews';
	break;
	case 3:
		$recycle=$met_product;
		$defilename='showproduct';
	break;
	case 4:
		$recycle=$met_download;
		$defilename='showdownload';
	break;
	case 5:
		$recycle=$met_img;
		$defilename='showimg';
	break;
}
	$query = "update $recycle set recycle='0',updatetime='".date('Y-m-d H:i:s')."' where id='$rid[0]'";
	$db->query($query);
	if($met_webhtm!=0){
		$admin_list = $db->get_one("SELECT * FROM $recycle WHERE id='$rid[0]'");
		$htmjs.= contenthtm($admin_list['class1'],$rid[0],$defilename,$admin_list['filename'],0,'',$admin_list['addtime']).'$|$';
		$reclass[$admin_list['class1']][$admin_list['class2']][$admin_list['class3']]=1;
	}
}
if($met_webhtm!=0){
	foreach($reclass as $key1=>$val1){
		$htmjs.= classhtm($key1,0,0,0,1,0).'$|$';
		foreach($val1 as $key2=>$val2){
			$htmjs.= classhtm($key1,$key2,0,0,2,0).'$|$';
			foreach($val2 as $key3=>$val3){
				$htmjs.= classhtm($key1,$key2,$key3,0,3,0).'$|$';
			}
		}
	}
}
$htmjs.= indexhtm();
if($met_webhtm==2){
   metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth,$htmjs);
}else{
   metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth);
}
}
else if($action=="delall"){
	if($met_webhtm!=0&&$met_htmway==0){
		$query = "SELECT id,class1,class2,class3,filename,recycle FROM $met_product where lang='$lang' and recycle=3";
		$query .=" UNION SELECT id,class1,class2,class3,filename,recycle FROM $met_news where lang='$lang' and recycle=2";
		$query .=" UNION SELECT id,class1,class2,class3,filename,recycle FROM $met_download where lang='$lang' and recycle=4";
		$query .=" UNION SELECT id,class1,class2,class3,filename,recycle FROM $met_img where lang='$lang' and recycle=5";
		$result = $db->query($query);
		 while($list = $db->fetch_array($result)) {
			$recycle_list[]=$list;
		}
	}
	$query = "update $met_news set recycle='0',updatetime='".date('Y-m-d H:i:s')."' where lang='$lang' and recycle='2'";
	$db->query($query);
	$query = "update $met_product set recycle='0',updatetime='".date('Y-m-d H:i:s')."' where lang='$lang' and recycle='3'";
	$db->query($query);
	$query = "update $met_download set recycle='0',updatetime='".date('Y-m-d H:i:s')."' where lang='$lang' and recycle='4'";
	$db->query($query);
	$query = "update $met_img set recycle='0',updatetime='".date('Y-m-d H:i:s')."' where lang='$lang' and recycle='5'";
	$db->query($query);
	foreach($recycle_list as $key=>$val){
		switch($val['recycle']){
				case 2:
					$recycle=$met_news;
					$defilename='shownews';
				break;
				case 3:
					$recycle=$met_product;
					$defilename='showproduct';
				break;
				case 4:
					$recycle=$met_download;
					$defilename='showdownload';
				break;
				case 5:
					$recycle=$met_img;
					$defilename='showimg';
				break;
			}
			$htmjs .= contenthtm($val['class1'],$val['id'],$defilename,$val['filename'],0,'',$val['addtime']).'$|$';
			$reclass[$val['class1']][$val['class2']][$val['class3']]=1;
	}
	if($met_webhtm==2&&$met_htmway==0){
		foreach($reclass as $key1=>$val1){
			$htmjs.= classhtm($key1,0,0,0,1,0).'$|$';
			foreach($val1 as $key2=>$val2){
				$htmjs.= classhtm($key1,$key2,0,0,2,0).'$|$';
				foreach($val2 as $key3=>$val3){
					$htmjs.= classhtm($key1,$key2,$key3,0,3,0).'$|$';
				}
			}
		}
	}
	$htmjs.= indexhtm();
	metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth,$htmjs);
}
else{
$rid=explode('|',$id);
switch($rid[1]){
	case 2:
		$recycle=$met_news;
		$defilename='shownews';
	break;
	case 3:
		$recycle=$met_product;
		$defilename='showproduct';
	break;
	case 4:
		$recycle=$met_download;
		$defilename='showdownload';
	break;
	case 5:
		$recycle=$met_img;
		$defilename='showimg';
	break;
}
$admin_list = $db->get_one("SELECT * FROM $recycle WHERE id='$rid[0]'");
if(!$admin_list)metsave('-1',$lang_dataerror,$depth);
$query = "update $recycle set recycle='0',updatetime='".date('Y-m-d H:i:s')."' where id='$rid[0]'";
$db->query($query);
$htmjs = contenthtm($admin_list['class1'],$rid[0],$defilename,$admin_list['filename'],0,'',$admin_list['addtime']).'$|$';
$htmjs.= classhtm($admin_list['class1'],$admin_list['class2'],$admin_list['class3']).'$|$';
$htmjs.= indexhtm();
metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
