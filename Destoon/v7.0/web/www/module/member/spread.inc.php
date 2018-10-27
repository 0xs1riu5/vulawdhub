<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$MG['biz'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
$MG['spread'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
include load('extend.lang');
$menu_id = 2;
if($action == 'add') {
	if($kw) {
		$word = $kw;
	} else {
		$word = isset($word) ? dhtmlspecialchars(trim($word)) : '';
	}
	$_word = encrypt($word, DT_KEY.'CR');
	if($word && $mid > 3 && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) {
		$word = dhtmlspecialchars(trim($word));
		$this_month = date('n', $DT_TIME);
		$this_year  = date('Y', $DT_TIME);
		$next_month = $this_month == 12 ? 1 : $this_month + 1;
		$next_year  = $this_month == 12 ? $this_year + 1 : $this_year;
		$next_time = strtotime($next_year.'-'.$next_month.'-1');
		$spread_max = $EXT['spread_max'] ? $EXT['spread_max'] : 10;
		$currency = $EXT['spread_currency'];
		$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
		$r = $db->get_one("SELECT * FROM {$DT_PRE}spread WHERE username='$_username' AND mid=$mid AND word='$word' AND fromtime>=$next_time");
		if($r) message($L['spread_msg_buy'], $DT_PC ? $EXT['spread_url'] : $EXT['spread_mob']);
		$mid or $mid = 5;
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}spread WHERE mid=$mid AND status=3 AND word='$word' AND fromtime>=$next_time");
		if($r['num'] > $spread_max) message(lang($L['spread_msg_over'], array($word)), $DT_PC ? $EXT['spread_url'] : $EXT['spread_mob']);

		$price = dround($EXT['spread_price']);
		$p1 = $db->get_one("SELECT * FROM {$DT_PRE}spread_price WHERE mid=$mid AND word='$word' ORDER BY edittime DESC");
		if($p1) {
			$price = $p1['price'];
		} else {
			$p2 = $db->get_one("SELECT * FROM {$DT_PRE}spread_price WHERE mid=$mid AND word='' ORDER BY edittime DESC");
			if($p2) $price = $p2['price'];
		}
		$step = $EXT['spread_step'];
		$month = $EXT['spread_month'] ? $EXT['spread_month'] : 1;
		$auto = 0;
		$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
		if($auth && substr($auth, 0, 7) == 'spread|') {
			$auto = $submit = 1;
			$tmp = explode('|', $auth);
			$buy_price = dround($tmp[3]);
			$buy_month = intval($tmp[4]);
			$buy_tid = intval($tmp[5]);
		}
		if($submit) {
			$buy_price = dround($buy_price);
			if($buy_price < $price) message($L['spread_msg_price_min']);
			if(($buy_price-$price)%$step != 0) message($L['spread_msg_step']);
			$buy_month = intval($buy_month);
			if($buy_month < 1 || $buy_month > $month) message($L['spread_msg_month']);
			$amount = $buy_price*$buy_month;
			if($currency == 'money') {
				$amount <= $_money or message($L['money_not_enough']);
				if($amount <= $DT['quick_pay']) $auto = 1;
				if(!$auto) {
					is_payword($_username, $password) or message($L['error_payword']);
				}
			} else {
				$amount <= $_credit or message($L['credit_not_enough'], 'credit.php?action=buy&amount='.($amount-$_credit));
			}
			$buy_tid = $mid == 4 ? $_userid : intval($buy_tid);
			if(!$buy_tid) message($L['spread_msg_itemid']);
			if($mid == 5 || $mid == 6 || $mid == 16) {
				$table = get_table($mid);
				$item = $db->get_one("SELECT itemid FROM {$table} WHERE itemid='$buy_tid' AND status=3 AND username='$_username'");
				if(!$item) message($L['spread_msg_yours']);
			}
			$months = $next_month + $buy_month;
			$year = floor($months/12);
			if($months%12 == 0) {
				$to_month = 12;
				$to_year = $next_year + $year - 1;
			} else {
				$to_month = $months%12;
				$to_year = $next_year + $year;
			}
			$totime = strtotime($to_year.'-'.$to_month.'-1');
			$status = $EXT['spread_check'] ? 2 : 3;
			if($currency == 'money') {
				money_add($_username, -$amount);
				money_record($_username, -$amount, $L['in_site'], 'system', $MODULE[$mid]['name'].$L['spread_title'], $word.'('.$L['spread_infoid'].$buy_tid.')');
			} else {
				credit_add($_username, -$amount);
				credit_record($_username, -$amount, 'system', $MODULE[$mid]['name'].$L['spread_title'], $word.'(ID:'.$buy_tid.')');
			}
			$db->query("INSERT INTO {$DT_PRE}spread (mid,tid,word,price,currency,company,username,addtime,fromtime,totime,status) VALUES ('$mid','$buy_tid','$word','$buy_price','$currency','$_company','$_username','$DT_TIME','$next_time','$totime','$status')");
			dmsg($L['spread_msg_success'], '?status='.$status);
		} else {
			//
		}
	} else {
		dheader('?action=list&mid='.$mid);
	}
} else if($action == 'list') {
} else {
	$status = isset($status) ? intval($status) : 3;
	in_array($status, array(2, 3)) or $status = 3;
	$condition = "username='$_username' AND status=$status";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}spread WHERE $condition");
	$items = $r['num'];
	$pages = pages($items, $page, $pagesize);
	$lists = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}spread WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		if($r['totime'] < $DT_TIME) {
			$r['process'] = $L['status_expired'];
		} else if($r['fromtime'] > $DT_TIME) {
			$r['process'] = $L['status_not_start'];
		} else {
			$r['process'] = $L['status_displaying'];
		}
			$r['days'] = $r['totime'] > $DT_TIME ? intval(($r['totime']-$DT_TIME)/86400) : 0;
		$lists[] = $r;
	}
}
$head_title = $L['spread_title'];
$nums = array();
for($i = 2; $i < 4; $i++) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}spread WHERE username='$_username' AND status=$i");
	$nums[$i] = $r['num'];
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'list') {
		$back_link = '?action=index';
	} else if ($action == 'add') {
		$back_link = '?action=list';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=index' : 'biz.php';
	}
}
include template('spread', $module);
?>