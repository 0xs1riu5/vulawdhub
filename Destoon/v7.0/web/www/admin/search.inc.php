<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('后台搜索', '?file='.$file),
);
$lists = $files = array();
if($kw) {
	$ukw = urlencode($kw);
	$mid = $moduleid;
	$menu = array();
	include DT_ROOT.'/admin/menu.inc.php';
	foreach($menu as $m) {
		$m[0] = '系统维护 - 系统工具 - '.$m[0];
		if(strpos($m[0], $kw) !== false) $files[] = $m;
	}
	foreach($menu_system as $m) {
		if(strpos($m[0], $kw) !== false) {
			$m[0] = '系统维护 - 系统设置 - '.$m[0];
			$files[] = $m;
		}
	}
	foreach($MODULE as $k=>$v) {
		if($v['islink'] || $k == 1) continue;
		$menu = array();
		$moduleid = $k;
		$name = $v['name'];
		include DT_ROOT.'/module/'.$v['module'].'/admin/menu.inc.php';
		$name = $moduleid == 3 ? '扩展功能' : $name.'管理';
		foreach($menu as $m) {
			if(strpos($m[0], $kw) !== false) {
				$m[0] = ($moduleid > 2 ? '功能模块 - ' : '').$name.' - '.$m[0];
				$files[] = $m;
			}
		}
	}
	foreach($menu_finance as $m) {
		if(strpos($m[0], $kw) !== false) {
			$m[0] = '会员管理 - 财务管理 - '.$m[0];
			$files[] = $m;
		}
	}
	foreach($menu_relate as $m) {
		if(strpos($m[0], $kw) !== false) {
			$m[0] = '会员管理 - 会员相关 - '.$m[0];
			$files[] = $m;
		}
	}
	$moduleid = $mid;
	$content = file_get_contents(DT_ROOT.'/admin/template/setting.tpl.php');
	if(preg_match_all('/('.$kw.')/i', $content, $m)) {
		$lists[1]['num'] = count($m[1]);
		$lists[1]['name'] = '系统维护 - 网站设置';
	}
	foreach($MODULE as $k=>$v) {
		if($v['islink'] || $k == 1) continue;
		$content = file_get_contents(DT_ROOT.'/module/'.$v['module'].'/admin/template/setting.tpl.php');
		if(preg_match_all('/('.$kw.')/i', $content, $m)) {
			$lists[$k]['num'] = count($m[1]);
			$lists[$k]['name'] = '功能模块 - '.($k == 3 ? '扩展功能' : $v['name'].'管理').' - 模块设置';
		}
	}
	$content = file_get_contents(DT_ROOT.'/module/member/admin/template/group_edit.tpl.php');
	if(preg_match_all('/('.$kw.')/i', $content, $m)) {
		foreach(cache_read('group.php') as $m) {
			$_m = array();
			$_m[0] = '会员管理 - 会员组管理 - '.$m['groupname'];
			$_m[1] = '?moduleid=2&file=group&action=edit&groupid='.$m['groupid'].'&kw='.$ukw;
			$files[] = $_m;
		}
	}
	foreach(cache_read('menu-'.$_userid.'.php') as $m) {
		if(strpos($m['title'], $kw) !== false) {
			$_m = array();
			$_m[0] = '我的面板 - '.$m['title'];
			$_m[1] = $m['url'];
			$files[] = $_m;
		}
	}
}
include tpl('search');
?>