<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：cat.fun.php
 * $author：lucks
 */
 if(!defined('IN_BLUE'))
 {
 	die('Access Denied!');
 }

  function check_catname($cat_name, $parentid, $cat_id = ''){
 	global $db;
 	if(strlen($cat_name)<4 || strlen($cat_name)>20) showmsg('分类标题字数(汉字)在2-10之间！');
 	$sql = "SELECT COUNT(*) as catnum FROM ".table('category')." WHERE cat_name='$cat_name' and parentid ='$parentid'";
 	if($cat_id){
 		$sql .= " AND cat_id!='$cat_id'";
 	}
 	$row = $db->getone($sql);
 	if($row['catnum']){
 		showmsg('该分类名已存在！');
 	}
 }
 function get_cat_name($cat_id){
 	global $db;
 	if(!$cat_id){
 		return false;
 	}else{
 		$cat = $db->getone("SELECT cat_name FROM ".table('category')." WHERE cat_id='$cat_id'");
 		return $cat['cat_name'];
 	}
 }
 /**
  *
  * 取得分类缩进
  * $parentid	参数		上级分类编号
  *
  */
 function get_catindent($parentid){
 	global $db;
 	$row = $db->getone("SELECT cat_indent FROM ".table('category')." WHERE cat_id='$parentid'");
 	return $row[cat_indent];
 }

 function get_areaindent($parentid){
	 global $db;
	 $row = $db->getone("SELECT area_indent FROM ".table('area')." WHERE area_id='$parentid'");
	 return $row[area_indent];
 }
 /**
  *
  * 取得分类信息分类父分类id
  * $catid	参数		当前分类号
  *
  */
 function get_parentid($catid){
 	global $db;
 	$cat = $db->getone("SELECT parentid FROM ".table('category')." WHERE cat_id='$catid'");
 	return $cat['parentid'];
 }

 /**
  *
  * 取得新闻分类父类id
  *
  * @param $catid 当前分类ID
  *
  */
 function get_arc_parentid($catid){
 	global $db;
 	$cat = $db->getone("SELECT parent_id FROM ".table('arc_cat')." WHERE cat_id='$cat_id'");
 	return $cat['parent_id'];
 }

 /**
  *
  * 判断当前分类是否有子分类
  *
  */
 function ishavechild($catid){
 	global $db;
 	$result = $db->getone("SELECT COUNT(*) as childnum FROM ".table('category')." WHERE parentid = '$catid'");
 	if($result['childnum']) return true;
 	else return false;
 }

 /**
  *
  * 判断当前地区分类是否有子分类
  *
  */
 function area_ishavechild($area_id){
 	global $db;
 	$result = $db->getone("SELECT COUNT(*) as childnum FROM ".table('area')." WHERE parentid = ".$area_id);
 	if($result['childnum']) return true;
 	else return false;
 }


?>