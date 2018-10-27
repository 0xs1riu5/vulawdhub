<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('添加规则', '?file='.$file.'&action=add'),
    array('采编规则', '?file='.$file),
);
switch($action) {
	case 'add':
		if($submit) {
			if(!$domain) msg('请输入采编域名');
			if(strpos($content, '[content]') === false) msg('请输入内容规则');
			$db->query("INSERT INTO {$DT_PRE}fetch (sitename,domain,encode,title,content,editor,edittime) VALUES ('$sitename','$domain','$encode','$title','$content','$_username','$DT_TIME')");
			dmsg('添加成功', $forward);
		} else {
			$domain = $sitename = $title = '';
			$encode = strtolower(DT_CHARSET);
			$content = '<div class="content">[content]</div>';
			include tpl('fetch_edit');
		}
	break;
	case 'edit':
		$itemid or msg('请选择规则');
		if($submit) {
			if(!$domain) msg('请输入采编域名');
			if(strpos($content, '[content]') === false) msg('请输入内容规则');
			$db->query("UPDATE {$DT_PRE}fetch SET sitename='$sitename',domain='$domain',encode='$encode',title='$title',content='$content',editor='$_username',edittime='$DT_TIME' WHERE itemid=$itemid");
			dmsg('修改成功', $forward);
		} else {
			extract($db->get_one("SELECT * FROM {$DT_PRE}fetch WHERE itemid=$itemid"));
			include tpl('fetch_edit');
		}
	break;
	case 'delete':
		$itemid or msg('请选择规则');
		$ids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}fetch WHERE itemid IN ($ids)");
		dmsg('删除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '域名', '网站', '编辑');
		$dfields = array('domain', 'domain', 'sitename', 'username');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}fetch WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}fetch WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['edittime'] = timetodate($r['edittime'], 5);
			$lists[] = $r;
		}
		include tpl('fetch');
	break;
}
?>