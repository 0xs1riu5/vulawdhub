<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：comment.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
$id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : '';
$type = intval($_REQUEST['type']);
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';

$cat_nav = read_static_cache('cat_list_0');
$add_nav_list = read_static_cache('add_nav');
$bot_nav = read_static_cache('bot_nav');

$smarty->caching = false;

if($act == 'list')
{
	if(empty($id))
	{
		return false;
	}
	if($_CFG['comment_is_check'] == 1)
	{
		$condition = " and a.is_check = 1 ";
	}
	else
	{
		$condition = '';
	}
	if($type == 0)
	{
		$sql = "SELECT a.*, b.user_name, c.title 
				FROM (".table('comment')." AS a 
				LEFT JOIN ".table('user')." AS b ON a.user_id = b.user_id ) LEFT JOIN ".table('post')." AS c ON a.post_id = c.post_id 
				WHERE a.type=0 and a.post_id = ".$id.$condition." 
				ORDER BY pub_date DESC";
		$comment_list = $db->getall($sql);
		$title['post_id'] = $comment_list[0]['post_id'];
		$title['name'] = $comment_list[0]['title'];
		$title['url'] = url_rewrite('post', array('id'=>$comment_list[0]['post_id']));
	}
	elseif($type == 1)
	{
		$sql = "SELECT a.*, b.user_name, c.title 
				FROM (".table('comment')." AS a LEFT JOIN ".table('user')." AS b ON a.user_id = b.user_id) LEFT JOIN ".table('article')." AS c ON a.post_id = c.id 
				WHERE a.type=1 and a.post_id = ".$id.$condition." 
				ORDER BY pub_date DESC";
		$comment_list = $db->getall($sql);
		$title['post_id'] = $comment_list[0]['post_id'];
		$title['name'] = $comment_list[0]['title'];
		$title['url'] = url_rewrite('news', array('id'=>$comment_list[0]['post_id']));
	}
	template_assign(
		array(
			'current_act', 
			'cat_nav', 
			'add_nav_list', 
			'bot_nav', 
			'comment_list', 
			'title', 
			'user_name', 
			'url',
			'type', 
			'cat_option', 
			'area_option'
		), 
		array(
			'评论列表', 
			$cat_nav, 
			$add_nav_list, 
			$bot_nav, 
			$comment_list, 
			$title,
	  		$_SESSION['user_name'], 
	  		base64_encode($url), 
	  		$type, 
	  		get_option(1), 
	  		get_area_option(1)
	  	)
	);
	$smarty->display('comment.htm');
}
elseif($act == 'send')
{
	if(empty($id))
	{
 		return false;
 	}

 	$user_id = $_SESSION['user_id'] ? $_SESSION['user_id'] : 0;
 	$mood = intval($_POST['mood']);
 	$content = !empty($_POST['comment']) ? htmlspecialchars($_POST['comment']) : '';
 	$content = nl2br($content);
 	$type = intval($_POST['type']);
 	if(empty($content))
 	{
 		showmsg('评论内容不能为空');
 	}
 	if($_CFG['comment_is_check'] == 0)
 	{
 		$is_check = 1;
 	}
 	else
 	{
 		$is_check = 0;
 	}

 	$sql = "INSERT INTO ".table('comment')." (com_id, post_id, user_id, type, mood, content, pub_date, ip, is_check) 
 			VALUES ('', '$id', '$user_id', '$type', '$mood', '$content', '$timestamp', '".getip()."', '$is_check')";
 	$db->query($sql);
 	if($type == 1)
 	{
 		$db->query("UPDATE ".table('article')." SET comment = comment+1 WHERE id = ".$id);
 	}
 	elseif($type == 0)
 	{
 		$db->query("UPDATE ".table('post')." SET comment = comment+1 WHERE post_id = ".$id);
 	}
	if($_CFG['comment_is_check'] == 1)
	{
		showmsg('请稍候，您的评论正在审核当中...','comment.php?id='.$id.'&type='.$type);
	}
	else
	{
		showmsg('发布评论成功','comment.php?id='.$id.'&type='.$type);
	}
}

?>