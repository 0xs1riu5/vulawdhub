<?php
/**
 * [Skymps]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：user.fun.php
 * $author：lucks
 */
 if(!defined('IN_BLUE'))
 {
 	die('Access Denied!');
 }

 /**
  *
  * 登录函数
  *
  * @param $user_name 用户名
  *
  * @param $pwd 用户密码
  *
  */
 function login($user_name,$pwd){
 	global $db;
	$row = $db->getone("SELECT COUNT(*) AS num FROM ".table('user')." WHERE user_name='$user_name'");
	if($row['num']==0){
		$result = 0;
	}else{
		$sql = "SELECT COUNT(*) AS num FROM ".table('user')." WHERE user_name='$user_name' and pwd=md5('$pwd')";
 		$user_num = $db->getone($sql);
 		if($user_num['num']){
 			$result = 1;
 		}else $result = -1;
	}
 	return $result;
 }

 /**
  *
  * 更新用户信息
  *
  * @param $user_name 用户名
  *
  */
 function update_user_info($user_name){
 	global $timestamp, $online_ip, $db;
	$user_info = $db->getone('SELECT user_id, last_login_time, last_login_ip, money FROM '.table('user')." WHERE user_name='$user_name'");
 	$_SESSION['user_id'] = $user_info['user_id'];
 	$_SESSION['user_name'] = $user_name;
	$_SESSION['last_login_time'] = $user_info['last_login_time'];
	$_SESSION['last_login_ip'] = $user_info['last_login_ip'];
	$_SESSION['money'] = $user_info['money'];

	$last_login_time = $timestamp;
	$last_login_ip = $online_ip;
	$sql = "UPDATE ".table('user')." SET last_login_time = '$last_login_time', last_login_ip = '$last_login_ip' WHERE user_id='$_SESSION[user_id]'";
	$db->query($sql);

 }

 /**
  *
  * 更新后台管理员的信息
  *
  * @param $admin_name 管理员名称
  *
  */
 function update_admin_info($admin_name){
 	global $timestamp, $online_ip, $db;
	$admin = $db->getone("SELECT admin_id, admin_name, purview FROM ".table('admin')." WHERE admin_name = '$admin_name'");
 	$_SESSION['admin_id'] = $admin['admin_id'];
 	$_SESSION['admin_name'] = $admin['admin_name'];
 	$_SESSION['admin_purview'] = explode(',', $admin['purview']);
	$user = $db->getone("SELECT user_id, user_name, money FROM ".table('user')." WHERE user_name='$admin[admin_name]'");
	$_SESSION['user_id'] = $user['user_id'];
	$_SESSION['user_name'] = $user['user_name'];
	$_SESSION['money'] = $user['money'];

	$last_login_time = $timestamp;
	$last_login_ip = $online_ip;
	$db->query("UPDATE ".table('user')." SET last_login_time='$last_login_time' WHERE user_id=".$user['user_id']);
	$sql = "UPDATE ".table('admin')." SET last_login_time = '$last_login_time', last_login_ip = '$last_login_ip' WHERE admin_id='$_SESSION[admin_id]'";
	$db->query($sql);
 }

 /**
  *
  * 验证用户名是否可用
  *
  * @param $user_name 用户名
  *
  */
 function check_user_name($user_name){
 	global $db;
 	$user1 = $db->getone("SELECT count(*) AS num FROM ".table('user')." WHERE user_name ='$user_name'");
 	$user2 = $db->getone("SELECT count(*) AS num FROM ".table('admin')." WHERE admin_name ='$user_name'");
 	if($user1['num'] || $user2['num']) return true;
 	else return false;
 }

 /**
  *
  * 验证该用户是否存在
  *
  * @param $user_id 用户ID
  *
  * @param $pwd 用户密码
  *
  */
 function check_user($user_id, $pwd){
 	global $db;
 	$sql = "SELECT pwd FROM ".table('user')." WHERE user_id='$user_id'";
 	$user = $db->getone($sql);
 	if(md5($pwd)==$user['pwd']) return true;
 	else return false;
 }

 /**
  *
  * 验证客户端COOKIE是否正确
  *
  * @param $user_name 用户名
  *
  * @param $pwd 用户密码
  *
  */
 function check_cookie($user_name, $pwd){
 	global $db, $_CFG;
 	$sql = "SELECT pwd FROM ".table('user')." WHERE user_name='$user_name'";
 	$user = $db->getone($sql);
 	if(md5($user['pwd'].$_CFG['cookie_hash']) == $pwd) return true;
 	else return false;
 }

 function edit_pwd($user, $pwd){
 	global $db;
	if(empty($pwd)){
		return false;
	}
 	$sql = "UPDATE ".table('user')." SET pwd=md5('$pwd') WHERE user_id='$user'";
 	$db->query($sql);
 }

 function checknameunique($username)
 {
 	global $db;
 	$sql = "select count(*) AS num FROM ".table('user')." WHERE user_name='$username'";
	$user = $db->getone($sql);
 	if($user['num']>0)
 	{
 		showmsg('该用户名已被使用');
 	}
 }


?>
