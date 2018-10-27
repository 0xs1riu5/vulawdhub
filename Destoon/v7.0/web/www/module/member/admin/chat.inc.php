<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('在线交谈', '?moduleid='.$moduleid.'&file=chat'),
    array('记录清理', '?moduleid='.$moduleid.'&file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了系统安全,系统仅删除30天之前的聊天记录\')) return false"'),
);
$table = $DT_PRE.'chat';
switch($action) {
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$table} WHERE lasttime<$time");
		for($i = 0; $i < 10; $i++) {
			$db->query("DELETE FROM {$table}_data_{$i} WHERE addtime<$time");
		}
		dmsg('清理成功', $forward);
	break;
	case 'delete':
		if(is_array($chatid)) {
			foreach($chatid as $cid) {
				if(is_md5($cid)) $db->query("DELETE FROM {$table} WHERE chatid='$cid'");
			}
		} else {
			if(is_md5($chatid)) $db->query("DELETE FROM {$table} WHERE chatid='$chatid'");
		}
		dmsg('删除成功', $forward);
	break;
	case 'view':
		$lists = array();
		if(is_md5($chatid)) {
			$table = get_chat_tb($chatid);
			(isset($username) && check_name($username)) or $username = '';
			$fromdate = isset($fromdate) ? $fromdate : '';
			$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
			$todate = isset($todate) ? $todate : '';
			$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
			$condition = "chatid='$chatid'";
			if($keyword) $condition .= " AND content LIKE '%$keyword%'";
			if($fromtime) $condition .= " AND addtime>=$fromtime";
			if($totime) $condition .= " AND addtime<=$totime";
			if($username) $condition .= " AND username='$username'";
			if($page > 1 && $sum) {
				$items = $sum;
			} else {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
				$items = $r['num'];
			}
			$pages = pages($items, $page, $pagesize);
			$lists = array();
			$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$id = $r['itemid'];
				$time = $r['addtime'];
				$name = $r['username'];
				$word = $r['content'];
				if($MOD['chat_url'] || $MOD['chat_img']) {
					if(preg_match_all("/([http|https]+)\:\/\/([a-z0-9\/\-\_\.\,\?\&\#\=\%\+\;]{4,})/i", $word, $m)) {
						foreach($m[0] as $u) {
							if($MOD['chat_img'] && preg_match("/^(jpg|jpeg|gif|png|bmp)$/i", file_ext($u)) && !preg_match("/([\?\&\=]{1,})/i", $u)) {
								$word = str_replace($u, '<img src="'.$u.'" onload="if(this.width>320)this.width=320;" onclick="window.open(this.src);"/>', $word);
							} else if($MOD['chat_img'] && preg_match("/^(mp4)$/i", file_ext($u)) && !preg_match("/([\?\&\=]{1,})/i", $u)) {
								$word = str_replace($u, '<video src="'.$u.'" width="200" height="150" controls="controls"></video>', $word);
							} else if($MOD['chat_url']) {
								$word = str_replace($u, '<a href="'.$u.'" target="_blank">'.$u.'</a>', $word);
							}
						}
					}
				}
				if(strpos($word, ')') !== false) $word = parse_face($word);
				if(strpos($word, '[emoji]') !== false) $word = emoji_decode($word);
				$r = array();
				$r['date'] = timetodate($time, 6);
				$r['name'] = $name;
				$r['word'] = $word;
				$lists[] = $r;
			}
		}
		include tpl('chat_view', $module);
	break;
	default:
		$sfields = array('按条件', '发起人', '接收人', '来源');
		$dfields = array('fromuser', 'fromuser', 'touser', 'forward');
		$sorder  = array('结果排序方式', '开始时间降序', '开始时间升序');
		$dorder  = array('freadtime DESC', 'freadtime DESC', 'freadtime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields]='$kw'";
		$order = $dorder[$order];
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			if($r['forward'] && strpos($r['forward'], '://') === false) $r['forward'] = 'http://'.$r['forward'];
			$lists[] = $r;
		}
		include tpl('chat', $module);
	break;
}
?>