<?php
defined('IN_DESTOON') or exit('Access Denied');
#买家订单管理
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
include load('order.lang');
$_status = $L['trade_status'];
$dstatus = $L['trade_dstatus'];
$_send_status = $L['send_status'];
$dsend_status = $L['send_dstatus'];
$step = isset($step) ? trim($step) : '';
$timenow = timetodate($DT_TIME, 3);
$memberurl = $DT_PC ? $MOD['linkurl'] : $MOD['mobile'];
$myurl = userurl($_username);
$table = $DT_PRE.'order';
$STARS = $L['star_type'];
if($action == 'update') {
	$itemid or message();
	$td = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	$td or message($L['trade_msg_null']);
	if($td['buyer'] != $_username || $td['pid'] > 0) message($L['trade_msg_deny']);
	$td['total'] = $td['amount'] + $td['fee'];
	$td['total'] = number_format($td['total'], 2, '.', '');
	$td['money'] = $td['amount'] + $td['discount'];
	$td['money'] = number_format($td['money'], 2, '.', '');
	$td['adddate'] = timetodate($td['addtime'], 5);
	$td['updatedate'] = timetodate($td['updatetime'], 5);
	$td['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$td['mid'].'&itemid='.$td['mallid'];
	$td['par'] = '';
	if(strpos($td['note'], '|') !== false) list($td['note'], $td['par']) = explode('|', $td['note']);
	$lists = array($td);
	if(($td['amount'] + $td['discount']) > $td['price']*$td['number']) {
		$result = $db->query("SELECT * FROM {$table} WHERE pid=$itemid ORDER BY itemid DESC");
		while($r = $db->fetch_array($result)) {
			$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$r['mid'].'&itemid='.$r['mallid'];
			$r['par'] = '';
			if(strpos($r['note'], '|') !== false) list($r['note'], $r['par']) = explode('|', $r['note']);
			$lists[] = $r;
		}
	}
	$mallid = $td['mallid'];
	switch($step) {
		case 'detail'://订单详情
			$auth = encrypt('mall|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');
			$head_title = $L['trade_detail_title'];
		break;
		case 'express'://快递追踪
			($td['send_type'] && $td['send_no']) or dheader('?action=update&step=detail&itemid='.$itemid);
			$auth = encrypt('mall|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');
			$head_title = $L['trade_exprss_title'];
		break;
		case 'pay'://买家付款
			if($td['status'] == 2) dmsg($L['trade_pay_order_success'], '?nav=2&itemid='.$itemid);
			if($td['status'] == 0) message($L['trade_msg_confirm'], '?action=update&step=detail&itemid='.$itemid);
			if($td['status'] != 1) message($L['trade_msg_deny']);
			$money = $td['amount'] + $td['fee'];
			$money > 0 or message($L['trade_msg_deny']);
			$seller = userinfo($td['seller']);
			$auto = 0;
			$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
			if($auth && substr($auth, 0, 6) == 'trade|') {				
				$_itemid = intval(substr($auth, 6));
				if($_itemid == $itemid) $auto = $submit = 1;
			}
			if($submit) {
				$money <= $_money or message($L['money_not_enough']);
				if($money <= $DT['quick_pay']) $auto = 1;
				if(!$auto) {
					is_payword($_username, $password) or message($L['error_payword']);
				}
				money_add($_username, -$money);
				money_record($_username, -$money, $L['in_site'], 'system', $L['trade_pay_order_title'], $L['trade_order_id'].$itemid);
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=2,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
				}
				$touser = $td['seller'];
				$title = lang($L['trade_message_t2'], array($itemid));
				$url = $memberurl.'trade.php?itemid='.$itemid;
				$content = lang($L['trade_message_c2'], array($myurl, $_username, $timenow, $url));
				$content = ob_template('messager', 'mail');
				send_message($touser, $title, $content);			
				//send sms
				if($DT['sms'] && $_sms && $touser && isset($sendsms)) {
					$touser = userinfo($touser);
					if($touser['mobile']) {
						$message = lang('sms->ord_pay', array($itemid, $money));
						$message = strip_sms($message);
						$word = word_count($message);
						$sms_num = ceil($word/$DT['sms_len']);
						if($sms_num <= $_sms) {
							$sms_code = send_sms($touser['mobile'], $message, $word);
							if(strpos($sms_code, $DT['sms_ok']) !== false) {
								$tmp = explode('/', $sms_code);
								if(is_numeric($tmp[1])) $sms_num = $tmp[1];
								if($sms_num) sms_add($_username, -$sms_num);
								if($sms_num) sms_record($_username, -$sms_num, $_username, $L['trade_sms_pay'], $itemid);
							}
						}
					}
				}
				//send sms
				//更新商品数据
				foreach($lists as $k=>$v) {
					if($MODULE[$v['mid']]['module'] == 'mall') {
						$db->query("UPDATE ".get_table($v['mid'])." SET orders=orders+1,sales=sales+$v[number],amount=amount-$v[number] WHERE itemid=$v[mallid]");
					} else {
						$db->query("UPDATE ".get_table($v['mid'])." SET amount=amount-$v[number] WHERE itemid=$v[mallid]");
					}
				}
				dmsg($L['trade_pay_order_success'], '?nav=2&itemid='.$itemid);
			} else {
				$head_title = $L['trade_pay_order_title'];
			}
		break;
		case 'refund'://买家退款
			$gone = $DT_TIME - $td['updatetime'];
			if(!in_array($td['status'], array(2, 3))) message($L['trade_msg_deny']);
			if($td['status'] == 3 && $gone > ($MOD['trade_day']*86400 + $td['add_time']*3600)) message($L['trade_msg_deny']);
			$money = $td['amount'] + $td['fee'];
			if($submit) {
				$content or message($L['trade_refund_reason']);
				clear_upload($content, $itemid, $table);
				$content = dsafe(addslashes(save_remote(save_local(stripslashes($content)))));
				is_payword($_username, $password) or message($L['error_payword']);
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=5,updatetime=$DT_TIME,buyer_reason='$content' WHERE itemid=$v[itemid]");
				}
				message($L['trade_refund_success'], $forward, 3);
			} else {
				$head_title = $L['trade_refund_title'];
			}
		break;
		case 'remind'://买家提醒卖家发货			
			if($td['status'] != 2) message($L['trade_msg_deny']);
		break;
		case 'receive_goods'://确认收货
			$gone = $DT_TIME - $td['updatetime'];
			if($td['status'] != 3 || $gone > ($MOD['trade_day']*86400 + $td['add_time']*3600)) message($L['trade_msg_deny']);
			//交易成功
			$money = $td['amount'] + $td['fee'];
			money_add($td['seller'], $money);
			money_record($td['seller'], $money, $L['in_site'], 'system', $L['trade_record_pay'], $L['trade_order_id'].$itemid);
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
			foreach($lists as $k=>$v) {
				$db->query("UPDATE {$table} SET status=4,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
			}
			$touser = $td['seller'];
			$title = lang($L['trade_message_t4'], array($itemid));
			$url = $memberurl.'trade.php?itemid='.$itemid;
			$content = lang($L['trade_message_c4'], array($myurl, $_username, $timenow, $url));
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);

			message($L['trade_success'], $forward, 3);
		break;
		case 'comment'://交易评价
			if($MODULE[$td['mid']]['module'] != 'mall') message($L['trade_msg_deny_comment']);
			if($submit) {
				foreach($lists as $k=>$v) {
					$mid = $v['mid'];
					$itemid = $v['itemid'];
					$mallid = $v['mallid'];
					$star = intval($stars[$itemid]);
					in_array($star, array(1, 2, 3)) or $star = 3;
					$content = dhtmlspecialchars($contents[$itemid]);
					$db->query("UPDATE ".get_table($mid)." SET comments=comments+1 WHERE itemid=$mallid");
					$db->query("UPDATE {$table} SET seller_star=$star WHERE itemid=$itemid");
					$s = 's'.$star;
					$db->query("UPDATE {$DT_PRE}mall_comment_".$mid." SET seller_star=$star,seller_comment='$content',seller_ctime=$DT_TIME WHERE itemid=$itemid");
					$db->query("UPDATE {$DT_PRE}mall_stat_".$mid." SET scomment=scomment+1,`$s`=`$s`+1 WHERE mallid=$mallid");
				}
				message($L['trade_msg_comment_success'], $forward);
			}
		break;
		case 'comment_detail'://评价详情
			if($MODULE[$td['mid']]['module'] != 'mall') message($L['trade_msg_deny_comment']);
			$comments = $O = $C = array();
			foreach($lists as $k=>$v) {
				$id = $v['itemid'];
				$c = $db->get_one("SELECT * FROM {$DT_PRE}mall_comment_$v[mid] WHERE itemid=$id");
				$comments[$k] = $c;
				$O[$id] = $v;
				$C[$id] = $c;
			}
			if($submit) {
				$oid = intval($oid);
				if(isset($C[$oid])) {
					$content = dhtmlspecialchars($content);
					$content or message($L['trade_msg_empty_explain']);
					if($C[$oid]['seller_reply']) message($L['trade_msg_explain_again']);
					$db->query("UPDATE {$DT_PRE}mall_comment_".$O[$oid]['mid']." SET seller_reply='$content',seller_rtime=$DT_TIME WHERE itemid=$oid");
					dmsg($L['trade_msg_explain_success'], '?action='.$action.'&step='.$step.'&itemid='.$itemid);
				}
			}
		break;
		case 'close'://关闭交易
			if($td['status'] == 0) {
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=8,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
				}
				dmsg($L['trade_close_success'], $forward);
			} else if($td['status'] == 1) {
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=8,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
				}
				dmsg($L['trade_close_success'], $forward);
			} else if($td['status'] == 9) {
				foreach($lists as $k=>$v) {
					$db->query("DELETE FROM {$table} WHERE itemid=$v[itemid]");
				}
				dmsg($L['trade_delete_success'], $forward);
			} else {
				message($L['trade_msg_deny']);
			}
		break;
	}
} else if($action == 'muti') {//批量付款
	$auto = 0;	
	$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
	if($auth && substr($auth, 0, 7) == 'trades|') {				
		$auto = $submit = 1;
		$itemid = explode(',', substr($auth, 7));
	}
	if($submit) {
		($itemid && is_array($itemid)) or message($L['trade_msg_muti_choose']);
		$itemids = implode(',', $itemid);
		$condition = "pid=0 AND buyer='$_username' AND status=1 AND itemid IN ($itemids)";
		$tds = array();
		$money = 0;
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT 50");
		while($r = $db->fetch_array($result)) {
			$money += ($r['amount'] + $r['fee']);
			$tds[] = $r;
		}
		$money <= $_money or message($L['money_not_enough']);
		if($money <= $DT['quick_pay']) $auto = 1;
		if(!$auto) {
			is_payword($_username, $password) or message($L['error_payword']);
		}
		foreach($tds as $td) {
			$itemid = $td['itemid'];
			$mallid = $td['mallid'];
			$money = $td['amount'] + $td['fee'];
			$seller = userinfo($td['seller']);
			money_add($_username, -$money);
			money_record($_username, -$money, $L['in_site'], 'system', $L['trade_pay_order_title'], $L['trade_order_id'].$itemid);
			$lists = get_orders($itemid);
			foreach($lists as $k=>$v) {
				$db->query("UPDATE {$table} SET status=2,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
			}
			$touser = $td['seller'];
			$title = lang($L['trade_message_t2'], array($itemid));
			$url = $memberurl.'trade.php?itemid='.$itemid;
			$content = lang($L['trade_message_c2'], array($myurl, $_username, $timenow, $url));
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);			
			//send sms
			if($DT['sms'] && $_sms && $touser && isset($sendsms)) {
				$touser = userinfo($touser);
				if($touser['mobile']) {
					$message = lang('sms->ord_pay', array($itemid, $money));
					$message = strip_sms($message);
					$word = word_count($message);
					$sms_num = ceil($word/$DT['sms_len']);
					if($sms_num <= $_sms) {
						$sms_code = send_sms($touser['mobile'], $message, $word);
						if(strpos($sms_code, $DT['sms_ok']) !== false) {
							$tmp = explode('/', $sms_code);
							if(is_numeric($tmp[1])) $sms_num = $tmp[1];
							if($sms_num) sms_add($_username, -$sms_num);
							if($sms_num) sms_record($_username, -$sms_num, $_username, $L['trade_sms_pay'], $itemid);
						}
					}
				}
			}
			//send sms
			//更新商品数据
			foreach($lists as $k=>$v) {
				if($MODULE[$v['mid']]['module'] == 'mall') {
					$db->query("UPDATE ".get_table($v['mid'])." SET orders=orders+1,sales=sales+$v[number],amount=amount-$v[number] WHERE itemid=$v[mallid]");
				} else {
					$db->query("UPDATE ".get_table($v['mid'])." SET amount=amount-$v[number] WHERE itemid=$v[mallid]");
				}
			}
		}
		dmsg($L['trade_pay_order_success'], '?nav=2');
	} else {
		$ids = isset($ids) ? explode(',', $ids) : array();
		if($ids) $ids = array_map('intval', $ids);
		$condition = "pid=0 AND buyer='$_username' AND status=1";
		if($ids) $condition .= " AND itemid IN (".implode(',', $ids).")";
		$lists = $pids = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT 50");
		while($r = $db->fetch_array($result)) {
			if($r['amount'] > $r['price']*$r['number']) $pids[] = $r['itemid'];
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$r['mid'].'&itemid='.$r['mallid'];
			$r['par'] = '';
			if(strpos($r['note'], '|') !== false) list($r['note'], $r['par']) = explode('|', $r['note']);
			$r['dstatus'] = $_status[$r['status']];
			$r['money'] = $r['amount'] + $r['fee'];
			$r['money'] = number_format($r['money'], 2, '.', '');
			$lists[] = $r;
		}
		if($pids) {
			$result = $db->query("SELECT * FROM {$table} WHERE pid IN (".implode(',', $pids).") ORDER BY itemid DESC");
			while($r = $db->fetch_array($result)) {
				$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$r['mid'].'&itemid='.$r['mallid'];
				$r['par'] = '';
				if(strpos($r['note'], '|') !== false) list($r['note'], $r['par']) = explode('|', $r['note']);
				$tags[$r['pid']][] = $r;
			}
		}
		if(!$lists) {
			if($ids) dmsg($L['trade_pay_order_success'], '?nav=2');
			message($L['trade_msg_muti_empty'], '?nav=1', 5);
		}
		$head_title = $L['trade_muti_title'];
	}
} else if($action == 'express') {//我的快递
	$sfields = $L['express_sfields'];
	$dfields = array('title', 'title', 'send_type ', 'send_no');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$status = isset($status) && isset($dsend_status[$status]) ? intval($status) : '';
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dsend_status, 'status', $L['status'], $status, '', 1, '', 1);
	$condition = "pid=0 AND send_no<>'' AND buyer='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($status !== '') $condition .= " AND send_status='$status'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);	
	$lists = $pids = array();
	$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		if($r['amount'] > $r['price']*$r['number']) $pids[] = $r['itemid'];
		$r['addtime'] = timetodate($r['addtime'], 5);
		$r['updatetime'] = timetodate($r['updatetime'], 5);
		$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$r['mid'].'&itemid='.$r['mallid'];
		$r['dstatus'] = $_send_status[$r['send_status']];
		$lists[] = $r;
	}
	$head_title = $L['express_title'];
} else {
	$sfields = $L['trade_order_sfields'];
	$dfields = array('title', 'title ', 'amount', 'fee', 'fee_name', 'seller', 'send_type', 'send_no', 'note');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$mallid = isset($mallid) ? intval($mallid) : 0;
	$cod = isset($cod) ? intval($cod) : 0;
	$nav = isset($nav) ? intval($nav) : -1;
	(isset($seller) && check_name($seller)) or $seller = '';
	$fromdate = isset($fromdate) ? $fromdate : '';
	$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
	$todate = isset($todate) ? $todate : '';
	$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
	$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
	$condition = "buyer='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($fromtime) $condition .= " AND addtime>=$fromtime";
	if($totime) $condition .= " AND addtime<=$totime";
	if($status !== '') $condition .= " AND status='$status'";
	if($itemid) $condition .= " AND itemid='$itemid'";
	if($mallid) $condition .= " AND mallid=$mallid";
	if($seller) $condition .= " AND seller='$seller'";
	if($cod) $condition .= " AND cod=1";
	if(in_array($nav, array(0,1,2,3,5,6))) {
		$condition .= " AND status=$nav";
		$status = $nav;
	} else if($nav == 4) {
		$condition .= " AND status=$nav AND seller_star=0";
		$status = $nav;
	}
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dstatus, 'status', $L['status'], $status, '', 1, '', 1);
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$lists = $tags = $pids = array();
	$amount = $fee = $money = 0;
	$result = $db->query("SELECT pid,itemid FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		$pid = $r['pid'] ? $r['pid'] : $r['itemid'];
		$pids[$pid] = $pid;
	}
	if($pids) {
		$result = $db->query("SELECT * FROM {$table} WHERE itemid IN (".implode(',', $pids).") ORDER BY itemid DESC");
		while($r = $db->fetch_array($result)) {
			$r['gone'] = $DT_TIME - $r['updatetime'];
			if($r['status'] == 3) {
				if($r['gone'] > ($MOD['trade_day']*86400 + $r['add_time']*3600)) {
					$r['lefttime'] = 0;
				} else {
					$r['lefttime'] = secondstodate($MOD['trade_day']*86400 + $r['add_time']*3600 - $r['gone']);
				}
			}
			$r['par'] = '';
			if(strpos($r['note'], '|') !== false) list($r['note'], $r['par']) = explode('|', $r['note']);
			$r['addtime'] = timetodate($r['addtime'], $DT_PC ? 5 : 3);
			$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$r['mid'].'&itemid='.$r['mallid'];
			$r['dstatus'] = $_status[$r['status']];
			$r['money'] = $r['amount'] + $r['fee'];
			$r['money'] = number_format($r['money'], 2, '.', '');
			$amount += $r['amount'];
			$fee += $r['fee'];
			$lists[] = $r;
		}
		$result = $db->query("SELECT * FROM {$table} WHERE pid IN (".implode(',', $pids).") ORDER BY itemid DESC");
		while($r = $db->fetch_array($result)) {
			$r['par'] = '';
			if(strpos($r['note'], '|') !== false) list($r['note'], $r['par']) = explode('|', $r['note']);
			$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$r['mid'].'&itemid='.$r['mallid'];
			$tags[$r['pid']][] = $r;
		}
	}
	$money = $amount + $fee;
	$money = number_format($money, 2, '.', '');
	$head_title = $L['trade_order_title'];
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'update') {
		$back_link = '?action=index';
	} else if($action == 'order') {
		$back_link = '?action=index';
	} else if($action == 'express') {
		$back_link = ($kw || $page > 1) ? '?action='.$action : '?action=index';
	} else {
		$back_link = ($kw || $page > 1) ? '?action=index' : 'index.php';
	}
}
include template('order', $module);
?>