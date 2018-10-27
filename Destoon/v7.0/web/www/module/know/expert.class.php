<?php 
defined('IN_DESTOON') or exit('Access Denied');
class expert {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		global $table_expert;
		$this->table = $table_expert;
		$this->fields = array('title','style','major','username','passport','addtime','editor','edittime','introduce','content');
    }

    function expert() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!check_name($post['username'])) return $this->_($L['expert_pass_username']);
		if(!$post['title']) return $this->_($L['expert_pass_truename']);
		if(strlen($post['major']) < 4) return $this->_($L['expert_pass_major']);
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		$post['content'] = stripslashes($post['content']);
		$post['content'] = save_local($post['content']);
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		$post['introduce'] = addslashes(get_intro($post['content'], 120));
		$post['passport'] = addslashes(get_user($post['username'], 'username', 'passport'));
		if($this->itemid) {
			$post['editor'] = $_username;
			$new = $post['content'];
			$r = $this->get_one();
			$old = $r['content'];
			delete_diff($new, $old);
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
		$post['content'] = addslashes(dsafe($content));
		return array_map("trim", $post);
	}

	function get_one($condition = '') {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid' $condition");
	}

	function get_list($condition = '1', $order = 'addtime DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $sum;
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
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['title'] = set_style($r['title'], $r['style']);
			$r['linkurl'] = $MOD['linkurl'].rewrite('expert.php?itemid='.$r['itemid']);
			$r['rate'] = ($r['answer'] && $r['best'] < $r['answer']) ? dround($r['best']*100/$r['answer'], 2, true).'%' : '100%';
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		global $MOD, $L;
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = DB::insert_id();
		clear_upload($post['content'], $this->itemid, $this->table);
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
		clear_upload($post['content'], $this->itemid, $this->table);
		return true;
	}

	function delete($itemid) {
		global $MOD, $L;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			if($r['content']) delete_local($r['content'], get_user($r['username']));
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>