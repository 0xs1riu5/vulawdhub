<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$ext = 'spread';
$url = $EXT[$ext.'_url'];
$mob = $EXT[$ext.'_mob'];
$this_month = date('n', $DT_TIME);
$this_year  = date('Y', $DT_TIME);
$next_month = $this_month == 12 ? 1 : $this_month + 1;
$next_year  = $this_month == 12 ? $this_year + 1 : $this_year;
$next_time = strtotime($next_year.'-'.$next_month.'-1');
$spread_max = $MOD['spread_max'] ? $MOD['spread_max'] : 10;
$currency = $MOD['spread_currency'];
$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
if($action == 'list') {
	if($kw) {
		$condition = "status=3 AND word='$kw'";
		if($mid) $condition .= " AND mid=$mid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}spread WHERE $condition");
		if($MOD['spread_list']) {
			$items = $r['num'];
			$pages = pages($items, $page, $pagesize);
		} else {
			$pages = '';
			$page = 1;			
			$items = $offset = 0;
		}
		$i = $j = 0;
		$lists = array();
		$result = $db->query("SELECT DATE_FORMAT(FROM_UNIXTIME(fromtime),'%Y%m') as id,itemid,mid,tid,word,price,currency,company,username,addtime,fromtime,totime FROM {$DT_PRE}spread WHERE $condition ORDER BY id DESC,price DESC,itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			if($r['totime'] < $DT_TIME) {
				$r['process'] = $L['status_expired'];
			} else if($r['fromtime'] > $DT_TIME) {
				$r['process'] = $L['status_not_start'];
			} else {
				$r['process'] = $L['status_displaying'];
			}
			if($j == 0) $i = $r['id'];
			$r['bg'] = $i == $r['id'] ? 0 : 1;
			if($i != $r['id']) $i = $r['id'];
			$j++;
			$lists[] = $r;
		}
		$head_title = $kw.($mid ? $DT['seo_delimiter'].$MODULE[$mid]['name'] : '').$DT['seo_delimiter'].$L['spread_title'];
	} else {
		dheader('./');
	}
} else {
	$head_title = $L['spread_title'];
	if($kw) dheader(rewrite('list.php?kw='.urlencode($kw)));
}
$template = $ext;
if($DT_PC) {
	$destoon_task = "moduleid=$moduleid&html=$ext";
	if($EXT['mobile_enable']) $head_mobile = str_replace($url, $mob, $DT_URL);
} else {
	$foot = '';
	if($action == 'list') {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = $mob;
	} else {
		$back_link = DT_MOB.'more.php';
	}
}
include template($template, $module);
?>