<?php
defined('IN_DESTOON') or exit('Access Denied');
function update_company_setting($userid, $setting) {
	$S = get_company_setting($userid);
	foreach($setting as $k=>$v) {
		if(!check_name($k)) continue;
		if(is_array($v)) {
			foreach($v as $i=>$j) {
				$v[$i] = str_replace(',', '', $j);
			}
			$v = implode(',', $v);
		}
		if(isset($S[$k])) {
			DB::query("UPDATE ".DT_PRE."company_setting SET item_value='$v' WHERE userid=$userid AND item_key='$k'");
		} else {
			DB::query("INSERT INTO ".DT_PRE."company_setting (userid,item_key,item_value) VALUES ('$userid','$k','$v')");
		}
	}
	return true;
}

function get_company_setting($userid, $key = '', $cache = '') {
	if($key) {
		$r = DB::get_one("SELECT * FROM ".DT_PRE."company_setting WHERE userid=$userid AND item_key='$key'", $cache);
		return $r ? $r['item_value'] : '';
	} else {
		$setting = array();
		$query = DB::query("SELECT * FROM ".DT_PRE."company_setting WHERE userid=$userid", $cache);
		while($r = DB::fetch_array($query)) {
			$setting[$r['item_key']] = $r['item_value'];
		}
		return $setting;
	}
}

function max_sms($mobile) {
	global $DT, $L, $today_endtime, $_username;
	$max = intval($DT['sms_max']);
	if($max) {
		$condition = $_username ? "editor='$_username'" : "mobile='$mobile'";
		$condition .= " AND message LIKE '%".$L['sms_code']."%' AND sendtime>$today_endtime-86400";
		$items = DB::count(DT_PRE.'sms', $condition);
		if($items >= $max) return true;
	}
	return false;
}

function get_paylist() {
	global $DT_PC, $DT_MOB;
	$PAY = cache_read('pay.php');
	if($DT_PC) {
		$PAY['aliwap']['enable'] = 0;
	} else {
		if($PAY['aliwap']['enable']) {
			$PAY['alipay']['enable'] = 0;
			$tmp = $PAY['aliwap'];
			unset($PAY['aliwap']);
			$PAY = array_merge(array('aliwap'=>$tmp), $PAY);
		} else {
			if($PAY['alipay']['enable']) {
				$tmp = $PAY['alipay'];
				unset($PAY['alipay']);
				$PAY = array_merge(array('alipay'=>$tmp), $PAY);
			}
		}
	}
	if($DT_MOB['browser'] == 'weixin' && $PAY['weixin']['enable']) {
		$tmp = $PAY['weixin'];
		unset($PAY['weixin']);
		$PAY = array_merge(array('weixin'=>$tmp), $PAY);
	}
	$bank = get_cookie('pay_bank');
	if($bank && $PAY[$bank]['enable']) {
		$tmp = $PAY[$bank];
		unset($PAY[$bank]);
		$PAY = array_merge(array($bank=>$tmp), $PAY);
	}
	$P = array();
	foreach($PAY as $k=>$v) {
		if($v['enable']) {
			$v['bank'] = $k;
			$P[] = $v;
		}
	}
	return $P;
}

function get_chat_id($f, $t) {
	return md5(strcmp($f, $t) > 0 ? $f.'|'.$t : $t.'|'.$f);
}

function get_chat_tb($chatid) {
	$k = 0;
	for($i = 0; $i < 32; $i++) {
		if(is_numeric($chatid{$i})) {$k = $chatid{$i}; break;}
	}
	return DT_PRE.'chat_data_'.$k;
}

function emoji_decode($str){
    return preg_replace_callback('/\[emoji\](.+?)\[\/emoji\]/s', "_emoji_decode", $str);
}

function _emoji_decode($matches) {
	return rawurldecode($matches[1]);;
}

function get_orders($itemid) {
	$table = DT_PRE.'order';
	$lists = array();
	$r = DB::get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$r) return $lists;
	$lists[$r['itemid']] = $r;
	$result = DB::query("SELECT * FROM {$table} WHERE pid=$itemid ORDER BY itemid DESC");
	while($r = DB::fetch_array($result)) {
		$lists[$r['itemid']] = $r;
	}
	return $lists;
}

function get_orders_id($itemid) {
	$ids = '';
	foreach(get_orders($itemid) as $k=>$v) {
		$ids .= ','.$k;
	}
	return $ids ? substr($ids, 1) : 0;
}
?>