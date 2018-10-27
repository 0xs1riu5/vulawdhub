<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
include load($module.'.lang');
include load('my.lang');
if($job == 'resume') exit(include(DT_ROOT.'/module/'.$module.'/my_'.$job.'.inc.php'));
$mod_limit = intval($MOD['limit_'.$_groupid]);
$mod_free_limit = intval($MOD['free_limit_'.$_groupid]);
$mod_limit > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
$do = new $module($moduleid);
if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
	$CP = $MOD['cat_property'];
	if($CP) require DT_ROOT.'/include/property.func.php';
	isset($post_ppt) or $post_ppt = array();
}
$sql = $_userid ? "username='$_username'" : "ip='$DT_IP'";
$limit_used = $limit_free = $need_password = $need_captcha = $need_question = $fee_add = 0;
if(in_array($action, array('', 'add'))) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql");
	$limit_used = $r['num'];
	$limit_free = $mod_limit > $limit_used ? $mod_limit - $limit_used : 0;
}
if(check_group($_groupid, $MOD['group_refresh'])) $MOD['credit_refresh'] = 0;
switch($action) {
	case 'add':
		if($mod_limit && $limit_used >= $mod_limit) dalert(lang($L['info_limit'], array($mod_limit, $limit_used)), $_userid ? '?mid='.$mid : '?action=index');
		if($MG['hour_limit']) {
			$today = $DT_TIME - 3600;
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND addtime>$today");
			if($r && $r['num'] >= $MG['hour_limit']) dalert(lang($L['hour_limit'], array($MG['hour_limit'])), $_userid ? '?mid='.$mid : '?action=index');
		}
		if($MG['day_limit']) {
			$today = $today_endtime - 86400;
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND addtime>$today");
			if($r && $r['num'] >= $MG['day_limit']) dalert(lang($L['day_limit'], array($MG['day_limit'])), $_userid ? '?mid='.$mid : '?action=index');
		}

		if($mod_free_limit >= 0) {
			$fee_add = ($MOD['fee_add'] && (!$MOD['fee_mode'] || !$MG['fee_mode']) && $limit_used >= $mod_free_limit && $_userid) ? dround($MOD['fee_add']) : 0;
		} else {
			$fee_add = 0;
		}
		$fee_currency = $MOD['fee_currency'];
		$fee_unit = $fee_currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
		$need_password = $fee_add && $fee_currency == 'money' && $fee_add > $DT['quick_pay'];
		$need_captcha = $MOD['captcha_add'] == 2 ? $MG['captcha'] : $MOD['captcha_add'];
		$need_question = $MOD['question_add'] == 2 ? $MG['question'] : $MOD['question_add'];
		$could_color = check_group($_groupid, $MOD['group_color']) && $MOD['credit_color'] && $_userid;

		if($submit) {
			if($fee_add && $fee_add > ($fee_currency == 'money' ? $_money : $_credit)) dalert($L['balance_lack']);
			if($need_password && !is_payword($_username, $password)) dalert($L['error_payword']);

			if(!$_userid) {
				if(strlen($post['company']) < 4) dalert($L['type_company']);
			}

			if($MG['add_limit']) {
				$last = $db->get_one("SELECT addtime FROM {$table} WHERE $sql ORDER BY itemid DESC");
				if($last && $DT_TIME - $last['addtime'] < $MG['add_limit']) dalert(lang($L['add_limit'], array($MG['add_limit'])));
			}
			$msg = captcha($captcha, $need_captcha, true);
			if($msg) dalert($msg);
			$msg = question($answer, $need_question, true);
			if($msg) dalert($msg);

			if($do->pass($post)) {
				$CAT = get_cat($post['catid']);
				if(!$CAT || !check_group($_groupid, $CAT['group_add'])) dalert(lang($L['group_add'], array($CAT['catname'])));
				$post['addtime'] = $post['level'] = $post['fee'] = 0;
				$post['style'] = $post['template'] = $post['note'] = '';
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status(3, $need_check);
				$post['hits'] = 0;
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				if($CP) property_check($post_ppt);				
				if($could_color && $color && $_credit > $MOD['credit_color']) {
					$post['style'] = $color;
					credit_add($_username, -$MOD['credit_color']);
					credit_record($_username, -$MOD['credit_color'], 'system', $L['title_color'], '['.$MOD['name'].']'.$post['title']);
				}
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				if($MOD['show_html'] && $post['status'] > 2) $do->tohtml($do->itemid);
				if($fee_add) {
					if($fee_currency == 'money') {
						money_add($_username, -$fee_add);
						money_record($_username, -$fee_add, $L['in_site'], 'system', lang($L['credit_record_add'], array($MOD['name'])), 'ID:'.$do->itemid);
					} else {
						credit_add($_username, -$fee_add);
						credit_record($_username, -$fee_add, 'system', lang($L['credit_record_add'], array($MOD['name'])), 'ID:'.$do->itemid);
					}
				}
				
				$msg = $post['status'] == 2 ? $L['success_check'] : $L['success_add'];
				$js = '';
				if(isset($post['sync_sina']) && $post['sync_sina']) $js .= sync_weibo('sina', $moduleid, $do->itemid);
				if($_userid) {
					set_cookie('dmsg', $msg);
					$forward = '?mid='.$mid.'&status='.$post['status'];
					$msg = '';
				} else {
					$forward = '?mid='.$mid.'&action=add';
				}
				$js .= 'window.onload=function(){parent.window.location="'.$forward.'";}';
				dalert($msg, '', $js);
			} else {
				dalert($do->errmsg, '', ($need_captcha ? reload_captcha() : '').($need_question ? reload_question() : ''));
			}
		} else {
			if($itemid) {
				$MG['copy'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
				$do->itemid = $itemid;
				$item = $do->get_one();
				if(!$item || $item['username'] != $_username) message();
				extract($item);
				$thumb = '';
				$totime = $totime ? timetodate($totime, 6) : '';
			} else {
				$_catid = $catid;
				foreach($do->fields as $v) {
					$$v = '';
				}
				$content = '';
				$catid = $_catid;
				$sex = 1;
				if($_userid) {
					$user = userinfo($_username);
					$company = $user['company'];
					$truename = $user['truename'];
					$email = $user['email'];
					$mobile = $user['mobile'];
					$areaid = $user['areaid'];
					$telephone = $user['telephone'];
					$address = $user['address'];
					$qq = $user['qq'];
					$wx = $user['wx'];
					$ali = $user['ali'];
					$skype = $user['skype'];
					$gender = $user['gender'];
				}
				$total = 1;
				$minage = 18;
				$maxage = 0;
			}
			$item = array();
		}
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();
		if($submit) {
			if($do->pass($post)) {
				$CAT = get_cat($post['catid']);
				if(!$CAT || !check_group($_groupid, $CAT['group_add'])) dalert(lang($L['group_add'], array($CAT['catname'])));
				$post['addtime'] = timetodate($item['addtime']);
				$post['level'] = $item['level'];
				$post['fee'] = $item['fee'];
				$post['style'] = addslashes($item['style']);
				$post['template'] = addslashes($item['template']);
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status($item['status'], $need_check);
				$post['hits'] = $item['hits'];
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				if($CP) property_check($post_ppt);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				$do->edit($post);
				set_cookie('dmsg', $L['success_edit']);
				dalert('', '', 'parent.window.location="'.$forward.'"');
			} else {
				dalert($do->errmsg);
			}
		} else {
			extract($item);
			$totime = $totime ? timetodate($totime, 6) : '';
		}
	break;
	case 'update':
		$do->_update($_username);
		dmsg($L['success_update'], $forward);
	break;
	case 'resume_show':
		$itemid or message();
		$resumeid or message();
		$db->query("UPDATE {$table_apply} SET status=2 WHERE applyid='$itemid' AND job_username='$_username' AND status=1");
		dheader(($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).rewrite('resume.php?itemid='.$resumeid));
	break;
	case 'resume_delete':
		$itemid or message();
		$apply = $db->get_one("SELECT * FROM {$table_apply} WHERE applyid='$itemid' AND job_username='$_username' AND status>0");
		if($apply) {
			$db->query("UPDATE {$table_apply} SET status=0 WHERE applyid='$itemid'");
			$db->query("UPDATE {$table} SET apply=apply-1 WHERE itemid='$apply[jobid]'");
		}
		dmsg($L['success_delete'], $forward);
	break;
	case 'resume_invite':
		$itemid or message();
		$apply = $db->get_one("SELECT * FROM {$table_apply} WHERE applyid='$itemid' AND job_username='$_username'");
		$apply or message($L['msg_not_apply']);
		$resume = $db->get_one("SELECT * FROM {$table_resume} WHERE itemid='$apply[resumeid]'");
		$resume or message($L['msg_not_resume']);
		if(!$resume['username']) message($L['msg_not_member']);
		if($resume['status'] != 3) message($L['msg_resume_close']);
		$job = $db->get_one("SELECT * FROM {$table} WHERE itemid='$apply[jobid]' AND status=3");
		$job or message($L['msg_not_job']);
		if($job['totime'] && $job['totime'] < $DT_TIME) message($L['msg_job_expired']);
		$title = lang($L['msg_invite_title'], array($job['company']));
		$joburl = $MOD['linkurl'].$job['linkurl'];
		$db->query("UPDATE {$table_apply} SET status=3 WHERE applyid='$itemid' AND job_username='$_username' AND status>0");
	break;
	case 'resume_search':
		include load('search.lang');
		$thumb = isset($thumb) ? intval($thumb) : 0;
		$level = isset($level) ? intval($level) : 0;
		$vip = isset($vip) ? intval($vip) : 0;
		$gender = isset($gender) ? intval($gender) : 0;
		$type = isset($type) ? intval($type) : 0;
		$marriage = isset($marriage) ? intval($marriage) : 0;
		$education = isset($education) ? intval($education) : 0;
		$experience = isset($experience) ? intval($experience) : 0;
		if(!$areaid && $cityid && strpos($DT_URL, 'areaid') === false) {
			$areaid = $cityid;
			$ARE = $AREA[$cityid];
		}
		$minsalary = isset($minsalary) ? intval($minsalary) : 0;
		$maxsalary = isset($maxsalary) ? intval($maxsalary) : 0;
		$areaid = isset($areaid) ? intval($areaid) : 0;
		$fromdate = isset($fromdate) && is_date($fromdate) ? $fromdate : '';
		$fromtime = $fromdate ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) && is_date($todate) ? $todate : '';
		$totime = $todate ? strtotime($todate.' 23:59:59') : 0;
		$category_select = ajax_category_select('catid', $L['all_jobtype'], $catid, $moduleid);
		$area_select = ajax_area_select('areaid', $L['all_area'], $areaid);
		$condition = 'status=3 AND open=3';
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
		if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		if($thumb) $condition .= " AND thumb<>''";
		if($vip) $condition .= " AND vip>0";
		if($minsalary)  $condition .= " AND minsalary>$minsalary";
		if($maxsalary)  $condition .= " AND maxsalary<$maxsalary";
		if($fromtime) $condition .= " AND edittime>=$fromtime";
		if($totime) $condition .= " AND edittime<=$totime";
		if($level) $condition .= " AND level=$level";
		if($gender) $condition .= " AND gender=$gender";
		if($type) $condition .= " AND type=$type";
		if($marriage) $condition .= " AND marriage=$marriage";
		if($education) $condition .= " AND education>=$education";
		if($experience) $condition .= " AND experience>=$experience";
		if($minsalary) $condition .= " AND minsalary>=$minsalary";
		if($maxsalary) $condition .= " AND maxsalary<=$maxsalary";
		$GENDER[0] = $L['all_gender'];
		$TYPE[0] = $L['all_work'];
		$MARRIAGE[0] = $L['all_marriage'];
		$EDUCATION[0] = $L['all_education'];
		$pagesize = $MOD['pagesize'];
		$offset = ($page-1)*$pagesize;
		$items = $db->count($table_resume, $condition);
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		if($items) {
			$result = $db->query("SELECT * FROM {$table_resume} WHERE {$condition} ORDER BY edittime DESC LIMIT {$offset},{$pagesize}");
			if($kw) {
				$replacef = explode(' ', $kw);
				$replacet = array_map('highlight', $replacef);
			}
			while($r = $db->fetch_array($result)) {
				$r['adddate'] = timetodate($r['addtime'], 5);
				$r['editdate'] = timetodate($r['edittime'], 5);
				if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
				$r['alt'] = $r['title'];
				$r['title'] = set_style($r['title'], $r['style']);
				if($kw) $r['title'] = str_replace($replacef, $replacet, $r['title']);
				$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
				$r['parentid'] = $CATEGORY[$r['catid']]['parentid'] ? $CATEGORY[$r['catid']]['parentid'] : $r['catid'];
				$lists[] = $r;
			}
		}
	break;
	case 'resume':
		$condition = '';
		if($keyword) $condition .= " AND r.keyword LIKE '%$keyword%'";
		if($itemid) $condition .= " AND j.itemid=$itemid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_apply} a LEFT JOIN {$table_resume} r ON a.resumeid=r.itemid LEFT JOIN {$table} j ON a.jobid=j.itemid WHERE a.job_username='$_username' AND a.status>0 $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT a.*,r.truename,r.catid,r.gender,r.education,r.school,r.areaid,r.age,r.experience,j.title,j.linkurl FROM {$table_apply} a LEFT JOIN {$table_resume} r ON a.resumeid=r.itemid LEFT JOIN {$table} j ON a.jobid=j.itemid WHERE a.job_username='$_username' AND a.status>0 $condition ORDER BY a.applyid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$lists[] = $r;
		}
	break;
	case 'talent_delete':
		$itemid or message();
		$db->query("DELETE FROM {$table_talent} WHERE username='$_username' AND talentid=$itemid");
		dmsg($L['success_delete'], $forward);
	break;
	case 'talent':
		$condition = '';
		if($keyword) $condition .= " AND r.keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CAT['child']) ? " AND r.catid IN (".$CAT['arrchildid'].")" : " AND r.catid=$catid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_talent} t LEFT JOIN {$table_resume} r ON t.resumeid=r.itemid WHERE t.username='$_username' $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$table_talent} t LEFT JOIN {$table_resume} r ON t.resumeid=r.itemid WHERE t.username='$_username' $condition ORDER BY t.talentid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['parentid'] = $CATEGORY[$r['catid']]['parentid'] ? $CATEGORY[$r['catid']]['parentid'] : $r['catid'];
			$r['linkurl'] = ($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$r['linkurl'];
			$lists[] = $r;
		}
	break;
	case 'delete':
		$MG['delete'] or message();
		$itemid or message();
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $db->get_one("SELECT username FROM {$table} WHERE itemid=$itemid");
			if(!$item || $item['username'] != $_username) message();
			$do->recycle($itemid);
		}
		dmsg($L['success_delete'], $forward);
	break;
	case 'refresh':
		$MG['refresh_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
		$itemid or message($L['select_info']);
		if($MOD['credit_refresh'] && $_credit < $MOD['credit_refresh']) message($L['credit_lack']);
		$itemids = $itemid;
		$s = $f = 0;
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $db->get_one("SELECT username,edittime FROM {$table} WHERE itemid=$itemid");
			$could_refresh = $item && $item['username'] == $_username;
			if($could_refresh && $MG['refresh_limit'] && $DT_TIME - $item['edittime'] < $MG['refresh_limit']) $could_refresh = false;
			if($could_refresh && $MOD['credit_refresh'] && ($MOD['credit_refresh'] > $_credit || $_credit < 0)) $could_refresh = false;
			if($could_refresh) {
				$do->refresh($itemid);
				$s++;
				if($MOD['credit_refresh']) $_credit = $_credit - $MOD['credit_refresh'];
			} else {
				$f++;
			}			
		}
		if($MOD['credit_refresh'] && $s) {
			$credit = $s*$MOD['credit_refresh'];
			credit_add($_username, -$credit);
			credit_record($_username, -$credit, 'system', lang($L['credit_record_refresh'], array($MOD['name'])), lang($L['refresh_total'], array($s)));
		}
		$msg = lang($L['refresh_success'], array($s));
		if($f) $msg = $msg.' '.lang($L['refresh_fail'], array($f));
		dmsg($msg, $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		$condition = "username='$_username'";
		$condition .= " AND status=$status";
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
		if($typeid >=0 ) $condition .= " AND typeid=$typeid";
		$timetype = strpos($MOD['order'], 'add') !== false ? 'add' : '';
		$lists = $do->get_list($condition, $MOD['order']);
	break;
}
if($_userid) {
	$nums = array();
	for($i = 1; $i < 5; $i++) {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE username='$_username' AND status=$i");
		$nums[$i] = $r['num'];
	}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_talent} WHERE username='$_username'");
	$nums['talent'] = $r['num'];
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_apply} WHERE job_username ='$_username' AND status>0");
	$nums['resume'] = $r['num'];
}
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MODULE[2]['linkurl'], $MODULE[2]['mobile'], $DT_URL);
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit') {
		$back_link = '?mid='.$mid;
	} else {
		$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'edittime';
		foreach($lists as $k=>$v) {
			$lists[$k]['linkurl'] = str_replace($MOD['linkurl'], $MOD['mobile'], $v['linkurl']);
			$lists[$k]['date'] = timetodate($v[$time], 5);
		}
		$pages = mobile_pages($items, $page, $pagesize);
		$foot = '';
		$back_link = ($kw || $page > 1) ? '?mid='.$mid.'&status='.$status : '?action=index';
	}
}
$head_title = lang($L['module_manage'], array($MOD['name']));
include template($MOD['template_my'] ? $MOD['template_my'] : 'my_'.$module, 'member');
?>