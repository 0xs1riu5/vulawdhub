<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('上传记录', '?file='.$file),
);
$id = isset($id) ? intval($id) : -1;
($id > -1 && $id < 10) or $id = -1;
if($id == -1 && $action != 'part' && $action != 'delete_user' && $action != 'find') $action = 'part';
if($id > -1) $table = $DT_PRE.'upload_'.$id;
switch($action) {
	case 'delete':
		$itemid or msg('请选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$result = $db->query("SELECT fileurl FROM {$table} WHERE pid IN ($itemids)");
		while($r = $db->fetch_array($result)) {
			 delete_upload($r['fileurl'], 0);
		}
		$db->query("DELETE FROM {$table} WHERE pid IN ($itemids)");
		if(isset($ajax)) {
			exit('1');
		} else {
			dmsg('删除成功', $forward);
		}
	break;
	case 'delete_record':
		$itemid or msg('请选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$table} WHERE pid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	case 'delete_user':
		check_name($username) or msg('请填写会员名');
		$u = $db->get_one("SELECT userid,groupid FROM {$DT_PRE}member WHERE username='$username'");
		if($u && $u['groupid'] == 1) msg('管理组不可删除');
		if($id > -1) {
			if(!isset($fid)) {
				$r = $db->get_one("SELECT min(pid) AS fid FROM {$table} WHERE username='$username'");
				$fid = $r['fid'] ? $r['fid'] : 0;
			}
			if(!isset($tid)) {
				$r = $db->get_one("SELECT max(pid) AS tid FROM {$table} WHERE username='$username'");
				$tid = $r['tid'] ? $r['tid'] : 0;
			}
			isset($num) or $num = 2;
			isset($sid) or $sid = $fid;
			isset($itemid) or $itemid = 1;
			if($fid <= $tid) {
				$result = $db->query("SELECT * FROM {$table} WHERE pid>=$fid AND username='$username' ORDER BY pid LIMIT 0,$num ");
				if($db->affected_rows($result)) {
					while($r = $db->fetch_array($result)) {
						$itemid = $r['pid'];
						delete_upload($r['fileurl'], 0);
					}
					$itemid += 1;
				} else {
					$itemid = $fid + $num;
				}
				msg('ID从'.$fid.'至'.($itemid-1).'删除成功'.progress($sid, $fid, $tid), "?file=$file&action=$action&username=$username&id=$id&sid=$sid&fid=$itemid&tid=$tid&num=$num");
			} else {
				dmsg('删除成功', "?file=$file");
			}
		} else {
			if($u) {
				$id = $u['userid']%10;
			} else {
				for($i = 0; $i < 10; $i++) {
					$t = $db->get_one("SELECT itemid FROM {$DT_PRE}upload_{$i} WHERE username='$username'");
					if($t) {
						$id = $i;
						break;
					}
				}
				if($id == -1) msg('会员['.$username.']没有上传记录');
			}
			msg('正在开始删除..', "?file=$file&action=$action&username=$username&id=$id");
		}
	break;
	case 'part':
		$lists = array();
		for($i = 0; $i < 10; $i++) {
			$r = array();
			$r['table'] = $DT_PRE.'upload_'.$i;
			$t = $db->get_one("SHOW TABLE STATUS FROM `".$CFG['db_name']."` LIKE '".$r['table']."'");
			$r['rows'] = $t['Rows'];
			$r['name'] = $t['Comment'];
			$lists[] = $r;
		}
		include tpl('upload_part');
	break;
	case 'play':
		isset($video) or exit;
		include tpl('header');
		load('player.js');
		exit('<script type="text/javascript">document.write(player("'.$video.'", 480, 360, 1));</script></body></html>');
	break;
	case 'find':
		is_url($url) or msg();
		$t = parse_url($url);
		$kw = $t['path'];
		if(strpos($url, '/file/upload/') !== false) $kw = str_replace('/file/upload/', '', $kw);
		dheader('?file='.$file.'&id='.(match_userid($url)%10).'&kw='.$kw);
	break;
	default:
		$sfields = array('按条件', '文件名', '会员', '来源', '后缀', '信息ID', '表名');
		$dfields = array('fileurl', 'fileurl', 'username', 'upfrom', 'fileext', 'itemid', 'tb');
		$sorder  = array('排序方式', '文件大小降序', '文件大小升序', '上传时间降序', '上传时间升序', '图片宽度降序', '图片宽度升序', '图片高度降序', '图片高度升序');
		$dorder  = array('pid DESC', 'filesize DESC', 'filesize ASC', 'addtime DESC', 'addtime ASC', 'width DESC', 'width ASC', 'height DESC', 'height ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		(isset($username) && check_name($username)) or $username = '';
		$thumb = isset($thumb) ? intval($thumb) : 0;
		$upfrom = isset($upfrom) ? $upfrom : '';
		$tb = isset($tb) ? $tb : '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= $fields < 2 ? " AND $dfields[$fields] LIKE '%$keyword%'" : " AND $dfields[$fields]='$keyword'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($mid) $condition .= " AND moduleid='$mid'";	
		if($itemid) $condition .= " AND itemid='$itemid'";	
		if($username) $condition .= " AND username='$username'";
		if($upfrom) $condition .= " AND upfrom='$upfrom'";
		if($tb) $condition .= " AND tb='$tb'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['ext'] = file_ext($r['fileurl']);
			is_file(DT_ROOT.'/file/ext/'.$r['ext'].'.gif') or $r['ext'] = 'oth';
			if($r['filesize'] > 1024*1024*1024) {
				$r['size'] = dround($r['filesize']/1024/1024/1024, 2).'G';
			} else if($r['filesize'] > 1024*1024) {
				$r['size'] = dround($r['filesize']/1024/1024, 2).'M';
			} else {
				$r['size'] = dround($r['filesize']/1024, 2).'K';
			}
			$r['addtime'] = timetodate($r['addtime'], 6);
			$r['image'] = is_image($r['fileurl']) ? 1 : 0;
			$r['video'] = in_array($r['ext'], array('swf', 'flv', 'mp4')) ? 1 : 0;
			$r['fileurl'] = str_replace('.thumb.'.$r['ext'], '', $r['fileurl']);
			$r['img_w'] = $r['width'] > 100 ? 100 : $r['width'];
			$lists[] = $r;
		}
		include tpl('upload');
	break;
}
?>