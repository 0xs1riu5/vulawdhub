<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：post.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
require_once BLUE_ROOT . 'include/index.fun.php';

$cat_option = get_option(1);
//地区列表
$area_option = get_area_option(1);
$add_nav_list = read_static_cache('add_nav');
$bot_nav = read_static_cache('bot_nav');
 
//最新信息
$info_arr = get_info('', 0, 5);
//推荐信息
$rec_info = get_rec_info('', 8);
//热门信息
$hot_info = get_hot_info('', 10);
//图文信息
$info_pic		= array();
$info_title	= array();
$sql = "SELECT post_id, title, lit_pic, pub_date 
		FROM " . table('post').
		" WHERE lit_pic != '' 
		ORDER BY pub_date DESC 
		LIMIT 9";
$result = $db -> query($sql);
while ($row = $db -> fetch_array($result))
{
	$row['url'] = url_rewrite('post', array('id'=>$row['post_id']));
	$row['pub_date'] = date("Y-m-d", $row['pub_date']);
	$info_title[] = $row;
}
if (count($info_title) > 6)
{
	$count = 6;
}
else 
{
	$count = count($info_title);
}
for ($i = 0; $i < $count; $i++) 
{
    $info_pic[$i] = $info_title[$i];
}

$head_line_list = get_head_line('info', 8);

template_assign(
	array(
		'add_nav_list',
		'bot_nav',
		'cat_option',
		'area_option',
		'info_arr',
		'rec_info',
		'hot_info',
		'index_info', 
		'info_pic', 
		'info_title',
		'head_line_list'
	), 
	array(
		$add_nav_list,
		$bot_nav,
		$cat_option,
		$area_option,
		$info_arr,
		$rec_info,
		$hot_info,
		get_index_info(),
		$info_pic,
		$info_title,
		$head_line_list
	)
);

function smarty_block_dynamic($param, $content, &$smarty) 
{
	return $content;
}

$smarty -> register_block('dynamic', 'smarty_block_dynamic', false);

$smarty -> display('info_index.htm');

?>