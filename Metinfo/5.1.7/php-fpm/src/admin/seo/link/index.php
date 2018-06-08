<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="linkopen"){
$met_addlinkopen=$met_addlinkopen;
$langp=$lang;
$metcms[$langp]['met_addlinkopen']=$met_addlinkopen;
require_once $depth.'../include/config.php';
okinfo('../link/index.php?lang='.$lang);
}else{																																		
    $serch_sql=" where lang='$lang' ";
	if($link_type!="")$serch_sql.=" and link_type=$link_type ";
    if($com_ok!="")$serch_sql.=" and com_ok=$com_ok ";
	if($show_ok!="")$serch_sql.=" and show_ok=$show_ok ";
	if($link_lang!="")$serch_sql.=" and link_lang=$link_lang ";
	$order_sql=" order by orderno desc";
    if($search == "detail_search") {	
        if($webname) { $serch_sql .= " and webname like '%$webname%' "; }
        $total_count = $db->counter($met_link, "$serch_sql", "*");
    }else{
        $total_count = $db->counter($met_link, "$serch_sql", "*");
    }
    require_once 'include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = 20;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
    $query = "SELECT * FROM $met_link $serch_sql $order_sql LIMIT $from_record, $list_num";
    $result = $db->query($query);
	while($list = $db->fetch_array($result)){
	$list[show_ok]=($list[show_ok])?$lang_yes:$lang_no;
	$list[com_ok]=($list[com_ok])?$lang_yes:$lang_no;
	$list[link_type]=($list[link_type])?$lang_linkType5:$lang_linkType4;
    $link_list[]=$list;
    }
$met_weburl=substr($met_weburl, 0, -1);
$page_list = $rowset->link("index.php?lang=$lang&anyid=$anyid&link_type=$link_type&com_ok=$com_ok&show_ok=$show_ok&link_lang=$link_lang&search=$search&webname=$webname&page=");
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('seo/link/link');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>