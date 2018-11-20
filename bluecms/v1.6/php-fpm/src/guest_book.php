<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：guest_book.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';

$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';
$ann_cat = $db->getall("SELECT * FROM ".table('ann_cat')." ORDER BY show_order, cid");

if ($act == 'list')
{
	$guest_total = $db->getfirst("SELECT COUNT(*) FROM ".table('guest_book')." WHERE rid =0");
	include_once BLUE_ROOT.'include/page.class.php';
	$perpage = '10';
 	$page = new page(array('total'=>$guest_total, 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

	$sql = "SELECT a.*, b.user_name, c.add_time AS reply_time, c.content AS reply_content 
			FROM (" . table('guest_book')." AS a 
			LEFT JOIN ".table('user')." AS b 
			ON a.user_id = b.user_id) 
			LEFT JOIN " . table('guest_book')." AS c 
			ON a.id = c.rid 
			WHERE a.rid=0 
			ORDER BY id DESC LIMIT $offset, $perpage";
	$guest_list = $db->getall($sql);
	
	template_assign(
		array(
			'current_act',
			'cat_nav', 
			'add_nav_list', 
			'bot_nav', 
			'user_name',
			'url',
			'guest_list',
			'guest_total',
			'page',
			'page_id',
			'user_id',
			'ann_cat'
		),
		array(
			'留言列表',
			$cat_nav,
			$add_nav_list, 
			$bot_nav, 
			$_SESSION['user_name'],
			base64_encode($url),
			$guest_list,
			$guest_total,
			$page->show(3),
			$currenpage,
			$_SESSION['user_id'],
			$ann_cat
		)
	);
	$smarty->display('guest_book.htm');
}

elseif ($act == 'send')
{
	$user_id = $_SESSION['user_id'] ? $_SESSION['user_id'] : 0;
	$rid = intval($_POST['rid']);
 	$content = !empty($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
 	$content = nl2br($content);
 	if(empty($content))
 	{
 		showmsg('评论内容不能为空');
 	}
	$sql = "INSERT INTO " . table('guest_book') . " (id, rid, user_id, add_time, ip, content) 
			VALUES ('', '$rid', '$user_id', '$timestamp', '$online_ip', '$content')";
	$db->query($sql);
	showmsg('恭喜您留言成功', 'guest_book.php?page_id='.$_POST['page_id']);
}

elseif ($act == 'del')
{
	$id = intval($_GET['id']);
	if (empty($id))
	{
		return false;
	}
	if ($_SESSION['user_id'] != 1) 
	{
		showmsg('您没有删除此留言的权限');
	}
	$db->query("DELETE FROM " . table('guest_book') . " WHERE id=" . $id);
	$db->query("DELETE FROM " . table('guest_book') . " WHERE rid=" . $id);
}

?>