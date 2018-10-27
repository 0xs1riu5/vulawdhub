<?php 
defined('IN_DESTOON') or exit('Access Denied');
class announce {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'announce';
		$this->fields = array('typeid','areaid','level', 'title','style','content','addtime','fromtime','totime','editor','edittime','template', 'islink', 'linkurl');
    }

    function announce() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['typeid']) return $this->_($L['announce_pass_type']);
		if(!$post['title']) return $this->_($L['announce_pass_title']);
		if(isset($post['islink'])) {
			if(!$post['linkurl']) return $this->_($L['announce_pass_url']);
		} else {
			if(!$post['content']) return $this->_($L['announce_pass_content']);
		}
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['islink'] = isset($post['islink']) ? 1 : 0;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		$post['editor'] = $_username;
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

	function get_list($condition = '1', $order = 'listorder DESC,addtime DESC') {
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
			$r['title'] = set_style($r['title'], $r['style']);
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['fromdate'] = $r['fromtime'] ? timetodate($r['fromtime'], 3) : $L['timeless'];
			$r['todate'] = $r['totime'] ? timetodate($r['totime'], 3) : $L['timeless'];
			$r['typename'] = $TYPE[$r['typeid']]['typename'];
			$r['typeurl'] = $MOD['announce_url'].list_url($r['typeid']);
			$lists[] = $r;
		}
		return $lists;
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
		if(!$post['islink']) {
			$linkurl = $this->linkurl($this->itemid);
			DB::query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$this->itemid");
		}
		clear_upload($post['content'], $this->itemid, $this->table);
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
		if(!$post['islink']) {
			$linkurl = $this->linkurl($this->itemid);
			DB::query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$this->itemid");
		}
		clear_upload($post['content'], $this->itemid, $this->table);
		return true;
	}

	function linkurl($itemid) {
		global $MOD;
		$linkurl = show_url($itemid);
		return $MOD['announce_url'].$linkurl;
	}

	function delete($itemid, $all = true) {
		global $DT;
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->delete($v, $all); 
			}
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			if($all) {
				$userid = get_user($r['editor']);
				if($r['content']) delete_local($r['content'], $userid);
				DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				$fileurl = DT_ROOT.'/announce/'.$itemid.'.'.$DT['file_ext'];
				if(is_file($fileurl)) unlink($fileurl);
			}
		}
	}

	function order($listorder) {
		if(!is_array($listorder)) return false;
		foreach($listorder as $k=>$v) {
			$k = intval($k);
			$v = intval($v);
			DB::query("UPDATE {$this->table} SET listorder=$v WHERE itemid=$k");
		}
		return true;
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