<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
define('MANAGE_ADMIN', true);
$AREA or $AREA = cache_read('area.php');
require DT_ROOT.'/admin/admin.class.php';
$do = new admin;
$menus = array (
    array('添加管理员', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('管理员列表', '?moduleid='.$moduleid.'&file='.$file),
);
$this_forward = '?file='.$file;
switch($action) {
	case 'add':
		if($submit) {
			$admin = $admin == 1 ? 1 : 2;
			if($do->set_admin($username, $admin, $role, $aid)) {
				$r = $do->get_one($username);
				$userid = $r['userid'];
				if($r['admin'] == 2) {
					foreach($MODULE as $m) {
						if(isset($roles[$m['moduleid']])) {
							$right = array();
							$right['title'] = $m['name'].'管理';
							$right['url'] = '?moduleid='.$m['moduleid'];
							$do->add($userid, $right, $admin);
						}
					}
					if(isset($roles['database'])) {
						$right = array();
						$right['title'] = '数据库管理';
						$right['url'] = '?file=database';
						$do->add($userid, $right, $admin);
					}
					if(isset($roles['template'])) {
						$right = array();
						$right['title'] = '模板管理';
						$right['url'] = '?file=template';
						$do->add($userid, $right, $admin);
						$right = array();
						$right['title'] = '风格管理';
						$right['url'] = '?file=skin';
						$do->add($userid, $right, $admin);
						$right = array();
						$right['title'] = '标签向导';
						$right['url'] = '?file=tag';
						$do->add($userid, $right, $admin);
					}
					$do->cache_right($userid);
					$do->cache_menu($userid);
				}
				msg('管理员添加成功，下一步请分配权限和管理面板', '?file='.$file.'&id='.$userid.'&tm='.($DT_TIME+5));
			}
			msg($do->errmsg);
		} else {
			isset($username) or $username = '';
			include tpl('admin_add');
		}
	break;
	case 'edit':
		if($submit) {
			$admin = $admin == 1 ? 1 : 2;
			if($do->set_admin($username, $admin, $role, $aid)) {
				$r = $do->get_one($username);
				$userid = $r['userid'];
				if($r['admin'] == 2) {
					$do->cache_right($userid);
					$do->cache_menu($userid);
				}
				dmsg('修改成功', '?file='.$file);
			}
			msg($do->errmsg);
		} else {
			if(!$userid) msg();
			$user = $do->get_one($userid, 0);
			include tpl('admin_edit');
		}
	break;
	case 'delete':
		if($do->delete_admin($username)) dmsg('撤销成功', $this_forward);
		msg($do->errmsg);
	break;
	case 'right':
		if(!$userid) msg();
		$user = $do->get_one($userid, 0);
		if($submit) {
			$right[0]['action'] = $right[0]['action'] ? implode('|', $right[0]['action']) : '';
			$right[0]['catid'] = $right[0]['catid'] ? implode('|', $right[0]['catid']) : '';
			if($do->update($userid, $right, $user['admin'])) {
				dmsg('更新成功', '?file='.$file.'&action=right&userid='.$userid);
			}
			msg($do->errmsg);
		} else {
			$username = $user['username'];
			$drights = $do->get_right($userid);
			$dmenus = $do->get_menu($userid);
			include tpl('admin_right');
		}
	break;
	case 'ajax':
		@include DT_ROOT.'/'.($mid == 1 ? 'admin' : 'module/'.$MODULE[$mid]['module'].'/admin').'/config.inc.php';
		if(isset($fi)) {
			if(isset($RT) && isset($RT['action'][$fi])) {
				$action_select = '<select name="right[0][action][]" size="2" multiple  style="height:200px;width:150px;"><option value="">选择动作[按Ctrl键多选]</option>';
				foreach($RT['action'][$fi] as $k=>$v) {
					$action_select .= '<option value="'.$k.'">'.$v.'['.$k.']</option>';
				}
				$action_select .= '</select>';
				echo $action_select;
			} else {
				echo '0';
			}
		} else {
			if(isset($RT)) {
				$file_select = '<select name="right[0][file]" size="2" style="height:200px;width:150px;" onchange="get_action(this.value, '.$mid.');"><option value="">选择文件[单选]</option>';
				foreach($RT['file'] as $k=>$v) {
					$file_select .= '<option value="'.$k.'">'.$v.'['.$k.']</option>';
				}
				$file_select .= '</select>';
				echo $file_select.'|';
				if($CT) {
					$CATEGORY = cache_read('category-'.$mid.'.php');
					echo '<select name="right[0][catid][]" size="2" multiple style="height:200px;width:300px;">';
					echo '<option>选择分类多选[按Ctrl键多选]</option>';
					foreach($CATEGORY as $c) {
						if($c['parentid'] == 0) echo '<option value="'.$c['catid'].'">'.$c['catname'].'</option>';
					}
					echo '</select>';
				} else {
					echo '0';
				}
			} else {
				echo '0|0';
			}
		}
	break;
	default:
		$sfields = array('按条件', '用户名', '姓名', '角色');
		$dfields = array('username', 'username', 'truename', 'role');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$type = isset($type) ? intval($type) : 0;
		$areaid = isset($areaid) ? intval($areaid) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = 'groupid=1 AND admin>0';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($type) $condition .= " AND admin=$type";
		if($areaid) $condition .= ($AREA[$areaid]['child']) ? " AND aid IN (".$AREA[$areaid]['arrchildid'].")" : " AND aid=$areaid";
		$lists = $do->get_list($condition);
		include tpl('admin');
	break;
}
?>