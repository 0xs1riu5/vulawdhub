<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：area.php
 * $author：lucks
 */
define('IN_BLUE', true);
 require_once(dirname(__FILE__) . '/include/common.inc.php');
 $act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list';
 $pid = $_REQUEST['pid'] ? intval($_REQUEST['pid']) : 0;
 $aid = $_REQUEST['aid'] ? intval($_REQUEST['aid']) : '';
 if($act == 'list'){
	$sql = "SELECT area_id, area_indent, area_name, parentid, ishavechild, show_order FROM ".table('area')." WHERE parentid=".$pid." ORDER BY show_order, area_id";
	$area_list = $db->getall($sql);
	template_assign(array('parentid', 'dparentid', 'area_list', 'current_act'),array($pid, get_area_parentid($pid), $area_list, '地区列表'));
	$smarty->display('area.htm');
 }
/**
  *
  * 添加地区界面
  *
  */
 elseif($act=='add'){
 	$area_list = get_area_option(0);
	template_assign(array('area_list', 'current_act', 'act'), array($area_list, '添加新地名', $act));
	$smarty->display('area_info.htm');
 }
 /**
  *
  * 添加地区提交
  *
  */
 elseif($act=='doadd'){
 	$area_name = empty($_POST['area_name']) ? '' : trim($_POST['area_name']);
 	$parentid = intval($_POST['parentid']);
 	$show_order = !empty($_POST['showorder']) ? intval($_POST['showorder']) : '';
 	if($parentid == 0){
 		$area_indent = 0;
 	}else{
 		$area_indent = get_areaindent($parentid)+1;
 	}
	if(empty($area_name)){
		showmsg('地区名称不能为空');
	}
	$sql = "INSERT INTO ".table('area')." (area_id, area_name, parentid, area_indent, ishavechild, show_order ) VALUES ('', '$area_name', '$parentid', '$area_indent', '0', '$show_order')";
	if(!$db->query($sql)){
		showmsg('添加新地区出错', true);
	}else{
		$sql = "UPDATE ".table('area')." SET ishavechild='1' where area_id=$parentid";
		if(!$db->query($sql)){
			showmsg('更新地区出错','area.php', true);
			$db->query("DELETE FROM ".table('area')." WHERE area_id='$area_id'");
		}
		showmsg('添加分类成功','area.php?pid='.$parentid, true);
	}
 }
 /**
  *
  * 编辑地区界面
  *
  */
 elseif($act=='edit'){
	$sql = "SELECT area_id, area_name, parentid, show_order FROM ".table('area')." WHERE area_id = ".$aid;
	$area = $db->getone($sql);
	$area_list = get_area_option(0,$area[parentid], $area[area_id]);
	template_assign(array('area_list', 'area', 'act', 'current_act'), array($area_list, $area, $act, '编辑地区'));
	$smarty->display('area_info.htm');
 }
 /**
  *
  * 编辑地区提交
  *
  */
 elseif($act=='doedit'){
	$aid = intval($_POST['aid']);
	$area_name = empty($_POST['area_name']) ? '' : trim($_POST['area_name']);
 	$parentid = intval($_POST['parentid']);
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['show_order']) : '';
 	if($parentid == 0){
 		$area_indent = 0;
 	}else{
 		$area_indent = get_areaindent($parentid)+1;
 	}
	if(empty($area_name)){
		showmsg('地区名称不能为空');
	}
	$old_parentid = get_area_parentid($aid);
	$sql = "UPDATE ".table('area')." SET area_name = '$area_name', parentid = '$parentid', area_indent = '$area_indent',show_order = '$show_order' WHERE area_id =".$aid;
	if(!$db->query($sql)){
		showmsg('更新地区分类出错','area.php', true);
	}else{
		//更新新上级分类
		if($parentid <> $old_parentid){
			if($parentid != 0){
				$db->query("UPDATE ".table('area')." SET ishavechild = '1' WHERE area_id='$parentid'");
			}
			//更新原上级目录信息
			if(!area_ishavechild($old_parentid)){
				$db->query("UPDATE ".table('area')." SET ishavechild = '0' WHERE area_id = '$old_parentid'");
			}
		}
		showmsg('更新地区分类成功','area.php?pid='.$parentid, true);
	}
 }
 /**
  *
  * 删除地区信息
  *
  */
  elseif($act=='del'){
  	if(ishavechild($aid)){
  		showmsg('该分类有下级分类，不能删除！');
  	}
  	$parentid = get_area_parentid($aid);
  	if(!$db->query("DELETE FROM ".table('area')." WHERE area_id = ".$aid)){
  		showmsg('删除分类出错', true);
  	}else{
  		showmsg('删除分类成功','area.php?pid='.$parentid, true);
  	}
  }




































?>