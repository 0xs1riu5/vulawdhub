<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('发送邮件', '?moduleid='.$moduleid.'&file='.$file),
    array('发送记录', '?moduleid='.$moduleid.'&file='.$file.'&action=record'),
    array('获取列表', '?moduleid='.$moduleid.'&file='.$file.'&action=make'),
    array('邮件列表', '?moduleid='.$moduleid.'&file='.$file.'&action=list'),
);
function _userinfo($fields, $email) {
	if($fields == 'mail') {
		return DB::get_one("SELECT * FROM ".DT_PRE."member m,".DT_PRE."company c WHERE m.userid=c.userid AND c.mail='$email'");
	} else {
		return DB::get_one("SELECT * FROM ".DT_PRE."member m,".DT_PRE."company c WHERE m.userid=c.userid AND m.email='$email'");
	}
}
function _safecheck($content) {
	if(strpos($content, '{$user[') === false) return false;
	$str = str_replace('{$user[', '', $content);
	foreach(array('$', '(', '{', '[') as $v) {
		if(strpos($str, $v) !== false) return false;
	}
	return true;

}
switch($action) {
	case 'list':		 
		$others = array();
		$mailfiles = glob(DT_ROOT.'/file/email/*.txt');
		$mail = $mails = array();
		if(is_array($mailfiles)) {
			$mailfiles = array_reverse($mailfiles);
			$class = 1;
			foreach($mailfiles as $id=>$mailfile) {
				$tmp = basename($mailfile);
				$mail['filename'] = $tmp;
				$mail['filesize'] = round(filesize($mailfile)/(1024), 2);
				$mail['mtime'] = timetodate(filemtime($mailfile), 5);
				$mail['count'] = substr_count(file_get($mailfile), "\n") + 1;	
				$mails[] = $mail;
			}
		}
		include tpl('sendmail_list', $module);
	break;
	case 'make':
		if(isset($make)) {
			if(isset($first)) {
				$tb or $tb = $DT_PRE.'member';
				$tb = strip_sql($tb, 0);
				$num or $num = 1000;
				$sql or $sql = 'groupid>4';
				$title = $title ? file_vname('-'.$title) : '';
				$random = strtolower(random(10));
				$item = array();
				$item['tb'] = $tb;
				$item['num'] = $num;
				$item['sql'] = $sql;
				$item['title'] = $title;
				$item['random'] = $random;
				cache_write('mail-list-'.$_userid.'.php', $item);
			} else {
				$item = cache_read('mail-list-'.$_userid.'.php');
				$item or msg();
				extract($item);
			}
			$pagesize = $num;
			$offset = ($page-1)*$pagesize;
			$data = '';
			$query = "SELECT email FROM $tb WHERE $sql AND email<>'' LIMIT $offset,$pagesize";
			$result = $db->query($query);
			while($r = $db->fetch_array($result)) {
				if(is_email($r['email'])) $data .= $r['email']."\r\n";
			}
			if($data) {
				$filename = timetodate($DT_TIME, 'YmdHis').$title.'-'.$random.'-'.$page.'.txt';
				file_put(DT_ROOT.'/file/email/'.$filename, trim($data));
				$page++;
				msg('文件'.$filename.'获取成功。<br/>请稍候，程序将自动继续...', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&page='.$page.'&make=1');
			} else {
				cache_delete('mail-list-'.$_userid.'.php');
				msg('列表获取成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
			}
		} else {
			include tpl('sendmail_make', $module);
		}
	break;
	case 'download':
		$file_ext = file_ext($filename);
		$file_ext == 'txt' or msg('只能下载TxT文件');
		file_down(DT_ROOT.'/file/email/'.$filename);
	break;
	case 'upload':
		require DT_ROOT.'/include/upload.class.php';
		$do = new upload($_FILES, 'file/email/', $uploadfile_name, 'txt');	
		$do->adduserid = false;
		if($do->save()) msg('上传成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
		msg($do->errmsg);
	break;
	case 'delete':
		 if(is_array($filenames)) {
			 foreach($filenames as $filename) {
				 if(file_ext($filename) == 'txt') @unlink(DT_ROOT.'/file/email/'.$filename);
			 }
		 } else {
			 if(file_ext($filenames) == 'txt') @unlink(DT_ROOT.'/file/email/'.$filenames);
		 }
		 dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
	break;
	case 'record':
		$table = $DT_PRE.'mail_log';
		$sfields = array('按条件', '邮件标题', '邮件地址', '邮件内容', '备注');
		$dfields = array('title', 'title', 'email', 'content', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($email) or $email = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($type) or $type = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($type) $condition .= $type == 1 ? " AND status=3" : " AND status=2";
		if($email) $condition .= " AND email='$email'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$records = array();
		$result = $db->query("SELECT itemid,email,title,addtime,status,note FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$records[] = $r;
		}
		include tpl('sendmail_record', $module);
	break;
	case 'show':
		$itemid or msg();
		$item = $db->get_one("SELECT * FROM {$DT_PRE}mail_log WHERE itemid=$itemid");
		$item or msg();
		extract($item);
		include tpl('sendmail_show', $module);		
	break;
	case 'resend':
		$itemid or msg('未选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$DT['mail_log'] = $i = 0;		
		$result = $db->query("SELECT * FROM {$DT_PRE}mail_log WHERE itemid IN ($itemids)");
		while($r = $db->fetch_array($result)) {
			if($r['status'] == 3) continue;
			if(send_mail($r['email'], $r['title'], $r['content'])) {
				$db->query("UPDATE {$DT_PRE}mail_log SET status=3,edittime='".DT_TIME."',editor='$_username',note='' WHERE itemid=$r[itemid]");
				$i++;
			}
		}
		dmsg('成功发送('.$i.')封', $forward);
	break;
	case 'delete_record':
		$itemid or msg('未选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}mail_log WHERE itemid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$DT_PRE}mail_log WHERE addtime<$time");
		dmsg('清理成功', $forward);
	break;
	default:
		if(isset($send)) {
			if(isset($preview) && $preview) {
				$content = stripslashes($content);
				if($template) {
					if($sendtype == 2) {
						$emails = explode("\n", $emails);
						$email = trim($emails[0]);
					} else if($sendtype == 3) {
						$emails = explode("\n", file_get(DT_ROOT.'/file/email/'.$mail));
						$email = trim($emails[0]);
					}
					$user = _userinfo($fields, $email);
					if($user && _safecheck($title)) eval("\$title = \"$title\";");
					$content = ob_template($template, 'mail');
				}
				echo '<br/><strong>邮件标题：</strong>'.$title.'<br/><br/>';
				echo '<strong>邮件正文：</strong><br/><br/>';
				echo $content;
				exit;
			}
			if($sendtype == 1) {
				$title or msg('请填写邮件标题');
				is_email($email) or msg('请填写邮件地址');
				($template || $content) or msg('请填写邮件内容');
				$email = trim($email);
				$content = save_local(stripslashes($content));
				clear_upload($content, $_userid, 'sendmail');
				$DT['mail_name'] = $name;
				if($template) {
					$user = _userinfo($fields, $email);
					if($user && _safecheck($title)) eval("\$title = \"$title\";");
					$content = ob_template($template, 'mail');					
				}
				send_mail($email, $title, $content, $sender);
			} else if($sendtype == 2) {
				$title or msg('请填写邮件标题');
				$emails or msg('请填写邮件地址');
				($template || $content) or msg('请填写邮件内容');
				$emails = explode("\n", $emails);
				$content = save_local(stripslashes($content));
				clear_upload($content, $_userid, 'sendmail');
				$DT['mail_name'] = $name;
				$_content = $content;
				foreach($emails as $email) {
					$email = trim($email);
					if(is_email($email)) {
					    $content = $_content;
						if($template) {
							$user = _userinfo($fields, $email);
							if($user && _safecheck($title)) eval("\$title = \"$title\";");
							$content = ob_template($template, 'mail');
						}
						send_mail($email, $title, $content, $sender);
					}
				}
			} else if($sendtype == 3) {
				if(isset($id)) {
					$data = cache_read($_username.'_sendmail.php');
					$title = $data['title'];
					$content = $data['content'];
					$sender = $data['sender'];
					$name = $data['name'];
					$template = $data['template'];
					$maillist = $data['maillist'];
					$fields = $data['fields'];
				} else {
					$id = 0;
					$title or msg('请填写邮件标题');
					$maillist or msg('请选择邮件列表');
					($template || $content) or msg('请填写邮件内容');
					$content = save_local(stripslashes($content));
					clear_upload($content, $_userid, 'sendmail');
					$data = array();
					$data['title'] = $title;
					$data['content'] = $content;
					$data['sender'] = $sender;
					$data['name'] = $name;
					$data['template'] = $template;
					$data['maillist'] = $maillist;
					$data['fields'] = $fields;
					cache_write($_username.'_sendmail.php', $data);
				}
				$_content = $content;
				$pernum = intval($pernum);
				if(!$pernum) $pernum = 5;
				$pertime = intval($pertime);
				if(!$pertime) $pertime = 5;
				$DT['mail_name'] = $name;
				$emails = file_get(DT_ROOT.'/file/email/'.$maillist);
				$emails = explode("\n", $emails);
				for($i = 1; $i <= $pernum; $i++) {
					$email = trim($emails[$id++]);
					if(is_email($email)) {						
						$content = $_content;
						if($template) {
							$user = _userinfo($fields, $email);							
							if($user && _safecheck($title)) eval("\$title = \"$title\";");
							$content = ob_template($template, 'mail');
						}
						send_mail($email, $title, $content, $sender);
					}
				}
				if($id < count($emails)) {
					msg('已发送 '.$id.' 封邮件，系统将自动继续，请稍候...', '?moduleid='.$moduleid.'&file='.$file.'&sendtype=3&id='.$id.'&pernum='.$pernum.'&pertime='.$pertime.'&send=1', $pertime);
				}
				cache_delete($_username.'_sendmail.php');
				$forward = '?moduleid='.$moduleid.'&file='.$file;
			}
			dmsg('邮件发送成功', $forward);
		} else {
			$sendtype = isset($sendtype) ? intval($sendtype) : 1;
			isset($email) or $email = '';
			$emails = '';
			if(isset($userid)) {
				if($userid) {
					$userids = is_array($userid) ? implode(',', $userid) : $userid;					
					$result = $db->query("SELECT email FROM {$DT_PRE}member WHERE userid IN ($userids)");
					while($r = $db->fetch_array($result)) {
						$emails .= $r['email']."\n";
					}
				}
			}
			if($email) {
				if(strpos($email, ',') !== false) $email = explode(',', $email);
				$emails .= is_array($email) ? implode("\n", $email) : $email."\n";
			}
			if($emails) $sendtype = 2;
			include tpl('sendmail', $module);
		}
	break;
}
?>