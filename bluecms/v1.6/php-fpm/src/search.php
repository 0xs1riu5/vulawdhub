<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：search.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
require_once BLUE_ROOT . 'include/page.class.php';

$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$add_nav_list = read_static_cache('add_nav');
$bot_nav = read_static_cache('bot_nav');
if($act == 'search'){

}
$keywords = !empty($_REQUEST['keywords']) ? trim($_REQUEST['keywords']) : '';

$cid = !empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : '';
$aid = !empty($_REQUEST['aid']) ? intval($_REQUEST['aid']) : '';

if(empty($keywords))
{
	showmsg('关键字不能为空');
}
$condition = '';
if(!empty($cid) && get_parentid($cid) == 0)
{
 	$condition = " AND cat_id IN(SELECT cat_id FROM ".table('category')." WHERE parentid = ".$cid.")";
}
elseif(!empty($cid) && get_parentid($cid) != 0)
{
 	$condition = " AND cat_id = ".$cid;
}
else
{
 	$condition = '';
}

if(!empty($aid))
{
	$condition .= " AND area_id = ".$aid;
}
else
{
 	$condition .= '';
}

if(!empty($keywords))
{
 	$condition .= " AND title LIKE '%".$keywords."%' OR keywords LIKE '%".$keywords."%' ";
}

$row = $db->getone("SELECT COUNT(*) AS num FROM ".table('post')." WHERE 1=1 ".$condition);
$total = $row['num'];
$perpage = '8';
$page = new page(array('total'=>$total, 'perpage'=>$perpage, 'url'=>"search.php?keywords=$keywords&cid=$cid&aid=$aid"));
$currenpage=$page->nowindex;
$offset=($currenpage-1)*$perpage;

$sql = "SELECT post_id, title, link_man, pub_date, useful_time, click, comment, is_recommend 
		FROM ".table('post').
		" WHERE 1=1 ".$condition.
		" ORDER BY pub_date DESC 
		LIMIT $offset, $perpage";
$search_list = $db->getall($sql);
for($i=0;$i<count($search_list);$i++)
{
	$search_list[$i]['pub_date'] = date("Y-m-d H:i:s",$search_list[$i]['pub_date']);
	//$search_list[$i]['cat_url'] = url_rewrite('category', array('cid'=>$search_list[$i]['cat_id']));
	$search_list[$i]['info_url'] = url_rewrite('post', array('id'=>$search_list[$i]['post_id']));
	$search_list[$i]['title'] = str_replace($keywords, "<font style=\"color:red;\">$keywords</font>", $search_list[$i]['title']);
}

template_assign(
    array(
    	'current_act', 
    	'add_nav_list', 
    	'bot_nav', 
    	'search_list', 
    	'page'
    ), 
    array(
    	'搜索列表', 
        $add_nav_list, 
        $bot_nav, 
        $search_list, 
        $page->show(3), 
    )
);
$smarty -> caching = false;
$smarty -> display('search.htm');


























?>
