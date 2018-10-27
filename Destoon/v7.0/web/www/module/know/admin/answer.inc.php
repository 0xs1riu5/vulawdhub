<?php
defined('DT_ADMIN') or exit('Access Denied');
$qid = isset($qid) ? intval($qid) : 0;
require DT_ROOT.'/module/'.$module.'/answer.class.php';
$do = new answer();
$menus = array (
    array('答案列表', '?moduleid='.$moduleid.'&file='.$file.'&qid='.$qid),
    array('答案审核', '?moduleid='.$moduleid.'&file='.$file.'&qid='.$qid.'&action=check'),
);
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
if(in_array($action, array('', 'check'))) {
	$sfields = array('内容', '会员名', '昵称', 'IP', '问题ID', '答案ID', '参考资料');
	$dfields = array('content', 'username', 'passport', 'ip', 'qid', 'itemid', 'linkurl');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '投票次数降序', '投票次数升序');
	$dorder  = array('itemid desc', 'addtime DESC', 'addtime ASC', 'vote DESC', 'vote ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	isset($ip) or $ip = '';
	$expert = isset($expert) ? intval($expert) : 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= in_array($dfields[$fields], array('qid', 'itemid', 'ip')) ? " AND $dfields[$fields]='$kw'" : " AND $dfields[$fields] LIKE '%$keyword%'";
	if($qid) $condition .= " AND qid='$qid'";
	if($ip) $condition .= " AND ip='$ip'";
	if($expert) $condition .= " AND expert>0";
}
switch($action) {
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			$content = stripslashes(trim($post['content']));
			if(!$content) msg('请填写答案');
			$content = save_local($content);
			if($MOD['clear_alink']) $content = clear_link($content);
			if($MOD['save_remotepic']) $content = save_remote($content);
			$content = dsafe($content);
			$post['content'] = addslashes($content);
			clear_upload($content, $itemid, $table_answer);
			if($do->pass($post)) {
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$addtime = timetodate($addtime);
			include tpl('answer_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择答案');
		$do->delete($itemid);
		dmsg('删除成功', $this_forward);
	break;
	case 'check':
		if($itemid) {
			$status = $status == 3 ? 3 : 2;
			$do->check($itemid, $status);
			dmsg($status == 3 ? '审核成功' : '取消成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 1;
			include tpl('answer', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 0;
		include tpl('answer', $module);
	break;
}
?>