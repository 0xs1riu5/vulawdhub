<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('操作日志', '?file='.$file),
    array('日志清理', '?file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了系统安全,系统仅删除30天之前的日志\')) return false"'),
);
switch($action) {
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$DT_PRE}admin_log WHERE logtime<$time");
		dmsg('清理成功', '?file='.$file);
	break;
	default:
		$sfields = array('按条件', '网址', '管理员', 'IP');
		$dfields = array('qstring', 'qstring', 'username', 'ip');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$ip = isset($ip) ? $ip : '';
		(isset($username) && check_name($username)) or $username = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND logtime>=$fromtime";
		if($totime) $condition .= " AND logtime<=$totime";
		if($ip) $condition .= " AND ip='$ip'";
		if($username) $condition .= " AND username='$username'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {	
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}admin_log WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}admin_log WHERE $condition ORDER BY logid DESC LIMIT $offset,$pagesize");
		$F = array(
			'index' => '列表',
			'setting' => '设置',
			'category' => '栏目管理',
			'type' => '分类管理',
			'keylink' => '关联链接',
			'split' => '数据拆分',
			'html' => '更新数据',
			'mymenu' => '定义面板',
			'module' => '模块管理',
			'area' => '地区管理',
			'admin' => '管理员管理',
			'html' => '更新全站',
			'database' => '数据库',
			'template' => '模板管理',
			'tag' => '标签向导',
			'skin' => '风格管理',
			'scan' => '木马扫描',
			'log' => '后台日志',
			'upload' => '上传记录',
			'404' => '404日志',
			'keyword' => '搜索记录',
			'question' => '问题验证',
			'banword' => '词语过滤',
			'repeat' => '重名检测',
			'banip' => '禁止IP',
			'fetch' => '单页采编',
			'contact' => '联系会员',
			'grade' => '会员升级',
			'group' => '会员组',
			'vip' => VIP.'管理',
			'credit' => '荣誉资质',
			'news' => '公司新闻',
			'link' => '友情链接',
			'style' => '公司模板',
			'record' => '资金管理',
			'credits' => '积分管理',
			'charge' => '充值记录',
			'trade' => '交易记录',
			'cash' => '提现记录',
			'pay' => '信息支付',
			'card' => '充值卡',
			'promo' => '优惠码',
			'ask' => '客服中心',
			'validate' => '资料认证',
			'sendmail' => '电子邮件',
			'sms' => '手机短信',
			'alert' => '贸易提醒',
			'mail' => '邮件订阅',
			'message' => '站内信件',
			'favorite' => '商机收藏',
			'friend' => '会员商友',
			'loginlog' => '登录日志',
			'spread' => '排名推广',
			'ad' => '广告管理',
			'announce' => '公告管理',
			'webpage' => '单页管理',
			'comment' => '评论管理',
			'guestbook' => '留言管理',
			'vote' => '投票管理',
		);
		$A = array(
			'add' => '添加',
			'edit' => '修改',
			'delete' => '<span class="f_red">删除</span>',
			'check' => '审核',
			'level' => '级别',
			'order' => '排序',
			'update' => '更新',
			'send' => '发送',
		);
		while($r = $db->fetch_array($result)) {
			parse_str($r['qstring'], $t);
			$m = isset($t['moduleid']) ? $t['moduleid'] : 1;
			$r['mid'] = $m;
			$r['module'] = $MODULE[$m]['name'];
			$f = isset($t['file']) ? $t['file'] : 'index';
			if(isset($F[$f])) $f = $F[$f];
			$r['file'] = $f;
			$a = isset($t['action']) ? $t['action'] : '';
			if(isset($A[$a])) $a = $A[$a];
			$r['action'] = $a;
			$i = isset($t['itemid']) ? $t['itemid'] : (isset($t['userid']) ? $t['userid'] : '');
			$r['itemid'] = $i;
			$r['logtime'] = timetodate($r['logtime'], 6);
			$lists[] = $r;
		}
		include tpl('log');
	break;
}
?>