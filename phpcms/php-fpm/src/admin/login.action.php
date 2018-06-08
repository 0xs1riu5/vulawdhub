<?php
session_start ();
header('Content-Type: text/html; charset=utf-8');
include_once ("../include/config.inc.php");
if (isset ( $_POST ["username"] )) {
	$username = $_POST ["username"];
} else {
	$username = "";
}
if (isset ( $_POST ["password"] )) {
	$password = $_POST ["password"];
} else {
	$password = "";
}
//记住用户名
setcookie (username, $username,time()+3600*24*365);
if (empty($username)||empty($password)){
	exit("<script>alert('用户名或密码不能为空！');window.history.go(-1)</script>");
}
$user_row = $db->getOneRow("select userid from cms_users where username = '".$username."' and password='".md5 ( $password ) ."'");
if (!empty($user_row )) {
	setcookie (userid, $user_row ['userid'] ); 
	header("Location: index.php");
}else{
	exit("<script>alert('用户名或密码不正确！');window.history.go(-1)</script>");
}
?>
