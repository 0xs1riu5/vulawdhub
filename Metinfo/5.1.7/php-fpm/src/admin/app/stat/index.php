<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
$action_ajax=1;
require_once $depth.'../login/login_check.php';
if(!$met_stat && !isset($cs)){header("location:set.php?lang=".$lang.'&anyid='.$anyid);exit;}
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
//
$visitday=$db->get_one("SELECT * FROM {$met_visit_summary} WHERE stattime='{$dtime}'");
if(!$visitday && $met_stat){
	$met='';for($i=0;$i<24;$i++)$met.= '|';
	$parttime = $met;
	$query = "INSERT INTO {$met_visit_summary} SET
				pv         = '0',
				ip         = '0',
				alone      = '0',
				parttime   = '{$parttime}',
				stattime = '{$dtime}'";
	$db->query($query);
	if($met_stat_cr1)delet_estat_cr(1,$met_stat_cr1);
	if($met_stat_cr2)delet_estat_cr(2,$met_stat_cr2);
	if($met_stat_cr3)delet_estat_cr(3,$met_stat_cr3);
	if($met_stat_cr4)delet_estat_cr(4,$met_stat_cr4);
	if($met_stat_cr5)delet_estat_cr(5,$met_stat_cr5);
}
/*初始变量*/
$tmst=date("Y-m-d",$st);
$tmet=date("Y-m-d",$et);
$cs=isset($cs)?$cs:0;
$dancs[$cs]='class="dday round"';
$weekarray=array($lang_week7,$lang_week1,$lang_week2,$lang_week3,$lang_week4,$lang_week5,$lang_week6);
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
//
if($action=='all'){
	$query="select * from {$met_visit_summary} WHERE stattime >='{$st}' and stattime <='{$et}' order by stattime desc";
	$result= $db->query($query);
	while($list = $db->fetch_array($result)){
		$summarylist[]=$list;
	}
	if($summarylist){
		$mp=($et-$st)/60/60/24+1;
		if(count($summarylist)<$mp){
			for($i=0;$i<$mp;$i++){
				$k=(60*60*24)*($i);$xt=$st+$k;$p=0;
				foreach($summarylist as $key=>$val){
					if($val['stattime']==$xt)$p=1;
				}
				if($p==0)$summarylist[]=array('stattime'=>$xt,'pv'=>0,'alone'=>0,'ip'=>0);
			}
			foreach($summarylist as $key=>$val){
				$keyvalues[]=$val['stattime'];
			}
			sort($keyvalues);
			$mp=count($keyvalues);
			for($i=0;$i<$mp;$i++){
				$p=$mp-$i-1;
				foreach($summarylist as $key=>$val){
					if($val['stattime']==$keyvalues[$p])$newlist[$i]=$val;
				}
			}
			$summarylist=$newlist;
		}
	}else{
		if($st!=$et){
			$mp=($et-$st)/60/60/24+1;
			for($i=0;$i<$mp;$i++){
				$k=(60*60*24)*($i);$xt=$st+$k;
				$summarylist[]=array('stattime'=>$xt,'pv'=>0,'alone'=>0,'ip'=>0);
			}
			foreach($summarylist as $key=>$val){
				$keyvalues[]=$val['stattime'];
			}
			sort($keyvalues);
			$mp=count($keyvalues);
			for($i=0;$i<$mp;$i++){
				$p=$mp-$i-1;
				foreach($summarylist as $key=>$val){
					if($val['stattime']==$keyvalues[$p])$newlist[$i]=$val;
				}
			}
			$summarylist=$newlist;
		}else{
			$summarylist[]=array('stattime'=>$et,'pv'=>0,'alone'=>0,'ip'=>0);
		}
	}
}else{
	$query="select * from {$met_visit_summary}";
	$result= $db->query($query);
	while($list = $db->fetch_array($result)){
		if($list['stattime']==$dtime)$visit_summary=$list;
		$pvaccum=$pvaccum+$list['pv'];
		$ipaccum=$ipaccum+$list['ip'];
		$alaccum=$alaccum+$list['alone'];
		$summarylist[]=$list;
		$l++;
	}
	if(!$visit_summary)$visit_summary=array('stattime'=>$dtime,'pv'=>0,'alone'=>0,'ip'=>0);
	$pvaver=round($pvaccum/$l);
	$ipaver=round($ipaccum/$l);
	$alaver=round($alaccum/$l);
	foreach($summarylist as $key=>$val){
		$p=0;
		$i=0;
		$a=0;
		foreach($summarylist as $key=>$val2){
			if($val['pv']<$val2['pv'])$p=1;
			if($val['ip']<$val2['ip'])$i=1;
			if($val['alone']<$val2['alone'])$a=1;
		}
		if($p==0)$maxpv=$val['pv'].'<span>('.date("Y-m-d",$val['stattime']).')</span>';
		if($i==0)$maxip=$val['ip'].'<span>('.date("Y-m-d",$val['stattime']).')</span>';
		if($a==0)$maxal=$val['alone'].'<span>('.date("Y-m-d",$val['stattime']).')</span>';
	}
	$per_visit=sprintf("%.2f",($visit_summary['pv']/$visit_summary['alone']));
	$visit_summaryz=$db->get_one("SELECT * FROM {$met_visit_summary} WHERE stattime='{$ztime}'");
	if(!$visit_summaryz){
		$visit_summaryz['pv']=0;
		$visit_summaryz['alone']=0;
		$visit_summaryz['ip']=0;
	}
	$per_visitz=sprintf("%.2f",($visit_summaryz['pv']/$visit_summaryz['alone']));
}
include template('app/stat/index');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>