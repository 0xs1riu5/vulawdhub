<?php
defined('DT_ADMIN') or exit('Access Denied');
isset($username) or $username = '';
$menus = array (
    array('发送短信', '?moduleid='.$moduleid.'&file='.$file.'&username='.$username),
    array('发送记录', '?moduleid='.$moduleid.'&file='.$file.'&username='.$username.'&action=record'),
    array('获取列表', '?moduleid='.$moduleid.'&file='.$file.'&action=make'),
    array('号码列表', '?moduleid='.$moduleid.'&file='.$file.'&action=list'),
);
function _userinfo($mobile) {
	return DB::get_one("SELECT * FROM ".DT_PRE."member m,".DT_PRE."company c WHERE m.userid=c.userid AND m.mobile='$mobile'");
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
		$mailfiles = glob(DT_ROOT.'/file/mobile/*.txt');
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
		include tpl('sendsms_list', $module);
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
				cache_write('mobile-list-'.$_userid.'.php', $item);
			} else {
				$item = cache_read('mobile-list-'.$_userid.'.php');
				$item or msg();
				extract($item);
			}
			$pagesize = $num;
			$offset = ($page-1)*$pagesize;
			$result = $db->query("SELECT mobile FROM $tb WHERE $sql AND mobile<>'' LIMIT $offset,$pagesize");
			$data = '';
			while($r = $db->fetch_array($result)) {
				if(is_mobile($r['mobile'])) $data .= $r['mobile']."\r\n";
			}
			if($data) {
				$filename = timetodate($DT_TIME, 'YmdHis').$title.'-'.$random.'-'.$page.'.txt';
				file_put(DT_ROOT.'/file/mobile/'.$filename, trim($data));
				$page++;
				msg('文件'.$filename.'获取成功。<br/>请稍候，程序将自动继续...', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&page='.$page.'&make=1');
			} else {
				cache_delete('mobile-list-'.$_userid.'.php');
				msg('列表获取成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
			}
		} else {
			include tpl('sendsms_make', $module);
		}
	break;
	case 'download':
		$file_ext = file_ext($filename);
		if($file_ext != 'txt') msg('只能下载TxT文件');
		file_down(DT_ROOT.'/file/mobile/'.$filename);
	break;
	case 'upload':
		require DT_ROOT.'/include/upload.class.php';
		$do = new upload($_FILES, 'file/mobile/', $uploadfile_name, 'txt');	
		$do->adduserid = false;
		if($do->save()) msg('上传成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
		msg($do->errmsg);
	break;
	case 'delete':
		 if(is_array($filenames)) {
			 foreach($filenames as $filename) {
				 if(file_ext($filename) == 'txt') @unlink(DT_ROOT.'/file/mobile/'.$filename);
			 }
		 } else {
			 if(file_ext($filenames) == 'txt') @unlink(DT_ROOT.'/file/mobile/'.$filenames);
		 }
		 dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
	break;
	case 'delete_record':
		$itemid or msg('未选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}sms WHERE itemid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	case 'clear':
		$time = $today_endtime - 90*86400;
		$db->query("DELETE FROM {$DT_PRE}sms WHERE sendtime<$time");
		dmsg('清理成功', $forward);
	break;
	case 'record':
		$sfields = array('按条件', '短信内容', '发送结果', '手机号', 'IP', '操作人');
		$dfields = array('message', 'message', 'code', 'mobile', 'editor');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= $fields < 3 ? " AND $dfields[$fields] LIKE '%$keyword%'" : " AND $dfields[$fields]='$keyword'";
		if($fromtime) $condition .= " AND sendtime>=$fromtime";
		if($totime) $condition .= " AND sendtime<=$totime";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}sms WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}sms WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['sendtime'] = str_replace(' ', '<br/>', timetodate($r['sendtime'], 6));
			$r['num'] = ceil($r['word']/$DT['sms_len']);
			$lists[] = $r;
		}
		include tpl('sendsms_record', $module);
	break;
	default:
		if(isset($send)) {
			if(isset($preview) && $preview) {
				if($sendtype == 2) {
					$mobiles = explode("\n", $mobiles);
					$mobile = trim($mobiles[0]);
				} else if($sendtype == 3) {
					$mobiles = explode("\n", file_get(DT_ROOT.'/file/mobile/'.$mobilelist));
					$mobile = trim($mobiles[0]);
				}
				$user = _userinfo($mobile);
				if($user && _safecheck($content)) eval("\$content = \"$content\";");
				exit($content.$sign);
			}
			if($sendtype == 1) {
				$content or msg('请填写短信内容');
				$mobile or msg('请填写接收号码');
				$mobile = trim($mobile);
				$DT['sms_sign'] = $sign;
				$s = 0;
				if(is_mobile($mobile)) {
					$user = _userinfo($mobile);
					if($user && _safecheck($content)) eval("\$content = \"$content\";");
					$content = strip_sms($content);
					$sms_code = send_sms($mobile, $content);
					if(strpos($sms_code, $DT['sms_ok']) !== false) $s++;
				}
				dmsg($s ? '短信发送成功' : '短信发送失败', $forward);
			} else if($sendtype == 2) {
				$content or msg('请填写短信内容');
				$mobiles or msg('请填写接收号码');
				$mobiles = explode("\n", $mobiles);
				$_content = $content;
				$DT['sms_sign'] = $sign;
				$s = $f = 0;
				foreach($mobiles as $mobile) {
					$mobile = trim($mobile);
					if(is_mobile($mobile)) {
						$user = _userinfo($mobile);
						$content = $_content;
						if($user && _safecheck($content)) eval("\$content = \"$content\";");
						$content = strip_sms($content);
						$sms_code = send_sms($mobile, $content);
						if(strpos($sms_code, $DT['sms_ok']) !== false) {
							$s++;
						} else {
							$f++;
						}
					}
				}
				dmsg('发送成功('.$s.'),发送失败('.$f.')', $forward);
			} else if($sendtype == 3) {
				if(isset($id)) {
					$data = cache_read($_username.'_sendsms.php');
					$content = $data['content'];
					$mobilelist = $data['mobilelist'];
					$sign = $data['sign'];
				} else {
					$id = $s = $f = 0;
					$content or msg('请填写短信内容');
					$mobilelist or msg('请选择号码列表');
					$data = array();
					$data['mobilelist'] = $mobilelist;
					$data['content'] = $content;
					$data['sign'] = $sign;
					cache_write($_username.'_sendsms.php', $data);
				}
				$_content = $content;
				$DT['sms_sign'] = $sign;
				$pernum = intval($pernum);
				if(!$pernum) $pernum = 10;
				$mobiles = file_get(DT_ROOT.'/file/mobile/'.$mobilelist);
				$mobiles = explode("\n", $mobiles);
				for($i = 1; $i <= $pernum; $i++) {
					$mobile = trim($mobiles[$id++]);
					if(is_mobile($mobile)) {
						$user = _userinfo($mobile);
						$content = $_content;
						if($user && _safecheck($content)) eval("\$content = \"$content\";");
						$content = strip_sms($content);
						$sms_code = send_sms($mobile, $content);
						if(strpos($sms_code, $DT['sms_ok']) !== false) {
							$s++;
						} else {
							$f++;
						}
					}
				}
				if($id < count($mobiles)) {
					msg('已发送('.$id.')条短信，('.$s.')成功('.$f.')失败，系统将自动继续，请稍候...', '?moduleid='.$moduleid.'&file='.$file.'&sendtype=3&id='.$id.'&s='.$s.'&f='.$f.'&pernum='.$pernum.'&send=1');
				}
				cache_delete($_username.'_sendsms.php');
				dmsg('发送成功('.$s.'),发送失败('.$f.')', '?moduleid='.$moduleid.'&file='.$file);
			}
		} else {
			$sendtype = isset($sendtype) ? intval($sendtype) : 1;
			isset($mobile) or $mobile = '';
			$mobiles = '';
			if(isset($userid)) {
				if($userid) {
					$userids = is_array($userid) ? implode(',', $userid) : $userid;					
					$result = $db->query("SELECT mobile FROM {$DT_PRE}member WHERE userid IN ($userids)");
					while($r = $db->fetch_array($result)) {
						if($r['mobile']) $mobiles .= $r['mobile']."\n";
					}
				}
			}
			if($mobile) {
				if(strpos($mobile, ',') !== false) $mobile = explode(',', $mobile);
				$mobiles .= is_array($mobile) ? implode("\n", $mobile) : $mobile."\n";
			}
			if($mobiles) $sendtype = 2;
			include tpl('sendsms', $module);
		}
	break;
}
?>