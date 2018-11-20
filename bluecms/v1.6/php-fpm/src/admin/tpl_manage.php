<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：tpl_manage.php
 * $author：lucks
 */
 define('IN_BLUE', true);

 require_once(dirname(__FILE__).'/include/common.inc.php');

 $act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';

 if($act == 'list'){
 	$tpl = '';
 	$dir = BLUE_ROOT.'templates/default';
 	if($handle = @opendir($dir)){
 		$i = 0;
 		while(false !== ($file = @readdir($handle))){
 			if($file != 'css' && $file != 'images' && $file != '.' && $file != '..'){
 				$tpl[$i]['name'] = $file;
 				$tpl[$i]['modify_time'] = date('Y-m-d H:i:s',filemtime($dir.'/'.$file));
 				$tpl[$i]['size'] = filesize($dir.'/'.$file);
 				$i++;
 			}
 		}
 	}else{
 		echo '读取模板目录出错，请检查权限';
 		exit;
 	}
 	template_assign(array('current_act', 'tpl_list'), array('前台模板列表', $tpl));
 	$smarty->display('tpl.htm');
 }
 elseif($act == 'edit'){
	$file = $_GET['tpl_name'];
	if(!$handle = @fopen(BLUE_ROOT.'templates/default/'.$file, 'rb')){
		showmsg('打开目标模板文件失败');
	}
	$tpl['content'] = fread($handle, filesize(BLUE_ROOT.'templates/default/'.$file));
	$tpl['content'] = htmlentities($tpl['content'], ENT_QUOTES, GB2312);
	fclose($handle);
	$tpl['name'] = $file;
	template_assign(array('current_act', 'tpl'), array('编辑模板', $tpl));
	$smarty->display('tpl_info.htm');
 }
 elseif($act == 'do_edit'){
 	$tpl_name = !empty($_POST['tpl_name']) ? trim($_POST['tpl_name']) : '';
 	$tpl_content = !empty($_POST['tpl_content']) ? deep_stripslashes($_POST['tpl_content']) : '';
 	if(empty($tpl_name)){
 		return false;
 	}
 	$tpl = BLUE_ROOT.'templates/default/'.$tpl_name;
 	if(!$handle = @fopen($tpl, 'wb')){
		showmsg("打开目标模版文件 $tpl 失败");
 	}
 	if(fwrite($handle, $tpl_content) === false){
 		showmsg('写入目标 $tpl 失败');
 	}
 	fclose($handle);
 	showmsg('编辑模板成功', 'tpl_manage.php');
 }



?>
