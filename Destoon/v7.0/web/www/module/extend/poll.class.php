<?php 
defined('IN_DESTOON') or exit('Access Denied');
class poll {
	var $itemid;
	var $table;
	var $table_item;
	var $table_record;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'poll';
		$this->table_item = DT_PRE.'poll_item';
		$this->table_record = DT_PRE.'poll_record';
		$this->fields = array('typeid','areaid', 'title','style','level','content','groupid','verify','addtime','fromtime','totime','editor','edittime','template_poll','template', 'linkurl','poll_max','poll_page','poll_cols','poll_order','thumb_width','thumb_height');
    }

    function poll() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['typeid']) return $this->_($L['poll_pass_type']);
		if(!$post['title']) return $this->_($L['poll_pass_title']);
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
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
		$post['groupid'] = implode(',', $post['groupid']);
		$post['verify'] = intval($post['verify']);
		$post['poll_max'] = intval($post['poll_max']);
		$post['poll_page'] = intval($post['poll_page']);
		$post['poll_page'] or $post['poll_page'] = 30;
		$post['poll_cols'] = intval($post['poll_cols']);
		$post['poll_cols'] or $post['poll_cols'] = 1;
		$post['thumb_width'] = intval($post['thumb_width']);
		$post['thumb_width'] or $post['thumb_width'] = 120;
		$post['thumb_height'] = intval($post['thumb_height']);
		$post['thumb_height'] or $post['thumb_height'] = 90;
		return array_map("trim", $post);
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$this->itemid");
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
			$r['title'] = set_style($r['title'], $r['style']);
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['fromdate'] = $r['fromtime'] ? timetodate($r['fromtime'], 3) : $L['timeless'];
			$r['todate'] = $r['totime'] ? timetodate($r['totime'], 3) : $L['timeless'];
			$r['typename'] = $TYPE[$r['typeid']]['typename'];
			$r['typeurl'] = $MOD['poll_url'].list_url($r['typeid']);
			$lists[] = $r;
		}
		return $lists;
	}

	function get_list_record($condition = '1', $order = 'rid DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table_record} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table_record} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['polldate'] = timetodate($r['polltime'], 6);
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
		$linkurl = $this->linkurl($this->itemid);
		DB::query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$this->itemid");
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
		$linkurl = $this->linkurl($this->itemid);
		DB::query("UPDATE {$this->table} SET linkurl='$linkurl' WHERE itemid=$this->itemid");
		clear_upload($post['content'], $this->itemid, $this->table);
		return true;
	}

	function linkurl($itemid) {
		global $MOD;
		$linkurl = show_url($itemid);
		return $MOD['poll_url'].$linkurl;
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
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
			DB::query("DELETE FROM {$this->table_item} WHERE pollid=$itemid");
			DB::query("DELETE FROM {$this->table_record} WHERE pollid=$itemid");
		}
	}

	function level($itemid, $level) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET level=$level WHERE itemid IN ($itemids)");
	}

	function item_list($condition, $order = 'listorder DESC,itemid DESC', $num = 0) {
		global $pages, $page, $pagesize, $offset, $pagesize, $items;
		$items = DB::count($this->table_item, $condition);
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table_item} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$lists[] = $r;
		}
		if($num != $items) DB::query("UPDATE {$this->table} SET items=$items WHERE itemid=$this->itemid");
		return $lists;
	}

	function item_all($condition, $order = 'listorder DESC,itemid DESC') {
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table_item} WHERE $condition ORDER BY $order");
		while($r = DB::fetch_array($result)) {
			$lists[$r['itemid']] = $r;
		}
		return $lists;
	}

	function item_update($post) {
		global $_userid;
		$thumb = $post[0]['thumb'];
		$this->item_add($post[0]);
		unset($post[0]);
		foreach($post as $k=>$v) {
			if($v['thumb']) $thumb .= $v['thumb'];
			if(isset($v['delete'])) {
				if($v['thumb']) delete_upload($v['thumb'], $_userid);
				$this->item_delete($k);
				unset($post[$k]);
			}
		}
		if($thumb) clear_upload($thumb, $this->itemid, $this->table);
		$this->item_edit($post);
		return true;
	}

	function item_add($post) {
		$post['title'] = trim($post['title']);
		if(!$post['title']) return false;
		$post['listorder'] = intval($post['listorder']);
		$post['polls'] = intval($post['polls']);
		$post['pollid'] = $this->itemid;		
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			$sqlk .= ','.$k;
			$sqlv .= ",'$v'";
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table_item} ($sqlk) VALUES ($sqlv)");
	}

	function item_edit($post) {
		foreach($post as $k=>$v) {
			$v['title'] = trim($v['title']);
			if(!$v['title']) continue;			
			$sql = '';
			foreach($v as $kk=>$vv) {
				$sql .= ",$kk='$vv'";
			}
			$sql = substr($sql, 1);
			DB::query("UPDATE {$this->table_item} SET $sql WHERE itemid=$k");
		}
	}

	function item_delete($itemid) {
		DB::query("DELETE FROM {$this->table_item} WHERE itemid=$itemid");
		DB::query("DELETE FROM {$this->table_record} WHERE itemid=$itemid");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>