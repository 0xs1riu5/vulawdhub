<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="modify"){
$met_onlinenameok=$met_onlinenameok2;
require_once $depth.'../include/config.php';
$rurl='../interface/online/online.php?anyid='.$anyid.'&lang='.$lang;
metsave($rurl,'',$depth);
}else{
$cs=2;
$listclass[$cs]='class="now"';
$met_online_skinarray[]=array(1,$lang_onlineblue,1);
$met_online_skinarray[]=array(1,$lang_onlinered,2);
$met_online_skinarray[]=array(1,$lang_onlinepurple,3);
$met_online_skinarray[]=array(1,$lang_onlinegreen,4);
$met_online_skinarray[]=array(1,$lang_onlinegray,5);
$met_online_skinarray[]=array(2,$lang_onlineblue,1);
$met_online_skinarray[]=array(2,$lang_onlinered,2);
$met_online_skinarray[]=array(2,$lang_onlinepurple,3);
$met_online_skinarray[]=array(2,$lang_onlinegreen,4);
$met_online_skinarray[]=array(2,$lang_onlinegray,5);
$met_online_skinarray[]=array(3,$lang_onlineblue,1);
$met_online_skinarray[]=array(3,$lang_onlinered,2);
$met_online_skinarray[]=array(3,$lang_onlinepurple,3);
$met_online_skinarray[]=array(3,$lang_onlinegreen,4);
$met_online_skinarray[]=array(3,$lang_onlinegray,5);
$met_online_skinarray[]=array(4,$lang_onlineblue,1);
$met_online_skinarray[]=array(4,$lang_onlinered,2);
$met_online_skinarray[]=array(4,$lang_onlinepurple,3);
$met_online_skinarray[]=array(4,$lang_onlinegreen,4);
$met_online_skinarray[]=array(4,$lang_onlinegray,5);
$jslist = "<script language = 'JavaScript'>\n";
$jslist .= "var onecount;\n";
$jslist .= "subcat = new Array();\n";
$i=0;
foreach($met_online_skinarray as $key=>$val){
$jslist .= "subcat[".$i."] = new Array('".$val[0]."','".$val[1]."','".$val[2]."');\n";
if($val[0]==$met_online_skin)$met_online_skinarray1[]=$val;
$met_online_count[$val[0]]=$val[0];
$i++;
}
$jslist .= "onecount=".$i.";\n";
$jslist .= "</script>";
$met_online_type1[$met_online_type]="checked='checked'";
$met_online_skin1[$met_online_skin]="selected='selected'";
$met_online_color1[$met_online_color]="selected='selected'";
$met_qq_type1[$met_qq_type]="checked='checked'";
$met_msn_type1[$met_msn_type]="checked='checked'";
$met_taobao_type1[$met_taobao_type]="checked='checked'";
$met_alibaba_type1[$met_alibaba_type]="checked='checked'";
$met_skype_type1[$met_skype_type]="checked='checked'";
if($met_onlinenameok)$met_onlinenameok1="checked='checked'";
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('interface/online/set_online');footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>