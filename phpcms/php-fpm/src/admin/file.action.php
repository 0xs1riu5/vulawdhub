<?php
require_once ("admin.inc.php");

$act = $_POST ['act'];

if ($act=='add') {	
	$upload_file = uploadFile('file');//上传图片，返回地址
	header("Location: file.php");
}

if ($act=='delete') {	
	$id = $_POST ['id'];
	$list = $db->getList('select * from cms_file where id in('.$id.')');
	foreach($list as $ls){
		@unlink(dirname(dirname(__FILE__)).'/'.$ls['path']);
	}
	$db->delete('cms_file','id in('.$id.')');
	exit();
}

?>