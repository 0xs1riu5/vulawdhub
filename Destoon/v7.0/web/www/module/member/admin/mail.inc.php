<?php
defined('DT_ADMIN') or exit('Access Denied');
$TYPE = get_type('mail', 1);
$menus = array (
    array('添加邮件', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('邮件管理', '?moduleid='.$moduleid.'&file='.$file),
    array('订阅列表', '?moduleid='.$moduleid.'&file='.$file.'&action=list'),
    array('订阅分类', 'javascript:Dwidget(\'?file=type&item='.$file.'\', \'订阅分类\');'),
);
switch($action) {
	case 'add':
		if($submit) {
			$typeid or msg('请选择邮件分类');
			$title or msg('请填写邮件标题');
			$content or msg('请填写邮件内容');
			$content = addslashes(save_remote(save_local(stripslashes($content))));
			$db->query("INSERT INTO {$DT_PRE}mail (title,typeid,content,addtime,editor,edittime) VALUES ('$title','$typeid','$content','$DT_TIME','$_username','$DT_TIME')");
			dmsg('添加成功', $forward);
		} else {
			include tpl('mail_add', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		if($submit) {
			$typeid or msg('请选择邮件分类');
			$title or msg('请填写邮件标题');
			$content or msg('请填写邮件内容');
			$content = addslashes(save_remote(save_local(stripslashes($content))));
			$db->query("UPDATE {$DT_PRE}mail SET title='$title',typeid='$typeid',content='$content',editor='$_username',edittime='$DT_TIME' WHERE itemid=$itemid");
			dmsg('修改成功', $forward);
		} else {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}mail WHERE itemid=$itemid");
			$r or msg();
			extract($r);
			include tpl('mail_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg();
		$db->query("DELETE FROM {$DT_PRE}mail WHERE itemid=$itemid ");
		dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file);
	break;
	case 'list_delete':
		$itemid or msg();
		$db->query("DELETE FROM {$DT_PRE}mail_list WHERE itemid=$itemid ");
		dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list');
	break;
	case 'list':
		$sfields = array('按条件', '邮件地址', '会员名');
		$dfields = array('email', 'email', 'username');
		$dstatus = array('处理中', '受理中', '已解决', '未解决');
		$sorder  = array('结果排序方式', '订阅时间降序', '订阅时间升序', '更新时间降序', '更新时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select   = type_select($TYPE, 1, 'typeid', '请选择分类', $typeid);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid > 0) $condition .= " AND typeids LIKE '%,$typeid,%'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}mail_list WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}mail_list WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['edittime'] = timetodate($r['edittime'], 5);
			$typeids = explode(',', substr($r['typeids'], 1, -1));
			$r['type'] = '<select>';
			foreach($typeids as $t) {
				$r['type'] .= '<option'.($t == $typeid ? ' selected' : '').'>'.$TYPE[$t]['typename'].'</option>'; 
			}
			$r['type'] .= '</select>';
			$lists[] = $r;
		}
		include tpl('mail_list', $module);
	break;
	case 'send':
		$itemid or msg();
		if(isset($num)) {
			$m = cache_read($_username.'_mail.php');
		} else {
			$num = 0;
			$m = $db->get_one("SELECT title,content,typeid FROM {$DT_PRE}mail WHERE itemid=$itemid");
			$m or msg();
			cache_write($_username.'_mail.php', $m);
		}
		$pagesize = 2;
		$offset = ($page-1)*$pagesize;
		$result = $db->query("SELECT email FROM {$DT_PRE}mail_list WHERE typeids LIKE '%,".$m['typeid'].",%' ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$i = false;
		while($r = $db->fetch_array($result)) {
			send_mail($r['email'], $m['title'], $m['content']);
			$i = true;
			$num++;
		}
		if($i) {
			$page++;
			msg('已发送 '.$num.' 封邮件，系统将自动继续，请稍候...', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&page='.$page.'&itemid='.$itemid.'&num='.$num);
		} else {
			cache_delete($_username.'_mail.php');
			$db->query("UPDATE {$DT_PRE}mail SET sendtime='$DT_TIME' WHERE itemid=$itemid");
			msg('邮件发送成功 共发送 '.$num.' 封邮件', '?moduleid='.$moduleid.'&file='.$file, 5);
		}
	break;
	default:
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		$type_select = type_select($TYPE, 1, 'typeid', '请选择分类', $typeid);
		$condition = '1';
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		if($typeid > 0) $condition .= " AND typeid=$typeid";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}mail WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$mails = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}mail WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['edittime'] = timetodate($r['edittime'], 5);
			$r['sendtime'] = $r['sendtime'] ? timetodate($r['sendtime'], 5) : '<span style="color:red;">未发送</span>';
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : '<span style="color:red;">未分类</span>';
			$num = $db->get_one("SELECT count(itemid) as num FROM {$DT_PRE}mail_list WHERE typeids LIKE '%,".$r['typeid'].",%' ");
			$r['num'] = $num['num'];
			$mails[] = $r;
		}
		include tpl('mail', $module);
	break;
}
?>