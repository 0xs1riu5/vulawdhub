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
$search=$visitlan?"and lang='$visitlan'":'';
$query="select * from {$met_visit_detail} WHERE stattime>='{$st}' and stattime<='{$et}' and type='2' $search order by pv desc";
$result= $db->query($query);
$domain=array();
while($list = $db->fetch_array($result)){
	$valmet=acceptun($list['columnid'],$list['listid'],$list['lang']);
	switch($labtype){
		case 1:
			$list['title']=$valmet['title']?$valmet['title']:$list['name'];
			if($visit[$list['title']]){
				$list['pv']=$visit[$list['title']]['pv']+$list['pv'];
				$list['ip']=$visit[$list['title']]['ip']+$list['ip'];
				$list['alone']=$visit[$list['title']]['alone']+$list['alone'];
			}
			$list['per']=sprintf("%.2f",($list['pv']/$list['alone']));
			$visit[$list['title']]=$list;
		break;
		case 2:
			preg_match_all( '/(http:\/\/.*?)\/.*?/i ',$list['name'],$d);
			$do=explode('http://',$d[1][0]);
			$do[1]=strtolower($do[1]);
			$domain[$do[1]]['pv']=$domain[$do[1]]['pv']+$list['pv'];
		break;
		case 3:
			$langname=$list['lang'];
			if($langname!=''){
				$langlist[$langname]['pv']=$langlist[$langname]['pv']+$list['pv'];
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
		array_multisort($domain,SORT_DESC);
		foreach($domain as $key=>$val){
			$yqnum.=$key.'$'.$val['pv'].'|';
		}
		$visit=$domain;
	break;
	case 3:
		array_multisort($langlist,SORT_DESC);
		foreach($langlist as $key=>$val){
			$langnum.=$key.'$'.$val['pv'].'|';
		}
		$visit=$langlist;
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
	$page_list = $rowset->link("accept.php?lang={$lang}&anyid={$anyid}&st={$st}&et={$et}&cs={$cs}&field={$field}&visitlan={$visitlan}&labtype={$labtype}&page=");
}
	$visit=$newvisit;
include template('app/stat/accept');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>