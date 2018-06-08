<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
$flashrec1=$db->get_one("SELECT * FROM {$met_flash} where id='$id'");
$mtype=$met_flasharray[$module][type];
$flashmdtype=$flashrec1['img_path']!=''?1:2;
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
if($flashrec1['module']=='metinfo'){
	$met_clumid_all1='checked';
}else{
	$lmod = explode(',',$flashrec1['module']);
	for($i=0;$i<count($lmod);$i++){
		if($lmod[$i]!='')$feditlist[$lmod[$i]]=1;
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
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('interface/flash/flashedit');
footer();

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>