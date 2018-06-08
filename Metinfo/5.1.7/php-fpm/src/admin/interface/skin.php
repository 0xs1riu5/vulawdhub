<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=="modify"){
    require_once $depth.'../include/config.php';
	$txt='';
	if($cs==4){
		if($met_productdetail_x!=$moren_productdetail_x||$met_productdetail_y!=$moren_productdetail_y){
			$txt=$lang_metadmintext1;
		}
		if($met_imgdetail_x!=$moren_imgdetail_x||$met_imgdetail_y!=$moren_imgdetail_y){
			$txt=$lang_metadmintext1;
		}
	}
	metsave('../interface/skin.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$txt);
}else{
$cs=isset($cs)?$cs:2;
switch($cs){
	case 2:
		$cssfile="../../templates/".$met_skin_user."/images/css/css.inc.php";
		if(file_exists($cssfile)){
			$indexpage='';
			require_once $cssfile;
			if($indexpage)$indexpages=$indexpage;
		}
		$hadd_ok1[$index_hadd_ok]="checked='checked'";
		$link_ok1[$index_link_ok]="checked='checked'";
	break;
	case 3:
		$met_urlblank1[$met_urlblank]="checked='checked'";
		$met_pageskin1[$met_pageskin]="selected='selected'";
		$met_product_page1[$met_product_page]="checked='checked'";
		$met_img_page1[$met_img_page]="checked='checked'";
	break;
	case 4:
		$met_tools_ok1[$met_tools_ok]="checked='checked'";
		$met_product_detail1[$met_product_detail]="selected='selected'";
		$met_img_detail1[$met_img_detail]="selected='selected'";
		$met_pnorder1[$met_pnorder]="checked='checked'";
		if($met_pageclick)$met_page[1]='checked';
		if($met_pagetime)$met_page[2]='checked';
		if($met_pageprint)$met_page[3]='checked';
		if($met_pageclose)$met_page[4]='checked';
	break;
}
if($cs==3||$cs==4){
	$met_timetype[0]=array(0=>"selected='selected'",1=>'Y-m-d H:i:s',2=>date('Y-m-d H:i:s',$m_now_time));
	$met_timetype[1]=array(0=>"selected='selected'",1=>'Y-m-d',2=>date('Y-m-d',$m_now_time));
	$met_timetype[2]=array(0=>"selected='selected'",1=>'Y/m/d',2=>date('Y/m/d',$m_now_time));
	$met_timetype[3]=array(0=>"selected='selected'",1=>'Ymd',2=>date('Ymd',$m_now_time));
	$met_timetype[4]=array(0=>"selected='selected'",1=>'Y-m',2=>date('Y-m',$m_now_time));
	$met_timetype[5]=array(0=>"selected='selected'",1=>'Y/m',2=>date('Y/m',$m_now_time));
	$met_timetype[6]=array(0=>"selected='selected'",1=>'Ym',2=>date('Ym',$m_now_time));
	$met_timetype[6]=array(0=>"selected='selected'",1=>'m-d',2=>date('m-d',$m_now_time));
	$met_timetype[7]=array(0=>"selected='selected'",1=>'m/d',2=>date('m/d',$m_now_time));
	$met_timetype[8]=array(0=>"selected='selected'",1=>'md',2=>date('md',$m_now_time));
	for($i=0;$i<9;$i++){
		if($met_timetype[$i][1]==$met_listtime)$met_listtime1[$i]=$met_timetype[$i][0];
		if($met_timetype[$i][1]==$met_contenttime)$met_contenttime1[$i]=$met_timetype[$i][0];
	}
}
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('interface/set_skin');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>