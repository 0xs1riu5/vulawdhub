<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($html == 'show') {
	$itemid or exit;
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$item || $item['status'] < 3) exit;
	extract($item);
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	$currency = $MOD['fee_currency'];
	$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
	$name = $currency == 'money' ? $DT['money_name'] : $DT['credit_name'];
	$member = array();
	if(check_group($_groupid, $MOD['group_contact'])) {
		if($fee) {
			if($MG['fee_mode'] && $MOD['fee_mode']) {
				$user_status = 3;
			} else {
				$mid = $moduleid;
				if($_userid) {
					if(check_pay($mid, $itemid)) {
						$user_status = 3;
					} else {
						$user_status = 2;
						$pay_url = $MODULE[2]['linkurl'].'pay.php?mid='.$mid.'&itemid='.$itemid;
					}
				} else {
					$user_status = 0;
				}
			}
		} else {
			$user_status = 3;
		}
	} else {
		$user_status = $_userid ? 1 : 0;
	}
	if($_username && $_username == $item['username']) $user_status = 3;
	if($user_status == 3 && $item['username']) $member = userinfo($item['username']);
	$contact = strip_nr(ob_template('contact', 'chip'), true);
	echo 'Inner("contact", \''.$contact.'\');';
	if($MOD['hits']) echo 'Inner("hits", \''.$item['hits'].'\');';	
	$update = '';
	if($item['totime'] && $item['totime'] < $DT_TIME && $item['status'] == 3) $update .= ",status=4";
	if($member) {
		unset($item['areaid']);
		$update_user = update_user($member, $item);
		if($update_user) $db->query("UPDATE LOW_PRIORITY {$table} SET ".substr($update_user, 1)." WHERE username='$username'", 'UNBUFFERED');
	}
	if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
	if($MOD['show_html'] && $task_item && $DT_TIME - @filemtime(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl']) > $task_item) tohtml('show', $module);
} else if($html == 'list') {
	$catid or exit;
	if($MOD['list_html'] && $task_list && $CAT) {
		$num = 1;
		$totalpage = max(ceil($CAT['item']/$MOD['pagesize']), 1);
		$demo = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($CAT, '{DEMO}');
		$fid = $page;
		if($fid >= 1 && $fid <= $totalpage && $DT_TIME - @filemtime(str_replace('{DEMO}', $fid, $demo)) > $task_list) tohtml('list', $module);
		$fid = $page + 1;
		if($fid >= 1 && $fid <= $totalpage && $DT_TIME - @filemtime(str_replace('{DEMO}', $fid, $demo)) > $task_list) tohtml('list', $module);
		$fid = $totalpage + 1 - $page;
		if($fid >= 1 && $fid <= $totalpage && $DT_TIME - @filemtime(str_replace('{DEMO}', $fid, $demo)) > $task_list) tohtml('list', $module);
	}
} else if($html == 'index') {
	if($DT['cache_hits'] && $MOD['hits']) {
		$file = DT_CACHE.'/hits-'.$moduleid;
		if($DT_TIME - @filemtime($file.'.dat') > $DT['cache_hits'] || @filesize($file.'.php') > 102400) update_hits($moduleid, $table);
	}
	if($MOD['index_html']) {
		$file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$DT['index'].'.'.$DT['file_ext'];
		if($DT_TIME - @filemtime($file) > $task_index) tohtml('index', $module);
	}
}
?>