<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('资料审核', '?moduleid='.$moduleid.'&file='.$file.'&action=member'),
    array('公司认证', '?moduleid='.$moduleid.'&file='.$file.'&action=company'),
    array('实名认证', '?moduleid='.$moduleid.'&file='.$file.'&action=truename'),
    array('手机认证', '?moduleid='.$moduleid.'&file='.$file.'&action=mobile'),
    array('邮件认证', '?moduleid='.$moduleid.'&file='.$file.'&action=email'),
);
$table = $DT_PRE.'validate';
$V = array('member'=>'资料审核', 'company'=>'公司认证', 'truename'=>'实名认证', 'mobile'=>'手机认证', 'email'=>'邮件认证');
$S = array('member'=>'0', 'company'=>'1', 'truename'=>'2', 'mobile'=>'3', 'email'=>'4');

$reason = isset($reason) ? trim($reason) : '';
if($reason == '操作原因') $reason = '';
$msg = isset($msg) ? 1 : 0;
$eml = isset($eml) ? 1 : 0;
$sms = isset($sms) ? 1 : 0;
$wec = isset($wec) ? 1 : 0;
if(!$DT['sms']) $sms = 0;
if(!$EXT['weixin']) $wec = 0;

$action or $action = 'member';
switch($action) {
	case 'cancel':
		$itemid or msg('请选择记录');
		$i = 0;
		foreach($itemid as $id) {
			$r = $db->get_one("SELECT * FROM {$table} WHERE itemid='$id' AND status=3");
			if($r) {
				$username = $r['username'];
				$user = userinfo($username);
				$userid = $user['userid'];
				$fd = $r['type'];
				$vfd = 'v'.$r['type'];
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['thumb1']) delete_upload($r['thumb1'], $userid);
				if($r['thumb2']) delete_upload($r['thumb2'], $userid);
				$db->query("UPDATE {$DT_PRE}member SET `{$vfd}`=0 WHERE userid=$userid");
				$db->query("DELETE FROM {$table} WHERE itemid=$id");
				if($msg || $eml) {
					$content = $title = '您的'.$V[$fd].'已经被取消，请重新认证';
					if($reason) $content .= '<br/>取消原因:'.nl2br($reason);
					if($msg) send_message($username, $title, $content);
					if($eml) send_mail($user['email'], $title, $content);
				}
				$content = '您的'.$V[$fd].'已经被取消，请重新认证';
				if($reason) $content .= ',取消原因:'.$reason;
				if($sms) send_sms($user['mobile'], $content.$DT['sms_sign']);
				if($wec) send_weixin($user['username'], $content);
				$i++;
			}
		}
		dmsg('取消认证 '.$i.' 条', $forward);		
	break;
	case 'reject':
		$itemid or msg('请选择记录');
		$i = 0;
		foreach($itemid as $id) {
			$r = $db->get_one("SELECT * FROM {$table} WHERE itemid='$id' AND status=2");
			if($r) {
				$username = $r['username'];
				$user = userinfo($username);
				$userid = $user['userid'];
				$fd = $r['type'];
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['thumb1']) delete_upload($r['thumb1'], $userid);
				if($r['thumb2']) delete_upload($r['thumb2'], $userid);
				$db->query("DELETE FROM {$table} WHERE itemid=$id");
				if($msg || $eml) {
					$content = $title = '您的'.$V[$fd].'没有通过审核，请重新认证';
					if($reason) $content .= '<br/>失败原因:'.nl2br($reason);
					if($msg) send_message($username, $title, $content);
					if($eml) send_mail($user['email'], $title, $content);
				}
				$content = '您的'.$V[$fd].'没有通过审核，请重新认证';
				if($reason) $content .= ',失败原因:'.$reason;
				if($sms) send_sms($user['mobile'], $content.$DT['sms_sign']);
				if($wec) send_weixin($user['username'], $content);
				$i++;
			}
		}
		dmsg('拒绝认证 '.$i.' 条', $forward);		
	break;
	case 'check':
		$itemid or msg('请选择记录');
		$i = 0;
		foreach($itemid as $id) {
			$r = $db->get_one("SELECT * FROM {$table} WHERE itemid='$id' AND status=2");
			if($r) {
				$value = $r['title'];
				$username = $r['username'];
				$user = userinfo($username);
				$userid = $user['userid'];
				$fd = $r['type'];
				$vfd = 'v'.$r['type'];
				$db->query("UPDATE {$DT_PRE}member SET `{$fd}`='$value',`{$vfd}`=1 WHERE userid=$userid");
				if($fd == 'company') $db->query("UPDATE {$DT_PRE}company SET `company`='$value' WHERE userid=$userid");
				$db->query("UPDATE {$table} SET status=3,editor='$_username',edittime='$DT_TIME' WHERE itemid='$id'");
				if($msg || $eml) {
					$content = $title = '您的'.$V[$fd].'已经通过审核';
					if($reason) $content .= '<br/>'.nl2br($reason);
					if($msg) send_message($username, $title, $content);
					if($eml) send_mail($user['email'], $title, $content);
				}
				$content = '您的'.$V[$fd].'已经通过审核';
				if($reason) $content .= ','.$reason;
				if($sms) send_sms($user['mobile'], $content.$DT['sms_sign']);
				if($wec) send_weixin($user['username'], $content);
				$i++;
			}
		}
		dmsg('通过认证 '.$i.' 条', $forward);		
	break;
	case 'member':
		$sfields = array('按条件', '会员名', '资料内容');
		$dfields = array('username', 'username', 'content');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}member_check WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}member_check WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 6);
			$lists[] = $r;
		}
		include tpl('validate_member', $module);
	break;
	case 'show':
		check_name($username) or msg();
		$t = $db->get_one("SELECT * FROM {$DT_PRE}member_check WHERE username='$username'");
		$t or msg('记录不存在');
		$U = userinfo($username);
		$U or msg('会员不存在');
		$E = dstripslashes(unserialize($t['content']));
		$userid = $U['userid'];
		$content_table = content_table(4, $userid, is_file(DT_CACHE.'/4.part'), $DT_PRE.'company_data');
		$t = $db->get_one("SELECT * FROM {$content_table} WHERE userid=$userid");
		$U['content'] = $t['content'];
		if(isset($E['regunit']) && !isset($E['capital'])) $E['capital'] = $U['capital'];
		if($submit) {
			$sql1 = $sql2 = $sql3 = '';
			if(in_array('thumb', $pass) && isset($E['thumb'])) {
				if($U['thumb']) delete_upload($U['thumb'], $userid);
				$sql2 .= ",thumb='".addslashes($E['thumb'])."'";
			}
			if(in_array('areaid', $pass) && isset($E['areaid'])) {
				$sql1 .= ",areaid='".addslashes($E['areaid'])."'";
				$sql2 .= ",areaid='".addslashes($E['areaid'])."'";
			}
			if(in_array('type', $pass) && isset($E['type'])) {
				$sql2 .= ",type='".addslashes($E['type'])."'";
			}
			if(in_array('business', $pass) && isset($E['business'])) {
				$sql2 .= ",business='".addslashes($E['business'])."'";
			}
			if(in_array('regyear', $pass) && isset($E['regyear'])) {
				$sql2 .= ",regyear='".addslashes($E['regyear'])."'";
			}
			if(in_array('capital', $pass) && isset($E['capital'])) {
				$sql2 .= ",capital='".addslashes($E['capital'])."'";
				if(isset($E['regunit'])) $sql2 .= ",regunit='".addslashes($E['regunit'])."'";
			}
			if(in_array('address', $pass) && isset($E['address'])) {
				$sql2 .= ",address='".addslashes($E['address'])."'";
			}
			if(in_array('telephone', $pass) && isset($E['telephone'])) {
				$sql2 .= ",telephone='".addslashes($E['telephone'])."'";
			}
			if(in_array('content', $pass) && isset($E['content'])) {
				delete_diff($E['content'], $U['content']);
				$sql3 .= ",content='".addslashes($E['content'])."'";
			}
			$ECK = array(
				'thumb' => '形象图片',
				'areaid' => '所在地区',
				'type' => '公司类型',
				'business' => '经营范围',
				'regyear' => '成立年份',
				'capital' => '注册资本',
				'address' => '公司地址',
				'telephone' => '联系电话',
				'content' => '公司介绍',
			);
			$title = '会员资料修改审核结果';
			$content = '尊敬的会员：<br/>您的会员资料修改已经审核，现将结果通知如下：<br/>';
			foreach($E as $k=>$v) {
				if(!isset($ECK[$k])) continue;
				$content .= $ECK[$k].' ---------- '.(in_array($k, $pass) ? '<span style="color:green;">已通过</span>' : '<span style="color:red;">未通过</span>').'<br/>';
			}
			if($reason) $content .= '操作原因：'.nl2br($reason).'<br/>';
			if($msg) send_message($username, $title, $content);
			if($eml) send_mail($U['email'], $title, $content);
			if($sms) send_sms($U['mobile'], '您的会员资料修改审核结果已通过站内信发送，请注意查阅');
			if($wec) send_weixin($username, '您的会员资料修改审核结果已通过站内信发送，请注意查阅');
			if($sql1) $db->query("UPDATE {$DT_PRE}member SET ".substr($sql1, 1)." WHERE userid=$userid");
			if($sql2) $db->query("UPDATE {$DT_PRE}company SET ".substr($sql2, 1)." WHERE userid=$userid");
			if($sql3) $db->query("UPDATE {$content_table} SET ".substr($sql3, 1)." WHERE userid=$userid");
			$db->query("DELETE FROM {$DT_PRE}member_check WHERE username='$username'");
			dmsg('操作成功', '?moduleid='.$moduleid.'&file='.$file.'&action=member');
		} else {
			include tpl('validate_show', $module);
		}
	break;
	default:
		$menuid = $S[$action];
		$sfields = array('按条件', '认证项', '会员名', '操作人');
		$dfields = array('title', 'title', 'username', 'editor');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($type) or $type = '';
		$status = isset($status) ? intval($status) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($action) $condition .= " AND type='$action'";
		if($status) $condition .= " AND status=$status";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$lists[] = $r;
		}
		include tpl('validate', $module);
	break;
}
?>