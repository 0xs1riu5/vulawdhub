<?php
#define('DT_REWRITE', true);
$_COOKIE = array();
require '../../common.inc.php';
require DT_ROOT.'/include/module.func.php';
$url = isset($url) ? fix_link($url) : DT_MOB;
if(isset($username)) {
	if(check_name($username)) {
		$r = $db->get_one("SELECT linkurl FROM {$DT_PRE}company WHERE username='$username'");
		$url = $r ? $r['linkurl'] : userurl($username);
	}
} else if(isset($aid)) {
	$aid = intval($aid);
	if($aid) {
		$r = $db->get_one("SELECT url,key_moduleid,key_id,typeid FROM {$DT_PRE}ad WHERE aid=$aid AND fromtime<$DT_TIME AND totime>$DT_TIME");
		if($r) {
			$url = ($r['key_moduleid'] && $r['typeid'] > 5) ? 'redirect.php?mid='.$r['key_moduleid'].'&itemid='.$r['key_id'] : $r['url'];
			$db->query("UPDATE LOW_PRIORITY {$DT_PRE}ad SET hits=hits+1 WHERE aid=$aid", 'UNBUFFERED');
		}
	}
} else if($mid) {
	if(isset($MODULE[$mid]) && !$MODULE[$mid]['islink'] && $itemid && $mid > 3) {
		$condition = $mid == 4 ? "userid=$itemid" : "itemid=$itemid";
		$table = get_table($mid);
		if($MODULE[$mid]['module'] == 'job' && $page == 2) $table = $DT_PRE.'job_resume_'.$mid;
		$r = $db->get_one("SELECT * FROM {$table} WHERE $condition");
		if($r) {
			$url = strpos($r['linkurl'], '://') === false ? $MODULE[$mid]['mobile'].$r['linkurl'] : $r['linkurl'];
			if($sum) $url = $MODULE[2]['mobile'].'pay.php?mid='.$mid.'&itemid='.$itemid;
		}
	} else if($mid == 3) {
		isset($tb) or $tb = '';
		if($itemid && in_array($tb, array('announce', 'webpage', 'link', 'gift', 'vote', 'poll', 'form'))) {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}{$tb} WHERE itemid=$itemid");			
			if($r && $r['linkurl']) {
				if($tb == 'webpage') {
					$url = strpos($r['linkurl'], '://') === false ? DT_MOB.$r['linkurl'] : $r['linkurl'];
				} else {
					$k = $tb.'_mob';
					$url = strpos($r['linkurl'], '://') === false ? $EXT[$k].$r['linkurl'] : $r['linkurl'];
				}
			}
		}
	} else if($mid == 2) {
		isset($tb) or $tb = '';
		if($itemid && in_array($tb, array('news', 'honor', 'page'))) {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}{$tb} WHERE itemid=$itemid");			
			if($r && $r['linkurl']) $url = $r['linkurl'];
		}
	}
} else {
	check_referer() or $url = DT_MOB;
	$url = str_replace('unio&#110;pay.com', 'unionpay.com', $url);
}
dheader($url);
?>