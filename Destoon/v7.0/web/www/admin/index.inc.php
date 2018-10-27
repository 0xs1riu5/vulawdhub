<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
array('系统首页', '?action=main'),
array('修改密码', '?action=password'),
array('商务中心', $MODULE[2]['linkurl'], 'target="_blank"'),
array('网站首页', DT_PATH, 'target="_blank"'),
array('安全退出', "javascript:Dconfirm('确定要退出管理后台吗?', '?file=logout');"),
);
if($_admin > 1) unset($menus[1]);
switch($action) {
	case 'cache':
		dheader('?file=html&action=caches');
	break;
	case 'html':
		dheader('?file=html&action=homepage');
	break;
	case 'home':
		$url = '';
		if($job) {
			list($mid, $file) = explode('-', $job);
			$mid = intval($mid);
			check_name($file) or $file = '';
			if($mid == 3) {
				if(isset($EXT[$file.'_url'])) $url = $EXT[$file.'_url'];
			} else {				
				if(isset($MODULE[$mid])) $url = $MODULE[$mid]['linkurl'];
			}
		}
		dheader(is_url($url) ? $url : DT_PATH);
	break;
	case 'password':
		if($submit) {
			if(!$oldpassword) msg('请输入现有密码');
			if(!$password) msg('请输入新密码');
			if(strlen($password) < 6) msg('新密码最少6位，请修改');
			if($password != $cpassword) msg('两次输入的密码不一致，请检查');
			$r = $db->get_one("SELECT password,passsalt FROM {$DT_PRE}member WHERE userid='$_userid'");
			if($r['password'] != dpassword($oldpassword, $r['passsalt']))  msg('现有密码错误，请检查');
			if($password == $oldpassword) msg('新密码不能与现有密码相同');
			$passsalt = random(8);
			$password = dpassword($password, $passsalt);
			$db->query("UPDATE {$DT_PRE}member SET password='$password',passsalt='$passsalt' WHERE userid='$_userid'");
			userclean($_username);
			msg('管理员密码修改成功', '?action=main');
		} else {
			include tpl('password');
		}
	break;
	case 'cron':
		include DT_ROOT.'/api/cron.inc.php';
	break;
	case 'todo':
		$db->halt = 0;
		$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
		$htm = '';

		$num = $db->count($DT_PRE.'finance_charge', "status=0");
		if($num) $htm .= '<li><a href="?moduleid=2&file=charge&status=0">待受理在线充值 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'finance_cash', "status=0");
		if($num) $htm .= '<li><a href="?moduleid=2&file=cash&status=0">待受理资金提现 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'keyword', "status=2");
		if($num) $htm .= '<li><a href="?file=keyword&status=2">待审核搜索关键词 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'guestbook', "edittime=0");
		if($num) $htm .= '<li><a href="?moduleid=3&file=guestbook">待回复网站留言 (<b>'.$num.'</b>)</a></li>';

		$num = $db->count($DT_PRE.'member_check', "1");//待审核资料修改
		if($num) $htm .= '<li><a href="?moduleid=2&file=validate&action=member">待审核资料修改 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'ask', "status=0");
		if($num) $htm .= '<li><a href="?moduleid=2&file=ask&status=0">待受理客服中心 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'alert', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=alert&action=check">待审核贸易提醒 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'gift_order', "status='处理中'");
		if($num) $htm .= '<li><a href="?moduleid=3&file=gift&action=order&fields=3&kw=%E5%A4%84%E7%90%86%E4%B8%AD">待处理礼品订单 (<b>'.$num.'</b>)</a></li>';

		$num = $db->count($DT_PRE.'news', "status=2");//待审核公司新闻
		if($num) $htm .= '<li><a href="?moduleid=2&file=news&action=check">待审核公司新闻 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'honor', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=honor&action=check">待审核荣誉资质 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'page', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=page&action=check">待审核公司单页 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'link', "status=2 AND username<>''");
		if($num) $htm .= '<li><a href="?moduleid=2&file=link&action=check">待审核公司链接 (<b>'.$num.'</b>)</a></li>';

		$num = $db->count($DT_PRE.'validate', "type='company' AND status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=validate&action=company&status=2">待审核公司认证 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'validate', "type='truename' AND status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=validate&action=truename&status=2">待核审实名认证 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'validate', "type='mobile' AND status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=validate&action=mobile&status=2">待审核手机认证 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'validate', "type='email' AND status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=validate&action=email&status=2">待审核邮件认证 (<b>'.$num.'</b>)</a></li>';

		$num = $db->count($DT_PRE.'ad', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=3&file=ad&action=list&job=check">待审广告购买 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'spread', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=3&file=spread&action=check">待审核排名推广 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'comment', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=3&file=comment&action=check">待审核评论 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'link', "status=2 AND username=''");
		if($num) $htm .= '<li><a href="?moduleid=3&file=link&action=check">待审核友情链接 (<b>'.$num.'</b>)</a></li>';

		$num = $db->count($DT_PRE.'upgrade', "status=2");
		if($num) $htm .= '<li><a href="?moduleid=2&file=grade&action=check">待审核会员升级 (<b>'.$num.'</b>)</a></li>';
		$num = $db->count($DT_PRE.'member', "groupid=4");
		if($num) $htm .= '<li><a href="?moduleid=2&action=check">待审核会员注册 (<b>'.$num.'</b>)</a></li>';
		
		foreach($MODULE as $m) {
			if($m['moduleid'] < 5 || $m['islink']) continue;
			$mid = $m['moduleid'];
			$table = get_table($mid);
			$num = $db->count($table, "status=2");
			if($num) $htm .= '<li><a href="?moduleid='.$mid.'&action=check">待审核'.$m['name'].' (<b>'.$num.'</b>)</a></li>';

			if($m['module'] == 'mall' || $m['module'] == 'sell') {
				$num = $db->count($DT_PRE.'order', "mid=$mid AND status=5");
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=order&status=5">待受理'.$m['name'].'订单 (<b>'.$num.'</b>)</a></li>';
			}
			if($m['module'] == 'group') {
				$num = $db->count($DT_PRE.'group_order_'.$mid, "status=4");
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=order&status=4">待受理'.$m['name'].'订单 (<b>'.$num.'</b>)</a></li>';
			}
			if($m['module'] == 'quote') {
				$num = $db->count($DT_PRE.'quote_price_'.$mid, "status=2");
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=price&action=check">待审核'.$m['name'].'报价 (<b>'.$num.'</b>)</a></li>';
			}
			if($m['module'] == 'exhibit') {
				$num = $db->count($DT_PRE.'exhibit_sign_'.$mid, "addtime>$today");
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=sign">'.$m['name'].'今日报名 (<b>'.$num.'</b>)</a></li>';
			}
			if($m['module'] == 'know') {
				$num = $db->count($DT_PRE.'know_answer_'.$mid, "status=2");
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=answer&action=check">待审核'.$m['name'].'回答 (<b>'.$num.'</b>)</a></li>';
			}
			if($m['module'] == 'job') {
				$num = $db->count($DT_PRE.'job_resume_'.$mid, "status=2");
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=resume&action=check">待审核'.$m['name'].'简历 (<b>'.$num.'</b>)</a></li>';
			}
			if($m['module'] == 'club') {
				$num = $db->count($DT_PRE.'club_group_'.$mid, "status=2");//商圈
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=group&action=check">待审核'.$m['name'].'申请 (<b>'.$num.'</b>)</a></li>';

				$num = $db->count($DT_PRE.'club_reply_'.$mid, "status=2");//商圈回复
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=reply&action=check">待审核'.$m['name'].'回复 (<b>'.$num.'</b>)</a></li>';

				$num = $db->count($DT_PRE.'club_fans_'.$mid, "status=2");//商圈粉丝
				if($num) $htm .= '<li><a href="?moduleid='.$mid.'&file=fans&action=check">待审核'.$m['name'].'粉丝 (<b>'.$num.'</b>)</a></li>';
			}
		}
		if($htm) {
			$htm = '<div class="tt"><span class="f_r"><a href="?file=count" style="font-weight:normal;font-size:12px;">更多<span style="font-family:simsun;font-weight:bold;padding:0 2px;">&gt;</span></a></span>待办事项</div><ul>'.$htm.'</ul></div>';
			echo 'try{document.getElementById("todo").innerHTML=\''.$htm.'\';document.getElementById("todo").style.display=\'table\';}catch(e){}';
		}
	break;
	case 'main':
		if($submit) {
			$note = '<?php exit;?>'.dhtmlspecialchars(stripslashes($note));
			file_put(DT_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/note.php', $note);
			dmsg('保存成功', '?action=main');
		} else {
			$user = $db->get_one("SELECT loginip,logintime,logintimes FROM {$DT_PRE}member WHERE userid=$_userid");
			$note = DT_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/note.php';
			$note = file_get($note);
			if($note) {
				$note = substr($note, 13);
			} else {
				$note = '';
			}
			$install = file_get(DT_CACHE.'/install.lock');
			if(!$install) {
				$install = $DT_TIME;
				file_put(DT_CACHE.'/install.lock', $DT_TIME);
			}
			$notice_url = 'https://www.destoon.com/client.php?action=notice&product=b2b&version='.DT_VERSION.'&release='.DT_RELEASE.'&lang='.DT_LANG.'&charset='.DT_CHARSET.'&domain='.DT_DOMAIN.'&install='.$install.'&os='.PHP_OS.'&soft='.urlencode($_SERVER['SERVER_SOFTWARE']).'&php='.urlencode(phpversion()).'&mysql='.urlencode($db->version()).'&url='.urlencode($DT_URL).'&site='.urlencode($DT['sitename']).'&auth='.strtoupper(md5($DT_URL.$install.$_SERVER['SERVER_SOFTWARE']));
			$install = timetodate($install, 5);			
			$edition = edition(1);
			include tpl('main');
		}
	break;
	case 'left':
		$mymenu = cache_read('menu-'.$_userid.'.php');
		include tpl('left');
	break;
	default:
		include tpl('index');
	break;
}
?>