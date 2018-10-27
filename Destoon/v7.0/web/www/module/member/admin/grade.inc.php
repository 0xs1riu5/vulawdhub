<?php
defined('DT_ADMIN') or exit('Access Denied');
#require DT_ROOT.'/module/'.$module.'/grade.class.php';
$do = new grade();
$menus = array (
    array('升级记录', '?moduleid='.$moduleid.'&file='.$file),
    array('审核申请', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('拒绝记录', '?moduleid='.$moduleid.'&file='.$file.'&action=reject'),
);
if(in_array($action, array('', 'check', 'reject'))) {
	$sfields = array('按条件', '公司名', '会员名', '联系人', '电话', '手机', 'Email', 'QQ', '微信', 'IP', '附言', '备注');
	$dfields = array('company', 'company', 'username', 'truename', 'telephone', 'mobile', 'email', 'qq', 'wx', 'ip', 'content', 'note');
	$sorder  = array('结果排序方式', '申请时间降序', '申请时间升序', '受理时间降序', '受理时间升序', '付款金额降序', '付款金额升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'amount DESC', 'amount ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
}
$menuon = array('4', '2', '1', '0');
switch($action) {
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->edit($post)) {
				dmsg('操作成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$user = $username ? userinfo($username) : array();
			$addtime = timetodate($addtime);
			$edittime = timetodate($edittime);
			$fromtime = timetodate($DT_TIME, 3);
			$days = 364;
			$totime = timetodate($DT_TIME + 86400*$days, 3);
			$UG = cache_read('group-'.$groupid.'.php');
			$fee = $UG['fee'];
			$pay = $fee - $amount;
			include tpl('grade_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择记录');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'reject':
		$status = 1;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('grade', $module);
	break;
	case 'check':
		$status = 2;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('grade', $module);
	break;
	default:
		$status = 3;
		$lists = $do->get_list('status='.$status.$condition, $dorder[$order]);
		include tpl('grade', $module);
	break;
}

class grade {
	var $itemid;
	var $table;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'upgrade';
    }

    function grade() {
		$this->__construct();
    }

	function get_one($condition = '') {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid' $condition");
	}

	function get_list($condition = 'status=3', $order = 'addtime DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$lists[] = $r;
		}
		return $lists;
	}

	function edit($post) {
		global $_username, $GROUP, $L;
		$item = $this->get_one();
		$user = $item['username'] ? userinfo($item['username']) : array();		
		$msg = isset($post['msg']) ? 1 : 0;
		$eml = isset($post['eml']) ? 1 : 0;
		$sms = isset($post['sms']) ? 1 : 0;
		$wec = isset($post['wec']) ? 1 : 0;
		$message = ($msg || $eml || $sms || $wec) ? 1 : 0;
		$post['status'] = intval($post['status']);
		$post['reason'] = strip_tags($post['reason']);
		$post['note'] = strip_tags($post['note']);
		$gsql = $msql = $csql = '';
		$gsql = "edittime=".DT_TIME.",editor='$_username',status=$post[status],message='$message',reason='$post[reason]',note='$post[note]'";
		if($post['status'] == 1) {
			//reject
			if($user) {
				if($item['amount']) {
					money_add($user['username'], $item['amount']);
					money_record($user['username'], $item['amount'], $L['in_site'], 'system', $L['grade_title'], $L['grade_return']);
				}
				$subject = '您的'.$GROUP[$item['groupid']]['groupname'].'升级审核未通过';
				$body = '尊敬的会员：<br/>您的'.$GROUP[$item['groupid']]['groupname'].'升级审核未通过！<br/>';
				if($post['reason']) $body .= '操作原因：<br/>'.$post['reason'].'<br/>';
				$body .= '如果您对此操作有异议，请及时与网站联系。';
				if($msg) send_message($user['username'], $subject, $body);
				if($wec) send_weixin($user['username'], $subject);
				if($eml) send_mail($user['email'], $subject, $body);
				if($sms) send_sms($user['mobile'], $subject.$DT['sms_sign']);
			}
		} else if($post['status'] == 2) {
			//
		} else if($post['status'] == 3) {
			if($user) {
				if($GROUP[$item['groupid']]['type']) {
					$t = DB::get_one("SELECT userid FROM ".DT_PRE."company WHERE company='$post[company]' AND userid<>$user[userid]");
					if($t) msg('公司名称已存在');
				}
				$msql = $csql = "groupid=$item[groupid],company='$post[company]'";
				$gsql .= ",company='$post[company]'";
				$vip = $GROUP[$item['groupid']]['vip'];
				$csql .= ",vip=$vip,vipt=$vip";
				if(isset($post['fromtime'])) {
					$csql .= ",fromtime=".strtotime($post['fromtime']).",totime=".strtotime($post['totime'].' 23:59:59').",validtime=".strtotime($post['validtime']).",validator='$post[validator]',validated=$post[validated]";
				}
				$subject = '您的'.$GROUP[$item['groupid']]['groupname'].'升级审核已通过';
				$body = '尊敬的会员：<br/>您的'.$GROUP[$item['groupid']]['groupname'].'升级审核已通过！<br/>';
				if($post['reason']) $body .= '操作原因：<br/>'.$post['reason'].'<br/>';
				$body .= '感谢您的支持！';
				if($msg) send_message($user['username'], $subject, $body);
				if($wec) send_weixin($user['username'], $subject);
				if($eml) send_mail($user['email'], $subject, $body);
				if($sms) send_sms($user['mobile'], $subject.$DT['sms_sign']);
			}
		}
		DB::query("UPDATE {$this->table} SET $gsql WHERE itemid=$this->itemid");
		if($msql) DB::query("UPDATE ".DT_PRE."member SET $msql WHERE userid=$item[userid]");
		if($csql) DB::query("UPDATE ".DT_PRE."company SET $csql WHERE userid=$item[userid]");
		if($msql || $csql) userclean($user['username']);
		return true;
	}

	function delete($itemid, $all = true) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>