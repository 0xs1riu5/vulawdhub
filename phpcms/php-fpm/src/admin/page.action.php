<?php
require_once ("admin.inc.php");
$act = $_POST ['act'];
if ($act=='add') {	
	$record = array(
		'title'				=>$_POST ['title'],
		'code'				=>$_POST ['code'],
		'content'			=>$_POST ['content'],
		'created_date'		=>date("Y-m-d H:i:s")
	);
	$id = $db->insert('cms_page',$record);
	header("Location: page.php");
}

if ($act=='edit'){
	$id = $_POST ['id'];
	$record = array(
		'title'				=>$_POST ['title'],
		'code'				=>$_POST ['code'],
		'content'			=>$_POST ['content']
	);
	 $db->update('cms_page',$record,'id='.$id);
	 header("Location: page.php");
}

if ($act=='delete') {	
	$id = $_POST ['id'];
	$db->delete('cms_page','id in('.$id.')');
	exit();
}

?>