<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$resume_limit = intval($MOD['limit_'.$_groupid]);
$resume_free_limit = intval($MOD['free_limit_'.$_groupid]);
$resume_limit > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/resume.class.php';
$do = new resume($moduleid);
$table = $table_resume;
if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
}
$sql = $_userid ? "username='$_username'" : "ip='$DT_IP'";
$limit_used = $limit_free = $need_password = $need_captcha = $need_question = $fee_add = 0;
if(in_array($action, array('', 'add'))) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND status>1");
	$limit_used = $r['num'];
	$limit_free = $resume_limit > $limit_used ? $resume_limit - $limit_used : 0;
}
switch($action) {
	case 'add':
		if($resume_limit && $limit_used >= $resume_limit) dalert(lang($L['info_limit'], array($resume_limit, $limit_used)), $_userid ? '?mid='.$mid : '?action=index');
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

		if($resume_free_limit >= 0) {
			$fee_add = ($MOD['fee_add_resume'] && (!$MOD['fee_mode'] || !$MG['fee_mode']) && $limit_used >= $resume_free_limit && $_userid) ? dround($MOD['fee_add_resume']) : 0;
		} else {
			$fee_add = 0;
		}
		$fee_currency = $MOD['fee_currency'];
		$fee_unit = $fee_currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
		$need_password = $fee_add && $fee_currency == 'money' && $fee_add > $DT['quick_pay'];
		$need_captcha = $MOD['captcha_add_resume'] == 2 ? $MG['captcha'] : $MOD['captcha_add_resume'];
		$need_question = $MOD['question_add_resume'] == 2 ? $MG['question'] : $MOD['question_add_resume'];

		if($submit) {
			if($fee_add && $fee_add > ($fee_currency == 'money' ? $_money : $_credit)) dalert($L['balance_lack']);
			if($need_password && !is_payword($_username, $password)) dalert($L['error_payword']);
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
				$post['style'] = $post['template'] = $post['note'] = $post['filepath'] = '';
				$need_check =  $MOD['check_add_resume'] == 2 ? $MG['check'] : $MOD['check_add_resume'];
				$post['status'] = get_status(3, $need_check);
				$post['hits'] = 0;
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);

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
				if($_userid) {
					set_cookie('dmsg', $msg);
					$forward = '?mid='.$mid.'&job=resume&status='.$post['status'];
					dalert('', '', 'parent.window.location="'.$forward.'";');
				} else {
					dalert($msg, '', 'parent.window.location=parent.window.location;');
				}
			} else {
				dalert($do->errmsg, '', ($need_captcha ? reload_captcha() : '').($need_question ? reload_question() : ''));
			}
		} else {
			if($itemid) {
				$MG['copy'] && $_userid or dalert(lang('message->without_permission_and_upgrade'), 'goback');
				$do->itemid = $itemid;
				$item = $do->get_one();
				if(!$item || $item['username'] != $_username) message();
				extract($item);
				$thumb = '';
				list($byear, $bmonth, $bday) = explode('-', $birthday);
			} else {
				$_catid = $catid;
				foreach($do->fields as $v) {
					$$v = '';
				}
				$content = '';
				$catid = $_catid;
				$gender = 1;
				$byear = 19;
				$bmonth = $bday = $experience = $marriage = $type = 1;
				$education = 3;
				$minsalary = 1000;
				$maxsalary = 0;
				$open = 3;
				if($_userid) {
					$r = $db->get_one("SELECT * FROM {$table_resume} a,{$table_resume_data} c WHERE a.itemid=c.itemid AND a.username='$_username' ORDER BY a.edittime DESC");
					if($r) {
						extract($r);
						list($byear, $bmonth, $bday) = explode('-', $birthday);
					} else {
						$user = userinfo($_username);
						$truename = $user['truename'];
						$email = $user['email'];
						$mobile = $user['mobile'];
						$gender = $user['gender'];
						$areaid = $user['areaid'];
						$telephone = $user['telephone'];
						$address = $user['address'];
						$qq = $user['qq'];
						$wx = $user['wx'];
						$ali = $user['ali'];
						$skype = $user['skype'];
					}
				}
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
				$post['addtime'] = timetodate($item['addtime']);
				$post['level'] = $item['level'];
				$post['fee'] = $item['fee'];
				$post['style'] = addslashes($item['style']);
				$post['template'] = addslashes($item['template']);
				$post['filepath'] = addslashes($item['filepath']);
				$post['note'] = addslashes($item['note']);
				$need_check =  $MOD['check_add_resume'] == 2 ? $MG['check'] : $MOD['check_add_resume'];
				$post['status'] = get_status($item['status'], $need_check);
				$post['hits'] = $item['hits'];
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				$do->edit($post);
				set_cookie('dmsg', $L['success_edit']);
				dalert('', '', 'parent.window.location="'.$forward.'"');
			} else {
				dalert($do->errmsg);
			}
		} else {
			extract($item);				
			list($byear, $bmonth, $bday) = explode('-', $birthday);
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
	case 'update':
		$do->_update($_username);
		dmsg($L['success_update'], $forward);
	break;
	case 'apply_delete':
		$itemid or message();
		$apply = $db->get_one("SELECT * FROM {$table_apply} WHERE applyid='$itemid' AND apply_username='$_username'");
		if($apply) {
			if($apply['status']>0) $db->query("UPDATE {$table} SET apply=apply-1 WHERE itemid='$apply[jobid]'");
			$db->query("DELETE FROM {$table_apply} WHERE applyid='$itemid'");
		}
		dmsg($L['success_delete'], $forward);
	break;
	case 'apply':
		$condition = '';
		if($keyword) $condition .= " AND j.keyword LIKE '%$keyword%'";if($catid) $condition .= ($CAT['child']) ? " AND j.catid IN (".$CAT['arrchildid'].")" : " AND j.catid=$catid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_apply} a LEFT JOIN {$table_resume} r ON a.resumeid=r.itemid LEFT JOIN {$table} j ON a.jobid=j.itemid WHERE a.apply_username='$_username' $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT a.*,r.title AS resumetitle,j.title,j.linkurl FROM {$table_apply} a LEFT JOIN {$table_resume} r ON a.resumeid=r.itemid LEFT JOIN {$table} j ON a.jobid=j.itemid WHERE a.apply_username='$_username' $condition ORDER BY a.applyid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
	break;
	case 'refresh':
		$MG['refresh_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
		$itemid or message();
		$do->itemid = $itemid;
		$item = $db->get_one("SELECT username,edittime FROM {$table} WHERE itemid=$itemid");
		if(!$item || $item['username'] != $_username) message();
		if($MG['refresh_limit'] && $DT_TIME - $item['edittime'] < $MG['refresh_limit']) dalert(lang($L['refresh_limit'], array($MG['refresh_limit'])), $forward);
		$do->refresh($itemid);
		dmsg($L['success_update'], $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		$condition = "username='$_username'";
		$condition .= " AND status=$status";
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
		$lists = $do->get_list($condition);
	break;
}
if($_userid) {
	$nums = array();
	for($i = 1; $i < 4; $i++) {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_resume} WHERE username='$_username' AND status=$i");
		$nums[$i] = $r['num'];
	}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_apply} WHERE apply_username ='$_username'");
	$nums['apply'] = $r['num'];
}
$head_title = $L['resume_manage'];
include template($MOD['template_my_resume'] ? $MOD['template_my_resume'] : 'my_job_resume', 'member');
?>