<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/favorite.class.php';
$do = new favorite();
$menus = array (
    array('收藏列表', '?moduleid='.$moduleid.'&file='.$file),
);

switch($action) {
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			include tpl('favorite_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择收藏');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '标题', 'URL');
		$dfields = array('title', 'title', 'url');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$userid = isset($userid) ? intval($userid) : '';
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($userid) $condition .= " AND userid=$userid";
		$lists = $do->get_list($condition);
		include tpl('favorite', $module);
	break;
}
?>