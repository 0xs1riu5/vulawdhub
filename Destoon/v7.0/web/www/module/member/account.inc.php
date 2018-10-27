<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
switch($action) {
	case 'line':
		$forward or $forward = $DT_PC ? DT_PATH : DT_MOB;
		$online = $_online ? 0 : 1;
		$db->query("UPDATE {$DT_PRE}member SET online=$online WHERE userid=$_userid");
		$db->query("UPDATE {$DT_PRE}online SET online=$online WHERE userid=$_userid");
		dheader($forward);
	break;
	case 'promo':
		$code = dhtmlspecialchars(trim($code));
		if($code) {
			$p = $db->get_one("SELECT * FROM {$DT_PRE}finance_promo WHERE number='$code' AND totime>$DT_TIME");
			if($p && ($p['reuse'] || (!$p['reuse'] && !$p['username']))) {
				if($p['type']) {
					exit(lang($L['grade_msg_time_promo'], array($p['amount'])));
				} else {
					exit(lang($L['grade_msg_money_promo'], array($p['amount'])));
				}
			}
		}
		exit($L['grade_msg_bad_promo']);
	break;
	case 'grade':
		$GROUP = cache_read('group.php');
		$groupid = isset($groupid) ? intval($groupid) : 0;
		isset($GROUP[$groupid]) or $groupid = 0;
		$UP = $UG = array();
		if($_groupid > 2) {
			foreach($GROUP as $k=>$v) {
				if($v['listorder'] > $MG['listorder']) $UP[$k] = $v;
			}
		}
		array_key_exists($groupid, $UP) or $groupid = 0;
		$fee = 0;
		$could_up = $groupid;
		if($groupid) {
			$UG = cache_read('group-'.$groupid.'.php');
			$fee = $UG['fee'];
		}
		if($_groupid < 5) $could_up = false;
		($could_up && $groupid) or dheader('grade.php');
		$r = $db->get_one("SELECT status FROM {$DT_PRE}upgrade WHERE userid=$_userid ORDER BY itemid DESC");
		if($r && $r['status'] == 2) message($L['grade_msg_success']);
		$auto = 0;
		$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
		if($auth && substr($auth, 0, 6) == 'grade|') {
			$_gid = intval(substr($auth, 6));
			if($_gid == $groupid) $auto = $submit = 1;
		}
		if($submit) {
			if($fee > 0) {
				$fee <= $_money or message($L['money_not_enough']);
				if($fee <= $DT['quick_pay']) $auto = 1;
				if(!$auto) {
					is_payword($_username, $password) or message($L['error_payword']);
				}
				money_add($_username, -$fee);
				money_record($_username, -$fee, $L['in_site'], 'system', $L['grade_title'], $GROUP[$groupid]['groupname']);
				$company = dhtmlspecialchars($_company);
			} else {
				if(strlen($company) < 4) message($L['grade_pass_company']);
				$company = dhtmlspecialchars(trim($company));
			}
			$db->query("INSERT INTO {$DT_PRE}upgrade (userid,username,gid,groupid,company,addtime,ip,amount,status) VALUES ('$_userid','$_username','$_groupid','$groupid','$company','$DT_TIME', '$DT_IP','$fee','2')");
			 message($L['grade_msg_success'], '?action=index', 5);
		} else {
			$GROUPS = array();
			foreach($GROUP as $k=>$v) {
				if($k > 4) {
					$G = cache_read('group-'.$k.'.php');
					$G['moduleids'] = isset($G['moduleids']) ? explode(',', $G['moduleids']) : array();
					if($G['grade']) $GROUPS[$k] = $G;
				}
			}
			$head_title = $L['grade_title'];
		}
	break;
	case 'vip':
		$user = userinfo($_username);
		if(!$MG['vip'] || !$MG['fee'] || $user['totime'] < $DT_TIME) dheader('?action=index');
		$auto = 0;
		$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
		if($auth && substr($auth, 0, 4) == 'vip|') {
			$auto = $submit = 1;
			$year = intval(substr($auth, 4));
		}
		if($submit) {
			$year = intval($year);
			in_array($year, array(1, 2, 3)) or $year = 1;
			$fee = dround($MG['fee']*$year);
			$fee > 0 or message($L['vip_msg_fee']);
			$fee <= $_money or message($L['money_not_enough']);
			if($fee <= $DT['quick_pay']) $auto = 1;
			if(!$auto) {
				is_payword($_username, $password) or message($L['error_payword']);
			}
			$totime = $user['totime'] + 365*86400*$year;
			money_add($_username, -$fee);
			money_record($_username, -$fee, $L['in_site'], 'system', $L['vip_renew'], lang($L['vip_record'], array($year, timetodate($totime, 3))));
			$db->query("UPDATE {$DT_PRE}company SET totime=$totime WHERE userid=$_userid");
			dmsg($L['vip_msg_success'], '?action=index');
		} else {
		$havedays = ceil(($user['totime'] - $DT_TIME)/86400);
		$todate = timetodate($user['totime'], 3);
			$year = 1;
			if($sum > 1 && $sum < 4) $year = $sum;
			$fee = dround($MG['fee']*$year);
			$head_title = $L['vip_renew'];
		}
	break;
	default:
		$user = userinfo($_username);
		extract($user);
		$expired = $totime && $totime < $DT_TIME ? true : false;
		$havedays = $expired ? 0 : ceil(($totime-$DT_TIME)/86400);
		$head_title = $L['profile_title'];	
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'grade' || $action == 'vip') {
		$back_link = '?action=index';
	} else {
		$back_link = 'index.php';
	}
}
include template('account', $module);
?>