<?php 
defined('IN_DESTOON') or exit('Access Denied');
class gift {
	var $itemid;
	var $table;
	var $table_order;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'gift';
		$this->table_order = DT_PRE.'gift_order';
		$this->fields = array('typeid','areaid', 'title','style','thumb','level','credit','amount','groupid','maxorder','content','addtime','fromtime','totime','editor','edittime');
    }

    function gift() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['typeid']) return $this->_($L['gift_pass_type']);
		if(!$post['title']) return $this->_($L['gift_pass_title']);
		if(!$post['thumb']) return $this->_($L['gift_pass_thumb']);
		if(intval($post['credit']) < 1) return $this->_($L['gift_pass_credit']);
		if(intval($post['amount']) < 1) return $this->_($L['gift_pass_amount']);
		if(!$post['groupid']) return $this->_($L['gift_pass_group']);
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		$post['editor'] = $_username;
		$post['groupid'] = (isset($post['groupid']) && $post['groupid']) ? ','.implode(',', $post['groupid']).',' : '';
		$post['credit'] = intval($post['credit']);
		$post['amount'] = intval($post['amount']);
		$post['maxorder'] = intval($post['maxorder']);
		$post['content'] = addslashes(save_remote(save_local(stripslashes($post['content']))));
		if($this->itemid) {
			$new = $post['content'];
			$r = $this->get_one();
			$old = $r['content'];
			delete_diff($new, $old);
		}
		if($post['fromtime']) $post['fromtime'] = strtotime($post['fromtime'].' 0:0:0');
		if($post['totime']) $post['totime'] = strtotime($post['totime'].' 23:59:59');
		return array_map("trim", $post);
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
	}

	function get_list($condition = '1', $order = 'addtime DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $L, $sum, $items;
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
			$r['alt'] = $r['title'];
			$r['left'] = $r['amount'] - $r['orders'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['fromdate'] = $r['fromtime'] ? timetodate($r['fromtime'], 3) : $L['timeless'];
			$r['todate'] = $r['totime'] ? timetodate($r['totime'], 3) : $L['timeless'];
			$r['typename'] = $TYPE[$r['typeid']]['typename'];
			$r['typeurl'] = $MOD['gift_url'].list_url($r['typeid']);
			$lists[] = $r;
		}
		return $lists;
	}


	function get_list_order($condition = '1', $order = 'o.addtime DESC') {
		global $items, $pages, $page, $pagesize, $offset, $L, $items;
		$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table_order} o LEFT JOIN {$this->table} g ON g.itemid=o.itemid WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = array();
		$result = DB::query("SELECT g.title,g.linkurl,o.* FROM {$this->table_order} o LEFT JOIN {$this->table} g ON g.itemid=o.itemid WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$lists[] = $r;
		}
		return $lists;
	}

	
	function get_my_order($condition = '1', $order = 'oid DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table_order} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = DB::query("SELECT g.title,g.linkurl,o.* FROM {$this->table_order} o LEFT JOIN {$this->table} g ON g.itemid=o.itemid WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$lists[] = $r;
		}
		return $lists;
	}

	function update_order($post) {
		foreach($post as $k=>$v) {
			if(isset($v['delete'])) {
				DB::query("DELETE FROM {$this->table_order} WHERE oid=$k");
				DB::query("UPDATE {$this->table} SET orders=orders-1 WHERE itemid='$v[itemid]'");
			} else {
				DB::query("UPDATE {$this->table_order} SET status='$v[status]',note='$v[note]' WHERE oid=$k");
			}
		}
	}

	function add($post) {
		global $DT, $MOD, $module;
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = DB::insert_id();
		$linkurl = $this->linkurl($this->itemid);
		DB::query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$this->itemid");
		clear_upload($post['content'].$post['thumb'], $this->itemid, $this->table);
		return $this->itemid;
	}

	function edit($post) {
		global $DT, $MOD, $module;
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
		$linkurl = $this->linkurl($this->itemid);
		DB::query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$this->itemid");
		clear_upload($post['content'].$post['thumb'], $this->itemid, $this->table);
		return true;
	}

	function linkurl($itemid) {
		global $MOD;
		$linkurl = show_url($itemid);
		return $MOD['gift_url'].$linkurl;
	}

	function delete($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->delete($v, $all); 
			}
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			$userid = get_user($r['editor']);
			if($r['content']) delete_local($r['content'], $userid);
			if($r['thumb']) delete_upload($r['thumb'], $userid);
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
			DB::query("DELETE FROM {$this->table_order} WHERE itemid=$itemid");
		}
	}

	function level($itemid, $level) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET level=$level WHERE itemid IN ($itemids)");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>