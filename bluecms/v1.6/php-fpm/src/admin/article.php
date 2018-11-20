<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：article.php
 * $author：lucks
 */
 define('IN_BLUE', true);

 require_once(dirname(__FILE__) . '/include/common.inc.php');
 require_once(BLUE_ROOT.'include/upload.class.php');
 require_once(BLUE_ROOT.'include/index.fun.php');
 $image = new upload();
 $act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';

 $cid = !empty($_GET['cid']) ? intval($_GET['cid']) : '';
 if($act == 'list'){
 	if(empty($cid)){
 		return false;
 	}
 	$perpage = '20';
 	$page = new page(array('total'=>get_total("SELECT COUNT(*) AS num FROM ".table('article')." WHERE cid=".$cid), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

 	$article_list = get_news($offset, $perpage, $cid, true);
 	template_assign(array('current_act', 'article_list', 'page'), array('新闻列表', $article_list, $page->show(3)));
 	$smarty->display('article.htm');
 }

 elseif($act == 'add'){
 	$cat_option = get_arc_cat(0);
	create_editor('content', '', array('BasePath' => '../include/fckeditor/'));
 	template_assign(array('cat_option', 'act', 'current_act'), array($cat_option,$act, '发布新闻'));
 	$smarty->display('article_info.htm');
 }

 elseif($act == 'do_add'){
 	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
 	$color = !empty($_POST['color']) ? trim($_POST['color']) : '';
 	$cid = !empty($_POST['cid']) ? intval($_POST['cid']) : '';
 	if(empty($cid)){
 		showmsg('新闻分类不能为空');
 	}
 	$author = !empty($_POST['author']) ? trim($_POST['author']) : $_SESSION['admin_name'];
 	$source = !empty($_POST['source']) ? trim($_POST['source']) : '';
	$content = !empty($_POST['content']) ? trim($_POST['content']) : '';
	$descript = !empty($_POST['descript']) ? mb_substr($_POST['descript'], 0, 250) : mb_substr(html2text($_POST['content']),0, 250);
 	if(isset($_FILES['lit_pic']['error']) && $_FILES['lit_pic']['error'] == 0){
		$lit_pic = $image->img_upload($_FILES['lit_pic'],'lit_pic');
	}
    $lit_pic = empty($lit_pic) ? '' : $lit_pic;
	if(!empty($lit_pic)){
		$lit_pic = $image->small_img($lit_pic, 200, 115);
    }
 	$is_recommend = intval($_POST['is_recommend']);
 	$is_check = intval($_POST['is_check']);

 	if($title == ''){
 		showmsg('新闻标题不能为空');
 	}
 	if($content == ''){
 		showmsg('新闻内容不能为空');
 	}

 	$sql = "INSERT INTO ".table('article')." (id, cid, user_id, title, color, author, source, pub_date, lit_pic, 
 	descript, content, click, comment, is_recommend, is_check) VALUES ('', '$cid', '$_SESSION[user_id]', 
 	'$title', '$color', '$author', '$source', '$timestamp', '$lit_pic', '$descript', '$content', '0', '0', 
 	'$is_recommend', '$is_check')";
 	$db->query($sql);
 	showmsg('发布新闻成功', 'article.php?cid='.$cid);
 }

 elseif($act == 'edit'){
 	$article = $db->getone("SELECT id, cid, title, color, author, source, lit_pic, descript, content, is_recommend,									is_check						
							FROM ".table('article')." 
							WHERE id = ".intval($_GET['id']));
 	create_editor('content', $article['content'], array('BasePath' => '../include/fckeditor/'));
 	$cat_option = get_arc_cat(0, $article['cid']);
 	template_assign(array('act', 'current_act', 'article', 'cat_option'), array($act, '编辑本地新闻', $article, $cat_option));
 	$smarty->display('article_info.htm');
 }

 elseif($act == 'do_edit'){
 	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
 	$color = !empty($_POST['color']) ? trim($_POST['color']) : '';
 	$cid = !empty($_POST['cid']) ? intval($_POST['cid']) : '';
 	if(empty($cid)){
 		showmsg('新闻分类不能为空');
 	}
 	$author = !empty($_POST['author']) ? trim($_POST['author']) : $_SESSION['admin_name'];
 	$source = !empty($_POST['source']) ? trim($_POST['source']) : '';
 	$is_recommend = intval($_POST['is_recommend']);
 	$is_check = intval($_POST['is_check']);

 	if((!empty($_POST['lit_pic1']) && !empty($_FILES['lit_pic2']['name'])) || !empty($_FILES['lit_pic2']['name']))
	{
		if (file_exists(BLUE_ROOT . $_POST['lit_pic1']))
		{
			@unlink(BLUE_ROOT . $_POST['lit_pic1']);
		}
 		if($_FILES['lit_pic2']['error'] == 0)
		{
			$lit_pic = $image->img_upload($_FILES['lit_pic2'],'lit_pic');
		}
	    $lit_pic = empty($lit_pic) ? '' : $lit_pic;
		if(!empty($lit_pic)){
			$lit_pic = $image->small_img($lit_pic, 200, 115);
	    }
 	}else{
 		$lit_pic = !empty($_POST['lit_pic1']) ? $_POST['lit_pic1'] :'';
 	}
 	$content = !empty($_POST['content']) ? trim($_POST['content']) : '';
	$descript = !empty($_POST['descript']) ? mb_substr($_POST['descript'], 0, 250) : mb_substr(html2text($_POST['content']),0, 250);

 	if($title == ''){
 		showmsg('新闻标题不能为空');
 	}
 	if($content == ''){
 		showmsg('新闻内容不能为空');
 	}

 	$sql = "UPDATE ".table('article')." SET cid='$cid', title='$title', color='$color', author='$author', 
 	source='$source', lit_pic='$lit_pic', descript='$descript', content='$content', 
 	is_recommend='$is_recommend', is_check='$is_check' WHERE id=".intval($_POST['id']);
 	$db->query(($sql));
 	showmsg('编辑新闻成功', 'article.php?cid='.$cid);
 }

 elseif($act == 'del'){
	$article = $db->getone("SELECT cid, lit_pic FROM ".table('article')." WHERE id=".$_GET['id']);
 	$sql = "DELETE FROM ".table('article')." WHERE id=".intval($_GET['id']);
 	$db->query($sql);
 	if (file_exists(BLUE_ROOT.$article['lit_pic'])) {
 		@unlink(BLUE_ROOT.$article['list_pic']);
 	}
 	showmsg('删除本地新闻成功', 'article.php?cid='.$article['cid']);
 }



?>
