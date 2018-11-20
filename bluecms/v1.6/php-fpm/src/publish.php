<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：publish.php
 * $author：lucks
 */
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'step1';
$cid = !empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : '';
$bot_nav = read_static_cache('bot_nav');
if(!$_SESSION['user_id'])
{
	showmsg('您还没有登录，请先登录...', 'user.php?act=login');
}
if($act == 'step1')
{
 	$cat_list = get_cat_html();
 	template_assign(
 		array(
 			'cat_list', 
 			'current_act', 
 			'bot_nav'
 		), 
 		array(
 			$cat_list, 
 			'选择分类', 
 			$bot_nav
 		)
 	);
 	if($cache_set['publish1_pow'])
 	{
		$smarty->cache_lifetime = $cache_set['publish1'];
	}
	else
	{
		$smarty->caching = false;
	}
 	$smarty->display('publish1.htm');
}

elseif($act == 'step2' && $cid)
{
 	$model_id = get_model_id($cid);
 	$area_option = get_area_option(1);
 	$arr = read_static_cache('model');
 	$insert_must_att = $arr[$model_id]['must'];
 	$insert_nomust_att = $arr[$model_id]['nomust'];
 	$cat = get_cat_name($cid);
	$service_arr = array();
	$service_result = $db->query("SELECT service, price 
								FROM ".table('service').
								" WHERE type='info' ORDER BY id");
	while ($row = $db->fetch_array($service_result))
	{
		$service_arr[] = $row['price'];
	}
	$service_arr = implode(',', $service_arr);

 	template_assign(
		array(
			'cat', 
			'current_act', 
			'insert_must_att', 
			'insert_nomust_att', 
			'area_option', 
			'cid', 
			'bot_nav',
			'service_arr',
			'total'
		),
 		array(
			$cat, 
			'发布分类信息', 
			$insert_must_att, 
			$insert_nomust_att, 
			$area_option, 
			$cid, 
			$bot_nav,
			$service_arr,
			$_SESSION['money']
		)
	);
	if($cache_set['publish2_pow'])
	{
		$smarty->cache_lifetime = $cache_set['publish2'];
	}
	else
	{
		$smarty->caching = false;
	}
	$smarty->display('publish2.htm');
}

elseif($act == 'do_pub')
{
 	$must_att_arr = array();
 	$nomust_att_arr = array();
 	$title = !empty($_POST['title']) ? htmlspecialchars(trim($_POST['title'])) : '';
 	if (empty($title))
 	{
 		showmsg('信息标题不能为空');
 	}
 	$area = !empty($_POST['area']) ? intval($_POST['area']) : '';
 	if (empty($area))
 	{
		showmsg('您还没有选择地区');
	}
	
 	$useful_time = intval($_POST['useful_time']);
 	$content = !empty($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
	if (!empty($content))
	{
		$content = str_replace(' ', '&nbsp;', str_replace(array("\r\n", "\r", "\n"), "<br/>", $content));
	}
 	if ($content == '')
 	{
 		showmsg('您的信息描述太短啦');
 	}
	$is_recommend = $_POST['is_recommend'];
	if (!empty($is_recommend))
	{
		$rec_start 	= $timestamp;
		$rec_time	= $_POST['rec_time'];
		if (!preg_match('/^[1-9][0-9]+$/', $rec_time))
		{
			showmsg('推荐时间格式错误');
		}
	}
	else
	{
		$rec_start	= '';
		$rec_time	= '';
	}
	
	$top_type	= $_POST['top_type'];
	if ($top_type != 0)
	{
		$top_start	= $timestamp;
		$top_time	= $_POST['top_time'];
		if (!preg_match('/^[1-9][0-9]+$/', $top_time))
		{
			showmsg('置顶时间格式错误');
		}
	}
	else
	{
		$top_type	= '';
		$top_time	= '';
	}

	$is_head_line = $_POST['is_head_line'];
	if (!empty($is_head_line))
	{
		$head_line_start 	= $timestamp;
		$head_line_time	= $_POST['head_line_time'];
		if (!preg_match('/^[1-9][0-9]+$/', $head_line_time))
		{
			showmsg('头条时间格式错误');
		}
	}
	else
	{
		$rec_start	= '';
		$rec_time	= '';
	}
	
 	$link_man = !empty($_POST['link_man']) ? htmlspecialchars(trim($_POST['link_man'])) : '';
 	$link_phone = !empty($_POST['link_phone']) ? htmlspecialchars(trim($_POST['link_phone'])) : 0;
 	$link_email = !empty($_POST['link_email']) ? htmlspecialchars(trim($_POST['link_email'])) : '';
 	$link_qq = !empty($_POST['link_qq']) ? htmlspecialchars(trim($_POST['link_qq'])) : 0;
 	$link_address = !empty($_POST['link_address']) ? htmlspecialchars(trim($_POST['link_address'])) : '';
 	
 	if (empty($link_man))
 	{
 		showmsg('联系人姓名不能为空');
 	}
 	if (empty($link_phone))
 	{
 		showmsg('为了体现信息真实，联系电话不要为空');
 	}
 	$must_att_arr = get_att($model_id, $_POST['att1'], 'must_att');
 	$nomust_att_arr = get_att($model_id, $_POST['att2']);

	if ($_CFG['info_is_check'] == 0)
	{
		$is_check = 1;
	}
	else
	{
		$is_check = 0;
	}
	
	//交易过程
 	$rec_service = $db->getone("SELECT id, price FROM ".table('service').
 								" WHERE type='info' and service='rec'");
	if($top_type == 1)
	{
		$service = 'top1';
	}
	elseif($top_type == 2)
	{
		$service = 'top2';
	}
	else
	{
		$service = '';
	}
 	$top_service = $db->getone("SELECT id, price FROM ".table('service').
 								" WHERE type='info' and service='$service'");
	$head_line_service = $db->getone("SELECT id, price FROM ".table('service').
									" WHERE type='info' and service='head_line'");
	$money = $_SESSION['money'] - $rec_service['price'] * $rec_time - $top_service['price'] * $top_time - $head_line_service['price'] * $head_line_time;
	if ($money < 0)
	{
		showmsg('对不起，您的余额不足，请充值');
	}
	if ($is_recommend == 1)
	{
		$db->query("INSERT INTO ".table('buy_record')." (id, user_id, aid, pid, exp, time) 
					VALUES ('', '$_SESSION[user_id]', '$post_id', '$rec_service[id]', '$rec_time', '$timestamp'");
	}
	if ($top_type != 0)
	{
		$db->query("INSERT INTO ".table('buy_record')." (id, user_id, aid, pid, exp, time)
					VALUES ('', '$_SESSION[user_id]', '$post_id', '$top_service[id]', '$top_time', '$timestamp'");
	}
	if ($is_head_line == 1)
	{
		$db->query("INSERT INTO ".table('buy_record')." (id, user_id, aid, pid, exp, time) 
					VALUES ('', '$_SESSION[user_id]', '$post_id', '$rec_service[id]', '$head_line_time', '$timestamp'");
	}
	//从用户账户扣除花费金币
	$db->query("UPDATE ".table('user').
				" SET money='$money' ".
				"WHERE user_id='$_SESSION[user_id]'");

 	$sql = "INSERT INTO ".table('post')." (post_id, cat_id, user_id, area_id, title, keywords, content, 
 				link_man, link_phone, link_email, link_qq, link_address, pub_date, useful_time, click, comment, 
 				ip, is_check, is_recommend, rec_start, rec_time, top_type, top_start, top_time, is_head_line, 
 				head_line_start, head_line_time) 
 			VALUES ('', '$_POST[cid]', '$_SESSION[user_id]', '$area', '$title', '', '$content', '$link_man', 
 				'$link_phone', '$link_email', '$link_qq', '$link_address', '$timestamp', '$useful_time', 
 				'0', '0', '$online_ip', '$is_check', '$is_recommend', '$rec_start', '$rec_time', '$top_type', '$top_start', 
 				'0', '$is_head_line', '$head_line_start', '$head_line_time')";
 	$db->query($sql);
 	$post_id = $db->insert_id();
 	insert_att_value($must_att_arr, $post_id);
 	insert_att_value($nomust_att_arr, $post_id);
 	for ($i = 0; $i < 4; $i++)
 	{
 		if($_POST['pic'.$i] && file_exists(BLUE_ROOT.$_POST['pic'.$i]))
 		{
 			$sql = "INSERT INTO ".table('post_pic')." (pic_id, post_id, pic_path) 
 					VALUES ('', '$post_id', '".$_POST['pic'.$i]."')";
 			$db->query($sql);
 		}
 	}
	if (!empty($_POST['pic0']))
	{
		include_once(BLUE_ROOT."include/upload.class.php");
		$image = new upload();
		$lit_pic = $image->small_img($_POST['pic0'], 126, 80);
		$db->query("UPDATE ".table('post').
					" SET lit_pic='$lit_pic'" . " WHERE post_id='$post_id'");
	}
	
 	$post_url = url_rewrite('post', array('id'=>$post_id));
	template_assign(
		array(
			'current_act', 
			'post_url', 
			'bot_nav', 
			'info_is_check'
		), 
		array(
			'发布成功', 
			$post_url, 
			$bot_nav, 
			$_CFG['info_is_check']
		)
	);
 	$smarty->caching = false;
 	$smarty->display('publish3.htm');
}

elseif($act == 'upload')
{
 	template_assign();
 	$smarty->caching = false;
 	$smarty->display('upload.htm');
}

elseif($act == 'do_upload')
{
 	include_once BLUE_ROOT . "include/upload.class.php";
	$image = new upload();
 	if(isset($_FILES['upload_file']['error']) && $_FILES['upload_file']['error'] == 0)
 	{
		$upload_pic = $image->img_upload($_FILES['upload_file']);
	}
	template_assign('add_pic', $upload_pic);
	$smarty->caching = false;
	$smarty->display('upload.htm');
}

elseif($act == 'del_pic')
{
 	$id = $_REQUEST['id'];
 	$db->query("DELETE FROM ".table('post_pic').
 				" WHERE pic_path='$id'");
 	if(file_exists(BLUE_ROOT.$id))
 	{
 		@unlink(BLUE_ROOT.$id);
 	}
}
 
elseif ($act == 'check_price')
{
 	$type = $_GET['type'];
 	$service = $_GET['service'];
 	$exp = $_GET['exp'];
 	if (!preg_match('/^[1-9][0-9]+$/', $exp))
 	{
 		echo "<span style='color:red'>您输入的格式错误</span>";
 		exit;
 	}
 	$service_price = $db->getone("SELECT price FROM ".table('price').
 								" WHERE type='$type' and act='$act'");
	static $total_money;
	$total_money = $_SESSION['money'] - $service_price['price'] * $exp;
 	if($total_money < 0)
 		echo "<span style='color:red'>你的金币不够啦！</span>";
 	else 
 		echo "<span style='color:#006CCE;'>你的金币还很充裕！</span>";
}


?>