<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：ann.php
 * $author：lucks
 */
define('IN_BLUE', true);
require_once dirname(__FILE__) . '/include/common.inc.php';
require_once BLUE_ROOT . 'include/page.class.php';

$ann_id = !empty($_REQUEST['ann_id']) ? intval($_REQUEST['ann_id']) : '';
$cid = !empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : 1;

$hot_ann = get_hot_ann(10);

//导航条
$cat_nav = read_static_cache('cat_list_0');

$add_nav_list = read_static_cache('add_nav');
//底部导航列表
$bot_nav = read_static_cache('bot_nav');
$ann_cat = $db->getall("SELECT * FROM ".table('ann_cat')." ORDER BY show_order, cid");

if(empty($ann_id))
{
 	$perpage = 10;
 	$page = new page(array('total'=>get_ann_total($cid), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
	$offset=($currenpage-1)*$perpage;
	$ann_list = get_ann($offset, $perpage, $cid);
	$current_act = $db->getfirst("SELECT cat_name FROM ".table('ann_cat')." WHERE cid=".$cid);
	
 	template_assign(
		array(
			'current_act', 
			'hot_ann', 
			'ann_list', 
			'bot_nav', 
			'cat_option', 
			'area_option', 
			'ann_cat',
			'page'
		),
 		array(
			$current_act, 
			$hot_ann, 
			$ann_list, 
			$bot_nav, 
			get_option(1), 
			get_area_option(1), 
			$ann_cat,
			$page->show(3)
		)
	);
 	$smarty->display('ann_list.htm');
}

elseif(!empty($ann_id))
{
	if(!$smarty->is_cached("ann.htm", $ann_id)){
		$ann_list = $db->getall("SELECT a.ann_id, a.cid, a.title, a.add_time, a.color, a.author, a.add_time, 
										a.content, a.click, b.cat_name 
								FROM ".table('ann')." AS a 
								LEFT JOIN ".table('ann_cat')." AS b 
								ON a.cid=b.cid 
								WHERE a.ann_id = ".$ann_id);

 		$current_act = $db->getfirst("SELECT b.cat_name 
 										FROM ".table('ann')." AS a 
 										LEFT JOIN ".table('ann_cat')." AS b 
 										ON a.cid=b.cid 
 										WHERE a.ann_id=".$ann_id);

 		template_assign(
			array(
				'current_act', 
				'ann_cat', 
				'hot_ann', 
				'ann_list', 
				'cat_nav', 
				'add_nav_list', 
				'bot_nav', 
			),
 			array(
				$current_act, 
				$ann_cat, 
				$hot_ann, 
				$ann_list, 
				$cat_nav, 
				$add_nav_list, 
				$bot_nav, 
			)
		);
	 }
 	$smarty->display('ann_list.htm', $ann_id);

 	$db->query("UPDATE ".table('ann')." SET click = click+1 WHERE ann_id = ".$ann_id);
}
?>
