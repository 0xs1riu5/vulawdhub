<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$mid or $mid = 4;
$CATEGORY = cache_read('category-'.$mid.'.php');
$MOD = cache_read('module-'.$mid.'.php');
$NUM = count($CATEGORY);
$catid = isset($catid) ? intval($catid) : 0;
$do = new category($mid, $catid);
$parentid = isset($parentid) ? intval($parentid) : 0;
$table = $DT_PRE.'category';
$menus = array (
    array('添加分类', '?file='.$file.'&action=add&mid='.$mid.'&parentid='.$parentid),
    array('管理分类', '?file='.$file.'&mid='.$mid),
    array('分类复制', '?file='.$file.'&action=copy&mid='.$mid),
    array('更新缓存', '?file='.$file.'&action=caches&mid='.$mid),
);
if(strpos($forward, 'category') === false) $forward = '?file='.$file.'&mid='.$mid.'&parentid='.$parentid.'&kw='.urlencode($kw);
switch($action) {
	case 'add':
		if($submit) {
			if(!$category['catname']) msg('分类名不能为空');
			$category['catname'] = trim($category['catname']);
			$childs = '';
			$catids = array();
			if(strpos($category['catname'], "\n") === false) {
				$category['catdir'] = $do->get_catdir($category['catdir']);
				$do->add($category);
				$childs .= ','.$do->catid;
				$catids[] = $do->catid;
			} else {
				$catnames = explode("\n", $category['catname']);
				foreach($catnames as $catname) {
					$catname = trim($catname);
					if(!$catname) continue;
					$category['catname'] = $catname;
					$category['catdir'] = '';
					$category['letter'] = '';
					$category['seo_title'] = '';
					$category['seo_keywords'] = '';
					$category['seo_description'] = '';
					$do->add($category);
					$childs .= ','.$do->catid;
					$catids[] = $do->catid;
				}
			}
			if($category['parentid']) {
				$parents = array();
				$cid = $category['parentid'];
				$parents[] = $cid;
				while(1) {
					if($CATEGORY[$cid]['parentid']) {
						$parents[] = $cid = $CATEGORY[$cid]['parentid'];
					} else {
						break;
					}
				}
				foreach($parents as $catid) {
					$arrchildid = $CATEGORY[$catid]['child'] ? $CATEGORY[$catid]['arrchildid'].$childs : $catid.$childs;
					$db->query("UPDATE {$table} SET child=1,arrchildid='$arrchildid' WHERE catid=$catid");
				}
			}
			foreach($catids as $catid) {
				$CATEGORY[$catid] = $db->get_one("SELECT * FROM {$table} WHERE catid=$catid");
				update_category($CATEGORY[$catid]);
			}
			$NUM > 500 ? $do->cache() : $do->repair();
			dmsg('添加成功', '?file='.$file.'&mid='.$mid.'&parentid='.$category['parentid']);
		} else {
			include tpl('category_add');
		}
	break;
	case 'edit':
		$catid or msg();
		if($submit) {
			if(!$category['catname']) msg('分类名不能为空');
			if($category['parentid'] == $catid) msg('上级分类不能与当前分类相同');
			$do->edit($category);
			$category['catid'] = $catid;
			update_category($category);
			$NUM > 500 ? $do->cache() : $do->repair();
			dmsg('修改成功', '?file='.$file.'&mid='.$mid.'&parentid='.$category['parentid']);
		} else {
			extract($db->get_one("SELECT * FROM {$table} WHERE catid=$catid"));
			include tpl('category_edit');
		}
	break;
	case 'copy':
		if($submit) {
			if(!$fromid) msg('源模块ID不能为空');
			if(!$save) $db->query("DELETE FROM {$table} WHERE moduleid=$mid");
			$result = $db->query("SELECT * FROM {$table} WHERE moduleid=$fromid ORDER BY catid");
			$O = $R = array();
			while($r = $db->fetch_array($result)) {
				$O[$r['catid']] = $r['catname'];
				$sqlk = $sqlv = '';
				$catid = $r['catid'];
				unset($r['catid']);
				$r['moduleid'] = $mid;
				$r['item'] = $r['property'] = 0;
				$r = daddslashes($r);
				foreach($r as $k=>$v) {
					$sqlk .= ','.$k; $sqlv .= ",'$v'";
				}
				$sqlk = substr($sqlk, 1);
				$sqlv = substr($sqlv, 1);
				$db->query("INSERT INTO {$table} ($sqlk) VALUES ($sqlv)");
				$R[$catid] = $db->insert_id();
			}
			$result = $db->query("SELECT * FROM {$table} WHERE moduleid='$mid' ORDER BY catid");
			while($r = $db->fetch_array($result)) {
				$catid = $r['catid'];
				$v = $r['parentid'];
				$parentid = isset($R[$v]) ? $R[$v] : $v;
				$arrparentid = explode(',', $r['arrparentid']);
				foreach($arrparentid as $k=>$v) {
					if(isset($R[$v])) $arrparentid[$k] = $R[$v];
				}
				$arrparentid = implode(',', $arrparentid);
				$arrchildid = explode(',', $r['arrchildid']);
				foreach($arrchildid as $k=>$v) {
					if(isset($R[$v])) $arrchildid[$k] = $R[$v];
				}
				$arrchildid = implode(',', $arrchildid);
				$db->query("UPDATE {$table} SET parentid='$parentid',arrparentid='$arrparentid',arrchildid='$arrchildid' WHERE catid=$catid");
			}
			$do->repair();
			msg('分类复制成功', '?file='.$file.'&action=url&&mid='.$mid.'&forward='.urlencode('?file='.$file.'&mid='.$mid));
		} else {
			include tpl('category_copy');
		}
	break;
	case 'caches':
		msg('开始更新统计', "?file=$file&mid=$mid&action=count");
	break;
	case 'count':
		require DT_ROOT.'/include/module.func.php';
		$tb = get_table($mid);
		if($MODULE[$mid]['module'] == 'club') $tb = $DT_PRE.'club_group_'.$mid;
		if(!isset($num)) {
			$num = 50;
		}
		if(!isset($fid)) {
			$r = $db->get_one("SELECT MIN(catid) AS fid FROM {$table} WHERE moduleid=$mid");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT MAX(catid) AS tid FROM {$table} WHERE moduleid=$mid");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		if($fid <= $tid) {
			$result = $db->query("SELECT catid FROM {$table} WHERE moduleid=$mid AND catid>=$fid ORDER BY catid LIMIT 0,$num");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$catid = $r['catid'];					
					if($mid == 4) {
						$condition = "groupid>5 and catids like '%,".$catid.",%'";
					} else {
						$condition = 'status=3';
						$condition .= $CATEGORY[$catid]['child'] ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
					}
					$item = $db->count($tb, $condition);
					$db->query("UPDATE {$table} SET item=$item WHERE catid=$catid");
				}
				$catid += 1;
			} else {
				$catid = $fid + $num;
			}
		} else {
			msg('统计更新成功', "?file=$file&mid=$mid&action=url");
		}
		msg('ID从'.$fid.'至'.($catid-1).'更新成功'.progress($sid, $fid, $tid), "?file=$file&mid=$mid&action=$action&sid=$sid&fid=$catid&tid=$tid&num=$num");
	break;
	case 'url':	
		foreach($CATEGORY as $c) {
			update_category($c);
		}
		msg('地址更新成功', "?file=$file&mid=$mid&action=letters");
	break;
	case 'letters':
		$update = false;
		foreach($CATEGORY as $k=>$v) {
			if(strlen($v['letter']) != 1) {
				$letter = $do->get_letter($v['catname'], false);
				if($letter) {
					$update = true;
					$letter = substr($letter, 0, 1);
					$db->query("UPDATE {$table} SET letter='$letter' WHERE catid='$v[catid]'");
				}
			}
		}
		msg('索引修复成功', "?file=$file&mid=$mid&action=cache");
	break;
	case 'cache':
		$do->repair();
		dmsg('缓存更新成功', '?file='.$file.'&mid='.$mid);
	break;
	case 'delete':
		if($catid) $catids = $catid;
		$catids or msg('请选择分类');
		$do->delete($catids);
		$NUM > 500 ? $do->cache() : $do->repair();
		dmsg('删除成功', $forward);
	break;
	case 'update':
		if(!$category || !is_array($category)) msg();
		$do->update($category);
		foreach($category as $catid=>$v) {
			$CATEGORY[$catid] = $db->get_one("SELECT * FROM {$table} WHERE catid=$catid");
			update_category($CATEGORY[$catid]);
		}		
		$NUM > 500 ? $do->cache() : $do->repair();
		dmsg('更新成功', '?file='.$file.'&mid='.$mid.'&parentid='.$parentid);
	break;
	case 'letter':
		isset($catname) or $catname = '';
		if(!$catname || strpos($catname, "\n") !== false) exit('');
		exit($do->get_letter($catname, false));
	break;
	case 'ckdir':
		if($do->get_catdir($catdir)) {
			dialog('目录名可以使用');
		} else {
			dialog('目录名不合法或者已经被使用');
		}
	break;
	default:
		$total = 0;
		$DTCAT = array();
		$condition = "moduleid=$mid";
		$condition .= $keyword ? " AND catname LIKE '%$keyword%'" : " AND parentid=$parentid";
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY listorder,catid");
		while($r = $db->fetch_array($result)) {
			$r['childs'] = substr_count($r['arrchildid'], ',');
			$total += $r['item'];
			$DTCAT[$r['catid']] = $r;
		}
		if(!$DTCAT && !$parentid && !$keyword) msg('暂无分类,请先添加',  '?file='.$file.'&mid='.$mid.'&action=add&parentid='.$parentid);
		include tpl('category');
	break;
}

class category {
	var $moduleid;
	var $catid;
	var $category = array();
	var $table;	

	function __construct($moduleid = 1, $catid = 0) {
		global $CATEGORY;
		$this->moduleid = $moduleid;
		$this->catid = $catid;
		if(!isset($CATEGORY)) $CATEGORY = cache_read('category-'.$this->moduleid.'.php');
		$this->category = $CATEGORY;
		$this->table = DT_PRE.'category';
	}

	function category($moduleid = 1, $catid = 0) {
		$this->__construct($moduleid, $catid);
	}

	function add($category)	{
		$category['moduleid'] = $this->moduleid;
		$category['letter'] = preg_match("/^[a-z]{1}+$/i", $category['letter']) ? strtolower($category['letter']) : '';
		foreach(array('group_list',  'group_show',  'group_add') as $v) {
			$category[$v] = isset($category[$v]) ? implode(',', $category[$v]) : '';
		}
		$sqlk = $sqlv = '';
		foreach($category as $k=>$v) {
			$sqlk .= ','.$k; $sqlv .= ",'$v'"; 
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");		
		$this->catid = DB::insert_id();
		if($category['parentid']) {
			$category['catid'] = $this->catid;
			$this->category[$this->catid] = $category;
			$arrparentid = $this->get_arrparentid($this->catid, $this->category);
		} else {
			$arrparentid = 0;
		}
		$catdir = $category['catdir'] ? $category['catdir'] : $this->catid;
		DB::query("UPDATE {$this->table} SET listorder=$this->catid,catdir='$catdir',arrparentid='$arrparentid' WHERE catid=$this->catid");
		return true;
	}

	function edit($category) {
		$category['letter'] = preg_match("/^[a-z]{1}+$/i", $category['letter']) ? strtolower($category['letter']) : '';
		if($category['parentid']) {
			$category['catid'] = $this->catid;
			$this->category[$this->catid] = $category;
			$category['arrparentid'] = $this->get_arrparentid($this->catid, $this->category);
		} else {
			$category['arrparentid'] = 0;
		}
		foreach(array('group_list',  'group_show',  'group_add') as $v) {
			$category[$v] = isset($category[$v]) ? implode(',', $category[$v]) : '';
		}
		$category['linkurl'] = '';
		$sql = '';
		foreach($category as $k=>$v) {
			$sql .= ",$k='$v'";
		}
		$sql = substr($sql, 1);
		DB::query("UPDATE {$this->table} SET $sql WHERE catid=$this->catid");
		return true;
	}

	function delete($catids) {
		if(is_array($catids)) {
			foreach($catids as $catid) {
				if(isset($this->category[$catid])) $this->delete($catid);
			}
		} else {
			$catid = $catids;
			if(isset($this->category[$catid])) {
				DB::query("DELETE FROM {$this->table} WHERE catid=$catid");
				$arrchildid = $this->category[$catid]['arrchildid'] ? $this->category[$catid]['arrchildid'] : $catid;
				DB::query("DELETE FROM {$this->table} WHERE catid IN ($arrchildid)");			
				if($this->moduleid > 4) DB::query("UPDATE ".get_table($this->moduleid)." SET status=0 WHERE catid IN (".$arrchildid.")");
			}
		}
		return true;
	}

	function update($category) {
	    if(!is_array($category)) return false;
		foreach($category as $k=>$v) {
			if(!$v['catname']) continue;
			$v['parentid'] = intval($v['parentid']);
			if($k == $v['parentid']) continue;
			if($v['parentid'] > 0 && !isset($this->category[$v['parentid']])) continue;
			$v['listorder'] = intval($v['listorder']);
			$v['level'] = intval($v['level']);
			$v['letter'] = preg_match("/^[a-z0-9]{1}+$/i", $v['letter']) ? strtolower($v['letter']) : '';
			$v['catdir'] = $this->get_catdir($v['catdir'], $k);
			if(!$v['catdir']) $v['catdir'] = $k;
			DB::query("UPDATE {$this->table} SET catname='$v[catname]',parentid='$v[parentid]',listorder='$v[listorder]',style='$v[style]',level='$v[level]',letter='$v[letter]',catdir='$v[catdir]' WHERE catid=$k ");
		}
		return true;
	}

	function repair() {
		$query = DB::query("SELECT * FROM {$this->table} WHERE moduleid='$this->moduleid' ORDER BY listorder,catid");
		$CATEGORY = array();
		while($r = DB::fetch_array($query)) {
			$CATEGORY[$r['catid']] = $r;
		}
		$childs = array();
		foreach($CATEGORY as $catid => $category) {
			$CATEGORY[$catid]['arrparentid'] = $arrparentid = $this->get_arrparentid($catid, $CATEGORY);
			$CATEGORY[$catid]['catdir'] = $catdir = preg_match("/^[0-9a-z_\-\/]+$/i", $category['catdir']) ? $category['catdir'] : $catid;
			$sql = "catdir='$catdir',arrparentid='$arrparentid'";
			if(!$category['linkurl']) {
				$CATEGORY[$catid]['linkurl'] = listurl($category);
				$sql .= ",linkurl='$category[linkurl]'";
			}
			DB::query("UPDATE {$this->table} SET $sql WHERE catid=$catid");
			if($arrparentid) {
				$arr = explode(',', $arrparentid);
				foreach($arr as $a) {
					if($a == 0) continue;
					isset($childs[$a]) or $childs[$a] = '';
					$childs[$a] .= ','.$catid;
				}
			}
		}
		foreach($CATEGORY as $catid => $category) {
			if(isset($childs[$catid])) {
				$CATEGORY[$catid]['arrchildid'] = $arrchildid = $catid.$childs[$catid];
				$CATEGORY[$catid]['child'] = 1;
				DB::query("UPDATE {$this->table} SET arrchildid='$arrchildid',child=1 WHERE catid='$catid'");
			} else {
				$CATEGORY[$catid]['arrchildid'] = $catid;
				$CATEGORY[$catid]['child'] = 0;
				DB::query("UPDATE {$this->table} SET arrchildid='$catid',child=0 WHERE catid='$catid'");
			}
		}
		$this->cache($CATEGORY);
        return true;
	}

	function get_arrparentid($catid, $CATEGORY) {
		if($CATEGORY[$catid]['parentid'] && $CATEGORY[$catid]['parentid'] != $catid) {
			$parents = array();
			$cid = $catid;
			while($catid) {
				if($CATEGORY[$cid]['parentid']) {
					$parents[] = $cid = $CATEGORY[$cid]['parentid'];
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

	function get_arrchildid($catid, $CATEGORY) {
		$arrchildid = '';
		foreach($CATEGORY as $category) {
			if(strpos(','.$category['arrparentid'].',', ','.$catid.',') !== false) $arrchildid .= ','.$category['catid'];
		}
		return $arrchildid ? $catid.$arrchildid : $catid;
	}

	function get_catdir($catdir, $catid = 0) {
		if(preg_match("/^[0-9a-z_\-\/]+$/i", $catdir)) {
			$condition = "catdir='$catdir' AND moduleid='$this->moduleid'";
			if($catid) $condition .= " AND catid!=$catid";
			$r = DB::get_one("SELECT catid FROM {$this->table} WHERE $condition");
			if($r) {
				return '';
			} else {
				return $catdir;
			}
		} else {
			return '';
		}
	}

	function get_letter($catname, $letter = true) {
		return $letter ? strtolower(substr(gb2py($catname), 0, 1)) : str_replace(' ', '', gb2py($catname));
	}

	function cache($data = array()) {
		cache_category($this->moduleid, $data);
	}
}
?>