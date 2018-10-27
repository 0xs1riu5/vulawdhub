<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/guestbook.class.php';
$do = new guestbook();
$menus = array (
    array('留言列表', '?moduleid='.$moduleid.'&file='.$file),
    array('模块设置', 'javascript:Dwidget(\'?moduleid='.$moduleid.'&file=setting&action='.$file.'\', \'模块设置\');'),
);
if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';
if(in_array($action, array('', 'check'))) {
	$sfields = array('按条件', '留言标题', '会员名', '联系人', '联系电话', '电子邮件', 'QQ', '微信', '阿里旺旺', 'Skype', '留言IP', '留言内容', '回复内容');
	$dfields = array('title','title','username','truename','telephone','email','qq','wx','ali','skype','ip','content','reply');
	$sorder  = array('结果排序方式', '留言时间降序', '留言时间升序', '回复时间降序', '回复时间升序');
	$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '1';
	if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
}
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
			$addtime = timetodate($addtime);
			include tpl('guestbook_edit', $module);
		}
	break;
	case 'check':
		$itemid or msg('请选择留言');
		$do->check($itemid, $status);
		dmsg('设置成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择留言');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$lists = $do->get_list($condition, $dorder[$order]);
		include tpl('guestbook', $module);
	break;
}
?>