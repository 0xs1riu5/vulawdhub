<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：user.php
 * $author：lucks
 */
 define('IN_BLUE', true);

 require(dirname(__FILE__) . '/include/common.inc.php');
 $act = !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'list';

 if($act=='list')
 {
 	$perpage = '20';
 	$page = new page(array('total'=>get_total("SELECT COUNT(*) AS num FROM ".table('user')), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

 	$user_list=get_list("SELECT * FROM ".table('user')." ORDER BY reg_time DESC", $offset, $perpage);

 	template_assign(array('user_list', 'page'), array($user_list, $page->show(3)));

 	$smarty->display('user.htm');
 }
 elseif($act == 'do_add')
 {
 	$username = !empty($_POST['username']) ? trim($_POST['username']) : '' ;
 	$password = !empty($_POST['password']) ? trim($_POST['password']) : '' ;
 	$confirm_password = !empty($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '' ;
 	$email = !empty($_POST['email']) ? trim($_POST['email']) : '' ;
 	if($password != $confirm_password)
 	{
 		showmsg('两次输入密码不同');
 	}
 	if($username == '' || $password == '')
 	{
 		showmsg('信息填写不完整！');
 	}
 	checknameunique($username);
 	$sql = "INSERT INTO ".table('user')." (user_id,user_name,pwd,email,reg_time) VALUES ('','$username',md5('$password'),'$email','$timestamp')";
 	$db->query($sql);
 	showmsg('添加会员成功','user.php');
 }
 elseif($act == 'add')
 {
 	template_assign(array('act', 'current_act'), array($act, '添加新会员'));
 	$smarty->display('user_info.htm');
 }
 elseif($act == 'do_edit')
 {
 	$password = !empty($_POST['password']) ? trim($_POST['password']) : '' ;
 	$email = !empty($_POST['email']) ? trim($_POST['email']) : '' ;
 	$sex = !empty($_POST['sex']) ? intval($_POST['sex']) : '' ;
 	$birthday = $_POST['birthdayYear'].'-'.$_POST['birthdayMonth'].'-'.$_POST['birthdayDay'];
 	$address = !empty($_POST['address']) ? trim($_POST['address']) : '' ;
 	$msn = !empty($_POST['msn']) ? trim($_POST['msn']) : '' ;
 	$qq = !empty($_POST['qq']) ? trim($_POST['qq']) : '' ;
 	$office_phone = !empty($_POST['office_phone']) ? trim($_POST['office_phone']) : '' ;
 	$home_phone = !empty($_POST['home_phone']) ? trim($_POST['home_phone']) : '' ;
 	$mobie_phone = !empty($_POST['mobie_phone']) ? trim($_POST['mobie_phone']) : '' ;
	$money = !empty($_POST['money']) ? $_POST['money'] : '0.00';

 	if(!empty($password)){
		$sql = "UPDATE ".table('user')." SET pwd=md5('$password'),email='$email',sex='$sex',birthday='$birthday',address='$address',
 			msn='$msn',qq='$qq',office_phone='$office_phone',home_phone='$home_phone',mobile_phone='$mobile_phone', money='$money' WHERE user_id=".intval($_POST['user_id']);
		if (check_admin_name(get_name(intval($_POST['user_id']))))
		{
			$db->query("UPDATE " . table('admin') . " SET pwd=md5('$password') WHERE admin_id=" . intval($_POST['user_id']));
		}
	}else{
		$sql = "UPDATE ".table('user')." SET email='$email',sex='$sex',birthday='$birthday',address='$address',
 			msn='$msn',qq='$qq',office_phone='$office_phone',home_phone='$home_phone',mobile_phone='$mobile_phone',money='$money' WHERE user_id=".intval($_POST['user_id']);
	}
 	$db->query($sql);
	if(defined('UC_API') && @include_once(BLUE_ROOT.'uc_client/client.php') && !empty($password)){
		$ucresult = uc_user_edit($_SESSION['user_name'], $old_pwd, $new_pwd, '');
		echo $ucresult;
	}
 	showmsg('编辑会员信息成功','user.php');
 }
 elseif($act == 'edit')
 {
 	$sql = "select * from ".table('user')." where user_id = ".$_GET['user_id'];
 	$user = $db->getone($sql);
 	$sexarr=array('0'=>'保密','1'=>'男','2'=>'女');
 	template_assign(array('sexarr', 'user', 'act', 'current_act'), array($sexarr, $user, $act, '编辑会员信息'));
 	$smarty->display('user_info.htm');
 }
 elseif($act == 'del'){
 	$user = $db->getone("SELECT user_name FROM ".table('user')." WHERE user_id = ".intval($_GET['user_id']));
	 template_assign(array('act', 'current_act', 'user_id', 'user_name'), array($act, '删除用户', intval($_GET['user_id']), $user['user_name']));
	 $smarty->display('user_info.htm');
 }
 elseif($act == 'do_del')
 {
 	if(empty($_POST['user_id'])){
 		return false;
 	}
 	$db->query("DELETE FROM ".table('user')." WHERE user_id = ".$_POST['user_id']);
	if($_POST['del_info']){
		$db->query("DELETE FROM ".table('post')." WHERE user_id = ".$_POST['user_id']);
	}
	if($_POST['del_coment']){
		$db->query("DELETE FROM ".table('comment')." WHERE user_id = ".$_POST['user_id']);
	}
	if(defined('UC_API') && @include_once(BLUE_ROOT.'uc_client/client.php')){
		echo uc_user_delete($_POST['username']);
	}
 	showmsg('删除会员成功','user.php');

 }
?>