<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('审核资料', '?moduleid='.$moduleid.'&file='.$file),
);
switch($action) {
	case 'show':
		$itemid or msg('请选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}oauth WHERE itemid IN ($itemids)");
		dmsg('解除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '会员名', '修改内容');
		$dfields = array('username', 'username', 'content');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}member_check WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}member_check WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$lists[] = $r;
		}
		include tpl('check', $module);
	break;
}
?>