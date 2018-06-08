<?php 
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
switch($module){
	default:
		$temp = 'index';
		$waptitle=$wap_title;
		break;
	case 1:
		$temp = 'show';
		$dbname = $met_column;
		break;
	case 2:
		$temp = 'news';
		$dbname = $met_news;
		$list_num = $wap_news_list;
		break;
	case 3:
		$temp = 'product';
		$dbname = $met_product;
		$list_num = $wap_product_list;
		break;
	case 4:
		$temp = 'download';
		$dbname = $met_download;
		$list_num = $wap_download_list;
		break;
	case 5:
		$temp = 'img';
		$dbname = $met_img;
		$list_num = $wap_img_list;
		break;
	case 6:
		$temp = 'job';
		$dbname = $met_job;
		$list_num = $wap_job_list;
		break;
}
if($temp != 'index'){
$ctitle = $db->get_one("select * from $dbname where lang='$lang' and id = '$id'");
if(!$id){
	$clname = $class1?'class1':($class2?'class2':'class3');
	$classwap = $class1?$class1:($class2?$class2:$class3);
	$qtext = $met_wap_ok?"and wap_ok='1'":'';
	$serch_sql=" where lang='$lang' and $clname = '$classwap' $qtext";
	if($module==6)$serch_sql=" where lang='$lang' $qtext";
	$order_sql=$class3?list_order($class_list[$class3]['list_order']):($class2?list_order($class_list[$class2]['list_order']):list_order($class_list[$class1]['list_order']));
	if($module==6)$order_sql='order by no_order desc,addtime desc';
	$total_count = $db->counter($dbname, "$serch_sql", "*");
	$totaltop_count = $db->counter($dbname, "$serch_sql and top_ok='1'", "*");
	require_once '../include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
	$page = $page?$page:1;
	$query = "SELECT * FROM $dbname $serch_sql and top_ok='1' and access='0' and (recycle='0' or recycle='-1') $order_sql LIMIT $from_record, $list_num";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		$modlistnow[]=$list;
	}
	if(count($modlistnow)<intval($list_num)){
		if($totaltop_count>=$list_num){
			$from_record=$from_record-$totaltop_count;
			if($from_record<0)$from_record=0;
		}else{
			$from_record=$from_record?($from_record-$totaltop_count):$from_record;
		}
		$list_num=intval($list_num)-count($modlistnow);
		$query = "SELECT * FROM $dbname $serch_sql and top_ok='0' and access='0' and (recycle='0' or recycle='-1') $order_sql LIMIT $from_record, $list_num";
		$result = $db->query($query);
		while($list= $db->fetch_array($result)){
			$modlistnow[]=$list;
		}
	}
	foreach($modlistnow as $key=>$val){
		$val['url'] = 'index.php?lang='.$lang.'&id='.$val['id'].'&module='.$module;
		if($module==6)$val['url'] = 'index.php?lang='.$lang.'&id='.$val['id'].'&module='.$module.'&class1='.$class1;
		if($metwaphtm=='metwaphtm')$val['url'] = $fname[$module][1].$lang.$val['id'].'.html';
		$wap_list[] = $val;
	}
	if($metwaphtm=='metwaphtm'){
		$pagemor = $fname[$module][0].$lang.$classwap.'_';
		$page_list = $rowset->link($pagemor,'.html');
	}else{
		$pagemor = 'index.php?'.$lang.'&module='.$module."&class1=$class1&class2=$class2&class3=$class3&page=";
		$page_list = $rowset->link($pagemor,'');
	}

	$modulename = $db->get_one("select * from $met_column where lang='$lang' and id = '$classwap'");
	$waptitle=$modulename['name'].'-'.$wap_title;
}else{
	$show = $db->get_one("select * from $dbname where lang='$lang' and id = '$id'");
	$show['content'] = wap_replace($show['content'],'img','object|script','span|strong|table|tr|b|p');
	//$show['content'] =  preg_replace("/<(.*?)>/","",$show['content']);
	$classnow   = $show['class3']?$show['class3']:($show['class2']?$show['class2']:$show['class1']);
	if($module==1)$classnow = $id;
	if($module==6)$classnow = $class1;
	if($module>2 && $module<6)require_once 'paralist.php';
	$modulename = $db->get_one("select * from $met_column where lang='$lang' and id = '$classnow'");
	$waptitle=$show['title']?$show['title'].'-'.$wap_title:$show['name'].'-'.$wap_title;
}
}
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
?>