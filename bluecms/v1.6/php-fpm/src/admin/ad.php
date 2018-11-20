<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：ad.php
 * $author：lucks
 */
define('IN_BLUE', true);

require dirname(__FILE__) . '/include/common.inc.php';
$act = !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'list';

if($act == 'list')
{
 	$perpage = '15';
 	$page = new page(array('total'=>get_total("SELECT COUNT(*) AS num FROM ".table('ad')), 'perpage'=>$perpage));
 	$currenpage=$page->nowindex;
 	$offset=($currenpage-1)*$perpage;

 	$sql = "SELECT ad_id, ad_name, start_time, end_time, content FROM ".table('ad')." ORDER BY ad_id DESC LIMIT $offset, $perpage";
 	$ad_list = $db->getall($sql);
 	template_assign(array('current_act', 'ad_list', 'page'), array('广告列表', $ad_list, $page->show(3)));
 	$smarty->display('ad.htm');
}

elseif($act == 'add')
{
	$start_time = date("Y-m-d");
	$end_time = date("Y-m-d", time() + 31*24*3600);
	template_assign(array('current_act', 'act', 'start_time', 'end_time'), array('添加一个广告', $act, $start_time, $end_time));
	$smarty->display('ad_info.htm');
}
elseif($act == 'do_add')
{
	$ad_name = !empty($_POST['ad_name']) ? trim($_POST['ad_name']) : '';
	$time_set = isset($_POST['time_set']) ? intval($_POST['ad_name']) : 0;
	if($time_set == 1)
	{
		$start_time = !empty($start_time) ? explode('-',$_POST['start_time']) : '';
 		if($start_time)
 		{
 			if(!is_array($start_time))
 			{
 				showmsg('开始时间格式错误');
 			}
 			$start_time = mktime(0, 0, 0, $start_time[1], $start_time[2], $start_time[0]);
 		}
 		else
 		{
 			$start_time = time();
 		}

 		$end_time = !empty($end_time) ? explode('-', $_POST['end_time']) : 0;
 		if($end_time)
 		{
 			if(!is_array($end_time))
 			{
 				showmsg('结束时间格式错误');
 			}
 			$end_time = mktime(0, 0, 0, $end_time[1], $end_time[2], $end_time[0]);
 		}
	}
	else
	{
		$start_time = 0;
		$end_time = 0;
	}
	if($_POST['content']['type']=='code')
	{
		$content = !empty($_POST['content']['htmlcode']) ? trim($_POST['content']['htmlcode']) : '';
	}
	else
	{
		if(empty($_POST['content']['width']))
		{
			$width = "";
		}
		else
		{
			$width = " width=\"{$_POST['content']['width']}\"";
		}
		if (empty($_POST['content']['height']))
		{
			$height = "";
		}
		else
		{
			$height = "height=\"{$_POST['content']['height']}\"";
		}
		$content = "<a href=\"{$_POST['content']['link']}\" target=\"_blank\"><img src=\"{$_POST['content']['url']}\"$width $height border=\"0\" /></a>";
	}
	$exp_content = !empty($_POST['exp_content']) ? trim($_POST['exp_content']) : '';
	$sql = "INSERT INTO ".table('ad')." (ad_id, ad_name, time_set, start_time, end_time, content, exp_content) VALUES ('', '$ad_name', '$time_set', '$start_time', '$end_time', '$content', '$exp_content')";
	$db->query($sql);
	showmsg('添加新广告成功', 'ad.php');
}

elseif($act == 'edit')
{
	 $ad_id = !empty($_GET['ad_id']) ? trim($_GET['ad_id']) : '';
	 if(empty($ad_id))
	 {
		 return false;
	 }
	 $ad = $db->getone("SELECT ad_id, ad_name, time_set, start_time, end_time, content, exp_content FROM ".table('ad')." WHERE ad_id=".$ad_id);
	 template_assign(
	 	array(
	 		'current_act', 
	 		'act', 
	 		'ad'
	 	), 
	 	array(
	 		'编辑广告', 
	 		$act, 
	 		$ad
	 	)
	 );
	 $smarty->display('ad_info.htm');
}

elseif($act == 'do_edit')
{
	$ad_id = !empty($_POST['ad_id']) ? intval($_POST['ad_id']) : '';
	if(empty($ad_id))
	{
		return false;
	}
	$ad_name = !empty($_POST['ad_name']) ? trim($_POST['ad_name']) : '';
	$time_set = isset($_POST['time_set']) ? intval($_POST['ad_name']) : 0;
	if($time_set == 1)
	{
		$start_time = !empty($start_time) ? explode('-',$_POST['start_time']) : '';
 		if($start_time)
 		{
 			if(!is_array($start_time))
 			{
 				showmsg('开始时间格式错误');
 			}
 			$start_time = mktime(0, 0, 0, $start_time[1], $start_time[2], $start_time[0]);
 		}
 		else
 		{
 			$start_time = time();
 		}

 		$end_time = !empty($end_time) ? explode('-', $_POST['end_time']) : 0;
 		if($end_time)
 		{
 			if(!is_array($end_time))
 			{
 				showmsg('结束时间格式错误');
 			}
 			$end_time = mktime(0, 0, 0, $end_time[1], $end_time[2], $end_time[0]);
 		}
	}
	else
	{
		$start_time = 0;
		$end_time = 0;
	}
	$content = !empty($_POST['content']) ? trim($_POST['content']) : '';
	$exp_content = !empty($_POST['exp_content']) ? trim($_POST['exp_content']) : '';
	$sql = "UPDATE ".table('ad')." SET ad_name = '$ad_name', time_set = '$time_set', start_time = '$start_time', end_time = '$end_time', content = '$content', exp_content = '$exp_content' WHERE ad_id = ".$ad_id;
	$db->query($sql);
	showmsg('编辑广告成功', 'ad.php');
}

elseif($act == 'del')
{
	$ad_id = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : '';
	if(empty($ad_id))
	{
		return false;
	}
	$sql = "DELETE FROM ".table('ad')." WHERE ad_id = ".$ad_id;
	$db->query($sql);
	showmsg('删除广告成功', 'ad.php');
}

elseif($act == 'get_js')
{
	$ad_id = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : '';
	if(empty($ad_id))
	{
		return false;
	}
	$jscode = "<script src='{$_CFG['site_url']}/ad_js.php?ad_id=$ad_id' language='javascript'></script>";
	template_assign(
		array(
			'current_act', 
			'jscode'
		),
		array(
			'获取JS代码', 
			htmlentities($jscode)
		)
	);
	$smarty->display('get_ad_js.htm');
}

?>
