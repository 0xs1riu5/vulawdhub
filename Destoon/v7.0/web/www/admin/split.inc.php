<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$mid > 3 or msg();
$fd = $mid == 4 ? 'userid' : 'itemid';
$table = get_table($mid);
$table_data = get_table($mid, 1);
if($action == 'merge') {
	if(!isset($num)) {
		$num = 5000;
	}
	if(!isset($fid)) {
		$r = $db->get_one("SELECT MIN(`$fd`) AS fid FROM {$table}");
		$fid = $r['fid'] ? $r['fid'] : 0;
	}
	isset($sid) or $sid = $fid;
	if(!isset($tid)) {
		$r = $db->get_one("SELECT MAX(`$fd`) AS tid FROM {$table}");
		$tid = $r['tid'] ? $r['tid'] : 0;
		$part = split_id($tid);
		for($i = 1; $i <= $part; $i++) {
			split_content($mid, $i);
		}
	}
	if($fid <= $tid) {
		$result = $db->query("SELECT `$fd` FROM {$table} WHERE `$fd`>=$fid ORDER BY `$fd` LIMIT 0,$num");
		if($db->affected_rows($result)) {
			while($r = $db->fetch_array($result)) {
				$itemid = $r[$fd];
				$t = $db->get_one("SELECT content FROM ".split_table($mid, $itemid)." WHERE `$fd`=$itemid");
				if($t) {
					$content = addslashes($t['content']);
					$db->query("REPLACE INTO {$table_data} ($fd,content) VALUES ('$itemid','$content')");
				} else {
					$t = $db->get_one("SELECT `$fd` FROM {$table_data} WHERE `$fd`=$itemid");
					if(!$t) $db->query("REPLACE INTO {$table_data} ($fd,content) VALUES ('$itemid','')");
				}
			}
			$itemid += 1;
		} else {
			$itemid = $fid + $num;
		}
	} else {
		$db->halt = 0;
		$part = split_id($tid);
		for($i = 1; $i < $part+3; $i++) {
			$tb = $DT_PRE.$mid.'_'.$i;
			$db->query("DROP TABLE IF EXISTS `{$tb}`");
		}
		msg($MODULE[$mid]['name'].'内容合并成功');
	}
	msg('ID从'.$fid.'至'.($itemid-1).'合并成功'.progress($sid, $fid, $tid), "?mid=$mid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num");
} else {
	if(!isset($num)) {
		$num = 5000;
	}
	if(!isset($fid)) {
		$r = $db->get_one("SELECT MIN(`$fd`) AS fid FROM {$table}");
		$fid = $r['fid'] ? $r['fid'] : 0;
	}
	isset($sid) or $sid = $fid;
	if(!isset($tid)) {
		$r = $db->get_one("SELECT MAX(`$fd`) AS tid FROM {$table}");
		$tid = $r['tid'] ? $r['tid'] : 0;
		$part = split_id($tid);
		for($i = 1; $i < $part+2; $i++) {
			split_content($mid, $i);
		}
	}
	if($fid <= $tid) {
		$result = $db->query("SELECT `$fd` FROM {$table} WHERE `$fd`>=$fid ORDER BY `$fd` LIMIT 0,$num");
		if($db->affected_rows($result)) {
			while($r = $db->fetch_array($result)) {
				$itemid = $r[$fd];
				$t = $db->get_one("SELECT content FROM {$table_data} WHERE `$fd`=$itemid");
				if($t) {
					$content = addslashes($t['content']);
					$db->query("REPLACE INTO ".split_table($mid, $itemid)." ($fd,content) VALUES ('$itemid','$content')");
				} else {
					$t = $db->get_one("SELECT `$fd` FROM ".split_table($mid, $itemid)." WHERE `$fd`=$itemid");
					if(!$t) $db->query("REPLACE INTO ".split_table($mid, $itemid)." ($fd,content) VALUES ('$itemid','')");
				}
			}
			$itemid += 1;
		} else {
			$itemid = $fid + $num;
		}
	} else {
		$table_back = 'zzz_'.substr($table_data, strlen($DT_PRE));
		$db->query("RENAME TABLE `{$table_data}` TO `{$table_back}`");
		#$db->query("TRUNCATE TABLE `{$table_data}`");
		msg($MODULE[$mid]['name'].'内容拆分成功');
	}
	msg('ID从'.$fid.'至'.($itemid-1).'拆分成功'.progress($sid, $fid, $tid), "?mid=$mid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num");
}
?>