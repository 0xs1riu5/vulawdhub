<?php
defined('DT_ADMIN') or exit('Access Denied');
$TYPE = get_type('ask', 1);
$menus = array (
    array('待受理', '?moduleid='.$moduleid.'&file='.$file.'&status=0'),
    array('受理中', '?moduleid='.$moduleid.'&file='.$file.'&status=1'),
    array('已解决', '?moduleid='.$moduleid.'&file='.$file.'&status=2'),
    array('未解决', '?moduleid='.$moduleid.'&file='.$file.'&status=3'),
    array('问题分类', 'javascript:Dwidget(\'?file=type&item='.$file.'\', \'问题分类\');'),
);
$_status = array('待受理', '<span style="color:blue;">受理中</span>', '<span style="color:green;">已解决</span>', '<span style="color:red;">未解决</span>');
$dstatus = array('待受理', '受理中', '已解决', '未解决');
$stars = array('未评分', '<span style="color:red;">不满意</span>', '基本满意', '<span style="color:green;">非常满意</span>');
switch($action) {
	case 'edit':
		$itemid or msg();
		$a = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid");
		$a or msg();
		if($submit) {
			if($status > 1 && strlen($reply) < 5) msg('请填写回复内容');
			$reply = addslashes(save_remote(save_local(stripslashes($reply))));
			$db->query("UPDATE {$DT_PRE}ask SET status=$status,editor='$_username',edittime='$DT_TIME',reply='$reply' WHERE itemid=$itemid");
			if($status > 1) {
				$msg = isset($msg) ? 1 : 0;
				$eml = isset($eml) ? 1 : 0;
				$sms = isset($sms) ? 1 : 0;
				$wec = isset($wec) ? 1 : 0;
				if($msg == 0) $sms = $wec = 0;
				if($msg || $eml || $sms || $wec) {
					$linkurl = $MOD['linkurl'].'ask.php?action=show&itemid='.$itemid;
					$subject = '您的[问题]'.dsubstr($a['title'], 30, '...').'(流水号:'.$a['itemid'].')已经回复';
					$content = '尊敬的会员：<br/>您的[问题]'.$a['title'].'(流水号:'.$a['itemid'].')已经回复！<br/>';
					$content .= '请点击下面的链接查看详情：<br/>';
					$content .= '<a href="'.$linkurl.'" target="_blank">'.$linkurl.'</a><br/>';
					$user = userinfo($a['username']);
					if($msg) send_message($user['username'], $subject, $content);
					if($eml) send_mail($user['email'], $subject, $content);
					if($sms) send_sms($user['mobile'], $subject.$DT['sms_sign']);
					if($wec) send_weixin($user['username'], $subject);
				}
			}
			dmsg('受理成功', '?moduleid='.$moduleid.'&file='.$file.'&status='.$status);
		} else {
			extract($a);
			if($status == 0) {
				$status = 1;
				$db->query("UPDATE {$DT_PRE}ask SET status=1,edittime=$DT_TIME WHERE itemid=$itemid");
			}
			$addtime = timetodate($addtime, 6);
			$edittime = timetodate($edittime, 6);
			include tpl('ask_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg();
		$db->query("DELETE FROM {$DT_PRE}ask WHERE itemid=$itemid ");
		dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file);
	break;
	default:
		$sfields = array('按条件', '标题', '内容', '会员名', '回复', '受理人');
		$dfields = array('title', 'title', 'content', 'username', 'reply', 'editor');
		$sorder  = array('结果排序方式', '提交时间降序', '提交时间升序', '受理时间降序', '受理时间升序', '会员评分降序', '会员评分升序');
		$dorder  = array('itemid DESC', 'itemid DESC', 'itemid ASC', 'edittime DESC', 'edittime ASC', 'star DESC', 'star ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($typeid) or $typeid = 0;
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : 0;
		$star = isset($star) && isset($stars[$star]) ? intval($star) : '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select   = type_select($TYPE, 1, 'typeid', '请选择分类', $typeid);
		$status_select = dselect($dstatus, 'status', '受理状态', $status, '', 1, '', 1);
		$star_select = dselect($stars, 'star', '评分', $star, '', 1, '', 1);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid > 0) $condition .= " AND typeid=$typeid";
		if($status !== '') $condition .= " AND status=$status";
		if($star !== '') $condition .= " AND star=$star";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}ask WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$asks = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}ask WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = $r['edittime'] ? timetodate($r['edittime'], 5) : 'N/A';
			$r['dstatus'] = $_status[$r['status']];
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : '默认';
			$asks[] = $r;
		}
		include tpl('ask', $module);
	break;
}
?>