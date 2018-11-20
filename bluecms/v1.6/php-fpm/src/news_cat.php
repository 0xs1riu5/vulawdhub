<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：news_cat.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
require BLUE_ROOT . 'include/page.class.php';

$cid = !empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : '';
$arc_cat = $db->getone("SELECT cat_id, cat_name, title, keywords, description 
						FROM ".table('arc_cat').
						" WHERE cat_id=".intval($cid));

//设置分页
$perpage = 10;
$page = new page(array('total'=>get_article_total($cid), 'perpage'=>$perpage, 'action'=>'news_cat', 'cid'=>$cid));
$currenpage=$page->nowindex;
$offset=($currenpage-1)*$perpage;

//栏目列表
$cat_nav = read_static_cache('cat_list_0');
$arc_cat_list = read_static_cache('arc_cat_list');
//顶部导航
$add_nav_list = read_static_cache('add_nav');
$bot_nav = read_static_cache('bot_nav');
$rec_news = get_rec_news($cid, 0, $perpage);
$hot_news = get_hot_news($cid, 10);
//底部导航
$cat_option = get_option(1);

$cache_id = $cid.$currenpage;
if(!$smarty->is_cached('news_list.htm', $cache_id))
{
	$news_list = get_news($offset, $perpage, $cid);
	template_assign(
	    array(
	    	'arc_cat', 
	    	'arc_cat_list', 
	    	'news_list', 
	    	'cat_nav', 
	    	'add_nav_list', 
	    	'bot_nav', 
	    	'rec_news', 
	    	'hot_news', 
	    	'cat_option', 
	    	'area_option', 
	    	'page'
	    ), 
	    array(
	        $arc_cat, 
	        $arc_cat_list, 
	 		$news_list, 
	 		$cat_nav, 
	 		$add_nav_list, 
	 		$bot_nav, 
	 		$rec_news, 
	 		$hot_news, 
	 		get_option(1), 
	 		get_area_option(1), 
	 		$page -> show(3)
        )
    );
}
if($cache_set['list_news'])
{
	$smarty->cache_lifetime = $cache_set['list_news_v'];
}
else
{
	$smarty->caching = false;
}

function smarty_block_dynamic($param, $content, &$smarty) 
{
	return $content;
}

$smarty -> register_block('dynamic', 'smarty_block_dynamic', false);

$smarty -> display('news_list.htm', $cache_id);

?>