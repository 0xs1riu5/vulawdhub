<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/promo.class.php';
$do = new promo();
$menus = array (
    array('新增优惠', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('优惠促销', '?moduleid='.$moduleid.'&file='.$file),
    array('领券记录', '?moduleid='.$moduleid.'&file='.$file.'&action=coupon'),
);

switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功',  $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				$$v = '';
			}
			$fromtime = timetodate($DT_TIME, 3).' 00:00:00';
			$menuid = 0;
			include tpl('promo_edit', $module);
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
			$fromtime = $fromtime ? timetodate($fromtime, 6) : '';
			$totime = $totime ? timetodate($totime, 6) : '';
			$menuid = 1;
			include tpl('promo_edit', $module);
		}
	break;
	case 'give':
		$itemid or msg();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if($submit) {
			check_name($username) or msg('请填写会员名');
			$t = $db->get_one("SELECT itemid FROM {$DT_PRE}finance_coupon WHERE username='$_username' AND pid=$itemid");
			if($t) msg('会员已领取过该券');
			$user = userinfo($username);
			$user or msg('会员不存在');
			if($r['username'] == $username) msg('不能赠送商家自己的优惠券');
			if($r['totime'] < $DT_TIME) msg('优惠活动已结束');
			$title = addslashes($r['title']);
			$db->query("INSERT INTO {$DT_PRE}finance_coupon (title,username,seller,addtime,fromtime,totime,price,cost,pid,editor,edittime,note) VALUES ('$title','$username','$r[username]','$DT_TIME','$r[fromtime]','$r[totime]','$r[price]','$r[cost]','$itemid','$_username','$DT_TIME','$note')");
			$db->query("UPDATE {$DT_PRE}finance_promo SET number=number+1 WHERE itemid=$itemid");
			dmsg('赠送成功', '?moduleid='.$moduleid.'&file='.$file.'&action=coupon');
		} else {
			extract($r);
			$fromtime = $fromtime ? timetodate($fromtime, 6) : '';
			$totime = $totime ? timetodate($totime, 6) : '';
			$menuid = 1;
			include tpl('promo_give', $module);
		}
	break;
	case 'order':
		$itemid or msg();
		$r = $db->get_one("SELECT mid FROM {$DT_PRE}order WHERE itemid=$itemid");
		if($r) dheader('?moduleid='.$r['mid'].'&file=order&action=show&itemid='.$itemid);
		msg('订单不存在');
	break;
	case 'del':
		$itemid or msg('请选择优惠券');
		$do->del($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择优惠');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'coupon':
		$sfields = array('按条件', '优惠名称', '会员', '商家', '编辑', '备注');
		$dfields = array('title', 'title', 'username', 'seller', 'editor', 'note');
		$sorder  = array('排序方式', '添加时间降序', '添加时间升序', '优惠额度降序', '优惠额度升序', '最低消费降序', '最低消费升序',  '更新时间降序', '更新时间升序', '开始时间降序', '开始时间升序', '结束时间降序', '结束时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'price DESC', 'price ASC', 'cost DESC', 'cost ASC', 'edittime DESC', 'edittime ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;		
		(isset($username) && check_name($username)) or $username = '';
		(isset($seller) && check_name($seller)) or $seller = '';
		$pid = isset($pid) ? intval($pid) : '';
		$oid = isset($oid) ? intval($oid) : '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($timetype) or $timetype = 'addtime';
		isset($mtype) or $mtype = 'price';
		isset($minamount) or $minamount = '';
		isset($maxamount) or $maxamount = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($username) $condition .= " AND username='$username'";
		if($seller) $condition .= " AND seller='$seller'";
		if($pid) $condition .= " AND pid=$pid";
		if($oid) $condition .= " AND oid=$oid";
		if($minamount != '') $condition .= " AND $mtype>=$minamount";
		if($maxamount != '') $condition .= " AND $mtype<=$maxamount";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_coupon WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_coupon WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
		include tpl('promo_coupon', $module);
	break;
	default:
		$sfields = array('按条件', '优惠名称', '卖家', '编辑', '备注');
		$dfields = array('title', 'title', 'username', 'editor', 'note');
		$sorder  = array('排序方式', '添加时间降序', '添加时间升序', '优惠额度降序', '优惠额度升序', '最低消费降序', '最低消费升序', '数量限制降序', '数量限制升序', '领券人数降序', '领券人数升序', '更新时间降序', '更新时间升序', '开始时间降序', '开始时间升序', '结束时间降序', '结束时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'price DESC', 'price ASC', 'cost DESC', 'cost ASC', 'amount DESC', 'amount ASC', 'number DESC', 'number ASC', 'edittime DESC', 'edittime ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;		
		(isset($username) && check_name($username)) or $username = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$open = isset($open) ? intval($open) : -1;
		isset($timetype) or $timetype = 'addtime';
		isset($mtype) or $mtype = 'price';
		isset($minamount) or $minamount = '';
		isset($maxamount) or $maxamount = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($username) $condition .= " AND username='$username'";
		if($minamount != '') $condition .= " AND $mtype>=$minamount";
		if($maxamount != '') $condition .= " AND $mtype<=$maxamount";
		if($open > -1) $condition .= " AND open=$open";
		$lists = $do->get_list($condition, 'itemid DESC');
		include tpl('promo', $module);
	break;
}
?>