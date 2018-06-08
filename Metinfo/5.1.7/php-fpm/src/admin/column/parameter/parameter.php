<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="editor"){
	$allidlist=explode(',',$allid);
	$adnum = count($allidlist)-1;
	for($i=0;$i<$adnum;$i++){
		$name     = "name_".$allidlist[$i];	
		$name     = $$name;
		$no_order = "no_order_".$allidlist[$i];
		$no_order = $$no_order;
		$type     = "type_".$allidlist[$i];
		$type     = $$type;
		$access   = "access_".$allidlist[$i];
		$access   = $$access;
		if($module!=8){
			$class1   = "class1_".$allidlist[$i];
			$class1   = $$class1;
		}
		$wr_ok    = "wr_ok_".$allidlist[$i];
		$wr_ok    = $$wr_ok;
		$tpif = is_numeric($allidlist[$i])?1:0;
		$sql = $tpif?"id='$allidlist[$i]'":'';
		$uptp = $tpif?"update":"insert into";
		$upbp = $tpif?"where id='$allidlist[$i]'":",lang='$lang'";
		$query="$uptp $met_parameter set
				name               = '$name',
				no_order           = '$no_order',
				type               = '$type',
				access             = '$access',
				class1             = '$class1',
				wr_ok              = '$wr_ok',
				module             = '$module'
				$upbp";
		$db->query($query);
	}
	metsave('../column/parameter/parameter.php?anyid='.$anyid.'&module='.$module.'&lang='.$lang.'&class1='.$class1,'',$depth);
}elseif($action=="del"){
	$query="delete from $met_parameter where id='$id'";
	$db->query($query);
	if($type==2 or $type==4 or $type==6){
		$query="delete from $met_list where bigid='$id'";
		$db->query($query);
	}
	/*delete images*/
	if($met_deleteimg && $type==5){
		$query="select * from $met_plist where paraid='$id'";
		$result= $db->query($query);
		while($list = $db->fetch_array($result)){
			file_unlink("../../".$list[info]);
		}
	}
	$query="delete from $met_plist where paraid='$id'";
	$db->query($query);
	metsave('../column/parameter/parameter.php?anyid='.$anyid.'&module='.$module.'&lang='.$lang.'&class1='.$class1,'',$depth);
}elseif($action=="delete"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		$para_list = $db->get_one("SELECT * FROM $met_parameter WHERE id='$val'");
		$type = $para_list['type'];
		$query="delete from $met_parameter where id='$val'";
		$db->query($query);
		if($type==2 or $type==4 or $type==6){
			$query="delete from $met_list where bigid='$val'";
			$db->query($query);
		}
		/*delete images*/
		if($met_deleteimg && $type==5){
			$query="select * from $met_plist where paraid='$val'";
			$result= $db->query($query);
			while($list = $db->fetch_array($result)){
				file_unlink("../../".$list[info]);
			}
		}
		$query="delete from $met_plist where paraid='$val'";
		$db->query($query);
		$type='';
	}
	metsave('../column/parameter/parameter.php?anyid='.$anyid.'&module='.$module.'&lang='.$lang.'&class1='.$class1,'',$depth);
}elseif($action=="addsave"){
	$newslit = "<tr class='mouse newlist'>\n"; 
	$newslit.= "<td class='list-text'><input name='id' type='checkbox' value='new$lp' checked='checked' /></td>\n";
	$newslit.= "<td class='list-text'><input name='no_order_new$lp' type='text' class='text no_order' /></td>\n";
	$newslit.= "<td class='list-text' style='padding-left:15px; text-align:left;'><input name='name_new$lp' type='text' class='text nonull' /></td></td>\n";
	if($module<6){		
	$newslit.= "<td class='list-text'>\n";
	$newslit.= "<select name='class1_new$lp' >\n";
	$newslit.= "<option value='0' selected='selected'>$lang_allcategory</option>\n";
		foreach($met_classindex[$module] as $key=>$val1){
	$newslit.= "<option value='$val1[id]' >$val1[name]</option>\n";
		}
	$newslit.= "</select>\n";
	}
	if($met_member_use){
	$newslit.= "<td class='list-text'>\n";
	$newslit.="<select name='access_new$lp' id='access' >";
	$lev=0;
	require '../../content/access.php';
	$newslit.=$level;
	$newslit.= "</select></td>\n";	
	}
	$newslit.= "<td class='list-text'><select name='type_new$lp' id='access'>\n";
	$newslit.= "<option value='1' >{$lang_parameter1}</option>\n";
	$newslit.= "<option value='2' >{$lang_parameter2}</option>\n";
	$newslit.= "<option value='3' >{$lang_parameter3}</option>\n";
	$newslit.= "<option value='4' >{$lang_parameter4}</option>\n";
	$newslit.= "<option value='5' >{$lang_parameter5}</option>\n";
	$newslit.= "<option value='6' >{$lang_parameter6}</option>\n";
	$newslit.= "</select></td>\n";
	$newslit.= "<td class='list-text'><input type='checkbox' name='wr_ok_new$lp' value='1' /></td>\n";
	$newslit.= "<td class='list-text'><a href='javascript:;' class='hovertips' style='padding:0px 5px;' onclick='delettr($(this));'>$lang_js49</a></td>\n";
	$newslit.= "</tr>";
	echo $newslit;
}else{
    $query="select * from $met_parameter where module='$module' and lang='$lang'  order by no_order";
	if($class1)$query="select * from $met_parameter where module='$module' and (class1='$class1' or class1='0') and lang='$lang'  order by no_order";
	$result= $db->query($query);
	while($list1 = $db->fetch_array($result)){
		$typelist="type".$list1[type];
		$list1[$typelist]="selected='selected'";
		$list1[wr_ok]=($list1[wr_ok]==1)?"checked='checked'":"";
		if($met_member_use){
			$lev=0;
			$list_access['access']=$list1['access'];
			require '../../content/access.php';
			$list1[level]=$level;
		}
		$list[]=$list1;
	}
	if($module==6){
		$m_list = $db->get_one("SELECT * FROM $met_column WHERE module='6' and lang='$lang'");
		$class1 = $m_list['id'];
	}
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('column/parameter');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>