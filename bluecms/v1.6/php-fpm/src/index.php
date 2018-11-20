<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：index.php
 * $author：lucks
 */
define("IN_BLUE",true);
require_once('include/common.inc.php');
require_once(BLUE_ROOT.'include/index.fun.php');

do_task('update_info');
$cat_nav = read_static_cache('cat_list_0');

$add_nav_list = read_static_cache('add_nav');

$flash_list = flash_list();

$hot_news = get_hot_news('', 6);

//$f_rec_news = get_rec_news('', 0, 1);
//$rec_news = get_rec_news('', 1, 4);

$rec_news_result = $db->query("SELECT id, title, lit_pic, descript 
								FROM ".table('article').
								" WHERE is_check = 1 and is_recommend = 1 
								ORDER BY id DESC LIMIT 7");
$rec_count = 0;
while ($row = $db->fetch_array($rec_news_result))
{
	$row['url'] = url_rewrite('news', array('id'=>$row['id']));
	if ($rec_count == 0 && $row['lit_pic'] != '')
	{
		$f_rec_news = $row;
		$rec_count++;
	}
	else
	{
		$rec_news[] = $row;
	}
}


//新闻栏目
$news_cat_result = $db->query("SELECT cat_id, cat_name 
 								FROM ".table('arc_cat').
 								" WHERE parent_id=0 
 								ORDER BY show_order, cat_id");
while ($row = $db->fetch_array($news_cat_result))
{
	$row['url'] = url_rewrite('news_cat', array('cid'=>$row['cat_id']));
	$news_cat[] = $row;
	$news_cat_n[] = $row['cat_id'];
}
if (is_array($news_cat_n))
{
	$news_cat_n = implode(',',$news_cat_n);
}

//新闻分类列表
$i = 0;
$result1 = $db->query("SELECT * 
						FROM ".table('arc_cat').
						" ORDER BY show_order");
while($row1 = $db->fetch_array($result1))
{
	$result2 = $db->query("SELECT * FROM ".table('article')." WHERE cid=$row1[cat_id] and is_recommend = 1 and is_check = 1 ORDER BY id DESC LIMIT 7");
	$r = 0;
	 //$news_arr[$i]['cat_name']= $row1['cat_name'];
	 //$news_arr[$i]['url'] = url_rewrite('news_cat', array('cid'=>$row1['cat_id']));
	$news_arr[$i]['cat_id'] = $row1['cat_id'];
	while($row2 = $db->fetch_array($result2)){
		$row2['url'] = url_rewrite('news', array('id'=>$row2['id']));
		if($r==0 && $row2['lit_pic'])
		{
			$news_arr[$i]['f_r_news'] = $row2;
			$r++;
		}
		else
		{
			$news_arr[$i]['rec_news'][] = $row2;
		}
	}

	$result3 = $db->query("SELECT id, title, descript, lit_pic 
							FROM ".table('article').
							" WHERE is_check = 1 and cid=$row1[cat_id] 
							ORDER BY id DESC LIMIT 9");
	$j = 0;
	$r = 0;
	while($row3 = $db->fetch_array($result3))
	{
		$row3['url'] = url_rewrite('news', array('id'=>$row3['id']));
		if($r ==0)
		{
			$news_arr[$i]['f_l_news'] = $row3;
			$r++;
			continue;
		}
		if($j<2 && $row3['lit_pic'] != '')
		{
			$news_arr[$i]['photo'][] = $row3;
			$j++;
		}
		$news_arr[$i]['latest_news'][] = $row3;
	} 
	$i++;
}

$f_news = get_news(0, 1);
$news_list = get_news(1, 9);
 
 //最新分类信息
$i = 0;
$f_info = $db->getone("SELECT post_id, title, lit_pic, content 
						FROM ".table('post').
						" WHERE is_check = 1 
						ORDER BY post_id DESC 
						LIMIT 1");
$f_info['url'] = url_rewrite('post', array('id'=>$f_info['post_id']));
if($f_info['lit_pic'])
{
	$info_photo[] = $f_info;
	$i++;
}

 //分类信息列表
$info_list_result = $db->query("SELECT post_id, title, lit_pic 
								FROM ".table('post').
								" WHERE is_check = 1 
								ORDER BY post_id DESC 
								LIMIT 1,9");
while ($row = $db->fetch_array($info_list_result))
{
	$row['url'] = url_rewrite('post', array('id'=>$row['post_id']));
	if ($i < 2 && $row['lit_pic'])
	{
		$info_photo[] = $row;
		$i++;
	}
	$info_list[] = $row;
}

//推荐分类信息
$rec_info_result = $db->query("SELECT post_id, lit_pic 
								FROM ".table('post').
								" WHERE is_check = 1 and is_recommend = 1 and lit_pic != '' 
								ORDER BY post_id DESC 
								LIMIT 6");
while ($row = $db->fetch_array($rec_info_result))
{
	$row['url'] = url_rewrite('post', array('id'=>$row['post_id']));
	$rec_info_p[] = $row;
}
$article_list = $db->getall("SELECT a.id, a.cid, a.title, b.cat_name FROM ".table('article'). " AS a LEFT JOIN ".table('arc_cat')." AS b ON a.cid=b.cat_id WHERE is_check = 1 ORDER BY pub_date DESC LIMIT 1,4");
for($i=0;$i<count($article_list);$i++)
{
	$article_list[$i]['url'] = url_rewrite('news', array('id'=>$article_list[$i]['id']));
	$article_list[$i]['cat_url'] = url_rewrite('news_cat', array('cid'=>$article_list[$i]['cid']));
}

//分类信息栏目
$info_cat_result = $db->query("SELECT cat_id, cat_name FROM ".table('category')." WHERE parentid=0 ORDER BY show_order, cat_id");
while ($row = $db->fetch_array($info_cat_result))
{
	 $row['url'] = url_rewrite('category', array('cid'=>$row['cat_id']));
	 $info_cat[] = $row;
}

//网站公告
$ann_arr = get_index_ann(1,5);
//帮助中心
$help_arr = get_index_ann(3, 3);

//付费推广
$service_arr = get_index_ann(2, 3);

$pics = is_array($flash_list['pics']) ? implode('|', $flash_list['pics']) : $flash_list['pics'];
$links = is_array($flash_list['links']) ? implode('|', $flash_list['links']) : $flash_list['pics'];

$rec_info = get_rec_info('', 8);

$ad_phone_list = read_static_cache('phone_ad');

$link_list_text = read_static_cache('friend_link_text');
$link_list_img = read_static_cache('friend_link_img');

$tel = explode('|', $_CFG['tel']);
$qq = explode('|', $_CFG['qq']);
$qq_group = explode('|', $_CFG['qq_group']);

$bot_nav = read_static_cache('bot_nav');

if($_SESSION['user_id'])
{
	$face_pic = $db->getone("SELECT face_pic 
							FROM ".table('user').
							" WHERE user_id = ".intval($_SESSION['user_id']));
	$face_pic = $face_pic['face_pic'];
}
$user_name = !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : $user_name;

template_assign(
	array(
 		'cat_nav', 
 		'ad_phone_list', 
 		'pics', 
 		'links', 
 		'add_nav_list', 
 		'link_list_text',
  		'link_list_img', 
  		'f_news',					//首页顶部头条新闻
		'news_list',				//新闻列表
		'hot_news',					//热门新闻
  		'ann_arr',					//公告列表
		'help_arr',					//帮助中心
		'service_arr',				//推广服务
  		'site_name', 
  		'tel', 
  		'qq', 
  		'qq_group', 
  		'bot_nav', 
  		'keywords', 
  		'description', 
  		'user_name', 
  		'active',
		'face_pic', 
		'site_url', 
		'rec_info',
		'rec_news',
		'f_rec_news',
		'news_arr',					//新闻分类列表
 		'info_photo',				//分类信息图片
 		'info_list',				//分类信息
 		'f_info',					//头条分类信息
 		'rec_info_p',				//推荐分类信息
		'info_cat',
		'news_cat',
		'news_cat_n'
 	),
  	array(
  		$cat_nav, 
  		$ad_phone_list, 
  		$pics, 
  		$links, 
  		$add_nav_list,
  		$link_list_text, 
  		$link_list_img,
  		$f_news[0],
		$news_list,
		$hot_news,
  		$ann_arr, 
		$help_arr,
		$service_arr,
  		$_CFG['site_name'], 
  		$tel, 
  		$qq,
  		$qq_group, 
  		$bot_nav, 
  		$_CFG['keywords'], 
  		$_CFG['description'], 
  		$user_name, $active,  
  		$face_pic, 
  		$_CFG['site_url'], 
		$rec_info,
		$rec_news,
		$f_rec_news,
  		$news_arr,
  		$info_photo,
  		$info_list,
  		$f_info,
  		$rec_info_p,
		$info_cat,
		$news_cat,
		$news_cat_n
  	)
);
function smarty_block_dynamic($param, $content, &$smarty)
{
    return $content;
}

$smarty->register_block('dynamic', 'smarty_block_dynamic', false);

if($cache_set['index_pow'])
{
	$smarty->cache_lifetime = $cache_set['index'];
}
else
{
	 $smarty->caching = false;
}
$smarty->display('index.htm');


?>
