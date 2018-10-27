<?php 
defined('IN_DESTOON') or exit('Access Denied');
class promo {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'finance_promo';
		$this->fields = array('title','price','cost','amount','fromtime','totime','username','open','addtime','editor','edittime','note');
    }

    function promo() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!trim($post['title'])) return $this->_($L['promo_msg_title']);
		if(dround($post['price']) < 0.1) return $this->_($L['promo_msg_price']);
		if($post['cost'] && dround($post['price']) > dround($post['cost'])) return $this->_($L['promo_msg_cost']);
		if(intval($post['amount']) < 1) return $this->_($L['promo_msg_amount']);
		if(!is_time($post['fromtime']) || !is_time($post['totime'])) return $this->_($L['promo_msg_date']);
		if(strtotime($post['fromtime']) > strtotime($post['totime'])) return $this->_($L['promo_msg_date']);
		return true;
	}

	function set($post) {
		global $_username;
		$post['price'] = dround($post['price']);
		$post['cost'] = dround($post['cost']);
		$post['amount'] = intval($post['amount']);
		$post['fromtime'] = strtotime($post['fromtime']);
		$post['totime'] = strtotime($post['totime']);
		$post['open'] = $post['open'] ? 1 : 0;
		$post['edittime'] = DT_TIME;
		$post['editor'] = $_username;
		if($this->itemid) {
			//$post['editor'] = $_username;
		} else {
			$post['addtime'] = DT_TIME;
		}
		$post = dhtmlspecialchars($post);		
		return array_map("trim", $post);
	}

	function get_one($condition = '') {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$this->itemid $condition");
	}

	function get_list($condition, $order = 'itemid DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $items, $sum;
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

	function delete($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function del($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->del($v); }
		} else {
			DB::query("DELETE FROM ".DT_PRE."finance_coupon WHERE itemid=$itemid");
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>