<?php
require_once ("admin.inc.php");
$act = $_POST ['act'];
if ($act=='add') {	
	$record = array(
		'title'			=>$_POST ['title'],
		'content'		=>$_POST ['content'],
		'state'			=>$_POST ['state']
	);
	$id = $db->insert('cms_notice',$record);
	header("Location: notice.php");
}

if ($act=='edit'){
	$id = $_POST ['id'];
	$record = array(
		'title'			=>$_POST ['title'],
		'content'		=>$_POST ['content'],
		'state'			=>$_POST ['state']
	);
	 $db->update('cms_notice',$record,'id='.$id);
	 header("Location: notice.php");
}

if ($act=='delete') {	
	$id = $_POST ['id'];
	$db->delete('cms_notice','id in('.$id.')');
	exit();
}

?>