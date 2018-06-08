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
$field=$field?$field:'pv';
$zvist[$field]='<b style="color:#f00;">↓</b>';
$labtypeclass[$labtype]='now';
$query="select * from {$met_visit_detail} WHERE stattime>='{$st}' and stattime<='{$et}' and type='3' order by pv desc";
$result= $db->query($query);
$domain=array();
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
			preg_match_all( '/(http:\/\/.*?)\/.*?/i ',$list['name'],$d);
			$do=explode('http://',$d[1][0]);
			if($do[1]=='')$do[1]=$lang_statweb;
			$domain[$do[1]]['pv']=$domain[$do[1]]['pv']+$list['pv'];
		break;
	}
}
switch($labtype){
	case 1:
		$visit=arraysort2($visit,$field,SORT_DESC);
	break;
	case 2:
		array_multisort($domain,SORT_DESC);
		foreach($domain as $key=>$val){
			$yqnum.=$key.'$'.$val['pv'].'|';
		}
		$visit=$domain;
	break;
}
/*分页*/
$total_count = count($visit);
$list_num = 15;
require_once $depth.'../include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$i=0;
foreach($visit as $key=>$val){
	$i++;
	$maxl=$from_record+$list_num;
	if($i>$from_record and $i<=$maxl){
		$val['order']=$i;
		$newvisit[$key]=$val;
	}
}
if($total_count>$list_num){
	$page_list = $rowset->link("source.php?lang={$lang}&anyid={$anyid}&st={$st}&et={$et}&cs={$cs}&field={$field}&labtype={$labtype}&page=");
}
	$visit=$newvisit;
include template('app/stat/source');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>