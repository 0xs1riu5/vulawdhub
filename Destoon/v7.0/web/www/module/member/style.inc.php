<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
($MG['biz'] && $MG['homepage'] && $MG['style']) or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/style.class.php';
$do = new style();
$user = userinfo($_username);
$domain = $user['domain'];
$skin = $user['skin'] ? $user['skin'] : 'default';
$template = $user['template'] ? $user['template'] : 'default';
$menu_id = 2;
switch($action) {
	case 'buy':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		$r or message($L['style_msg_not_exist']);
		if($r['groupid']) {
			$groupids = explode(',', $r['groupid']);
			if(!in_array($_groupid, $groupids)) message($L['style_msg_group']);
		}
		$r['fee'] or dheader('?action=choose&itemid='.$itemid);
		$currency = $r['currency'];
		$months = array(1, 2, 3, 6, 12, 24, 36, 48, 60);
		$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
		$auto = 0;
		$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
		if($auth && substr($auth, 0, 6) == 'style|') {
			$auto = $submit = 1;
			$tmp = explode('|', $auth);
			$month = intval($tmp[2]);
		}
		if($submit) {			
			in_array($month, $months) or message($L['style_msg_month']);
			$amount = $r['fee']*$month;
			if($currency == 'money') {
				$amount <= $_money or message($L['money_not_enough']);
				if($amount <= $DT['quick_pay']) $auto = 1;
				if(!$auto) {
					is_payword($_username, $password) or message($L['error_payword']);
				}
				money_add($_username, -$amount);
				money_record($_username, -$amount, $L['in_site'], 'system', $L['style_title_buy'], lang($L['style_record_buy'], array($r['title'].'('.$r['itemid'].')', $month)));
				$fd = 'money';
			} else {
				$amount <= $_credit or message($L['credit_not_enough'], 'credit.php?action=buy&amount='.($amount-$_credit));
				credit_add($_username, -$amount);
				credit_record($_username, -$amount, 'system', lang($L['style_record_buy'], array($r['title'].'('.$r['itemid'].')', $month)));
				$fd = 'credit';
			}
			$styletime = $DT_TIME + 86400*30*$month;
			$o = $db->get_one("SELECT itemid FROM {$DT_PRE}style WHERE skin='$skin' AND template='$template'");
			if($o) $db->query("UPDATE {$DT_PRE}style SET hits=hits-1 WHERE itemid=$o[itemid] AND hits>1");			
			$db->query("UPDATE {$DT_PRE}style SET hits=hits+1,`$fd`=`$fd`+$amount WHERE itemid=$itemid");
			$db->query("UPDATE {$DT_PRE}company SET template='$r[template]',skin='$r[skin]',styletime=$styletime WHERE userid=$_userid");
			userclean($_username);
			dmsg($L['style_msg_buy_success'], '?action=index');
		} else {
			$r['thumb'] = is_file(DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/'.$r['skin'].'/thumb.gif') ? $MODULE[4]['linkurl'].'skin/'.$r['skin'].'/thumb.gif' : $MODULE[4]['linkurl'].'image/nothumb.gif';
			extract($r);
			$head_title = $L['style_title_buy'];
		}
	break;	
	case 'choose':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		$r or message($L['style_msg_not_exist']);
		if($r['groupid']) {
			$groupids = explode(',', $r['groupid']);
			if(!in_array($_groupid, $groupids)) message($L['style_msg_group']);
		}
		if($r['fee']) dheader('?action=buy&itemid='.$itemid);
		$o = $db->get_one("SELECT itemid FROM {$DT_PRE}style WHERE skin='$skin' AND template='$template'");
		if($o) $db->query("UPDATE {$DT_PRE}style SET hits=hits-1 WHERE itemid=$o[itemid] AND hits>1");
		$db->query("UPDATE {$DT_PRE}style SET hits=hits+1 WHERE itemid=$itemid");
		$db->query("UPDATE {$DT_PRE}company SET template='$r[template]',skin='$r[skin]',styletime=0 WHERE userid=$_userid");
		userclean($_username);
		dmsg($L['style_msg_use_success'], $forward);
	break;		
	case 'view':
		$c = $db->get_one("SELECT * FROM {$DT_PRE}style WHERE skin='$skin' AND template='$template'");
		$c['thumb'] = is_file(DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/'.$c['skin'].'/thumb.gif') ? $MODULE[4]['linkurl'].'skin/'.$c['skin'].'/thumb.gif' : $MODULE[4]['linkurl'].'image/nothumb.gif';
		$havedays = $user['styletime'] ? ceil(($user['styletime']-$DT_TIME)/86400) : 0;
	break;
	default:
		$TYPE = get_type('style', 1);
		$pagesize = 12;
		$offset = ($page-1)*$pagesize;
		$sfields = $L['style_sfields'];
		$dfields = array('title', 'title', 'author');
		$sorder  = $L['style_sorder'];
		$dorder  = array('listorder desc,addtime desc', 'addtime DESC', 'addtime ASC', 'hits DESC', 'hits ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$all = isset($all) ? intval($all) : 0;
		$typeid = isset($typeid) ? intval($typeid) : 0;
		isset($currency) or $currency = '';
		$minfee = isset($minfee) ? dround($minfee) : '';
		$maxfee = isset($maxfee) ? dround($maxfee) : '';
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$type_select = type_select($TYPE, 1, 'typeid', $L['choose_type'], $typeid);
		$condition = "1";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if(!$all) $condition .= " AND groupid LIKE '%,$_groupid,%'";
		if($typeid) $condition .= " AND typeid=$typeid";
		if($currency) $condition .= $currency == 'free' ? " AND fee=0" : " AND currency='$currency'";
		if($minfee) $condition .= " AND fee>=$minfee";
		if($maxfee) $condition .= " AND fee<=$maxfee";
		if(!$skin) {
			if($MG['styleid']) {
				$o = $db->get_one("SELECT skin FROM {$DT_PRE}style WHERE itemid='$MG[styleid]'");
				if($o) $skin = $o['skin'];
			}
		}
		$skin or $skin = 'default';
		$havedays = $user['styletime'] ? ceil(($user['styletime']-$DT_TIME)/86400) : 0;
		$lists = $do->get_list($condition, $dorder[$order]);
		$head_title = $L['style_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'buy') {
		$back_link = '?action=index';
	} elseif($action == 'view') {
		$back_link = '?action=index';
	} else {
		$back_link = 'biz.php';
	}
}
include template('style', $module);
?>