<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
class admin {
	var $userid;
	var $username;
	var $errmsg = errmsg;

	function __construct() {
		global $admin;
	}

	function admin() {
		$this->__construct();
	}

	function is_member($username) {
		return DB::get_one("SELECT userid FROM ".DT_PRE."member WHERE username='$username'");
	}

	function count_admin() {
		$r = DB::get_one("SELECT COUNT(*) AS num FROM ".DT_PRE."member WHERE groupid=1 AND admin=1 ");
		return $r['num'];
	}

	function set_admin($username, $admin, $role, $aid) {
		$username = trim($username);
		$r = $this->is_member($username);
		if(!$r) return $this->_('会员不存在');
		$userid = $r['userid'];
		if(is_founder($userid)) {
			$admin = 1;
			$aid = 0;
		}
		if($admin == 1) $aid = 0;
		DB::query("UPDATE ".DT_PRE."member SET groupid=1,admin=$admin,role='$role',aid=$aid WHERE userid=$userid");
		DB::query("UPDATE ".DT_PRE."company SET groupid=1 WHERE userid=$userid");
		return true;
	}

	function move_admin($username) {
		$r = $this->get_one($username);
		if($r && $r['admin'] > 0) {			
			if(is_founder($r['userid'])) return $this->_('创始人不可改变级别');
			if($r['admin'] == 1 && $this->count_admin() < 2) return $this->_('系统最少需要保留一位超级管理员');
			$admin = $r['admin'] == 1 ? 2 : 1;
			DB::query("UPDATE ".DT_PRE."member SET admin=$admin WHERE username='$username'");
			return true;
		} else {
			return $this->_('管理员不存在');
		}
	}

	function delete_admin($username) {
		$r = $this->get_one($username);
		if($r) {
			if(is_founder($r['userid'])) return $this->_('创始人不可删除');
			if($r['admin'] == 1 && $this->count_admin() < 2) return $this->_('系统最少需要保留一位超级管理员');
			$userid = $r['userid'];
			$groupid = $r['regid'] ? $r['regid'] : 6;
			DB::query("UPDATE ".DT_PRE."member SET groupid=$groupid,admin=0,role='',aid=0 WHERE userid=$userid");
			DB::query("UPDATE ".DT_PRE."company SET groupid=$groupid WHERE userid=$userid");
			DB::query("DELETE FROM ".DT_PRE."admin WHERE userid=$userid");
			cache_delete('menu-'.$userid.'.php');
			cache_delete('right-'.$userid.'.php');
			return true;
		} else {
			return $this->_('会员不存在');
		}
	}

	function get_one($user, $type = 1) {
		$fields = $type ? 'username' : 'userid';
        return DB::get_one("SELECT * FROM ".DT_PRE."member WHERE `$fields`='$user'");
	}

	function get_list($condition) {
		global $pages, $page, $pagesize, $offset, $pagesize, $CFG, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM ".DT_PRE."member WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$admins = array();
		$result = DB::query("SELECT * FROM ".DT_PRE."member WHERE $condition ORDER BY admin ASC,userid ASC LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['logintime'] = timetodate($r['logintime'], 5);
			$r['adminname'] = $r['admin'] == 1 ? (is_founder($r['userid']) ? '<span class="f_red">网站创始人</span>' : '<span class="f_blue">超级管理员</span>') : '普通管理员';
			$admins[] = $r;
		}
		return $admins;
	}

	function get_right($userid) {
		global $MODULE;
		$rights = array();
		$result = DB::query("SELECT * FROM ".DT_PRE."admin WHERE userid=$userid AND url='' ORDER BY moduleid DESC,file DESC,adminid DESC ");
		while($r = DB::fetch_array($result)) {
			@include DT_ROOT.'/'.($r['moduleid'] == 1 ? 'admin' : 'module/'.$MODULE[$r['moduleid']]['module'].'/admin').'/config.inc.php';
			$r['name'] = isset($RT['file'][$r['file']]) ? '('.$RT['file'][$r['file']].')' : '';
			$r['module'] = '('.$MODULE[$r['moduleid']]['name'].')';
			$rights[] = $r;
		}
		return $rights;
	}

	function get_menu($userid) {
		$menus = array();
		$result = DB::query("SELECT * FROM ".DT_PRE."admin WHERE userid=$userid AND url!='' ORDER BY listorder ASC,adminid ASC ");
		while($r = DB::fetch_array($result)) {
			$menus[] = $r;
		}
		return $menus;
	}

	function update($userid, $right, $admin) {
		if(isset($right[-1])) {
			$this->add($userid, $right[-1], $admin);
			unset($right[-1]);
			$type = 1;//right
		} else {
			$type = 0;//menu
		}
		$this->add($userid, $right[0], $admin);
		unset($right[0]);
		foreach($right as $k=>$v) {
			if(isset($v['delete'])) {
				$this->delete($k);
				unset($right[$k]);
			}
		}
		$this->edit($right, $type);
		if($admin == 1) DB::query("DELETE FROM ".DT_PRE."admin WHERE userid=$userid AND url=''");
		$this->cache_right($userid);
		$this->cache_menu($userid);
		return true;
	}

	function add($userid, $right, $admin) {
		if(isset($right['url'])) {
			if(!$right['title'] || !$right['url']) return false;
			$r = DB::get_one("SELECT * FROM ".DT_PRE."admin WHERE userid=$userid AND url='".$right['url']."'");
			if($r) return false;
			if($admin == 2 && defined('MANAGE_ADMIN')) {
				$r = $this->url_right($right['url']);
				if($r) $this->add($userid, $r, $admin);
			}
		} else {
			$right['moduleid'] = intval($right['moduleid']);
			if(!$right['moduleid']) return false;
			$_right = $this->get_right($userid);			
			foreach($_right as $v) {//module admin
				if($v['file'] == '' && $v['moduleid'] == $right['moduleid']) return false;
			}
			if($right['file']) {//file exists
				foreach($_right as $v) {
					if($v['file'] == $right['file'] && $v['moduleid'] == $right['moduleid']) return false;
				}
			} else {
				unset($right['action'], $right['catid']);
			}
		}
		$right['userid'] = $userid;
		$sql1 = $sql2 = '';
		foreach($right as $k=>$v) {
			$sql1 .= ','.$k;
			$sql2 .= ",'$v'";
		}		
        $sql1 = substr($sql1, 1);
        $sql2 = substr($sql2, 1);
		DB::query("INSERT INTO ".DT_PRE."admin ($sql1) VALUES($sql2)");
	}

	function edit($right, $type = 0) {
		if($type) {
			//when module admin, have all rights
			$moduleids = $adminids = array();
			foreach($right as $k=>$v) {
				if(!$v['file']) { 
					$moduleids[] = $v['moduleid'];
					$adminids[$v['moduleid']] = $k;
					$right[$k]['action'] = $right[$k]['catid'] = '';
				}
			}
			if($moduleids) {
				foreach($right as $k=>$v) {
					if(in_array($v['moduleid'], $moduleids) && !in_array($k, $adminids)) {
						unset($right[$k]);
						$this->delete($k);
					}
				}
			}
		}
		foreach($right as $key=>$value) {
			if(isset($value['title'])) {
				if(!$value['title'] || !$value['url']) continue;
			} else {
				$value['moduleid'] = intval($value['moduleid']);
				if(!$value['moduleid']) continue;
			}
			$sql = '';
			foreach($value as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
			DB::query("UPDATE ".DT_PRE."admin SET $sql WHERE adminid='$key'");
		}
	}

	function url_right($url) {
		if(substr($url, 0, 1) == '?') $url = substr($url, 1);
		$arr = array();
		parse_str($url);
		$arr['moduleid'] = isset($moduleid) ? $moduleid : 1;
		$arr['file'] = isset($file) ? $file : 'index';
		$arr['action'] = isset($action) ? $action : '';
		return $arr;
	}

	function cache_right($userid) {
		$rights = $this->get_right($userid);
		$right = $moduleids = array();
		foreach($rights as $v) {//get moduleids
			isset($moduleids[$v['moduleid']]) or $moduleids[$v['moduleid']] = $v['moduleid'];
		}
		foreach($moduleids as $m) {//get rights
			foreach($rights as $r) {
				if($r['moduleid'] == $m) {
					$r['file'] = $r['file'] ? $r['file'] : 'NA';
					$right[$m][$r['file']]['action'] = $r['action'] ? explode('|', $r['action']) : '';
					$right[$m][$r['file']]['catid'] = $r['catid'] ? explode('|', $r['catid']) : '';
				}
			}
		}
		foreach($right as $k=>$v) {
			if(isset($v['NA'])) $right[$k] = '';
		}
		foreach($right as $k=>$v) {
			if($v) {
				foreach($v as $i=>$j) {
					if(!$j['action'] && !$j['catid']) $right[$k][$i] = '';
				}
			}
		}
		cache_write('right-'.$userid.'.php', $right);		
	}

	function cache_menu($userid) {
		$menus = $this->get_menu($userid);
		$menu = $r = array();
		foreach($menus as $k=>$v) {
			$r['title'] = $v['title'];
			$r['style'] = $v['style'];
			$r['url'] = $v['url'];
			$menu[] = $r;
		}
		cache_write('menu-'.$userid.'.php', $menu);
	}

	function delete($adminid) {
		DB::query("DELETE FROM ".DT_PRE."admin WHERE adminid=$adminid");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>