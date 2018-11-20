<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：comment.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';

if($act == 'list')
{
	$perpage = '20';
 	$page = new page(array('total'=>get_total("SELECT COUNT(*) AS num FROM ".table('comment')), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

 	$comment_list = $db->getall("SELECT a.com_id, a.type, a.content, a.pub_date, a.is_check, b.user_name FROM ".table('comment')." AS a LEFT JOIN ".table('user')." AS b ON a.user_id=b.user_id ORDER BY pub_date DESC LIMIT $offset, $perpage");

 	template_assign(array('comment_list', 'act', 'current_act', 'page'), array($comment_list, $act, '评论列表', $page->show(3)));
 	$smarty->display('comment.htm');
}

elseif($act == 'edit')
{
 	if(empty($_GET['com_id']))
 	{
 		return false;
 	}
 	if($_GET['type'] == 0)
 	{
 		$sql = "SELECT a.com_id, a.content, a.is_check, b.title FROM ".table('comment')." AS a LEFT JOIN ".table('post')." AS b ON a.post_id=b.post_id WHERE com_id=".intval($_GET['com_id']);
 	}
 	elseif($_GET['type'] == 1)
 	{
 		$sql = "SELECT a.com_id, a.content, a.is_check, b.title FROM ".table('comment')." AS a LEFT JOIN ".table('article')." AS b ON a.post_id=b.id WHERE com_id=".intval($_GET['com_id']);
 	}
 	$comment = $db->getone($sql);
 	template_assign(
 		array(
 			'comment', 
 			'act', 
 			'current_act'
 		), 
 		array(
 			$comment, 
 			$act, 
 			'编辑评论'
 		)
 	);
 	$smarty->display('comment_info.htm');
}

elseif($act == 'do_edit')
{
 	if(empty($_POST['com_id']))
 	{
 		return false;
 	}
 	$content = !empty($_POST['content']) ? trim($_POST['content']) : '';
 	$content = str_replace(' ', '&nbsp;', str_replace(array('\n', '\r', '\r\n'), '<br/>', $content));
 	$is_check = !empty($_POST['is_check']) ? intval($_POST['is_check']) : 0;
 	$db->query("UPDATE ".table('comment')." SET content='$content', is_check='$is_check' WHERE com_id=".intval($_POST['com_id']));
 	showmsg('编辑评论成功', 'comment.php', true);
}

elseif($act == 'del')
{
 	$com_id = count($_POST['checkboxes']) > 1 ? $_POST['checkboxes'] : $_GET['com_id'];
 	if(empty($com_id))
 	{
 		showmsg('请选择操作对象');
 	}
	if(count($com_id) > 1)
	{
		for($i=0;$i<count($com_id);$i++)
		{
			$db->query("DELETE FROM ".table('comment')." WHERE com_id=".intval($com_id[$i]));
		}
	}
	else
	{
		$db->query("DELETE FROM ".table('comment')." WHERE com_id = ".intval($com_id));
	}
 	showmsg('删除评论成功', 'comment.php');
}
elseif($act == 'check')
{
 	$com_id = $_POST['checkboxes'];
 	if(empty($com_id))
 	{
 		showmsg('请选择操作对象');
 	}
 	for($i=0;$i<count($com_id);$i++)
 	{
 		$db->query("UPDATE ".table('comment')." SET is_check = 1");
 	}
 	showmsg('审核评论成功','comment.php');
}





?>
