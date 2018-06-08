<?php
require_once ("admin.inc.php");
$act = $_POST ['act'];

if ($act=='add') {	
	$record = array(
		'name'			=>$_POST ['name'],
		'url'			=>$_POST ['url'],
		'description'	=>$_POST ['description'],
		'logo'			=>$_POST ['logo'],
		'seq'			=>$_POST ['seq']
	);
	$id = $db->insert('cms_friendlink',$record);
	header("Location: friendlink.php");
}

if ($act=='edit'){
	$id = $_POST ['id'];
	$record = array(
		'name'			=>$_POST ['name'],
		'url'			=>$_POST ['url'],
		'description'	=>$_POST ['description'],
		'logo'			=>$_POST ['logo'],
		'seq'			=>$_POST ['seq']
	);
	 $db->update('cms_friendlink',$record,'id='.$id);
	 header("Location: friendlink.php");
}

if ($act=='delete') {	
	$id = $_POST ['id'];
	$db->delete('cms_friendlink','id in('.$id.')');
	exit();
}

?>