<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：news.php
 * $author：lucks
 */
 define('IN_BLUE', true);

 require_once(dirname(__FILE__) . '/include/common.inc.php');
 require_once(BLUE_ROOT.'include/page.class.php');
 $id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : '';
 $page_id = !empty($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : '';

 //导航条
 $cat_nav = read_static_cache('cat_list_0');

 $add_nav_list = read_static_cache('add_nav');
 //底部导航列表
 $bot_nav = read_static_cache('bot_nav');

 $rec_news = get_rec_news('', 0, 10);

 $hot_news = get_hot_news('', 10);

 function smarty_block_dynamic($param, $content, &$smarty) {
	return $content;
 }

 $smarty->register_block('dynamic', 'smarty_block_dynamic', false);

 $cache_id = $id.$page_id;
 if(empty($id)){
 	if(!$smarty->is_cached('news_list.htm', $cache_id)){
		$perpage = 10;
	 	$page = new page(array('total'=>get_article_total(), 'perpage'=>$perpage, 'action'=>'news'));
	 	$currenpage=$page->nowindex;
		$offset=($currenpage-1)*$perpage;

		$news_list = get_news($offset, $perpage);

	 	template_assign(array('current_act', 'news_list', 'cat_nav', 'add_nav_list', 'bot_nav', 'rec_news', 'hot_news', 'cat_option', 'area_option', 'page'), array('本地新闻',
	 					$news_list, $cat_nav, $add_nav_list, $bot_nav, $rec_news, $hot_news, get_option(1), get_area_option(1), $page->show(3)));
 	}
	if($cache_set['list_news']){
		$smarty->cache_lifetime = $cache_set['list_news_v'];
	}else{
		$smarty->caching = false;
	}
 	$smarty->display('news_list.htm',$cache_id);
 }
 elseif(!empty($id)){
 	if(!$smarty->is_cached('news_list.htm', $cache_id)){
		$news = $db->getone("SELECT id, title, color, author, source, pub_date, content, click, comment, is_check FROM ".table('article')." WHERE id = ".$id);
		if ($news['is_check'] == 0) {
			showmsg('对不起，该新闻正在审核当中，请浏览其他新闻', url_rewrite('news_cat',array()));
		}
	 	$comment_list = $db->getall("SELECT a.*, b.user_name FROM ".table('comment')." AS a LEFT JOIN ".table('user')." AS b ON a.user_id = b.user_id WHERE a.type=1 and a.post_id = ".$id." ORDER BY pub_date DESC LIMIT 5");	 	

	 	$location = array('name1'=>'本地新闻', 'url1'=>url_rewrite('news_cat', array()), 'name2'=>$news['title']);
	 	template_assign(array('current_act', 'news', 'cat_nav', 'add_nav_list', 'bot_nav', 'rec_news', 'hot_news', 'location', 'user_name',
	 					 'url', 'comment_list', 'cat_option', 'area_option'), array('本地新闻',$news, $cat_nav, $add_nav_list, $bot_nav, $rec_news, $hot_news, $location,
	 					 $_SESSION['user_name'], base64_encode($url), $comment_list, get_option(1), get_area_option(1)));
 	}else{
		$comment_list = $db->getall("SELECT a.*, b.user_name FROM ".table('comment')." AS a LEFT JOIN ".table('user')." AS b ON a.user_id = b.user_id WHERE a.type=1 and a.post_id = ".$id." ORDER BY pub_date DESC LIMIT 5");

		$news = $db->getone("SELECT id, title, color, author, source, pub_date, content, click, comment FROM ".table('article').
				" WHERE id = ".$id);
		template_assign(array('news', 'url', 'comment_list', 'user_name'),array($news, base64_encode($url), $comment_lsit, $_SESSION['user_name']));
	}

	if($cache_set['news']){
		$smarty->cache_lifetime = $cache_set['news_v'];
	}else{
		$smarty->caching = false;
	}
 	$smarty->display('news.htm', $cache_id);
	$db->query("UPDATE ".table('article')." SET click = click+1 WHERE id = ".$id);
 }
?>
