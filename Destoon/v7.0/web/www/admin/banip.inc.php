<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('IP禁止', '?file='.$file),
    array('登录锁定', '?file='.$file.'&action=ban'),
    array('IP库更新', '?file='.$file.'&action=data'),
);
$http = decrypt('d0b5BA8OvRjCMbKiF6r0t7Qz5cfeOctTBMnzOuJ7mtHDC1uzbeXHCz1EckQAeYlm6dC3zGA83ZKqPCFXjIX27FQ', 'DESTOON');
switch($action) {
	case 'add':
		if(!$ip) msg('请填写IP地址或IP段');
		$ip = trim($ip);
		if(!preg_match("/^[0-9]{1,3}\.[0-9\*]{1,3}\.[0-9\*]{1,3}\.[0-9\*]{1,3}$/", $ip)) msg('IP地址或IP段格式错误');
		$totime = $todate ? strtotime($todate.' 00:00:00') : 0;
		$db->query("INSERT INTO {$DT_PRE}banip (ip,editor,addtime,totime) VALUES ('$ip','$_username','$DT_TIME','$totime')");
		dmsg('添加成功', '?file='.$file);
	break;
	case 'delete':
		$itemid or msg();
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}banip WHERE itemid IN ($itemids)");
		dmsg('删除成功', '?file='.$file);
	break;
	case 'clear':
		$db->query("DELETE FROM {$DT_PRE}banip WHERE totime>0 and totime<$DT_TIME");
		dmsg('清空成功', '?file='.$file);
	break;
	case 'unban':
		$ip or msg('IP不能为空');
		if(is_array($ip)) {
			foreach($ip as $v) {
				file_del(DT_CACHE.'/ban/'.$v.'.php');
			}
		} else {
			file_del(DT_CACHE.'/ban/'.$ip.'.php');
		}
		dmsg('删除成功', '?file='.$file.'&action=ban');
	break; 
	case 'down':
		dheader($http.'wry.rar');
	break;
	case 'update':
		$wry = DT_ROOT.'/file/ipdata/wry.dat';
		$new = file_get($http.'wry.txt');
		is_date($new) or msg('无法连接更新服务器');		
		if(is_file($wry)) {
			$now = timetodate(filemtime($wry), 'Ymd');
			$new > $now or msg('已是最新版本，无需更新');
			rename($wry, DT_ROOT.'/file/ipdata/'.$now.'.dat');
		}
		file_copy($http.'wry.dat', $wry);
		is_file($wry) or msg('更新失败，请重试');
		@touch($wry, strtotime($new));
		dmsg('更新成功', '?file='.$file.'&action=data');
	break;
	case 'data':
		$wry = DT_ROOT.'/file/ipdata/wry.dat';
		$new = file_get($http.'wry.txt');
		$get = 0;
		$update = 0;
		if(is_date($new)) {
			$get = 1;
		} else {
			$new = '<span class="f_red">获取失败</span>';
		}
		if(is_file($wry)) {
			$now = timetodate(filemtime($wry), 'Ymd');
			if($get && $new > $now) $update = 1;
		} else {
			$now = '<span class="f_red">文件不存在</span>';
			$update =1;
		}
		include tpl('banip_data');
	break;
	case 'ban':
		$ips = glob(DT_CACHE.'/ban/*.php');
		$lists = array();
		if($ips) {
			foreach($ips as $k=>$v) {
				$lists[$k]['ip'] = basename($v, '.php');
				$lists[$k]['addtime'] = timetodate(filemtime($v), 5);
			}
		}
		include tpl('banip_ban');
	break;
	default:
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}banip");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}banip ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['status'] = ($r['totime'] && $DT_TIME >  $r['totime']) ? '<span style="color:red;">过期</span>' : '<span style="color:blue;">有效</span>';
			$r['totime'] = $r['totime'] ? timetodate($r['totime'], 3) : '永久';
			$lists[] = $r;
		}
		cache_banip();
		include tpl('banip');
	break;
}
?>