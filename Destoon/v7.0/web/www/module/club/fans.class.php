<?php 
defined('IN_DESTOON') or exit('Access Denied');
class fans {
	var $itemid;
	var $table;
	var $errmsg = errmsg;

    function __construct() {
		global $table_fans;
		$this->table = $table_fans;
    }

    function fans() {
		$this->__construct();
    }

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
	}

	function get_list($condition = 'status=3', $order = 'itemid DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $items, $sum, $table_group;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();	
		$lists = $groupids = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$groupids[$r['gid']] = $r['gid'];
			$lists[] = $r;
		}
		if($groupids) {
			$GRPS = array();
			$result = DB::query("SELECT itemid,title,linkurl FROM {$table_group} WHERE itemid IN (".implode(',', $groupids).")");
			while($r = DB::fetch_array($result)) {
				$GRPS[$r['itemid']] = $r;
			}
			if($GRPS) {
				foreach($lists as $k=>$v) {
					$lists[$k]['groupname'] = $v['gid'] ? $GRPS[$v['gid']]['title'] : '';
					$lists[$k]['groupurl'] = $v['gid'] ? $MOD['linkurl'].$GRPS[$v['gid']]['linkurl'] : '';
				}
			}
		}
		return $lists;
	}

	function recycle($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			return true;
		}		
	}

	function restore($itemid) {
		global $module, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->restore($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3 WHERE itemid=$itemid");
			return true;
		}		
	}

	function delete($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->delete($v); 
			}
		} else {
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function check($itemid, $status = 3) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->check($v, $status); 
			}
		} else {
			DB::query("UPDATE {$this->table} SET status=$status WHERE itemid=$itemid");
		}
	}

	function reject($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->reject($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=1 WHERE itemid=$itemid");
			return true;
		}
	}

	function clear($condition = 'status=0') {		
		$result = DB::query("SELECT itemid FROM {$this->table} WHERE $condition");
		while($r = DB::fetch_array($result)) {
			$this->delete($r['itemid']);
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>