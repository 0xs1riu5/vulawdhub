<?php
error_reporting(0);
$DT_ROOT = str_replace("\\", '/', dirname(__FILE__));
$DT_ROOT = substr($DT_ROOT, 0, -10);
$size = isset($_GET['size']) ? $_GET['size'] : '';
$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
$username = isset($_GET['username']) ? trim($_GET['username']) : '';
in_array($size, array('large', 'small')) or $size = 'middle';
$ext = 'x48.jpg';
if($size == 'large') $ext = '.jpg';
if($size == 'small') $ext = 'x20.jpg';
$file = $DT_ROOT.'api/avatar/default'.$ext;
if($userid) {
	$md5 = md5($userid);
	$img = $DT_ROOT.'file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.$userid.$ext;
	if(is_file($img)) $file = $img;
} else if($username) {
	$md5 = md5($username);
	$img = $DT_ROOT.'file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/_'.$username.$ext;
	if(is_file($img) && preg_match("/^[a-z0-9]{1}[a-z0-9_\-]{0,}[a-z0-9]{1}$/", $username)) $file = $img;
}
$file = str_replace($DT_ROOT, '../../', $file);
if(strpos($file, '/default') === false) {
	$remote = file_get_contents($DT_ROOT.'file/avatar/remote.html');
	if(strlen($remote) > 10) $file = str_replace('../../file/', $remote, $file);
}
header('location:'.$file.(isset($_GET['time']) ? '?v='.time() : ''));
?>