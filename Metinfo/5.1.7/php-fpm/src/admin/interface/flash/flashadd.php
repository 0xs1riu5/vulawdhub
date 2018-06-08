<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$mtype=$met_flasharray[$module]['type'];
$flashmdtype=$flashmdtype?$flashmdtype:1;
$mtype=$flashmdtype==2?2:1;
$flashmdtype1[$flashmdtype]='selected';
$query="select * from $met_column where lang='$lang' and if_in='0' order by no_order";
$result= $db->query($query);
while($list = $db->fetch_array($result)){
	if(!$met_flasharray[$list[id]]){
		$met_flasharray[$list[id]]=$met_flasharray[10000];
		$name='flash_'.$list[id];
		$value=$met_flasharray[10000]['type'].'|'.$met_flasharray[10000]['x'].'|'.$met_flasharray[10000]['y'].'|'.$met_flasharray[10000]['imgtype'];
		$query = "INSERT INTO $met_config SET
				name              = '$name',
				value             = '$value',
				flashid           = '$list[id]',
				lang              = '$lang'
				";
		$db->query($query);
	}
}
foreach($met_flasharray as $key=>$val){
	if($val['type']==$flashmdtype || ($flashmdtype==1 && $val['type']==3)){
		if($key==10001){
			$modclumlist[]=array('id'=>10001,'name'=>$lang_indexhome);
		}else{
			$modclumlist[]=$met_class[$key];
		}
	}
}
switch($mtype){
	case 1:
		$met_module_type=$lang_flashMode1;
	break;
	case 2:
		$met_module_type=$lang_flashMode2;
	break;
	case 3:
		$met_module_type=$lang_setflashMode3;
	break;
}
if($module==10000||$module==10001){
	$columnid=$module==10000?array('name'=>$lang_flashGlobal):array('name'=>$lang_indexhome);
}else{
	$columnid=$db->get_one("select * from {$met_column} where id='{$module}' and lang='{$lang}'");
}
$i=1;
foreach($modclumlist as $key=>$list){
	if($list[classtype]==1 || $list['id']==10001){
		$mod1[$i]=$list;
		$i++;
	}
	if($list[classtype]==2)$mod2[$list[bigclass]][]=$list;
	if($list[classtype]==3)$mod3[$list[bigclass]][]=$list;
	$mod[$list['id']]=$list;
}
$met_flash_type[$met_flasharray[10000][type]]="checked='checked'";
$style1=$met_flasharray[10000][type]==1?"style='display:block;'":"style='display:none;'";
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('interface/flash/flashadd');
footer();

# 本程序是一个开源系统,使用时请你仔细阅读使用协议,商业用途请自觉购买商业授权.
# Copyright (C) 长沙米拓信息技术有限公司 (http://www.metinfo.cn). All rights reserved.
?>