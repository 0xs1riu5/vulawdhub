<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array($MOD['name'].'管理', '?moduleid='.$moduleid),
    array('订单管理', '?moduleid='.$moduleid.'&file='.$file),
    array('快递管理', '?moduleid='.$moduleid.'&file='.$file.'&action=express'),
    array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&action=stats'),
);
include load('order.lang');
$_status = $L['group_status'];
$dstatus = $L['group_dstatus'];
$_send_status = $L['send_status'];
$dsend_status = $L['send_dstatus'];
$table = $table_order;
if($action == 'refund' || $action == 'show') {
	$itemid or msg('未指定记录');
	$td = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	$td or msg('记录不存在');
	$td['linkurl'] = DT_PATH.'api/redirect.php?mid=17&itemid='.$td['itemid'];
	$td['money'] = $td['amount'];
	$td['adddate'] = timetodate($td['addtime'], 6);
	$td['updatedate'] = timetodate($td['updatetime'], 6);
}
switch($action) {
	case 'stats':
		$year = isset($year) ? intval($year) : date('Y', $DT_TIME);
		$year or $year = date('Y', $DT_TIME);
		$month = isset($month) ? intval($month) : date('n', $DT_TIME);
		isset($seller) or $seller = '';
		$chart_data = '';
		$T1 = $T2 = 0;
		if($month) {
			$L = date('t', strtotime($year.'-'.$month.'-01'));
			for($i = 1; $i <= $L; $i++) {
				if($i > 1) $chart_data .= '\n';
				$chart_data .= $i;
				$F = strtotime($year.'-'.$month.'-'.$i.' 00:00:00');
				$T = strtotime($year.'-'.$month.'-'.$i.' 23:59:59');
				$condition = "addtime>=$F AND addtime<=$T";
				if($seller) $condition .= " AND seller='$seller'";
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE {$condition} AND status=3");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T1 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE {$condition} AND status=4");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T2 += $num;
			}
			$title = $year.'年'.$month.'月交易报表';
			if($seller) $title = '['.$seller.'] '.$title;
		} else {
			for($i = 1; $i < 13; $i++) {
				if($i > 1) $chart_data .= '\n';
				$chart_data .= $i;
				$F = strtotime($year.'-'.$i.'-01 00:00:00');
				$T = strtotime($year.'-'.$i.'-'.date('t', $F).' 23:59:59');
				$condition = "addtime>=$F AND addtime<=$T";
				if($seller) $condition .= " AND seller='$seller'";
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE {$condition} AND status=3");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T1 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE {$condition} AND status=4");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T2 += $num;
			}
			$title = $year.'年交易报表';
			if($seller) $title = '['.$seller.'] '.$title;
		}
		include tpl('order_stats', $module);
	break;
	case 'refund':
		if($td['status'] != 4) msg('此交易无需受理');
		if($submit) {
			isset($status) or msg('请指定受理结果');
			strlen($content) > 5 or msg('请填写操作理由');
			$content .= '[网站]';
			clear_upload($content, $itemid, $table);
			$content = dsafe(addslashes(save_remote(save_local(stripslashes($content)))));
			if($status == 5) {//已退款，买家胜 退款
				money_add($td['buyer'], $td['money']);
				money_record($td['buyer'], $td['money'], '站内', 'system', '订单退款', '团购单号:'.$itemid.'[网站]');
				$_msg = '受理成功，交易状态已经改变为 已退款给买家';
			} else if($status == 3) {//已退款，卖家胜 付款
				money_add($td['seller'], $td['money']);
				money_record($td['seller'], $td['money'], '站内', 'system', '交易成功', '团购单号:'.$itemid.'[网站]');
				//网站服务费
				$G = $db->get_one("SELECT groupid FROM {$DT_PRE}member WHERE username='".$td['seller']."'");
				$SG = cache_read('group-'.$G['groupid'].'.php');
				if($SG['commission']) {
					$fee = dround($money*$SG['commission']/100);
					if($fee > 0) {
						money_add($td['seller'], -$fee);
						money_record($td['seller'], -$fee, $L['in_site'], 'system', $L['trade_fee'], $L['trade_order_id'].$itemid);	
					}
				}
				$_msg = '受理成功，交易状态已经改变为 交易成功';
			} else {
				msg();
			}
			$db->query("UPDATE {$table} SET status=$status,editor='$_username',updatetime=$DT_TIME,refund_reason='$content' WHERE itemid=$itemid");
			$msg = isset($msg) ? 1 : 0;
			$eml = isset($eml) ? 1 : 0;
			$sms = isset($sms) ? 1 : 0;
			$wec = isset($wec) ? 1 : 0;
			if($msg == 0) $sms = $wec = 0;
			if($msg || $eml || $sms || $wec) {
				$reason = $content;
				$linkurl = $MODULE[2]['linkurl'].'group.php?action=update&step=detail&itemid='.$itemid;

				$result = ($status == 5 ? '退款成功' : '退款失败');
				$subject = '您的[团购订单]'.dsubstr($td['title'], 30, '...').'(单号:'.$td['itemid'].')'.$result;
				$content = '尊敬的会员：<br/>您的[团购订单]'.$td['title'].'(单号:'.$td['itemid'].')'.$result.'！<br/>';
				if($reason) $content .= '操作原因：<br/>'.$reason.'<br/>';
				$content .= '请点击下面的链接查看订单详情：<br/>';
				$content .= '<a href="'.$linkurl.'" target="_blank">'.$linkurl.'</a><br/>';
				$content .= '如果您对此操作有异议，请及时与网站联系。<br/>';
				$user = userinfo($td['buyer']);
				if($msg) send_message($user['username'], $subject, $content);
				if($eml) send_mail($user['email'], $subject, $content);
				if($sms) send_sms($user['mobile'], $subject.$DT['sms_sign']);
				if($wec) send_weixin($user['username'], $subject);

				$result = ($status == 5 ? '已经退款给买家' : '未退款给买家，交易成功');
				$subject = '您的[团购订单]'.dsubstr($td['title'], 30, '...').'(单号:'.$td['itemid'].')'.$result;
				$content = '尊敬的会员：<br/>您的[团购订单]'.$td['title'].'(单号:'.$td['itemid'].')'.$result.'！<br/>';
				if($reason) $content .= '操作原因：<br/>'.$reason.'<br/>';
				$content .= '请点击下面的链接查看订单详情：<br/>';
				$content .= '<a href="'.$linkurl.'" target="_blank">'.$linkurl.'</a><br/>';
				$content .= '如果您对此操作有异议，请及时与网站联系。<br/>';
				$user = userinfo($td['seller']);
				if($msg) send_message($user['username'], $subject, $content);
				if($eml) send_mail($user['email'], $subject, $content);
				if($sms) send_sms($user['mobile'], $subject.$DT['sms_sign']);
				if($wec) send_weixin($user['username'], $subject);
			}
			msg($_msg, $forward, 3);
		} else {
			include tpl('order_refund', $module);
		}
	break;
	case 'show':
		include tpl('order_show', $module);
	break;
	case 'delete':
		$itemid or msg('未选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$table} WHERE itemid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	case 'express':
		$sfields = array('按条件', '商品名称', '卖家', '买家', '订单金额', '密码', '买家名称', '买家地址', '买家邮编', '买家电话', '买家手机', '发货快递', '发货单号', '备注');
		$dfields = array('title', 'title', 'seller', 'buyer', 'amount', 'password', 'buyer_name', 'buyer_address', 'buyer_postcode', 'buyer_phone', 'buyer_mobile', 'send_type', 'send_no', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$status = isset($status) && isset($dsend_status[$status]) ? intval($status) : '';
		$itemid or $itemid = '';
		$gid = isset($gid) && $gid ? intval($gid) : '';
		isset($seller) or $seller = '';
		isset($buyer) or $buyer = '';
		isset($send_no) or $send_no = '';
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($dsend_status, 'status', '状态', $status, '', 1, '', 1);
		$condition = "send_no<>''";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($status !== '') $condition .= " AND send_status='$status'";
		if($seller) $condition .= " AND seller='$seller'";
		if($buyer) $condition .= " AND buyer='$buyer'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($gid) $condition .= " AND gid=$gid";
		if($send_no) $condition .= " AND send_no='$send_no'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['updatetime'] = timetodate($r['updatetime'], 5);
			$lists[] = $r;
		}
		include tpl('order_express', $module);
	break;
	default:
		$sfields = array('按条件', '商品名称', '卖家', '买家', '订单金额', '密码', '买家名称', '买家地址', '买家邮编', '买家电话', '买家手机', '发货快递', '发货单号', '备注');
		$dfields = array('title', 'title', 'seller', 'buyer', 'amount', 'password', 'buyer_name', 'buyer_address', 'buyer_postcode', 'buyer_phone', 'buyer_mobile', 'send_type', 'send_no', 'note');
		$sorder  = array('排序方式', '下单时间降序', '下单时间升序', '更新时间降序', '更新时间升序', '商品单价降序', '商品单价升序', '购买数量降序', '购买数量升序', '订单金额降序', '订单金额升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'updatetime DESC', 'updatetime ASC', 'price DESC', 'price ASC', 'number DESC', 'number ASC', 'amount DESC', 'amount ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
		$itemid or $itemid = '';
		$gid = isset($gid) && $gid ? intval($gid) : '';
		$id = isset($id) ? intval($id) : 0;
		isset($seller) or $seller = '';
		isset($buyer) or $buyer = '';
		isset($amounts) or $amounts = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($timetype) or $timetype = 'addtime';
		isset($mtype) or $mtype = 'money';
		isset($minamount) or $minamount = '';
		isset($maxamount) or $maxamount = '';
		$logistic = isset($logistic) ? intval($logistic) : '-1';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($dstatus, 'status', '状态', $status, '', 1, '', 1);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND $timetype>=$fromtime";
		if($totime) $condition .= " AND $timetype<=$totime";
		if($status !== '') $condition .= " AND status='$status'";
		if($seller) $condition .= " AND seller='$seller'";
		if($buyer) $condition .= " AND buyer='$buyer'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($gid) $condition .= " AND gid=$gid";
		if($id) $condition .= " AND gid=$id";
		if($mtype == 'money') $mtype = "`amount`+`fee`";
		if($minamount != '') $condition .= " AND $mtype>=$minamount";
		if($maxamount != '') $condition .= " AND $mtype<=$maxamount";
		if($logistic>-1) $condition .= " AND logistic=$logistic";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		$amount = $fee = $money = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = str_replace(' ', '<br/>', timetodate($r['addtime'], 5));
			$r['updatetime'] = str_replace(' ', '<br/>', timetodate($r['updatetime'], 5));
			$r['linkurl'] = DT_PATH.'api/redirect.php?mid='.$moduleid.'&itemid='.$r['gid'];
			$r['dstatus'] = $_status[$r['status']];
			$r['money'] = $r['amount'];
			$amount += $r['amount'];
			$lists[] = $r;
		}
		$money = $amount + $fee;
		include tpl('order', $module);
	break;
}
?>