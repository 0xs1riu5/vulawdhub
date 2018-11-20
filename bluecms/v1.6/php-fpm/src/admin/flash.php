<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：flash.php
 * $author：lucks
 */
 define('IN_BLUE', true);
 require_once(dirname(__FILE__) . '/include/common.inc.php');
 require_once(BLUE_ROOT ."include/upload.class.php");
 $image = new upload();
 $act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list';
 if($act == 'list'){
 	$flash_list = $db->getall("SELECT image_id, image_path, image_link, show_order FROM ".table('flash_image'));
 	template_assign(array('flash_list', 'current_act'),array($flash_list, 'Flash图片列表'));
 	$smarty->display('flash.htm');
 }
 elseif($act == 'add'){
 	template_assign(array('act', 'current_act'), array($act, '添加新flash图片'));
	$smarty->display('flash_info.htm');
 }
 elseif($act == 'do_add'){
 	$image_link = !empty($_POST['image_link']) ? trim($_POST['image_link']) : '';
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['showorder']) : '';
 	if(isset($_FILES['image_path']['error']) && $_FILES['image_path']['error'] == 0){
		$image_path = $image->img_upload($_FILES['image_path'],'flash');
	}
	if($image_path == ''){
		showmsg('上传图片出错', true);
	}
    $image_path = empty($image_path) ? '' : $image_path;
    if(!$db->query("INSERT INTO ".table('flash_image')." (image_id, image_path, image_link, show_order) VALUES ('', '$image_path', '$image_link', '$show_order')")){
    	showmsg('添加flash图片出错', true);
    }else{
    	showmsg('添加flash图片成功', 'flash.php', true);
    }
 }
 elseif($act == 'edit'){
	if(empty($_GET['image_id'])){
		return false;
	}
 	$flash = $db->getone("SELECT image_id, image_path, image_link, show_order FROM ".table('flash_image')." WHERE image_id =".intval($_GET['image_id']));
	template_assign(array('flash', 'act', 'current_act'), array($flash, $act, '编辑Flash图片'));
	$smarty->display('flash_info.htm');
 }
 elseif($act == 'do_edit'){
	 if(empty($_POST['image_id'])){
		return false;
	}
 	$image_link = !empty($_POST['image_link']) ? trim($_POST['image_link']) : '';
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['show_order']) : '';
	$image_path = !empty($_POST['image_path']) ? trim($_POST['image_path']) : '';
	if (!empty($_POST['image_path'])){
        if (strpos($_POST['image_path'], 'http://') != false && strpos($_POST['image_path'], 'https://') != false){
           showmsg('只支持本站相对路径地址');
         }
        else{
           $link_logo = trim($_POST['image_path']);
        }
    }else{
		if(file_exists(BLUE_ROOT.$_POST['image_path2'])){
			@unlink(BLUE_ROOT.$_POST['image_path2']);
		}
	}

 	if(isset($_FILES['image_path1']['error']) && $_FILES['image_path1']['error'] == 0){
		$image_path = $image->img_upload($_FILES['image_path1'],'flash');
		
	}

    $image_path = empty($image_path) ? '' : $image_path;
    if(!$db->query("UPDATE ".table('flash_image')." SET image_path='$image_path', image_link='$image_link', show_order='$show_order' WHERE image_id=".intval($_POST['image_id']))){
    	showmsg('更新flash图片出错', true);
    }else{
    	showmsg('更新flash图片成功', 'flash.php', true);
    }
 }
elseif($act == 'del'){
	if(empty($_GET['image_id'])){
		return false;
	}
	$flash = $db->getone("SELECT image_path FROM ".table('flash_image')." WHERE image_id =".intval($_GET['image_id']));
	if(file_exists(BLUE_ROOT.$flash['image_path'])){
		@unlink(BLUE_ROOT.$flash['image_path']);
	}
	if(!$db->query("DELETE FROM ".table('flash_image')." WHERE image_id = ".intval($_GET['image_id']))){
  		showmsg('删除Flash图片出错', true);
  	}else{
  		showmsg('删除Flash图片成功！','flash.php', true);
  	}
}



?>
