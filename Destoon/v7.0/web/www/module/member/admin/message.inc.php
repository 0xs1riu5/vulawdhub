<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/message.class.php';
$menus = array (
    array('发送信件', '?moduleid='.$moduleid.'&file='.$file.'&action=send'),
    array('会员信件', '?moduleid='.$moduleid.'&file='.$file),
    array('系统信件', '?moduleid='.$moduleid.'&file='.$file.'&action=system'),
    array('邮件转发', '?moduleid='.$moduleid.'&file='.$file.'&action=mail'),
    array('信件清理', '?moduleid='.$moduleid.'&file='.$file.'&action=clear'),
);
$do = new message;
$this_forward = '?moduleid='.$moduleid.'&file='.$file;

$NAME = array('普通', '询价', '报价', '留言', '信使');

switch($action) {
	case 'send':
		if($submit) {
			if($do->_send($message)) {
				dmsg('发送成功', $this_forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			isset($touser) or $touser = '';
			$tousers = '';
			if(isset($userid)) {
				if($userid) {
					$userids = is_array($userid) ? implode(',', $userid) : $userid;					
					$result = $db->query("SELECT username FROM {$DT_PRE}member WHERE userid IN ($userids)");
					while($r = $db->fetch_array($result)) {
						if($r['username']) $tousers .= $r['username']."\n";
					}
				}
			}
			if($touser) {
				if(strpos($touser, ',') !== false) $touser = explode(',', $touser);
				$tousers .= is_array($touser) ? implode("\n", $touser) : $touser."\n";
			}
			if($tousers) $touser = str_replace("\n", ' ', trim($tousers));
			include tpl('message_send', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			$do->_edit($message);
			dmsg('修改成功', '?moduleid='.$moduleid.'&file='.$file.'&action=system');
		} else {
			extract($do->get_one());
			include tpl('message_edit', $module);
		}
	break;
	case 'clear':
		if($submit) {
			if($do->_clear($message)) {
				dmsg('清理成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			$todate = timetodate(strtotime('-1 year'), 3);
			include tpl('message_clear', $module);
		}
	break;
	case 'mail':
		if(isset($send)) {
			isset($num) or $num = 0;
			$hour = intval($hour);
			if(!$hour) $hour = 48;
			$pernum = intval($pernum);
			if(!$pernum) $pernum = 10;
			$pagesize = $pernum;
			$offset = ($page-1)*$pagesize;
			$time = $DT_TIME - $hour*3600;
			$result = $db->query("SELECT * FROM {$DT_PRE}message WHERE isread=0 AND issend=0 AND addtime<$time AND status=3 ORDER BY itemid DESC LIMIT $offset,$pagesize");
			$i = false;
			while($r = $db->fetch_array($result)) {
				$m = $db->get_one("SELECT email FROM {$DT_PRE}member WHERE username='$r[touser]' AND groupid>4");
				if(!$m) continue;
				$linkurl = $MODULE[2]['linkurl'].'message.php?action=show&itemid='.$r['itemid'];
				$r['fromuser'] or $r['fromuser'] = '系统信使';
				$r['content'] = $r['fromuser'].' 于 '.timetodate($r['addtime'], 5).' 向您发送一封站内信，内容如下：<br/><br/>'.$r['content'].'<br/><br/>原始地址：<a href="'.$linkurl.'" target="_blank">'.$linkurl.'</a><br/><br/>此邮件通过 <a href="'.DT_PATH.'" target="_blank">'.$DT['sitename'].'</a> 邮件系统发出<br/><br/>如果您不希望收到类似邮件，请经常登录网站查收站内信件或将未读信件标记为已读<br/><br/>';
				send_mail($m['email'], $r['title'], $r['content']);
				$db->query("UPDATE {$DT_PRE}message SET issend=1 WHERE itemid=$r[itemid]");
				$i = true;
				$num++;
			}
			if($i) {
				$page++;
				msg('已发送 '.$num.' 封邮件，系统将自动继续，请稍候...', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&page='.$page.'&hour='.$hour.'&pernum='.$pernum.'&num='.$num.'&send=1');
			} else {
				file_put(DT_CACHE.'/message.dat', $DT_TIME);
				msg('邮件发送成功 共发送 '.$num.' 封邮件', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action, 5);
			}
		} else {
			$lasttime = is_file(DT_CACHE.'/message.dat') ? file_get(DT_CACHE.'/message.dat') : 0;
			$lasttime = $lasttime ? timetodate($lasttime, 5) : '';
			include tpl('message_mail', $module);
		}
	break;
	case 'system_delete':
		$itemid or msg();
		$do->_delete($itemid);
		dmsg('删除成功', $this_forward);
	break;
	case 'system':
		$messages = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}message WHERE groupids<>'' ORDER BY itemid DESC");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['group'] = '<select>';
			$groupids = explode(',', $r['groupids']);
			foreach($groupids as $groupid) {
				$r['group'] .= '<option>'.$GROUP[$groupid]['groupname'].'</option>';
			}
			$r['group'] .= '</select>';
			$messages[] = $r;
		}
		include tpl('message_system', $module);
	break;
	case 'delete':
		if(!$itemid) msg();
		$do->itemid = $itemid;
		$do->delete();
		dmsg('删除成功', $forward);
	break;
	case 'show':
		$itemid or msg();
		$do->itemid = $itemid;
		$item = $do->get_one();
		$item or msg();
		extract($item);
		include tpl('message_show', $module);		
	break;
	default:
		$sfields = array('标题', '发件人', '收件人', 'IP', '内容');
		$dfields = array('title', 'fromuser', 'touser', 'ip', 'content');
		$S = array('状态', '草稿箱', '发件箱', '收件箱', '回收站');

		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? intval($typeid) : -1;
		$read = isset($read) ? intval($read) : -1;
		$send = isset($send) ? intval($send) : -1;
		$status = isset($status) ? intval($status) : 0;

		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($S, 'status', '', $status);

		$condition = "groupids=''";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($status) $condition .= " AND status=$status";
		if($typeid > -1) $condition .= " AND typeid=$typeid";
		if($read > -1) $condition .= " AND isread=$read";
		if($send > -1) $condition .= " AND issend=$send";

		$lists = $do->get_list($condition);
		include tpl('message', $module);
	break;
}
?>