<?php 
defined('IN_DESTOON') or exit('Access Denied');
class favorite {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'favorite';
		$this->fields = array('listorder','userid','typeid','mid','tid','title','style','thumb','url','addtime','note');
    }

    function favorite() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(strlen($post['title']) < 3) return $this->_($L['pass_title']);
		if(!is_url($post['url'])) return $this->_($L['pass_url']);
		return true;
	}

	function set($post) {
		$post['listorder'] = intval($post['listorder']);
		$post['mid'] = intval($post['mid']);
		$post['tid'] = intval($post['tid']);
		$post['thumb'] = is_url($post['thumb']) ? $post['thumb'] : '';
		$post = dhtmlspecialchars($post);
		return array_map("trim", $post);
	}

	function get_one($condition = '') {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$this->itemid $condition");
	}

	function get_list($condition = 'status=3', $order = 'addtime DESC') {
		global $MODULE, $TYPE, $pages, $page, $pagesize, $offset, $L, $items, $sum;
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
			$r['title'] = set_style($r['title'], $r['style']);
			$r['url'] = DT_PATH.'api/redirect.php?url='.urlencode(fix_link($r['url']));
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : $L['default_type'];
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
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("DELETE FROM {$this->table} WHERE itemid IN ($itemids)");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>