<?php 
defined('IN_DESTOON') or exit('Access Denied');
class ad {
	var $aid;
	var $pid;
	var $table;
	var $table_place;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'ad';
		$this->table_place = DT_PRE.'ad_place';
    }

    function ad() {
		$this->__construct();
    }

	function is_place($place) {
		global $L;
		if(!is_array($place)) return false;
		if(!$place['name']) return $this->_($L['pass_ad_name']);
		if($place['typeid'] == 3 || $place['typeid'] == 4 || $place['typeid'] == 5) {
			if(!$place['width']) return $this->_($L['pass_place_width']);
			if(!$place['height']) return $this->_($L['pass_place_height']);
		}
		if($place['typeid'] == 6 || $place['typeid'] == 7) {
			if(!$place['moduleid']) return $this->_($L['pass_place_module']);
			$condition = "moduleid=$place[moduleid] AND typeid=$place[typeid]";
			if($this->pid) $condition .= " AND pid<>$this->pid";
			$r = DB::get_one("SELECT pid FROM {$this->table_place} WHERE $condition");
			if($r) return $this->_($L['pass_place_repeat']);
		}
		return true;
	}

	function set_place($place) {
		global $_username;
		$place = array_map('ad_restore', $place);
		if(!$this->pid) $place['addtime'] = DT_TIME;
		$place['edittime'] = DT_TIME;
		$place['editor'] = $_username;
		$place['width'] = intval($place['width']);
		$place['height'] = intval($place['height']);
		return $place;
	}

	function add_place($place) {
		$place = $this->set_place($place);
		$sqlk = $sqlv = '';
		foreach($place as $k=>$v) {
			$sqlk .= ','.$k; $sqlv .= ",'$v'";
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table_place} ($sqlk) VALUES ($sqlv)");
		$this->pid = DB::insert_id();
		clear_upload($place['thumb'], $this->pid, $this->table_place);
		return $this->pid;
	}
	
	function edit_place($place) {
		$place = $this->set_place($place);
		$sql = '';
		foreach($place as $k=>$v) {
			$sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table_place} SET $sql WHERE pid=$this->pid");
		clear_upload($place['thumb'], $this->pid, $this->table_place);
		return true;
	}

	function get_one_place() {
        return DB::get_one("SELECT * FROM {$this->table_place} WHERE pid='$this->pid'");
	}

	function get_list_place($condition = '1', $order = 'listorder DESC,pid DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $sum, $items;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table_place} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$ads = array();
		$result = DB::query("SELECT * FROM {$this->table_place} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['alt'] = $r['name'];
			$r['name'] = set_style($r['name'], $r['style']);
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['width'] or $r['width'] = '--';
			$r['height'] or $r['height'] = '--';
			$r['typename'] = $TYPE[$r['typeid']];
			$r['typeurl'] = $MOD['ad_url'].list_url($r['typeid']);
			$ads[] = $r;
		}
		return $ads;
	}

	function get_place() {
		$ads = array();
		$result = DB::query("SELECT * FROM {$this->table_place} ORDER BY listorder DESC,pid DESC");
		while($r = DB::fetch_array($result)) {
			$ads[$r['pid']] = $r;
		}
		return $ads;
	}

	function order_place($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			DB::query("UPDATE {$this->table_place} SET listorder=$v WHERE pid=$k");
		}
		return true;
	}

	function delete_place($pid) {
		if(is_array($pid)) {
			foreach($pid as $v) { 
				$this->delete_place($v); 
			}
		} else {			
			$p = DB::get_one("SELECT * FROM {$this->table_place} WHERE pid=$pid");
			DB::query("DELETE FROM {$this->table_place} WHERE pid=$pid");
			$filename = $p['typeid'] > 5 ? 'ad_'.$p['moduleid'].'_d'.$p['typeid'].'.htm' : 'ad_'.$a['pid'].'_d0.htm';
			file_del(DT_CACHE.'/htm/'.$filename);
			file_del(DT_CACHE.'/htm/ad_'.$pid.'.htm');
			file_del(DT_ROOT.'/file/script/A'.$pid.'.js');
			$result = DB::query("SELECT aid FROM {$this->table} WHERE pid=$pid ORDER BY aid DESC");
			while($r = DB::fetch_array($result)) {
				$this->delete($r['aid']);
			}
		}
	}

	function is_ad($ad) {
		global $L;
		if(!is_array($ad)) return false;
		if(!$ad['title']) return $this->_($L['pass_ad_title']);
		if(!$ad['fromtime'] || !is_date($ad['fromtime'])) return $this->_($L['pass_ad_from']);
		if(!$ad['totime'] || !is_date($ad['totime'])) return $this->_($L['pass_ad_end']);
		if(strtotime($ad['fromtime'].' 0:0:0') > strtotime($ad['totime'].' 23:59:59')) return $this->_($L['pass_ad_bad_date']);
		if($ad['typeid'] == 1 || $ad['typeid'] == 7) {
			if(!$ad['code']) return $this->_($L['pass_ad_code']);
		} else if($ad['typeid'] == 2) {
			if(!$ad['text_name']) return $this->_($L['pass_ad_text_name']);
			if(!$ad['text_url']) return $this->_($L['pass_ad_text_url']);
		} else if($ad['typeid'] == 3) {
			if(!$ad['image_src']) return $this->_($L['pass_ad_image_src']);
		} else if($ad['typeid'] == 4) {
			if(!$ad['flash_src']) return $this->_($L['pass_ad_flash_src']);
		}
		return true;
	}

	function set_ad($ad) {
		global $_username;
		$ad = array_map('ad_restore', $ad);
		if(!$this->aid) $ad['addtime'] = DT_TIME;
		$ad['edittime'] = DT_TIME;
		$ad['editor'] = $_username;
		$ad['fromtime'] = strtotime($ad['fromtime'].' 0:0:0');
		$ad['totime'] = strtotime($ad['totime'].' 23:59:59');
		$ad['username'] or $ad['username'] = $_username;
		$ad['url'] = '';
		if($ad['typeid'] == 2) {
			$ad['url'] = $ad['text_url'];
		} else if($ad['typeid'] == 3 || $ad['typeid'] == 5) {
			$ad['url'] = $ad['image_url'];
		} else if($ad['typeid'] == 4) {
			$ad['url'] = $ad['flash_url'];
		}
		return $ad;
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE aid='$this->aid'");
	}

	function get_list($condition = '1', $order = 'fromtime DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $L, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();	
		$ads = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['fromdate'] = timetodate($r['fromtime'], 3);
			$r['todate'] = timetodate($r['totime'], 3);			
			$r['days'] = $r['totime'] > DT_TIME ? intval(($r['totime']-DT_TIME)/86400) : 0;
			if($r['totime'] < DT_TIME) {
				$r['process'] = $L['status_expired'];
			} else if($r['fromtime'] > DT_TIME) {
				$r['process'] = $L['status_not_start'];
			} else {
				$r['process'] = $L['status_displaying'];
			}
			$ads[] = $r;
		}
		return $ads;
	}

	function add($ad) {
		$ad = $this->set_ad($ad);
		$sqlk = $sqlv = '';
		foreach($ad as $k=>$v) {
			$sqlk .= ','.$k; $sqlv .= ",'$v'";
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->aid = DB::insert_id();
		DB::query("UPDATE {$this->table_place} SET ads=ads+1 WHERE pid='$ad[pid]'");
		clear_upload($ad['image_src'].$ad['flash_src'].$ad['code'], $this->aid, $this->table);
		return $this->aid;
	}

	function edit($ad) {
		$ad = $this->set_ad($ad);
		$sql = '';
		foreach($ad as $k=>$v) {
			$sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table} SET $sql WHERE aid=$this->aid");
		clear_upload($ad['image_src'].$ad['flash_src'].$ad['code'], $this->aid, $this->table);
		return true;
	}

	function delete($aid) {
		if(is_array($aid)) {
			foreach($aid as $v) { 
				$this->delete($v); 
			}
		} else {
			$this->aid = $aid;
			$a = $this->get_one();
			$filename = ad_name($a);
			file_del(DT_CACHE.'/htm/'.$filename);
			$userid = get_user($a['username']);
			if($a['image_src']) delete_upload($a['image_src'], $userid);
			if($a['flash_src']) delete_upload($a['flash_src'], $userid);
			DB::query("DELETE FROM {$this->table} WHERE aid=$aid");
			DB::query("UPDATE {$this->table_place} SET ads=ads-1 WHERE pid=$a[pid]");
		}
	}

	function order_ad($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			DB::query("UPDATE {$this->table} SET listorder=$v WHERE aid=$k");
		}
		return true;
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}

function ad_restore($string) {
	return str_replace(array('unio&#110;'), array('union'), $string);
}
?>