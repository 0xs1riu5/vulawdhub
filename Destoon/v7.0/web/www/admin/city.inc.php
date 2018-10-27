<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('分站添加', '?file='.$file.'&action=edit'),
    array('分站管理', '?file='.$file),
    array('批量索引', '?file='.$file.'&action=letter'),
);
$AREA = cache_read('area.php');
$areaid = isset($areaid) ? intval($areaid) : 0;
$do = new city($areaid);
switch($action) {
	case 'edit':
		if($submit) {
			if(!$post['areaid']) msg('请选择所在地区');
			if(!$post['name']) msg('分站名不能为空');
			$post['name'] = trim($post['name']);
			$post['domain'] = fix_domain($post['domain']);
			$do->edit($post);
			dmsg('更新成功', $forward);
		} else {
			if($areaid) {
				extract($do->get_one());
			} else {
				$areaid = $listorder = 0;
				$name = $style = $letter = $domain = $iparea = $template = $seo_title = $seo_keywords = $seo_description = '';
			}
			include tpl('city_edit');
		}
	break;
	case 'letter':
		$result = $db->query("SELECT * FROM {$DT_PRE}city WHERE letter=''");
		while($r = $db->fetch_array($result)) {
			$letter = $do->letter($r['name']);
			$db->query("UPDATE {$DT_PRE}city SET letter='$letter' WHERE areaid=$r[areaid]");
		}
		dmsg('更新成功', $forward);
	break;
	case 'delete':
		if($areaid) $areaids = $areaid;
		$areaids or msg();
		$do->delete($areaids);
		dmsg('删除成功', '?file='.$file);
	break;
	case 'update':
		foreach($post as $v) {
			$do->update($v);
		}
		dmsg('更新成功', '?file='.$file);
	break;
	default:
		$condition = '1';
		if($kw) $condition .= " AND (name LIKE '%$keyword%' OR domain LIKE '%$keyword%')";
		$lists = $do->get_list($condition);
		include tpl('city');
	break;
}

class city {
	var $areaid;
	var $table;

	function __construct($areaid = 0)	{
		global $city;
		$this->table = DT_PRE.'city';
		$this->areaid = $areaid;
	}

	function city($areaid = 0)	{
		$this->__construct($areaid);
	}

	function edit($post) {
		if(!is_array($post)) return false;
		$post['letter'] or $post['letter'] = $this->letter($post['name']);
		$sql1 = $sql2 = $s = '';
		foreach($post as $k=>$v) {
			$sql1 .= $s.$k;
			$sql2 .= $s."'".$v."'";
			$s = ',';
		}
		DB::query("REPLACE INTO {$this->table} ($sql1) VALUES ($sql2)");		
		return true;
	}

	function update($post) {
		if(!is_array($post)) return false;
		$areaid = $post['areaid'];
		if(!$areaid) return false;
		$post['letter'] or $post['letter'] = $this->letter($post['name']);
		$post['name'] = trim($post['name']);
		$post['domain'] = fix_domain($post['domain']);
		$sql = '';
		foreach($post as $k=>$v) {
			$sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table} SET $sql WHERE areaid=$areaid");	
		return true;
	}
	
	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE areaid=$this->areaid");
	}

	function get_list($condition) {
		global $pages, $page, $pagesize, $offset, $pagesize, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY letter,listorder LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['linkurl'] = DT_PATH.'api/city.php?action=go&forward=&areaid='.$r['areaid'];
			$lists[] = $r;
		}
		return $lists;
	}

	function delete($areaids) {
		$areaids = is_array($areaids) ? implode(',', $areaids) : $areaids;
		DB::query("DELETE FROM {$this->table} WHERE areaid IN ($areaids)");
		return true;
	}

	function letter($name) {
		return strtolower(substr(gb2py($name), 0, 1));
	}
}
?>