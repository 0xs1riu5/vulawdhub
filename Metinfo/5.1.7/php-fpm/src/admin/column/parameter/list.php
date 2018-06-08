<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$backurl='../column/parameter/list.php?anyid='.$anyid.'&bigid='.$bigid.'&lang='.$lang.'&class1='.$class1;
if($action=="add"){
	$list_if=$db->get_one("SELECT * FROM $met_list WHERE info='$info' and bigid='$bigid' ");
	if($list_if)metsave('-1',$lang_dataerror,$depth);
	$list_ok=$db->get_one("SELECT * FROM $met_list WHERE bigid='$bigid' and no_order=99999 ");
	if($listproduct=="metinfo" && $list_ok){
		$query=(trim($info)==0)?"delete from $met_list where bigid='$bigid' and no_order=99999":"update $met_list set info='$info' where bigid='$bigid' and no_order=99999";
	}else{
	   $query="insert into $met_list set
			   info     ='$info',
			   no_order ='$no_order',
			   lang     ='$lang',
			   bigid    ='$bigid'";
	}
	$db->query($query);
	metsave($backurl,'',$depth);
}elseif($action=="addsave"){
	$newslit = "<tr class='mouse newlist'>\n";
	$newslit.= "<td class='list-text'><input name='id' type='checkbox' value='new$lp' checked='checked' /><input name='bigid_new$lp' type='hidden' value='$bigid' /></td>\n";	
	$newslit.= "<td class='list-text'><input name='no_order_new$lp' type='text' class='text no_order' /></td>\n";	
	$newslit.= "<td class='list-text' style='text-align:left; padding-left:15px;'><input name='info_new$lp' type='text' class='text nonull' /></td>\n";	
	$newslit.= "<td class='list-text'><a href='javascript:;' class='hovertips' style='padding:0px 5px;' onclick='delettr($(this));'>$lang_js49</a></td>\n";
	$newslit.= "</tr>";
	echo $newslit;
}elseif($action=="editor"){
	$allidlist=explode(',',$allid);
	$adnum = count($allidlist)-1;
	for($i=0;$i<$adnum;$i++){
		$info = 'info_'.$allidlist[$i];
		$info = $$info;
		$no_order = 'no_order_'.$allidlist[$i];
		$no_order = $$no_order;
		$bigid    = 'bigid_'.$allidlist[$i];
		$bigid    = $$bigid;
		$tpif = is_numeric($allidlist[$i])?1:0;
		$sql = $tpif?"id='$allidlist[$i]'":'';
		if($sql!='')$skin_m=$db->get_one("SELECT * FROM $met_list WHERE $sql");
		if($tpif){
			if(!$skin_m){metsave('-1',$lang_dataerror,$depth);}
		}else{
			$list_if=$db->get_one("SELECT * FROM $met_list WHERE info='$info' and bigid='$bigid' ");
			if($list_if)metsave('-1',$lang_parameternameexist,$depth);
		}
		$uptp = $tpif?"update":"insert into";
		$upbp = $tpif?"where id='$allidlist[$i]'":",lang='$lang'";
		$query="$uptp $met_list set
				info       ='$info',
				no_order   ='$no_order',
				bigid      ='$bigid'
				$upbp";
		$db->query($query);
	}
    metsave($backurl,'',$depth);
}elseif($action=="delete"){
	if($action_type=="del"){
		$allidlist=explode(',',$allid);
		foreach($allidlist as $key=>$val){
			$query = "delete from {$met_list} where id='{$val}'";
			$db->query($query);
		}
	}else{
		$query="delete from $met_list where id='$id'";
		$db->query($query);
	}
	metsave($backurl,'',$depth);
}else{
	$listinfo='';
	$bigid_info=$db->get_one("select * from $met_parameter where id='$bigid'");
	if($bigid_info['module']==8){
		$listinfo=$db->get_one("select * from $met_list where bigid='$bigid' and no_order=99999");
		$listinfoid=intval(trim($listinfo['info']));
		
	}
	if($listinfo){
		$listmarknow='metinfo';
		$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$met_class[$listinfoid][classtype]);
		$query = "SELECT title FROM $met_product where $classtype=$listinfoid and lang='$lang' order BY updatetime desc";
		$result = $db->query($query);
		$i=0;
		while($list = $db->fetch_array($result)) {
			$list['info']=$list['title'];
			$i++;
			$list['no_order']=$i;
			$fd_list[]=$list;
		}
	}else{
		$query = "SELECT * FROM $met_list  where bigid='$bigid' order BY no_order";
		$result = $db->query($query);
		while($list = $db->fetch_array($result)) {
			$fd_list[]=$list;
		}
	}
	include template('column/parameter_list');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>