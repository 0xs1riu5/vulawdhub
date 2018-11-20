<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：link.php
 * $author：lucks
 */
 define('IN_BLUE', true);

 require_once(dirname(__FILE__) . '/include/common.inc.php');
 require_once(BLUE_ROOT."include/upload.class.php");
 $image = new upload();
 $act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';
 if($act == 'list'){
 	$perpage = '20';
 	$page = new page(array('total'=>get_total("SELECT COUNT(*) AS num FROM ".table('link')), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

 	$linklist = get_list("SELECT * FROM ".table('link'), $offset, $perpage);
 	template_assign(array('linklist', 'current_act', 'page'), array($linklist, '友情链接列表', $page->show(3)));
 	$smarty->display('link.htm');
 }
 elseif($act=='add'){
 	template_assign(array('act', 'current_act'), array($act, '添加新链接'));
 	$smarty->display('link_info.htm');
 }
 elseif($act == 'do_add'){
 	$link_name = !empty($_POST['link_name']) ? trim($_POST['link_name']) : '';
 	$link_site = !empty($_POST['link_site']) ? trim($_POST['link_site']) : '';
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['show_order']) : 0;
	if(isset($_FILES['link_logo']['error']) && $_FILES['link_logo']['error'] == 0){
		$link_logo = $image->img_upload($_FILES['link_logo'],'linklogo');
	}
    $link_logo = empty($link_logo) ? '' : $link_logo;
	$sql = "INSERT INTO ".table('link')."(linkid, linkname, linksite, linklogo, showorder) VALUES ('', '$link_name', '$link_site', '$link_logo', '$show_order')";
	if(!$db->query($sql)){
		showmsg('添加链接失败');
	}else{
		showmsg('添加链接成功','link.php');
	}
 }
 elseif($act == 'edit'){
 	$sql = "SELECT linkid, linkname, linksite, linklogo, showorder FROM ".table('link')." WHERE linkid=".intval($_REQUEST['linkid']);
 	$link = $db->getone($sql);
 	template_assign(array('link', 'act', 'current_act'), array($link, $act, '编辑友情链接'));
 	$smarty->display('link_info.htm');
 }
 elseif($act == 'do_edit'){
 	$link_name = !empty($_POST['link_name']) ? trim($_POST['link_name']) : '';
 	$link_site = !empty($_POST['link_site']) ? trim($_POST['link_site']) : '';
 	$show_order = !empty($_POST['show_order']) ? intval($_POST['show_order']) : 0;
	
	if (!empty($_POST['link_logo'])){
        if (strpos($_POST['link_logo'], 'http://') != false && strpos($_POST['link_logo'], 'https://') != false){
           showmsg('只支持本站相对路径地址');
         }
        else{
           $link_logo = trim($_POST['link_logo']);
        }
    }else{
		if(file_exists(BLUE_ROOT.$_POST['link_logo2'])){
			@unlink(BLUE_ROOT.$_POST['link_logo2']);
		}
	}

	if(isset($_FILES['link_logo1']['error']) && $_FILES['link_logo1']['error'] == 0){
		$link_logo = $image->img_upload($_FILES['link_logo1'],'linklogo');
	}
    $link_logo = empty($link_logo) ? '' : $link_logo;
	$sql = "UPDATE ".table('link')." SET linkname = '$link_name', linksite = '$link_site', linklogo = '$link_logo', showorder = '$show_order' WHERE linkid=".intval($_REQUEST['linkid']);
	if(!$db->query($sql)){
		showmsg('编辑链接失败');
	}else{
		showmsg('编辑链接成功','link.php');
	}
 }
 elseif($act == 'del'){
	if(empty($_GET['linkid'])){
		return false;
	}
	$link = $db->getone("SELECT linklogo FROM ".table('link')." WHERE linkid=".intval($_GET['linkid']));
	if(file_exists(BLUE_ROOT.$link['linklogo'])){
		@unlink(BLUE_ROOT.$link['linklogo']);
	}
 	$sql = "DELETE FROM ".table('link')." WHERE linkid=".intval($_GET['linkid']);
 	if(!$db->query($sql)){
 		showmsg('删除友情链接失败');
 	}else{
 		showmsg('删除友情链接成功','link.php');
 	}
 }

?>
