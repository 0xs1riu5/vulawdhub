<?php 
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
if(!$met_wap)okinfo('../index.php?lang='.$lang,$lang_metwapok);
foreach($met_langok as $key=>$val){
$met_langok[$val['mark']]['wapurl']="index.php?lang=$val[mark]";
if($metwaphtm=='metwaphtm')$met_langok[$val['mark']]['wapurl']="index$val[mark].html";
if($metwaphtm=='metwaphtm' && $met_index_type==$val[mark])$met_langok[$val['mark']]['wapurl']="index.html";
}
$wapindex_url="index.php?lang=$lang";
if($metwaphtm=='metwaphtm'){
$wapindex_url="index{$lang}.html";
if($met_index_type==$lang)$wapindex_url="index.html";
}
		$fname[1]=array(0=>'about',1=>'about');
		$fname[2]=array(0=>'news',1=>'shownews');
		$fname[3]=array(0=>'product',1=>'showproduct');
		$fname[4]=array(0=>'download',1=>'showdownload');
		$fname[5]=array(0=>'img',1=>'showimg');
		$fname[6]=array(0=>'job',1=>'showjob');
	$qtext = $met_wap_ok?"and wap_ok='1'":'';
	$query="select * from $met_column where lang='$lang' $qtext order by no_order";
	$result= $db->query($query);
	while($list = $db->fetch_array($result)){
		if($list['module']>0 && $list['module']<7){
			switch($list['classtype']){
				case 1:
					$urllast   = '&class1='.$list['id'];
				break;
				case 2:
					$urllast   = '&class2='.$list['id'];
				break;
				case 3:
					$urllast   = '&class3='.$list['id'];
				break;
			}
			if($list['isshow']!=1){
				if($list['classtype']==1)$isshow = 1;
				if($list['classtype']==2)$isshowe = 1;
				$list['urllabel'] = $list['classtype']==1?'url1':'url2';
			}
			$list['url'] = 'index.php?lang='.$lang.$urllast.'&module='.$list['module'];
			if($list['module']==1)$list['url'] = 'index.php?lang='.$lang.'&id='.$list['id'].'&module='.$list['module'];
			if($metwaphtm=='metwaphtm')$list['url'] = $fname[$list['module']][0].$lang.$list['id'].'.html';
			if($metwaphtm=='metwaphtm' && $list['module']>1)$list['url'] = $fname[$list['module']][0].$lang.$list['id'].'_1.html';
			$wap_navlist[]=$list;
			if($list['nav'] == 1 || $list['nav'] == 3)$wap_nav[]=$list;
			if($list['classtype']==2)$wap_nav2[$list['bigclass']][]=$list;
			if($list['classtype']==3)$wap_nav3[$list['bigclass']][]=$list;
			$class_list[$list['id']]=$list;
		}
	}
	if($isshowe){
		foreach($wap_navlist as $key=>$val){
			if($val['urllabel']=='url2'){
				foreach($wap_nav3[$val['id']] as $key=>$val1){
					$val['url'] = $val1['url'];
					break;
				} 
			}
			$wap_navlist00[] = $val;
			if($val['nav'] == 1 || $val['nav'] == 3)$wap_nav00[]=$val;
			if($val['classtype']==2)$wap_nav200[$val['bigclass']][]=$val;
			if($val['classtype']==3)$wap_nav300[$val['bigclass']][]=$val;
		}
		$wap_navlist = $wap_navlist00;
		$wap_nav = $wap_nav00;
		$wap_nav2 = $wap_nav200;
		$wap_nav3 = $wap_nav300;
	}
	if($isshow){
		foreach($wap_navlist as $key=>$val){
			if($val['urllabel']=='url1'){
				foreach($wap_nav2[$val['id']] as $key=>$val1){
					$val['url'] = $val1['url'];
					break;
				} 
			}
			$wap_navlist0[] = $val;
			if($val['nav'] == 1 || $val['nav'] == 3)$wap_nav0[]=$val;
			if($val['classtype']==2)$wap_nav20[$val['bigclass']][]=$val;
			if($val['classtype']==3)$wap_nav30[$val['bigclass']][]=$val;
		}
		$wap_navlist = $wap_navlist0;
		$wap_nav = $wap_nav0;
		$wap_nav2 = $wap_nav20;
		$wap_nav3 = $wap_nav30;
	}
require_once 'module.php';
$css_url="templates/met/css";
$img_url="templates/met/images";
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?> 