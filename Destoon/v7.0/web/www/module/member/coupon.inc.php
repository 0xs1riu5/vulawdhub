<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/coupon.class.php';
$do = new coupon();
include load('message.lang');
switch($action) {
	case 'get':
		$itemid or dheader('?action=index');
		$t = $db->get_one("SELECT itemid FROM {$DT_PRE}finance_coupon WHERE username='$_username' AND pid=$itemid");
		if($t) message($L['coupon_msg_got'], '?action=index&pid='.$itemid);
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_promo WHERE itemid=$itemid");
		if(!$r || !$r['open']) message($L['coupon_msg_exists'], '?action=index');
		if($r['username'] == $_username) message($L['coupon_msg_self'], '?action=index');
		if($r['number'] > $r['amount']) message($L['coupon_msg_none'], '?action=index');
		if($r['fromtime'] > $DT_TIME) message($L['coupon_msg_time'], '?action=index');
		if($r['totime'] < $DT_TIME) message($L['coupon_msg_timeout'], '?action=index');
		$title = addslashes($r['title']);
		$db->query("INSERT INTO {$DT_PRE}finance_coupon (title,username,seller,addtime,fromtime,totime,price,cost,pid) VALUES ('$title','$_username','$r[username]','$DT_TIME','$r[fromtime]','$r[totime]','$r[price]','$r[cost]','$itemid')");
		$db->query("UPDATE {$DT_PRE}finance_promo SET number=number+1 WHERE itemid=$itemid");
		if(strpos($forward, 'username=') === false && strpos($forward, 'itemid=') === false) $forward = '?action=my';
		dmsg($L['coupon_msg_success'], $forward);
	break;
	case 'delete':
		$itemid or message($L['coupon_msg_choose']);
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $do->get_one();
			if($item && $item['username'] == $_username) $do->delete($itemid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	case 'my':
		$condition = "username='$_username'";
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		if($itemid) $condition .= " AND itemid=$itemid";
		$lists = $do->get_list($condition);
		$head_title = $L['coupon_title'];
	break;
	default:
		$condition = "fromtime<$DT_TIME AND totime>$DT_TIME AND number<amount AND open=1";
		isset($username) or $username = '';
		if(check_name($username)) $condition .= " AND username='$username'";
		if($itemid) $condition .= " AND itemid=$itemid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_promo WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);		
		$lists = $gets = $pids = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_promo WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['left'] = $r['amount'] - $r['number'];
			$pids[] = $r['itemid'];
			$lists[] = $r;
		}
		if($pids) {
			$ids = implode(',', $pids);
			$result = $db->query("SELECT * FROM {$DT_PRE}finance_coupon WHERE pid IN ($ids) AND username='$_username' ORDER BY itemid DESC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$gets[$r['pid']] = $r;
			}
		}
		$head_title = $L['coupon_promo_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'my') {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = '?action=index';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=index' : 'index.php';
	}
}
include template('coupon', $module);
?>