<?php
defined('DT_ADMIN') or exit('Access Denied');
$TYPE = get_type('form', 1);
require DT_ROOT.'/module/'.$module.'/form.class.php';
$do = new form();
$menus = array (
    array('添加表单', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('表单列表', '?moduleid='.$moduleid.'&file='.$file),
    array('更新地址', '?moduleid='.$moduleid.'&file='.$file.'&action=html'),
    array('表单分类', 'javascript:Dwidget(\'?file=type&item='.$file.'\', \'表单分类\');'),
    array('模块设置', 'javascript:Dwidget(\'?moduleid='.$moduleid.'&file=setting&action='.$file.'\', \'模块设置\');'),
);
if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';
$table = $DT_PRE.'form';
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$addtime = timetodate($DT_TIME);
			$maxanswer = 1;
			$typeid = 0;
			$menuid = 0;
			include tpl('form_edit', $module);
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
			$addtime = timetodate($addtime);
			$fromtime = $fromtime ? timetodate($fromtime, 3) : '';
			$totime = $totime ? timetodate($totime, 3) : '';
			$menuid = 1;
			include tpl('form_edit', $module);
		}
	break;
	case 'html':
		$all = (isset($all) && $all) ? 1 : 0;
		$one = (isset($one) && $one) ? 1 : 0;
		if(!isset($num)) {
			$num = 50;
		}
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$DT_PRE}form");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$DT_PRE}form");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		if($fid <= $tid) {
			$result = $db->query("SELECT itemid,linkurl FROM {$DT_PRE}form WHERE itemid>=$fid ORDER BY itemid LIMIT 0,$num");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$itemid = $r['itemid'];
					$linkurl = $do->linkurl($itemid);
					if($linkurl != $r['linkurl']) $db->query("UPDATE {$DT_PRE}form SET linkurl='$linkurl' WHERE itemid=$itemid");
				}
				$itemid += 1;
			} else {
				$itemid = $fid + $num;
			}
		} else {
			if($all) dheader('?moduleid=3&file=html&action=html&all=1&one='.$one);
			dmsg('更新成功', "?moduleid=$moduleid&file=$file");
		}
		msg('ID从'.$fid.'至'.($itemid-1).'[表单]更新成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num&all=$all&one=$one");
	break;
	case 'delete':
		$itemid or msg('请选择表单');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择表单');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'record':
		$formid = intval($formid);
		$formid or msg();
		$do->itemid = $formid;		
		$P = $do->get_one();
		$P or exit('表单不存在');
		$I = $do->item_all("formid=$formid");
		$condition = "formid=$formid";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($keyword) $condition .= " AND (ip LIKE '%$keyword%' OR username LIKE '%$keyword%')";
		$lists = $do->get_list_record($condition);
		include tpl('form_record', $module);
	break;
	case 'question':
		$fid = isset($fid) ? intval($fid) : 0;
		$fid or msg();
		$do->itemid = $fid;
		$F = $do->get_one();
		$F or msg('表单不存在');
		$menus = array (
			array('添加选项', '?moduleid='.$moduleid.'&file='.$file.'&action=question&fid='.$fid.'&job=add'),
			array('选项管理', '?moduleid='.$moduleid.'&file='.$file.'&action=question&fid='.$fid),
			array('复制选项', '?moduleid='.$moduleid.'&file='.$file.'&action=question&fid='.$fid.'&job=copy'),
			array('回复记录', '?moduleid='.$moduleid.'&file='.$file.'&action=answer&fid='.$fid.'&job=record'),
			array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&action=answer&fid='.$fid.'&job=stats'),
		);
		$TYPE = array('单行文本(text)', '多行文本(textarea)', '列表选择(select)', '复选框(checkbox)', '单选框(radio)');
		if($job == 'add') {
			if($submit) {
				if(!$post['name']) msg('请填写选项名称');
				if($post['type'] > 1) {
					if(!$post['value']) msg('请填写备选值');
					if(strpos($post['value'], '|') === false) msg('最少需要设定2个备选值');
					if($post['type'] != 3 && substr_count($post['value'], '(*)') > 1) msg('只能默认选中一个值');
					if(substr_count($post['value'], '其他') > 1) msg('其他选择值只能有一个');
				}
				if(!preg_match("/^([0-9]{1,})\-([0-9]{1,})$/", $post['required'])) $post['required'] = abs(intval($post['required']));
				$post['fid'] = $fid;
				$sqlk = $sqlv = '';
				foreach($post as $k=>$v) {
					$sqlk .= ','.$k; $sqlv .= ",'$v'";
				}
				$sqlk = substr($sqlk, 1);
				$sqlv = substr($sqlv, 1);
				$db->query("INSERT INTO {$table}_question ($sqlk) VALUES ($sqlv)");
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&fid='.$fid);
			} else {
				$qid = 0;
				$type = 4;
				$required = '1';
				$name = $value = $extend = '';
				$menuid = 0;
				include tpl('form_question_edit', $module);
			}
		} else if($job == 'edit') {
			$qid = isset($qid) ? intval($qid) : 0;
			$qid or msg();
			$Q = $db->get_one("SELECT * FROM {$table}_question WHERE qid=$qid");
			$Q or msg('选项不存在');
			if($submit) {
				if(!$post['name']) msg('请填写选项名称');
				if($post['type'] > 1) {
					if(!$post['value']) msg('请填写备选值');
					if(strpos($post['value'], '|') === false) msg('最少需要设定2个备选值');
					if($post['type'] != 3 && substr_count($post['value'], '(*)') > 1) msg('只能默认选中一个值');
					if(substr_count($post['value'], '其他') > 1) msg('其他选择值只能有一个');
				}
				if(!preg_match("/^([0-9]{1,})\-([0-9]{1,})$/", $post['required'])) $post['required'] = abs(intval($post['required']));
				$sql = '';
				foreach($post as $k=>$v) {
					$sql .= ",$k='$v'";
				}
				$sql = substr($sql, 1);
				$db->query("UPDATE {$table}_question SET $sql WHERE qid=$qid");
				dmsg('修改成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&fid='.$fid);
			} else {
				extract($Q);
				$menuid = 1;
				include tpl('form_question_edit', $module);
			}
		} else if($job == 'delete') {
			$qid = isset($qid) ? intval($qid) : 0;
			$qid or msg();
			$db->query("DELETE FROM {$table}_question WHERE qid=$qid");
			dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&fid='.$fid);
		} else if($job == 'order') {
			is_array($listorder) or msg();
			foreach($listorder as $k=>$v) {
				$k = intval($k);
				$v = intval($v);
				$db->query("UPDATE {$table}_question SET listorder=$v WHERE qid=$k");
			}
			dmsg('排序成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&fid='.$fid);
		} else if($job == 'copy') {
			if($submit) {
				if($type) {
					$ffid = intval($ffid);
					$ffid or msg('请填写表单ID');
					$ffid != $fid or msg('表单ID与当前表单相同');
					$condition = "fid=$ffid";
				} else {
					$fqid = intval($fqid);
					$fqid or msg('请填写选项ID');
					$condition = "qid=$fqid";
				}
				$i = 0;
				$result = $db->query("SELECT * FROM {$table}_question WHERE {$condition}");
				while($r = $db->fetch_array($result)) {
					if($name) {
						$n = daddslashes($r['name']);
						$t = $db->get_one("SELECT * FROM {$table}_question WHERE fid=$fid AND name='$n'");
						if($t) {
							if($type) continue;
							msg('选项名称 ['.$r['name'].'] 已存在');
						}
					}
					unset($r['qid']);
					$r['fid'] = $fid;
					$post = daddslashes($r);
					$sqlk = $sqlv = '';
					foreach($post as $k=>$v) {
						$sqlk .= ','.$k; $sqlv .= ",'$v'";
					}
					$sqlk = substr($sqlk, 1);
					$sqlv = substr($sqlv, 1);
					$db->query("INSERT INTO {$table}_question ($sqlk) VALUES ($sqlv)");
					$i++;
				}
				if($i) dmsg('复制成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&fid='.$fid);
				msg('选项不存在或存在同名');
			} else {
				include tpl('form_question_copy', $module);
			}
		} else {
			$condition = "fid=$fid";
			if($page > 1 && $sum) {
				$items = $sum;
			} else {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table}_question WHERE $condition");
				$items = $r['num'];
			}
			$pages = pages($items, $page, $pagesize);	
			$lists = array();
			$result = $db->query("SELECT * FROM {$table}_question WHERE $condition ORDER BY listorder ASC,qid ASC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$lists[] = $r;
			}
			if($F['question'] != $items) $db->query("UPDATE {$table} SET question=$items WHERE itemid=$fid");
			include tpl('form_question', $module);
		}
	break;
	case 'answer':
		$fid = isset($fid) ? intval($fid) : 0;
		$fid or msg();
		$do->itemid = $fid;
		$F = $do->get_one();
		$F or msg('表单不存在');
		$menus = array (
			array('添加选项', '?moduleid='.$moduleid.'&file='.$file.'&action=question&fid='.$fid.'&job=add'),
			array('选项管理', '?moduleid='.$moduleid.'&file='.$file.'&action=question&fid='.$fid),
			array('复制选项', '?moduleid='.$moduleid.'&file='.$file.'&action=question&fid='.$fid.'&job=copy'),
			array('回复记录', '?moduleid='.$moduleid.'&file='.$file.'&action=answer&fid='.$fid.'&job=record'),
			array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&action=answer&fid='.$fid.'&job=stats'),
		);
		if($job == 'show') {
			$rid = isset($rid) ? intval($rid) : 0;
			$rid or msg();
			$R = $db->get_one("SELECT * FROM {$table}_record WHERE rid=$rid");
			$R or msg('记录不存在');
			$Q = array();
			$result = $db->query("SELECT * FROM {$table}_question WHERE fid=$fid ORDER BY listorder ASC,qid ASC");
			while($r = $db->fetch_array($result)) {
				$Q[$r['qid']] = $r;
			}
			$A = array();
			$result = $db->query("SELECT * FROM {$table}_answer WHERE rid=$rid ORDER BY rid ASC");
			while($r = $db->fetch_array($result)) {
				if($Q[$r['qid']]['type'] == 1) {
					$r['content'] = nl2br($r['content']);
				} else if($Q[$r['qid']]['type'] == 3) {
					$r['content'] = substr($r['content'], 1, -1);
				}
				$A[$r['qid']] = $r;
			}
			include tpl('form_answer_show', $module);
		} else if($job == 'delete') {
			$rid = isset($rid) ? intval($rid) : 0;
			$rid or msg();
			$R = $db->get_one("SELECT * FROM {$table}_record WHERE rid=$rid");
			$R or msg('记录不存在');
			$db->query("DELETE FROM {$table}_record WHERE rid=$rid");
			$db->query("DELETE FROM {$table}_answer WHERE rid=$rid");
			dmsg('删除成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&fid='.$fid);
		} else if($job == 'stats') {
			$F['answer'] > 0 or msg('数据不足，无法生成报表');
			$lists = array();
			$result = $db->query("SELECT * FROM {$table}_question WHERE fid=$fid AND type>1 ORDER BY listorder ASC,qid ASC");
			while($r = $db->fetch_array($result)) {
				$id = $r['qid'];
				$t = array();
				$t['title'] = $r['name'];
				$chart_data = '';
				$o = explode('|', str_replace('(*)', '', $r['value']));
				foreach($o as $k=>$v) {
					if($k) $chart_data .= '\n';
					if($r['type'] == 3) {
						$n = $db->count($table.'_answer', "qid=$id AND content LIKE '%,$v,%'");
					} else {
						$n = $db->count($table.'_answer', "qid=$id AND content='$v'");
					}
					$chart_data .= $v.';'.$n;
				}
				$t['chart_data'] = $chart_data;
				$lists[$id] = $t;
			}
			$lists or msg('数据不足，无法生成报表');
			$qid = isset($qid) ? intval($qid) : 0;
			if($qid && isset($lists[$qid])) {
				$t = $lists[$qid];
				$lists = array();
				$lists[$qid] = $t;
			}
			include tpl('form_answer_stats', $module);
		} else {
			$sfields = array('按条件', '会员', 'IP', '参数');
			$dfields = array('username','username','ip', 'item');
			isset($fields) && isset($dfields[$fields]) or $fields = 0;
			$fields_select = dselect($sfields, 'fields', '', $fields);
			$condition = "fid=$fid";
			if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
			if($page > 1 && $sum) {
				$items = $sum;
			} else {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table}_record WHERE $condition");
				$items = $r['num'];
			}
			$pages = pages($items, $page, $pagesize);
			$lists = array();
			$result = $db->query("SELECT * FROM {$table}_record WHERE $condition ORDER BY rid DESC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$r['adddate'] = timetodate($r['addtime'], 5);
				$lists[] = $r;
			}
			if($F['answer'] != $items) $db->query("UPDATE {$table} SET answer=$items WHERE itemid=$fid");
			include tpl('form_answer', $module);
		}
	break;
	default:
		$sfields = array('按条件', '标题', '内容');
		$dfields = array('title','title','content');
		$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '回复总数降序', '回复总数升序', '浏览次数降序', '浏览次数升序', '选项总数降序', '选项总数升序', '开始时间降序', '开始时间升序', '到期时间降序', '到期时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'answer DESC', 'answer ASC', 'hits DESC', 'hits ASC', 'question DESC', 'question ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		isset($typeid) or $typeid = 0;
		$level = isset($level) ? intval($level) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select = type_select($TYPE, 1, 'typeid', '请选择分类', $typeid);
		$order_select  = dselect($sorder, 'order', '', $order);
		$level_select = level_select('level', '级别', $level);
		$condition = '1';
		if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid) $condition .= " AND typeid IN (".type_child($typeid, $TYPE).")";
		if($level) $condition .= " AND level=$level";
		if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		$lists = $do->get_list($condition, $dorder[$order]);
		include tpl('form', $module);
	break;
}
?>