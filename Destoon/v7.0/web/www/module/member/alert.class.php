<?php 
defined('IN_DESTOON') or exit('Access Denied');
class alert {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'alert';
		$this->fields = array('word','email','mid','catid','areaid','rate','username','addtime','editor','edittime','status');
    }

    function alert() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['word'] && !$post['catid']) return $this->_($L['alert_pass']);
		return true;
	}

	function set($post) {
		global $MOD, $_username;
		$post['edittime'] = DT_TIME;
		$post['editor'] = $_username;
		$post = dhtmlspecialchars($post);
		return array_map("trim", $post);
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$this->itemid");
	}

	function get_list($condition = 'status=3', $order = 'itemid DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $sum, $items;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		global $MOD;
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = DB::insert_id();
		return $this->itemid;
	}

	function edit($post) {
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
		return true;
	}

	function delete($itemid, $all = true) {
		global $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function check($itemid, $status = 3) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v, $status); }
		} else {
			DB::query("UPDATE {$this->table} SET status=$status,editor='$_username',edittime=".DT_TIME." WHERE itemid=$itemid");
			return true;
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>