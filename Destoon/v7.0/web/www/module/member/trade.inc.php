<?php
defined('IN_DESTOON') or exit('Access Denied');
#卖家订单管理
login();
($MG['biz'] && $MG['trade_order']) or dalert(lang('message->without_permission_and_upgrade'), 'goback');
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
$menu_id = 2;
if($action == 'update') {
	$itemid or message();
	$td = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	$td or message($L['trade_msg_null']);
	if($td['seller'] != $_username || $td['pid'] > 0) message($L['trade_msg_deny']);
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
		case 'edit_price'://修改价格||确认订单||修改为货到付款
			if($td['status'] > 1) message($L['trade_msg_deny']);
			if($submit) {
				$fee = dround($fee);
				if($fee < 0 && $fee < -$td['amount']) message(lang($L['trade_msg_less_fee'], array(-$td['amount'])));
				$fee_name = dhtmlspecialchars(trim($fee_name));
				$status = isset($confirm_order) ? 1 : 0;
				$cod = 0;
				if(isset($edit_cod)) {
					$cod = 1;
					$status = 7;
				}
				$db->query("UPDATE {$table} SET fee='$fee',fee_name='$fee_name',status=$status,cod=$cod,updatetime=$DT_TIME WHERE itemid=$itemid");
				foreach($lists as $k=>$v) {
					if($v['itemid'] != $itemid) $db->query("UPDATE {$table} SET status=$status,cod=$cod,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
				}
				if(isset($confirm_order)) {
					$touser = $td['buyer'];
					$title = lang($L['trade_message_t1'], array($itemid));
					$url = $memberurl.'order.php?itemid='.$itemid;
					$content = lang($L['trade_message_c1'], array($myurl, $_username, $timenow, $url));
					$content = ob_template('messager', 'mail');
					send_message($touser, $title, $content);
					//send sms
					if($DT['sms'] && $_sms && $touser && isset($sendsms)) {
						$touser = userinfo($touser);
						if($touser['mobile']) {
							$message = lang('sms->ord_confirm', array($itemid));
							$message = strip_sms($message);
							$word = word_count($message);
							$sms_num = ceil($word/$DT['sms_len']);
							if($sms_num <= $_sms) {
								$sms_code = send_sms($touser['mobile'], $message, $word);
								if(strpos($sms_code, $DT['sms_ok']) !== false) {
									$tmp = explode('/', $sms_code);
									if(is_numeric($tmp[1])) $sms_num = $tmp[1];
									if($sms_num) sms_add($_username, -$sms_num);
									if($sms_num) sms_record($_username, -$sms_num, $_username, $L['trade_sms_confirm'], $itemid);
								}
							}
						}
					}
					//send sms
				}
				message($L['trade_price_edit_success'], $forward, 3);
			} else {
				$confirm = isset($confirm) ? 1 : 0;
				$head_title = $L['trade_price_title'];
			}
		break;
		case 'detail'://订单详情
			$auth = encrypt('mall|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');
			$head_title = $L['trade_detail_title'];
		break;
		case 'print'://订单打印
			include template('trade_print', $module);
			exit;
		break;
		case 'express'://快递追踪
			($td['send_type'] && $td['send_no']) or dheader('?action=update&step=detail&itemid='.$itemid);
			$auth = encrypt('mall|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');
			$head_title = $L['trade_exprss_title'];
		break;
		case 'refund_agree'://卖家同意买家退款
			if($td['status'] != 5) message($L['trade_msg_deny']);
			$money = $td['amount'] + $td['fee'];
			if($submit) {
				$content .= $L['trade_refund_by_seller'];
				clear_upload($content, $itemid, $table);
				$content = dsafe(addslashes(save_remote(save_local(stripslashes($content)))));
				is_payword($_username, $password) or message($L['error_payword']);
				money_add($td['buyer'], $money);
				money_record($td['buyer'], $money, $L['in_site'], 'system', $L['trade_refund'], $L['trade_order_id'].$itemid.$L['trade_refund_by_seller']);
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=6,editor='$_username',updatetime=$DT_TIME,refund_reason='$content' WHERE itemid=$v[itemid]");
				}
				//更新商品数据 增加库存
				foreach($lists as $k=>$v) {
					if($MODULE[$v['mid']]['module'] == 'mall') {
						$db->query("UPDATE ".get_table($v['mid'])." SET orders=orders-1,sales=sales-$v[number],amount=amount+$v[number] WHERE itemid=$v[mallid]");
					} else {
						$db->query("UPDATE ".get_table($v['mid'])." SET amount=amount+$v[number] WHERE itemid=$v[mallid]");
					}
				}
				message($L['trade_refund_agree_success'], $forward, 3);
			} else {
				$head_title = $L['trade_refund_agree_title'];
			}
		break;
		case 'send_goods'://卖家发货
			if(($td['status'] != 2 && $td['status'] != 7)) message($L['trade_msg_deny']);
			if($submit) {
				is_date($send_time) or message($L['msg_express_date_error']);
				$send_type = trim(dhtmlspecialchars($send_type));
				$send_no = trim(dhtmlspecialchars($send_no));
				$status = $td['status'] == 7 ? 7 : 3;
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=$status,updatetime=$DT_TIME,send_type='$send_type',send_no='$send_no',send_time='$send_time' WHERE itemid=$v[itemid]");
				}
				$touser = $td['buyer'];
				$title = lang($L['trade_message_t3'], array($itemid));
				$url = $memberurl.'order.php?itemid='.$itemid;
				$content = lang($L['trade_message_c3'], array($myurl, $_username, $timenow, $url));
				$content = ob_template('messager', 'mail');
				send_message($touser, $title, $content);
			
				//send sms
				if($DT['sms'] && $_sms && $touser && isset($sendsms)) {
					$touser = userinfo($touser);
					if($touser['mobile']) {
						$message = lang('sms->ord_send', array($itemid, $send_type, $send_no, $send_time));
						$message = strip_sms($message);
						$word = word_count($message);
						$sms_num = ceil($word/$DT['sms_len']);
						if($sms_num <= $_sms) {
							$sms_code = send_sms($touser['mobile'], $message, $word);
							if(strpos($sms_code, $DT['sms_ok']) !== false) {
								$tmp = explode('/', $sms_code);
								if(is_numeric($tmp[1])) $sms_num = $tmp[1];
								if($sms_num) sms_add($_username, -$sms_num);
								if($sms_num) sms_record($_username, -$sms_num, $_username, $L['trade_sms_send'], $itemid);
							}
						}
					}
				}
				//send sms
				
				//更新商品数据 限货到付款的商品
				if($td['cod']) {
					foreach($lists as $k=>$v) {
						if($MODULE[$v['mid']]['module'] == 'mall') {
							$db->query("UPDATE ".get_table($v['mid'])." SET orders=orders+1,sales=sales+$v[number],amount=amount-$v[number] WHERE itemid=$v[mallid]");
						} else {
							$db->query("UPDATE ".get_table($v['mid'])." SET amount=amount-$v[number] WHERE itemid=$v[mallid]");
						}
					}
				}
				message($L['trade_send_success'], $forward, 3);
			} else {
				$head_title = $L['trade_send_title'];
				$send_types = explode('|', trim($MOD['send_types']));
				$send_time = timetodate($DT_TIME, 3);
			}
		break;
		case 'cod_success'://货到付款，确认完成
			if($td['status'] != 7 || !$td['cod'] || !$td['send_time']) message($L['trade_msg_deny']);
			foreach($lists as $k=>$v) {
				$db->query("UPDATE {$table} SET status=4,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
			}
			//交易成功
			message($L['trade_success'], $forward, 3);
			
		break;
		case 'add_time'://增加确认收货时间
			if($td['status'] != 3) message($L['trade_msg_deny']);
			if($submit) {
				$add_time = intval($add_time);
				$add_time > 0 or message($L['trade_addtime_null']);
				$add_time = $td['add_time'] + $add_time;
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET add_time='$add_time' WHERE itemid=$v[itemid]");
				}
				message($L['trade_addtime_success'], $forward);
			} else {
				$head_title = $L['trade_addtime_title'];
			}
		break;
		case 'get_pay'://买家确认超时 卖家申请直接付款
			$gone = $DT_TIME - $td['updatetime'];
			if($td['status'] != 3 || $gone < ($MOD['trade_day']*86400 + $td['add_time']*3600)) message($L['trade_msg_deny']);
			//交易成功
			$money = $td['amount'] + $td['fee'];
			money_add($td['seller'], $money);
			money_record($td['seller'], $money, $L['in_site'], 'system', $L['trade_record_pay'], lang($L['trade_buyer_timeout'], array($itemid)));
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
					$db->query("UPDATE {$table} SET buyer_star=$star WHERE itemid=$itemid");
					$s = 'b'.$star;
					$db->query("UPDATE {$DT_PRE}mall_comment_".$mid." SET buyer_star=$star,buyer_comment='$content',buyer_ctime=$DT_TIME WHERE itemid=$itemid");
					$db->query("UPDATE {$DT_PRE}mall_stat_".$mid." SET bcomment=bcomment+1,`$s`=`$s`+1 WHERE mallid=$mallid");
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
					if($C[$oid]['buyer_reply']) message($L['trade_msg_explain_again']);
					$db->query("UPDATE {$DT_PRE}mall_comment_".$O[$oid]['mid']." SET buyer_reply='$content',buyer_rtime=$DT_TIME WHERE itemid=$oid");
					dmsg($L['trade_msg_explain_success'], '?action='.$action.'&step='.$step.'&itemid='.$itemid);
				}
			}
		break;
		case 'close'://关闭交易
			if($td['status'] == 0) {
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=9,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
				}
				dmsg($L['trade_close_success'], $forward);
			} else if($td['status'] == 1) {
				foreach($lists as $k=>$v) {
					$db->query("UPDATE {$table} SET status=9,updatetime=$DT_TIME WHERE itemid=$v[itemid]");
				}
				dmsg($L['trade_close_success'], $forward);
			} else if($td['status'] == 8) {
				foreach($lists as $k=>$v) {
					$db->query("DELETE FROM {$table} WHERE itemid=$v[itemid]");
				}
				dmsg($L['trade_delete_success'], $forward);
			} else { 
				message($L['trade_msg_deny']);
			}
		break;
	}
} else if($action == 'muti') {//批量发货
	if($submit) {
		($itemid && is_array($itemid)) or message($L['trade_msg_muti_choose']);
		is_date($send_time) or message($L['msg_express_date_error']);
		$send_type = trim(dhtmlspecialchars($send_type));
		$itemids = implode(',', $itemid);
		$condition = "pid=0 AND seller='$_username' AND status=2 AND itemid IN ($itemids)";
		$tds = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT 50");
		while($r = $db->fetch_array($result)) {
			$tds[] = $r;
		}
		foreach($tds as $td) {
			$itemid = $td['itemid'];
			$send_no = trim(dhtmlspecialchars($send_nos[$itemid]));
			$status = $td['status'] == 7 ? 7 : 3;
			$lists = get_orders($itemid);
			foreach($lists as $k=>$v) {
				$db->query("UPDATE {$table} SET status=$status,updatetime=$DT_TIME,send_type='$send_type',send_no='$send_no',send_time='$send_time' WHERE itemid=$v[itemid]");
			}
			$touser = $td['buyer'];
			$title = lang($L['trade_message_t3'], array($itemid));
			$url = $memberurl.'order.php?itemid='.$itemid;
			$content = lang($L['trade_message_c3'], array($myurl, $_username, $timenow, $url));
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);
			
			//send sms
			if($DT['sms'] && $_sms && $touser && isset($sendsms)) {
				$touser = userinfo($touser);
				if($touser['mobile']) {
					$message = lang('sms->ord_send', array($itemid, $send_type, $send_no, $send_time));
					$message = strip_sms($message);
					$word = word_count($message);
					$sms_num = ceil($word/$DT['sms_len']);
					if($sms_num <= $_sms) {
						$sms_code = send_sms($touser['mobile'], $message, $word);
						if(strpos($sms_code, $DT['sms_ok']) !== false) {
							$tmp = explode('/', $sms_code);
							if(is_numeric($tmp[1])) $sms_num = $tmp[1];
							if($sms_num) sms_add($_username, -$sms_num);
							if($sms_num) sms_record($_username, -$sms_num, $_username, $L['trade_sms_send'], $itemid);
							$_sms = $_sms - $sms_num;
						}
					}
				}
			}
			//send sms
			
			//更新商品数据 限货到付款的商品
			if($td['cod']) {
				foreach($lists as $k=>$v) {
					if($MODULE[$v['mid']]['module'] == 'mall') {
						$db->query("UPDATE ".get_table($v['mid'])." SET orders=orders+1,sales=sales+$v[number],amount=amount-$v[number] WHERE itemid=$v[mallid]");
					} else {
						$db->query("UPDATE ".get_table($v['mid'])." SET amount=amount-$v[number] WHERE itemid=$v[mallid]");
					}
				}
			}
		}
		dmsg($L['trade_send_success'], '?action=muti');
	} else {
		$sfields = $L['trade_sfields'];
		$dfields = array('title', 'title ', 'amount', 'fee', 'fee_name', 'buyer', 'buyer_name', 'buyer_address', 'buyer_postcode', 'buyer_mobile', 'buyer_phone', 'send_type', 'send_no', 'note');
		$mallid = isset($mallid) ? intval($mallid) : 0;
		$cod = isset($cod) ? intval($cod) : 0;
		$nav = isset($nav) ? intval($nav) : -1;
		(isset($buyer) && check_name($buyer)) or $buyer = '';
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
		$condition = "pid=0 AND seller='$_username' AND status=2";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($status !== '') $condition .= " AND status='$status'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($mallid) $condition .= " AND mallid=$mallid";
		if($buyer) $condition .= " AND buyer='$buyer'";
		if($cod) $condition .= " AND cod=1";
		if(in_array($nav, array(0,1,2,3,5,6))) {
			$condition .= " AND status=$nav";
			$status = $nav;
		} else if($nav == 4) {
			$condition .= " AND status=$nav AND buyer_star=0";
			$status = $nav;
		}
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($dstatus, 'status', $L['status'], $status, '', 1, '', 1);		
		$lists = $pids = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT 100");
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
		$send_types = explode('|', trim($MOD['send_types']));
		$send_time = timetodate($DT_TIME, 3);
		$t = $db->get_one("SELECT send_type FROM {$table} WHERE seller='$_username' AND send_type<>'' ORDER BY itemid DESC");
		$send_type = $t ? $t['send_type'] : '';
		$head_title = $L['trade_muti_send_title'];
	}
} else if($action == 'express') {//我的快递
	$sfields = $L['express_sfields'];
	$dfields = array('title', 'title', 'send_type ', 'send_no');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$status = isset($status) && isset($dsend_status[$status]) ? intval($status) : '';
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dsend_status, 'status', $L['status'], $status, '', 1, '', 1);
	$condition = "pid=0 AND send_no<>'' AND seller='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($status !== '') $condition .= " AND send_status='$status'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$lists = array();
	$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		$r['addtime'] = timetodate($r['addtime'], 5);
		$r['updatetime'] = timetodate($r['updatetime'], 5);
		$r['dstatus'] = $_send_status[$r['send_status']];
		$lists[] = $r;
	}
	$head_title = $L['express_title'];
} else {
	$sfields = $L['trade_sfields'];
	$dfields = array('title', 'title ', 'amount', 'fee', 'fee_name', 'buyer', 'buyer_name', 'buyer_address', 'buyer_postcode', 'buyer_mobile', 'buyer_phone', 'send_type', 'send_no', 'note');
	$mallid = isset($mallid) ? intval($mallid) : 0;
	$cod = isset($cod) ? intval($cod) : 0;
	$nav = isset($nav) ? intval($nav) : -1;
	(isset($buyer) && check_name($buyer)) or $buyer = '';
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$fromdate = isset($fromdate) ? $fromdate : '';
	$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
	$todate = isset($todate) ? $todate : '';
	$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
	$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
	$condition = "seller='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($fromtime) $condition .= " AND addtime>=$fromtime";
	if($totime) $condition .= " AND addtime<=$totime";
	if($status !== '') $condition .= " AND status='$status'";
	if($itemid) $condition .= " AND itemid=$itemid";
	if($mallid) $condition .= " AND mallid=$mallid";
	if($buyer) $condition .= " AND buyer='$buyer'";
	if($cod) $condition .= " AND cod=1";
	if(in_array($nav, array(0,1,2,3,5,6))) {
		$condition .= " AND status=$nav";
		$status = $nav;
	} else if($nav == 4) {
		$condition .= " AND status=$nav AND buyer_star=0";
		$status = $nav;
	}
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dstatus, 'status', $L['status'], $status, '', 1, '', 1);
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$orders = $r['num'];
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
	$head_title = $L['trade_title'];
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'update') {
		$back_link = '?action=index';
	} else if($action == 'muti') {
		$back_link = '?action=index';
	} else if($action == 'express') {
		$back_link = ($kw || $page > 1) ? '?action='.$action : '?action=index';
	} else {
		$back_link = ($kw || $page > 1) ? '?action=index' : 'biz.php';
	}
}
include template('trade', $module);
?>