<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$query="select * from $met_column where lang='$lang' and if_in='0' order by no_order";
$result= $db->query($query);
$mod1[0]=$mod[10000]=array(
			id=>10000,
			name=>"$lang_flashGlobal",
			url=>$met_weburl."index.php?lang=".$lang,
			bigclass=>0
		);
$mod1[1]=$mod[10001]=array(
			id=>10001,
			name=>"$lang_flashHome",
			url=>$met_weburl."index.php?lang=".$lang,
			bigclass=>0
		);
$i=2;
while($list = $db->fetch_array($result)){
	if(!isset($met_flasharray[$list[id]][type]))$met_flasharray[$list[id]]=$met_flasharray[10000];
	$list['url']=linkrules($list);
	if($list[classtype]==1){
		$mod1[$i]=$list;
		$i++;
	}
	if($list[classtype]==2)$mod2[$list[bigclass]][]=$list;
	if($list[classtype]==3)$mod3[$list[bigclass]][]=$list;
	$mod[$list['id']]=$list;
}
if($action=="modify"){
	$met_array=Array();
	$met_flash_typeall=$met_flash_10000_type;
	$met_flash_xall=$met_flash_10000_x;
	$met_flash_yall=$met_flash_10000_y;
	$met_flash_imgtypeall=$met_flash_10000_imgtype;
	foreach($mod as $key=>$val){
		$met_flash_all="met_flash_".$val[id]."_all";
		$met_flash_all=$$met_flash_all;
		if($met_flash_all==1){
			$met_array[$val['id']]['type']=$met_flash_typeall;
			$met_array[$val['id']]['x']=intval($met_flash_xall);
			$met_array[$val['id']]['y']=intval($met_flash_yall);
			$met_array[$val['id']]['imgtype']=$met_flash_imgtypeall;
			$query = "update $met_flash SET
					width	= '$met_flash_xall',
					height	= '$met_flash_yall'
					where module='$val[id]'";
			$db->query($query);
		}else{
			$met_flash_type="met_flash_".$val[id]."_type";
			$met_flash_type=$$met_flash_type;
			$met_flash_x="met_flash_".$val[id]."_x";
			$met_flash_x=$$met_flash_x;
			$met_flash_y="met_flash_".$val[id]."_y";
			$met_flash_y=$$met_flash_y;
			$met_flash_imgtype="met_flash_".$val[id]."_imgtype";
			$met_flash_imgtype=$$met_flash_imgtype;
			$met_flash_x=intval($met_flash_x)?$met_flash_x:$met_flash_xall;
			$met_flash_y=intval($met_flash_y)?$met_flash_y:$met_flash_yall;
			$met_array[$val['id']]['type']=$met_flash_type;
			$met_array[$val['id']]['x']=intval($met_flash_x);
			$met_array[$val['id']]['y']=intval($met_flash_y);
			$met_array[$val['id']]['imgtype']=$met_flash_imgtype;
			$query = "update $met_flash SET
					width	= '$met_flash_x',
					height	= '$met_flash_y'
					where module='$val[id]'";
			$db->query($query);
		}
	}
	foreach($met_flasharray as $key=>$val){
		if(!$met_array[$key]){
			$query = "delete from $met_config where flashid='$key' and lang='$lang'";
			$db->query($query);
		}
	}
	$met_flasharray=$met_array;
	foreach($met_flasharray as $key=>$val){
		$name='flash_'.$key;
		$value=$val['type'].'|'.$val['x'].'|'.$val['y'].'|'.$val['imgtype'];
		$configok = $db->get_one("SELECT * FROM $met_config WHERE flashid ='$key' and lang='$lang'");
		if(!$configok){
			$query = "INSERT INTO $met_config SET
					name              = '$name',
					value             = '$value',
					flashid           = '$key',
					lang              = '$lang'
					";
			$db->query($query);
		}elseif($configok['value']!=$value){
			$query = "update $met_config SET value = '$value' where flashid ='$key' and lang='$lang'";
			$db->query($query);
		}
	}
	$rurl='../interface/flash/setflash.php?anyid='.$anyid.'&lang='.$lang;
	metsave($rurl,'',$depth);
}else{
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('interface/flash/setflash');footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>