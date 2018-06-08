<?php
require_once ("admin.inc.php");
$act = $_POST ['act'];

//验证留言
if ($act=='validate'){
	$id = $_POST ['id'];
	$record = array(
		'validate'			=>$_POST ['validate']
	);
	 $db->update('cms_message',$record,'id='.$id);
	 exit();
}

//管理员回复
if ($act=='reply'){
	$id = $_POST ['id'];
	$record = array(
		'reply'			=>$_POST ['reply']
	);
	 $db->update('cms_message',$record,'id='.$id);
	 exit();
}

if ($act=='delete') {	
	$id = $_POST ['id'];
	$db->delete('cms_message','id in('.$id.')');
	exit();
}

?>