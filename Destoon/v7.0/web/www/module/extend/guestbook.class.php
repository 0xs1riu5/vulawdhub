<?php 
defined('IN_DESTOON') or exit('Access Denied');
class guestbook {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'guestbook';
		$this->fields = array( 'title','areaid','content','truename','telephone','email','qq','wx','ali','skype','hidden','status','username','addtime', 'ip', 'reply','editor','edittime');
    }

    function guestbook() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['content']) return $this->_($L['gbook_pass_content']);
		return true;
	}

	function set($post) {
		global $_username, $TYPE;
		$post['content'] = strip_tags($post['content']);
		$post['title'] = in_array($post['type'], $TYPE) ? '['.$post['type'].']' : '';
		$post['title'] .= dsubstr($post['content'], 30);
		$post['title'] = daddslashes($post['title']);
		$post['hidden'] = (isset($post['hidden']) && $post['hidden']) ? 1 : 0;
		if($this->itemid) {
			$post['status'] = $post['status'] == 2 ? 2 : 3;
			$post['editor'] = $_username;
			$post['edittime'] = DT_TIME;
		} else {
			$post['username'] = $_username;
			$post['addtime'] =  DT_TIME;
			$post['ip'] =  DT_IP;
			$post['edittime'] = 0;
			$post['reply'] = '';
			$post['status'] = 2;
		}
		$post = dhtmlspecialchars($post);
		return array_map("trim", $post);
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
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
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['content'] = nl2br($r['content']);
			$r['editdate'] = '--';
			if($r['reply']) {
				$r['reply'] = nl2br($r['reply']);
				$r['editdate'] = timetodate($r['edittime'], 5);
			}
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

	function check($itemid, $status) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v, $status); }
		} else {
			DB::query("UPDATE {$this->table} SET status=$status WHERE itemid=$itemid");
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>