<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：category.php
 * $author：lucks
 */
 define('IN_BLUE', true);
 require_once(dirname(__FILE__) . '/include/common.inc.php');
 $act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list';
 $pid = $_REQUEST['pid'] ? intval($_REQUEST['pid']) : 0;
 $cid = $_REQUEST['cid'] ? intval($_REQUEST['cid']) : '';
 if($act == 'list'){
	$sql = "SELECT cat_id, cat_indent, cat_name, model, is_havechild, a.show_order, model_name FROM ".table('category')." AS a LEFT JOIN ".table('model')." AS b ON model=model_id WHERE parentid=".$pid." ORDER BY a.show_order, cat_id";
	$cat_list = $db->getall($sql);
	template_assign(array('parentid', 'dparentid', 'cat_list', 'current_act'),array($pid, get_parentid($pid), $cat_list, '栏目列表'));
	$smarty->display('category.htm');
 }
 /**
  *
  * 添加栏目界面
  *
  */
 elseif($act=='add'){
 	$cat_list = get_option(0);
 	$model_list = model();
	template_assign(array('cat_list', 'model_list', 'current_act', 'act'), array($cat_list, $model_list, '添加新栏目', $act));
	$smarty->display('category_info.htm');
 }
 /**
  *
  * 添加栏目提交
  *
  */
 elseif($act=='do_add'){
 	$cat_name = trim($_POST['cat_name']);
 	$title_color = !empty($_POST['title_color']) ? $_POST['title_color'] : '';
 	$parentid = intval($_POST['parentid']);
 	$model = intval($_POST['model']);
 	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
 	$keywords = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
 	$description = !empty($_POST['description']) ? trim($_POST['description']) : '';
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['show_order']) : '';
 	if($parentid == 0){
 		$cat_indent = 0;
 	}else{
 		$cat_indent = get_catindent($parentid)+1;
 	}
	check_catname($cat_name, $parentid);
	if(empty($model)){
		showmsg('模型不能为空');
	}
	$sql = "INSERT INTO ".table('category')." (cat_id, cat_name, title_color, parentid, model, title, keywords, description, cat_indent, is_havechild, show_order ) VALUES ('', '$cat_name', '$title_color', '$parentid', '$model', '$title', '$keywords', '$description', '$cat_indent', '0', '$show_order')";
	if(!$db->query($sql)){
		showmsg('添加栏目出错', true);
	}else{
		$sql = "UPDATE ".table('category')." SET is_havechild='1' where cat_id=$parentid";
		if(!$db->query($sql)){
			showmsg('更新栏目出错','category.php', true);
			$db->query("DELETE FROM ".table('category')." WHERE cat_id=$cat_id");
		}
		showmsg('添加栏目成功','category.php?pid='.$parentid, true);
	}
 }
 /**
  *
  * 编辑栏目界面
  *
  */
 elseif($act=='edit'){
	$sql = "SELECT cat_id, cat_name, title_color, parentid, model, title, keywords, description, show_order FROM ".table('category')." WHERE cat_id = $cid";
	$cat = $db->getone($sql);
	$cat_list = get_option(0,$cat[parentid], $cat[catid]);
	$model_list = model();
	template_assign(array('cat_list', 'model_list', 'cat', 'act', 'current_act'), array($cat_list, $model_list, $cat, $act, '编辑栏目'));
	$smarty->display('category_info.htm');
 }
 /**
  *
  * 编辑栏目提交
  *
  */
 elseif($act=='do_edit'){
	$cid = intval($_POST['cid']);
	$cat_name = trim($_POST['cat_name']);
 	$title_color = !empty($_POST['title_color']) ? $_POST['title_color'] : '';
 	$parentid = intval($_POST['parentid']);
 	$model = intval($_POST['model']);
 	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
 	$keywords = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
 	$description = !empty($_POST['description']) ? trim($_POST['description']) : '';
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['show_order']) : '';
 	if($parentid == 0){
 		$cat_indent = 0;
 	}else{
 		$cat_indent = get_catindent($parentid)+1;
 	}
	check_catname($cat_name, $parentid, $cid);
	if(empty($model)){
		showmsg('模型不能为空');
	}
	$old_parentid = get_parentid($cid);
	$sql = "UPDATE ".table('category')." SET cat_name = '$cat_name', title_color = '$title_color', parentid = '$parentid', model = '$model', title = '$title', keywords = '$keywords', description = '$description', cat_indent = '$cat_indent',show_order = '$show_order' WHERE cat_id =$cid";
	if(!$db->query($sql)){
		showmsg('更新栏目出错','category.php?pid='.$parentid, true);
	}else{
		//更新新上级栏目
		if($parentid <> $old_parentid){
			if($parentid != 0){
				$db->query("UPDATE ".table('category')." SET is_havechild = '1' WHERE cat_id=$parentid");
				//更新原上级目录信息
				if(!ishavechild($old_parentid)){
				$db->query("UPDATE ".table('category')." SET is_havechild = '0' WHERE cat_id = $old_parentid");
				}
			}else{
				if(!ishavechild($old_parentid)){
					$db->query("UPDATE ".table('category')." SET is_havechild = '0' WHERE cat_id = $old_parentid");
				}
			}
		}
		showmsg('更新栏目成功','category.php', true);
	}
 }
 /**
  *
  * 删除栏目
  *
  */
  elseif($act=='del'){
  	if(ishavechild($cid)){
  		showmsg('该栏目有下级栏目，不能删除！');
  	}
  	$parentid = get_parentid($cid);
  	if(!$db->query("DELETE FROM ".table('category')." WHERE cat_id = $cid")){
  		showmsg('删除栏目出错', true);
  	}else{
		if(!ishavechild($parentid)){
			$db->query("UPDATE ".table('category')." SET is_havechild = 0 WHERE cat_id=".$parentid);
		}
  		showmsg('删除栏目成功','category.php?pid='.$parentid, true);
  	}
  }

?>
