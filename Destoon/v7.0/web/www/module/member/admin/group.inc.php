<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('会员组添加', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('会员组管理', '?moduleid='.$moduleid.'&file='.$file),
);
$do = new group;
if(isset($groupid)) $do->groupid = $groupid;
if(isset($groupname)) $do->groupname = $groupname;
if(isset($vip)) $do->vip = intval($vip);
$this_forward = '?moduleid='.$moduleid.'&file='.$file;

if($action == 'add') {
	if($submit) {
		if(!$groupname) msg('会员组名称不能为空');
		if($setting['fee_mode']) {//收费会员
			if($vip > 9) $do->vip = $vip = 9;
			if($vip < 1) $do->vip = $vip = 1;
			$setting['fee'] = intval($setting['fee']);
			if($setting['fee'] < 1) $setting['fee'] = 3000;
		} else {
			$do->vip = $vip = $setting['fee'] = 0;
		}
		$do->add($setting);
		dmsg('添加成功', $this_forward);
	} else {
		include load('homepage.lang');
		$do->groupid = 7;
		extract($do->get_one());
		$groupname = '';
		$discount = 100;
		$menuid = 0;
		include tpl('group_edit', $module);
	}
} else if($action == 'edit') {
	$groupid or msg();
	if($submit) {
		if(!$groupname) msg('会员组名称不能为空');
		if($setting['fee_mode']) {//收费会员
			if($vip > 9) $do->vip = $vip = 9;
			if($vip < 1) $do->vip = $vip = 1;
			$setting['fee'] = intval($setting['fee']);
			if($setting['fee'] < 1) $setting['fee'] = 3000;
			$setting['reg'] = 0;
		} else {
			$do->vip = $vip = $setting['fee'] = 0;
		}
		if($groupid == 6) $setting['reg'] = 1;
		$do->listorder = intval($listorder);
		$do->edit($setting);
		dmsg('修改成功', '?moduleid='.$moduleid.'&file='.$file.'&action=edit&groupid='.$groupid);
	} else {
		include load('homepage.lang');
		extract($do->get_one());
		$menuid = 1;
		if($kw) {
			$all = 1;
			ob_start();
		}
		include tpl('group_edit', $module);
		if($kw) {
			$data = $content = ob_get_contents();
			ob_clean();
			$data = preg_replace('\'(?!((<.*?)|(<a.*?)|(<strong.*?)))('.$kw.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</strong>))\'si', '<span class=highlight>'.$kw.'</span>', $data);
			$data = preg_replace('/<span class=highlight>/', '<a name=high></a><span class=highlight>', $data, 1);
			echo $data ? $data : $content;
		}
	}
} else if($action == 'delete') {
	$groupid or msg();
	$do->delete();
	dmsg('删除成功', $this_forward);
} else if($action == 'order') {	
	$do->order($listorder);
	dmsg('排序成功', $forward);
} else {
	$groups = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}member_group ORDER BY listorder ASC,groupid ASC");
	while($r = $db->fetch_array($result)) {
		$r['type'] = $r['groupid'] > 7 ? '自定义' : '系统';
		$groups[]=$r;
	}
	include tpl('group', $module);
}

class group {
	var $groupid;
	var $groupname;
	var $vip;
	var $listorder;
	var $table;

	function __construct() {
		$this->table = DT_PRE.'member_group';
	}

	function group() {
		$this->__construct();
	}

	function add($setting) {
		if(!is_array($setting)) return false;
		DB::query("INSERT INTO {$this->table} (groupname,vip) VALUES('$this->groupname','$this->vip')");
		$this->groupid = DB::insert_id();
		DB::query("UPDATE {$this->table} SET `listorder`=`groupid` WHERE groupid=$this->groupid");
		update_setting('group-'.$this->groupid, $setting);
		cache_group();
		return $this->groupid;
	}

	function edit($setting) {
		if(!is_array($setting)) return false;
		update_setting('group-'.$this->groupid, $setting);
		$setting = addslashes(serialize(dstripslashes($setting)));
		DB::query("UPDATE {$this->table} SET groupname='$this->groupname',vip='$this->vip',listorder='$this->listorder' WHERE groupid=$this->groupid");
		cache_group();
		return true;
	}

	function order($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			if($v > 6) DB::query("UPDATE {$this->table} SET listorder=$v WHERE groupid=$k");
		}
		cache_group();
		return true;
	}

	function delete() {
		if($this->groupid < 5) return false;
		DB::query("DELETE FROM {$this->table} WHERE groupid=$this->groupid");
		cache_delete('group-'.$this->groupid.'.php');
		cache_group();
		return true;
	}

	function get_one() {
		$r = DB::get_one("SELECT * FROM {$this->table} WHERE groupid=$this->groupid");
		$tmp = get_setting('group-'.$this->groupid);
		if($tmp) {
			foreach($tmp as $k=>$v) {
				isset($r[$k]) or $r[$k] = $v;
			}
		}
		return $r;
	}
}
?>