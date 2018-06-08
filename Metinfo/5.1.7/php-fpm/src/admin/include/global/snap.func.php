<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
function statime($ymd,$day=''){
	$day=$day==''?time():strtotime($day);
	$time=strtotime(date($ymd,$day));
	return $time;
}

function enginetype($met){
	global $lang_enginetype1,$lang_enginetype2,$lang_enginetype3,$lang_enginetype4,$lang_enginetype5,$lang_enginetype6,$lang_enginetype7,$lang_enginetype8;
	switch($met){
		case 's0':$metinfo=$lang_enginetype1;break;
		case 's1':$metinfo=$lang_enginetype2;break;
		case 's2':$metinfo=$lang_enginetype3;break;
		case 's3':$metinfo=$lang_enginetype4;break;
		case 's4':$metinfo=$lang_enginetype5;break;
		case 's5':$metinfo=$lang_enginetype6;break;
		case 's6':$metinfo=$lang_enginetype7;break;
		case 's7':$metinfo=$lang_enginetype8;break;
	}
	return $metinfo;
}
/*二维数组排序*/
function arraysort2($arr,$field,$sort){
	foreach($arr as $key=>$val){
		$list[$key]=$val[$field];
	}
	array_multisort($list,$sort);
	foreach($list as $key=>$val){
		foreach($arr as $key2=>$val2){
			if($key2==$key){
				$metinfo[$key]=$val2;
			}
		}
	}
	return $metinfo;
}
function acceptun($columnid,$listid,$langid){
	global $db,$met_column,$met_visit_detail,$met_langok;
	if($columnid){
		if(!$met_langok[$langid]){
			$query = "delete from {$met_visit_detail} where lang='{$langid}' and type='2'";
			$db->query($query);
			return false;
		}else{
			if($columnid=='10001'){
				$metinfo['title']=$lang_htmHome;
			}else{
				$column=$db->get_one("SELECT * FROM {$met_column} where id='{$columnid}'"); 
				if(!$column){
					$query = "delete from {$met_visit_detail} where columnid='{$columnid}' and type='2'";
					$db->query($query);
					return false;
				}
				$metinfo['title']=$column['name'];
				if($listid && $column['module']!=1 && $column['module']!=8){
					$metdbname=moduledb($column['module']);
					$list=$db->get_one("SELECT * FROM {$metdbname} where id='{$listid}'");			
					if(!$list){
						$query = "delete from {$met_visit_detail} where columnid='{$columnid}' and listid='{$listid}' and type='2'";
						$db->query($query);
						return false;
					}
					$metinfo['title']=$column['module']==6?$list['position']:$list['title'];
				}
			}
		}
	}
	return $metinfo;
}
function delet_estat_cr($type,$value){
	global $db,$met_visit_summary,$met_visit_detail,$met_visit_day,$met_adminfile;
	$time=date('Y-m');
	$string='';
	switch($value){
		case 1:
			$st=statime("Y-m-d");
		break;
		case 2:
			$st=statime("Y-m-d","-6 day");
		break;
		case 3:
			$st=statime("Y-m-d","last month");
		break;
		case 4:
			$st=statime("Y-m-d","-1 year");
		break;
	}
	if($st){
		switch($type){
			case 1:
				$query = "select * from {$met_visit_summary} where stattime<'{$st}'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_summary} VALUES('','$val[pv]','$val[ip]','$val[alone]','$val[parttime]','$val[stattime]');\n";
				}
				$query = "delete from {$met_visit_summary} where stattime<'{$st}'";
				$db->query($query);
			break;
			case 2:
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='1'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_detail} VALUES('','$val[name]','$val[pv]','$val[ip]','$val[alone]','$val[remark]','$val[type]','$val[columnid]','$val[listid]','$val[stattime]','$val[lang]');\n";
				}
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='1'";
				$db->query($query);
			break;
			case 3:
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='2'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_detail} VALUES('','$val[name]','$val[pv]','$val[ip]','$val[alone]','$val[remark]','$val[type]','$val[columnid]','$val[listid]','$val[stattime]','$val[lang]');\n";
				}
				$query = "delete from {$met_visit_detail} where stattime<'{$st}' and type='2'";
				$db->query($query);
			break;
			case 4:
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='3'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_detail} VALUES('','$val[name]','$val[pv]','$val[ip]','$val[alone]','$val[remark]','$val[type]','$val[columnid]','$val[listid]','$val[stattime]','$val[lang]');\n";
				}
				$query = "delete from {$met_visit_detail} where stattime<'{$st}' and type='3'";
				$db->query($query);
			break;
			case 5:
				$query = "select * from {$met_visit_day} where acctime<'{$st}'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_day} VALUES('','$val[ip]','$val[acctime]','$val[visitpage]','$val[antepage]','$val[columnid]','$val[listid]','$val[browser]','$val[dizhi]','$val[network]','$val[lang]');\n";
				}
				$query = "delete from {$met_visit_day} where acctime<'{$st}'";
				$db->query($query);
			break;
		}
		if(!file_exists("../../databack/stat/"))mkdir("../../databack/stat/",0777);
		if(!file_exists("../../databack/"))mkdir("../../databack/",0777);
		if($string)file_put_contents("../../databack/stat/$time.sql",$string,FILE_APPEND);
	}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>