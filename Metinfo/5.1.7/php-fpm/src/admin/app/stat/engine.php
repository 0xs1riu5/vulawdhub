<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
/*时间变量*/
$dtime=statime("Y-m-d");	
$ztime=statime("Y-m-d","-1 day");
$xtime=statime("Y-m-d","-6 day");
$timeq30=statime("Y-m-d","-29 day");
$timed1=strtotime(date('Y-m-d', mktime(0,0,0,date('n'),1,date('Y'))));
$st=isset($st)?$st:$dtime;
$et=isset($et)?$et:$dtime;
if($stt)$st=strtotime($stt);
if($ett)$et=strtotime($ett);
if($st>$et){
	$st=strtotime($ett);
	$et=strtotime($stt);
}
if($st && $st>$dtime)$st=$dtime;
if($et && $et>$dtime)$et=$dtime;
/*初始变量*/
$tmst=date("Y-m-d",$st);
$tmet=date("Y-m-d",$et);
$cs=isset($cs)?$cs:0;
$dancs[$cs]='class="dday round"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$labtype=isset($labtype)?($labtype==''?1:$labtype):1;
$labtypeclass[$labtype]='now';
$field=$field?$field:'pv';
$zvist[$field]='<b style="color:#f00;">↓</b>';
$query="select * from {$met_visit_detail} WHERE stattime>='{$st}' and stattime<='{$et}' and type='1' and name!='' order by pv desc";
$result= $db->query($query);
while($list = $db->fetch_array($result)){
	switch($labtype){
		case 1:
			if($visit[$list['name']]){
				$list['pv']=$visit[$list['name']]['pv']+$list['pv'];
				$list['ip']=$visit[$list['name']]['ip']+$list['ip'];
				$list['alone']=$visit[$list['name']]['alone']+$list['alone'];
			}
			$list['per']=sprintf("%.2f",($list['pv']/$list['alone']));
			$visit[$list['name']]=$list;
		break;
		case 2:
			$shp=explode('|',$list['remark']);
			for($i=0;$i<count($shp);$i++){
				if($shp[$i]!=''){
					$kp=explode('-',$shp[$i]);
					$stype='s'.$kp[0];
					$enginelist[$stype]['pv']=$enginelist[$stype]['pv']+$kp[1];
				}
			}
		break;
	}
}
switch($labtype){
	case 1:
	$field=$field?$field:'pv';
	foreach($visit as $key=>$val){
		$order[$key]=$val[$field];
	}
	array_multisort($order,SORT_DESC,SORT_NUMERIC,$visit);
	break;	
	case 2:
		array_multisort($enginelist,SORT_DESC);
		foreach($enginelist as $key=>$val){
			$yqnum.=$key.'-'.$val['pv'].'|';
		}
		$visit=$enginelist;
	break;
}
$i=0;foreach($visit as $key=>$val){$i++;$visit[$key]['order']=$i;}/*排序*/
/*分页*/
$total_count = count($visit);
$list_num = 15;
if($total_count>$list_num){
	require_once $depth.'../include/pager.class.php';
	$page = (int)$page;
	if($page_input){ $page=$page_input; }
	$rowset = new Pager($total_count,$list_num,$page);
	$from_record = $rowset->_offset();
	$i=0;
	foreach($visit as $key=>$val){
		$i++;
		$maxl=$from_record+$list_num;
		if($i>$from_record and $i<=$maxl){
			$newvisit[$key]=$val;
		}
	}
	$page_list = $rowset->link("engine.php?anyid={$anyid}&lang={$lang}&st={$st}&et={$et}&cs={$cs}&field={$field}&labtype={$labtype}&page=");
	$visit=$newvisit;
}
include template('app/stat/engine');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>