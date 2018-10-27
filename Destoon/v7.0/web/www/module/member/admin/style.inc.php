<?php
defined('DT_ADMIN') or exit('Access Denied');
$TYPE = get_type('style', 1);
require DT_ROOT.'/module/'.$module.'/style.class.php';
$do = new style();
$menus = array (
    array('安装模板', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('模板列表', '?moduleid='.$moduleid.'&file='.$file),
    array('模板分类', 'javascript:Dwidget(\'?file=type&item='.$file.'\', \'模板分类\');'),
);

switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
			} else {
				msg($do->errmsg);
			}
		} else {
			$addtime = timetodate($DT_TIME);
			include tpl('style_add', $module);
		}
	break;
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
			$groupid = substr($groupid, 1, -1);
			$addtime = timetodate($addtime);
			include tpl('style_edit', $module);
		}
	break;
	case 'show':
		$itemid or msg();
		$u = $db->get_one("SELECT c.username FROM {$DT_PRE}company c,{$DT_PRE}member m WHERE c.userid=m.userid AND c.vip>0 AND m.edittime>0 ORDER BY m.logintimes DESC");
		if($u) dheader(DT_PATH.'index.php?homepage='.$u['username'].'&preview='.$itemid);
		msg('暂无符合条件的会员');
	break;
	case 'order':
		$do->order($listorder);
		dmsg('更新成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择模板');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '模板名称', '风格目录', '模板目录', '作者');
		$dfields = array('title', 'title', 'skin', 'template', 'author');
		$sorder  = array('结果排序方式', '模板价格降序', '模板价格升序', $DT['money_name'].'收益降序', $DT['money_name'].'收益升序', $DT['credit_name'].'收益降序', $DT['credit_name'].'收益升序', '使用人数降序', '使用人数升序', '添加时间降序', '添加时间升序');
		$dorder  = array('listorder DESC,addtime DESC', 'fee DESC', 'fee ASC', 'money DESC', 'money ASC', 'credit DESC', 'credit ASC', 'hits DESC', 'hits ASC', 'addtime DESC', 'addtime ASC');
	
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$groupid = isset($groupid) ? intval($groupid) : 0;
		$typeid = isset($typeid) ? intval($typeid) : 0;
		isset($currency) or $currency = '';
		isset($minfee) or $minfee = '';
		isset($maxfee) or $maxfee = '';
	
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$type_select = type_select($TYPE, 1, 'typeid', '请选择分类', $typeid);
	
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($groupid) $condition .= " AND groupid LIKE '%,$groupid,%'";
		if($typeid) $condition .= " AND typeid=$typeid";
		if($currency) $condition .= $currency == 'free' ? " AND fee=0" : " AND currency='$currency'";
		if($minfee) $condition .= " AND fee>=$minfee";
		if($maxfee) $condition .= " AND fee<=$maxfee";
		$lists = $do->get_list($condition, $dorder[$order]);
		include tpl('style', $module);
	break;
}
?>