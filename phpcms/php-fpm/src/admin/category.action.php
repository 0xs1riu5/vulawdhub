<?php
require_once ("admin.inc.php");
$act = $_POST['act'];

if($act=='add'){
	$pid = $_POST['pid'];
	$record = array(
		'pid'		=>$_POST ['pid'],
		'name'		=>$_POST ['name'],		
		'seq'		=>$_POST ['seq']
	);
	$id = $db->insert('cms_category',$record);
	header("Location: category.php");
}
if ($act=='edit'){
	$pid = $_POST['pid'];
	$id  = $_POST['cid'];
	$record = array(
		'pid'		=>$_POST ['pid'],
		'name'		=>$_POST ['name'],		
		'seq'		=>$_POST ['seq']
	);
	$db->update('cms_category',$record,'id='.$id);
	header("Location: category.php");
}

if ($act=='delete'){
	$id = $_POST['id'];
	$ids = getAllCatetoryIds($id);
	$db->delete('cms_category','id in('.$ids.')');
	$db->update('cms_article',array('delete_session_id'=>$_COOKIE['userid']),'cid in('.$ids.')');
	exit(1);
}


//递归，返回所有子节点 1，2，3，
function getAllChildCategoryIds($id,&$ids=''){
	global $db;
	$list = $db->getList("select id from cms_category where pid=".$id);
	foreach($list as $ls){
		$ids = empty($ids)?$ls['id']:$ids .','.$ls['id'];
		getAllChildCategoryIds($ls['id'],$ids);
	}
	return $ids;
}
function getAllCatetoryIds($id){
	$ids = getAllChildCategoryIds($id);
	return empty($ids)?$id:$id.','.$ids;
}
?>