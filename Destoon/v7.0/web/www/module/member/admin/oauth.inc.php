<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('一键登录', '?moduleid='.$moduleid.'&file='.$file),
    array('接口设置', '?moduleid='.$moduleid.'&file=setting&tab=6'),
);
switch($action) {
	case 'delete':
		$itemid or msg('请选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}oauth WHERE itemid IN ($itemids)");
		dmsg('解除成功', $forward);
	break;
	default:
		$OAUTH = cache_read('oauth.php');
		$sfields = array('按条件', '会员名', '昵称', '平台', '头像', '网址');
		$dfields = array('username', 'username', 'nickname', 'site', 'avatar', 'url');
		$sorder  = array('结果排序方式', '绑定时间降序', '绑定时间升序', '登录时间降序', '登录时间升序', '登录次数降序', '登录次数升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'logintime DESC', 'logintime ASC', 'logintimes DESC', 'logintimes ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($site) or $site = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$thumb = isset($thumb) ? intval($thumb) : 0;
		$link = isset($link) ? intval($link) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($site) $condition .= " AND site='$site'";		
		if($thumb) $condition .= " AND avatar<>''";
		if($link) $condition .= " AND url<>''";
		$order = $dorder[$order];
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}oauth WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$members = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}oauth WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['logindate'] = timetodate($r['logintime'], 5);
			$r['avatar'] or $r['avatar'] = DT_PATH.'api/oauth/avatar.png';
			$members[] = $r;
		}
		include tpl('oauth', $module);
	break;
}
?>