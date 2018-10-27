<?php
defined('IN_DESTOON') or exit('Access Denied');
class item {
	var $specialid;
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct($specialid) {
		global $table_item;
		$this->specialid = $specialid;
		$this->table = $table_item;
		$this->fields = array('typeid','specialid','level','title','style','introduce','thumb','username','addtime', 'editor','edittime','ip','template','linkurl','note');
    }

    function item($specialid) {
		$this->__construct($specialid);
    }

	function pass($post) {
		if(!is_array($post)) return false;
		if(!$post['title']) return $this->_(lang('message->pass_title'));
		if(!$post['linkurl']) return $this->_(lang('message->pass_linkurl'));
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['adddate'] = timetodate($post['addtime'], 3);
		$post['edittime'] = DT_TIME;
		if($this->itemid) {
			$post['editor'] = $_username;
		} else {
			$post['username'] = $post['editor'] = $_username;
			$post['ip'] = DT_IP;
		}
		return $post;
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
	}

	function get_list($condition = 'status=3', $order = 'addtime DESC', $cache = '') {
		global $MOD, $pages, $page, $pagesize, $offset, $items, $TYPE, $special, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
			$items = $r['num'];
		}
		$pages =  pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : '';
			$r['typeurl'] = $r['type'] ? rewrite($MOD['linkurl'].'type.php?tid='.$r['typeid']) : '';
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		global $MOD;
		$post = $this->set($post);
		$t = DB::get_one("SELECT * FROM {$this->table} WHERE specialid=$post[specialid] AND linkurl='$post[linkurl]'");
		if($t) return false;
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = DB::insert_id();
		clear_upload($post['thumb'], $this->itemid, $this->table);
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
		clear_upload($post['thumb'], $this->itemid, $this->table);
		return true;
	}

	function delete($itemid, $all = true) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->delete($v, $all);
			}
		} else {
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function level($itemid, $level) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET level=$level WHERE itemid IN ($itemids)");
	}

	function type($itemid, $typeid) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET typeid=$typeid WHERE itemid IN ($itemids)");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>