<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('地区添加', '?file='.$file.'&action=add'),
    array('地区管理', '?file='.$file),
	array('导入省市', '?file='.$file.'&action=import','onclick="return confirm(\'确定导入中国省市数据吗？ 当前数据将被覆盖 \');"'),
    array('更新缓存', '?file='.$file.'&action=cache'),

);
$AREA = cache_read('area.php');
$areaid = isset($areaid) ? intval($areaid) : 0;
$do = new area($areaid);

$parentid = isset($parentid) ? intval($parentid) : 0;
$table = $DT_PRE.'area';
$this_forward = '?file='.$file.'&parentid='.$parentid;

switch($action) {
	case 'add':
		if($submit) {
			if(!$area['areaname']) msg('地区名不能为空');
			$area['areaname'] = trim($area['areaname']);
			if(strpos($area['areaname'], "\n") === false) {
				$do->add($area);
			} else {
				$areanames = explode("\n", $area['areaname']);
				foreach($areanames as $areaname) {
					$areaname = trim($areaname);
					if(!$areaname) continue;
					$area['areaname'] = $areaname;
					$do->add($area);
				}
			}
			$do->repair();
			dmsg('添加成功', $this_forward);
		} else {
			include tpl('area_add');
		}
	break;
	case 'import':
		$file = DT_ROOT.'/file/setting/area.sql';
		is_file($file) or msg('数据文件不存在，请上传程序包内 file/setting/area.sql 文件至 file/setting 目录');
		require DT_ROOT.'/include/sql.func.php';
		sql_execute(file_get($file));
		cache_area();
		dmsg('导入成功', $this_forward);
	break;
	case 'cache':
		$do->repair();
		dmsg('更新成功', $forward);
	break;
	case 'delete':
		if($areaid) $areaids = $areaid;
		$areaids or msg();
		$do->delete($areaids);
		dmsg('删除成功', $this_forward);
	break;
	case 'update':
		if(!$area || !is_array($area)) msg();
		$do->update($area);
		dmsg('更新成功', $this_forward);
	break;
	default:
		$DAREA = array();
		$condition = $keyword ? "areaname LIKE '%$keyword%'" : "parentid=$parentid";
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY listorder,areaid");
		while($r = $db->fetch_array($result)) {
			$r['childs'] = substr_count($r['arrchildid'], ',');
			$DAREA[$r['areaid']] = $r;
		}
		include tpl('area');
	break;
}

class area {
	var $areaid;
	var $area = array();
	var $table;

	function __construct($areaid = 0)	{
		global $AREA;
		$this->areaid = $areaid;
		$this->area = $AREA;
		$this->table = DT_PRE.'area';
	}

	function area($areaid = 0)	{
		$this->__construct($areaid);
	}

	function add($area)	{
		if(!is_array($area)) return false;
		$sql1 = $sql2 = $s = '';
		foreach($area as $key=>$value) {
			$sql1 .= $s.$key;
			$sql2 .= $s."'".$value."'";
			$s = ',';
		}
		DB::query("INSERT INTO {$this->table} ($sql1) VALUES($sql2)");		
		$this->areaid = DB::insert_id();
		if($area['parentid']) {
			$area['areaid'] = $this->areaid;
			$this->area[$this->areaid] = $area;
			$arrparentid = $this->get_arrparentid($this->areaid, $this->area);
		} else {
			$arrparentid = 0;
		}
		DB::query("UPDATE {$this->table} SET arrchildid='$this->areaid',listorder=$this->areaid,arrparentid='$arrparentid' WHERE areaid=$this->areaid");
		return true;
	}

	function delete($areaids) {
		if(is_array($areaids)) {
			foreach($areaids as $areaid) {
				if(isset($this->area[$areaid])) {
					$arrchildid = $this->area[$areaid]['arrchildid'];
					DB::query("DELETE FROM {$this->table} WHERE areaid IN ($arrchildid)");
				}
			}
		} else {
			$areaid = $areaids;
			if(isset($this->area[$areaid])) {
				$arrchildid = $this->area[$areaid]['arrchildid'];
				DB::query("DELETE FROM {$this->table} WHERE areaid IN ($arrchildid)");
			}
		}
		$this->repair();
		return true;
	}

	function update($area) {
	    if(!is_array($area)) return false;
		foreach($area as $k=>$v) {
			if(!$v['areaname']) continue;
			$v['parentid'] = intval($v['parentid']);
			if($k == $v['parentid']) continue;
			if($v['parentid'] > 0 && !isset($this->area[$v['parentid']])) continue;
			$v['listorder'] = intval($v['listorder']);
			DB::query("UPDATE {$this->table} SET areaname='$v[areaname]',parentid='$v[parentid]',listorder='$v[listorder]' WHERE areaid=$k");
		}
		cache_area();
		return true;
	}

	function repair() {		
		$query = DB::query("SELECT * FROM {$this->table} ORDER BY listorder,areaid");
		$AREA = array();
		while($r = DB::fetch_array($query)) {
			$AREA[$r['areaid']] = $r;
		}
		$childs = array();
		foreach($AREA as $areaid => $area) {
			$arrparentid = $this->get_arrparentid($areaid, $AREA);
			DB::query("UPDATE {$this->table} SET arrparentid='$arrparentid' WHERE areaid=$areaid");
			if($arrparentid) {
				$arr = explode(',', $arrparentid);
				foreach($arr as $a) {
					if($a == 0) continue;
					isset($childs[$a]) or $childs[$a] = '';
					$childs[$a] .= ','.$areaid;
				}
			}
		}
		foreach($AREA as $areaid => $area) {
			if(isset($childs[$areaid])) {
				$arrchildid = $areaid.$childs[$areaid];
				DB::query("UPDATE {$this->table} SET arrchildid='$arrchildid',child=1 WHERE areaid='$areaid'");
			} else {
				DB::query("UPDATE {$this->table} SET arrchildid='$areaid',child=0 WHERE areaid='$areaid'");
			}
		}
		cache_area();
        return true;
	}

	function get_arrparentid($areaid, $AREA) {
		if($AREA[$areaid]['parentid'] && $AREA[$areaid]['parentid'] != $areaid) {
			$parents = array();
			$aid = $areaid;
			while($areaid) {
				if($AREA[$aid]['parentid']) {
					$parents[] = $aid = $AREA[$aid]['parentid'];
				} else {
					break;
				}
			}
			$parents[] = 0;
			return implode(',', array_reverse($parents));
		} else {
			return '0';
		}
	}

	function get_arrchildid($areaid, $AREA) {
		$arrchildid = '';
		foreach($AREA as $area) {
			if(strpos(','.$area['arrparentid'].',', ','.$areaid.',') !== false) $arrchildid .= ','.$area['areaid'];
		}
		return $arrchildid ? $areaid.$arrchildid : $areaid;
	}
}
?>