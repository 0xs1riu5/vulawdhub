<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：sys_user.php Created on 2009-11-16
 * $author：lucks
 */
 define('IN_BLUE', true);

 require_once(dirname(__FILE__) . '/include/common.inc.php');
 $act = !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'list';

 if($act == 'list'){
 	/*if(!check_purview($_SESSION['admin_purview'], 'sys_manage')){
 		showmsg('对不起，您没有此操作的权限');
 	}*/
 	$perpage = '20';
 	$page = new page(array('total'=>get_total("SELECT COUNT(*) AS num FROM ".table('admin')), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

 	$user_list=get_list("SELECT admin_id, admin_name, email, add_time, last_login_time, last_login_ip FROM ".table('admin')." ORDER BY admin_id DESC", $offset, $perpage);

 	template_assign(array('current_act', 'user_list', 'page'), array('系统用户组列表', $user_list, $page->show(3)));

 	$smarty->display('sys_user.htm');
 }
 /*elseif($act == 'add'){
 	if(!check_purview($_SESSION['admin_purview'], 'add_sys_user')){
 		showmsg('对不起，您没有此操作的权限');
 	}
 	template_assign(array('act', 'current_act'), array($act, '添加一个系统用户'));
 	$smarty->display('sys_user_info.htm');
 }
 elseif($act == 'do_add'){
 	if(!check_purview($_SESSION['admin_purview'], 'add_sys_user')){
 		showmsg('对不起，您没有此操作的权限');
 	}
 	$admin_name = !empty($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
 	$pwd = !empty($_POST['pwd']) ? trim($_POST['pwd']) : '';
 	$confirm_pwd = !empty($_POST['confirm_pwd']) ? trim($_POST['confirm_pwd']) : '';
 	$email = !empty($_POST['email']) ? trim($_POST['email']) : '';
 	if(empty($admin_name)){
 		showmsg('用户名不能为空');
 	}
 	if(check_user_name($admin_name)){
 		showmsg('该用户名已被占用');
 	}
 	if(empty($pwd) || strlen($pwd) < 8){
 		showmsg('用户密码不能小于8位');
 	}
 	if(strtolower($pwd) != strtolower($confirm_pwd)){
 		showmsg('两次输入密码不一致');
 	}
 	if(empty($email)){
 		showmsg('电子邮件不能为空');
 	}
 	$sql = "INSERT INTO ".table('admin')." (admin_id, admin_name, email, pwd, add_time) VALUES ('', '$admin_name', '$email', '$pwd', '$timestamp')";
	$db->query($sql);
	showmsg('添加新系统用户成功', 'sys_user.php');
 }*/
 elseif($act == 'edit'){
 	//if(!check_purview($_SESSION['admin_purview'], 'edit_sys_user')){
 	//	showmsg('对不起，您没有此操作的权限');
 	//}
 	if(empty($_GET['admin_id'])){
 		return false;
 	}
 	$user = $db->getone("SELECT admin_id, admin_name, email, pwd FROM ".table('admin')." WHERE admin_id = ".intval($_GET['admin_id']));
 	if($_SESSION['admin_id'] != 1 && $_GET['admin_id'] == 1){
 		showmsg('您不能编辑创始人的资料');
 	}
 	template_assign(array('act', 'current_act', 'user'), array($act, '编辑系统用户资料', $user));
 	$smarty->display('sys_user_info.htm');
 }
 elseif($act == 'do_edit'){
 	//if(!check_purview($_SESSION['admin_purview'], 'edit_sys_user')){
 	//	showmsg('对不起，您没有此操作的权限');
 	//}
 	$admin_name = !empty($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
 	$pwd = !empty($_POST['pwd']) ? trim($_POST['pwd']) : '';
 	$email = !empty($_POST['email']) ? trim($_POST['email']) : '';

 	if(!empty($pwd) && strlen($pwd)<8){
 		showmsg('密码不能少于8位');
 	}
 	if(empty($email)){
 		showmsg('电子邮件不能为空');
 	}
 	if($pwd == ''){
 		$sql = "UPDATE ".table('admin')." SET email='$email' WHERE admin_id=".intval($_POST['admin_id']);
 	}else{
 		$sql = "UPDATE ".table('admin')." SET email='$email', pwd=md5('$pwd') WHERE admin_id=".intval($_POST['admin_id']);
		$db->query("UPDATE ".table('user')." SET email='$email', pwd=md5('$pwd') WHERE user_id=".intval($_POST['admin_id']));
 	}

 	$db->query($sql);
 	showmsg('编辑系统用户资料成功', 'sys_user.php');
 }
 /*elseif($act == 'del'){
 	if(!check_purview($_SESSION['admin_purview'], 'del_sys_user')){
 		showmsg('对不起，您没有此操作的权限');
 	}
 	if(empty($_GET['admin_id'])){
 		return false;
 	}
 	$sql = "DELETE FROM ".table('admin')." WHERE admin_id=".intval($_GET['admin_id']);
 	$db->query($sql);
 	showmsg('删除一个系统用户成功', 'sys_user.php');
 }

elseif($act == 'get_purview'){
	if(empty($_GET['admin_id'])){
		return false;
	}
	$purview = $db->getone("SELECT purview FROM ".table('admin')." WHERE admin_id=".intval($_GET['admin_id']));
	print_r($purview);
}*/


?>
