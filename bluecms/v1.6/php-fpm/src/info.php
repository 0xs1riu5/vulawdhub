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

$post_id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : '';
$cache_id = $post_id;
if(!$smarty->is_cached('info.htm', $cache_id))
{
 	//导航条
	$cat_nav = read_static_cache('cat_list_0');

	$add_nav_list = read_static_cache('add_nav');
	//底部导航列表
	$bot_nav = read_static_cache('bot_nav');

	$sql = "SELECT a.*, b.area_name, c.user_name 
			FROM (".table('post')." AS a 
			LEFT JOIN " . table('user') . " AS c ON a.user_id = c.user_id) 
			LEFT JOIN " . table('area') . " AS b ON a.area_id = b.area_id 
			WHERE post_id = " . intval($post_id);
	$info = $db->getone($sql);
	include BLUE_ROOT . 'include/ip.class.php';
	$ip = new ip('getarea');
	$ip_area_arr = $ip -> getaddress($info['ip']);
	if($_CFG['info_is_check'] == 1 && $info['is_check'] == 0)
	{
	 	showmsg('请稍候，您的信息正在审核当中……');
	}

	//地区列表
	$area_list = read_static_cache('area_list');
	$area_list = $area_list[$info['cat_id']];

	//当前位置导航
	$sql = "SELECT a.cat_id, a.cat_name, a.parentid, b.cat_id as pid, b.cat_name as p_name 
				FROM " . table('category') . " AS a, ".table('category')." AS b 
				WHERE a.parentid = b.cat_id and a.cat_id =" . $info['cat_id'];
	$location = $db -> getone($sql);
	$location['url'] = url_rewrite('category', array('cid'=>$location['cat_id']));
	$location['purl'] = url_rewrite('category', array('cid'=>$location['pid']));

	//右侧栏目列表
	$cat_list = $cat_list = read_static_cache('cat_list_1');
	$cat_list = $cat_list[get_parentid($info['cat_id'])];

	//分类信息图片
	$pics = $db->getall("SELECT pic_path 
							FROM ".table('post_pic')." 
							WHERE post_id=".intval($post_id));
	//分类信息附加属性
	$atts = $db->getall("SELECT a.value, b.att_name, b.unit, b.show_order 
							FROM ".table('post_att')." AS a, ".table('attachment')." AS b 
							WHERE a.att_id = b.att_id and a.post_id = ".intval($post_id)." 
							ORDER BY b.show_order");
	$comment_list = $db->getall("SELECT a.*, b.user_name 
									FROM ".table('comment')." AS a 
									LEFT JOIN ".table('user')." AS b 
									ON a.user_id = b.user_id 
									WHERE a.type=0 and a.post_id = ".$post_id." 
									ORDER BY pub_date DESC LIMIT 5");
	$info_count = $db->getfirst("SELECT COUNT(*) 
									FROM ".table('post')." 
									WHERE link_phone='$info[link_phone]'");
	template_assign(
	 	array(
	 		'cat_nav', 
	 		'add_nav_list', 
	 		'bot_nav', 
	 		'cat_list', 
	 		'area_list', 
	 		'info',
	 		'info_count',
	 		'ip_area', 
	 		'pics', 
	 		'atts', 
	 		'location', 
	 		'user_name', 
	 		'url', 
	 		'comment_list', 
	 		'cat_option', 
	 		'area_option'
	 	),
	  	array(
	  		$cat_nav, 
	  		$add_nav_list, 
	  		$bot_nav, 
	  		$cat_list, 
	  		$area_list, 
	  		$info,
	  		$info_count,
	  		$ip_area_arr['area1'], 
	  		$pics, 
	  		$atts, 
	  		$location, 
	  		$_SESSION['user_name'], 
	  		base64_encode($url), 
	  		$comment_list, 
	  		get_option(1), 
	  		get_area_option(1)
	  	)
	);
}
else
{
	$sql = "SELECT a.*, b.area_name, c.user_name FROM ".table('post')." AS a LEFT JOIN ".table('user')." AS c ON a.user_id = c.user_id,".table('area')." AS b WHERE a.area_id = b.area_id and post_id = ".intval($post_id);
	 $info = $db->getone($sql);
	 if($_CFG['info_is_check'] == 1 && $info['is_check'] == 0)
	 {
	 	showmsg('对不起，您的信息正在审核当中……');
	 }

	 $comment_list = $db->getall("SELECT a.*, b.user_name 
	 								FROM ".table('comment')." AS a 
	 									LEFT JOIN ".table('user')." AS b ON a.user_id = b.user_id 
	 								WHERE a.type=0 and a.post_id = ".$post_id." 
	 								ORDER BY pub_date DESC LIMIT 5");

	template_assign(
		array(
	 		'user_name', 
	 		'url', 
	 		'comment_list'
	 	), 
	 	array(
	 		$_SESSION['user_name'], 
	 		base64_encode($url), 
	 		$comment_list
	 	)
	);
}

function smarty_block_dynamic($param, $content, &$smarty)
{
	return $content;
}

$smarty->register_block('dynamic', 'smarty_block_dynamic', false);
if($cache_set['info_pow'])
{
	$smarty->cache_lifetime = $cache_set['info'];
}
else
{
	$smarty->caching = false;
}
$smarty->display('info.htm', $cache_id);
$db->query("UPDATE ".table('post')." 
			SET click = click+1 
			WHERE post_id = ".$post_id);


?>
