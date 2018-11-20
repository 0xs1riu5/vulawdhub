<?php
/*
 * [Skymps] 版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：model.php
 * $author：lucks
 */
 define('IN_BLUE', true);

 require(dirname(__FILE__).'/include/common.inc.php');
 $act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';
 if($act == 'list'){
 	$model_list = $db->getall("SELECT model_id, model_name, show_order FROM ".table('model')." ORDER BY model_id");
 	template_assign(array('model_list', 'current_act'), array($model_list, '网站模型列表'));
 	$smarty->display('model.htm');
 }
 elseif($act == 'add'){
 	template_assign(array('act', 'current_act'), array($act, '添加新模型'));
 	$smarty->display('model_info.htm');
 }
 elseif($act == 'doadd'){
 	$model_name = !empty($_POST['model_name']) ? trim($_POST['model_name']) : '';
 	$show_order = !isset($_POST['show_order']) ? intval($_POST['show_order']) : '';
 	if($model_name == ''){
 		showmsg('模型名称不能为空');
 	}
 	if(!$db->query("INSERT INTO ".table('model')." (model_id, model_name, show_order) VALUES ('', '$model_name', '$show_order')")){
 		showmsg('插入新模型出错', true);
 	}else{
 		showmsg('插入新模型成功', 'model.php', true);
 	}
 }
 elseif($act == 'edit'){
 	$model = $db->getone("SELECT model_id, model_name, show_order FROM ".table('model')." WHERE model_id=".intval($_GET['model_id']));
 	template_assign(array('model', 'act', 'current_act'), array($model, $act, '编辑模型'));
 	$smarty->display('model_info.htm');
 }
 elseif($act == 'doedit'){
 	$model_name = !empty($_POST['model_name']) ? trim($_POST['model_name']) : '';
 	$show_order = !isset($_POST['show_order']) ? intval($_POST['show_order']) : '';
 	if($model_name == ''){
 		showmsg('模型名称不能为空');
 	}
 	if(!$db->query("UPDATE ".table('model')." SET model_name='$model_name', show_order='$show_order' WHERE model_id=".intval($_POST['model_id']))){
 		showmsg('编辑模型出错', true);
 	}else{
 		showmsg('编辑模型成功', 'model.php', true);
 	}
 }
 elseif($act == 'del'){
 	if(model_has_child($_REQUEST['model_id'])){
 		showmsg('该模型含有栏目，不能删除');
 	}
 	if(!$db->query("DELETE FROM ".table('model')." WHERE model_id=".$_GET['model_id']))
 	{
 		showmsg('删除该模型出错', true);
 	}else{
 		showmsg('删除该模型成功', 'model.php', true);
 	}
 }






















?>
