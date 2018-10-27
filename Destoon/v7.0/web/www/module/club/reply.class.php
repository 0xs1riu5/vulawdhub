<?php 
defined('IN_DESTOON') or exit('Access Denied');
class reply {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		global $table_reply;
		$this->table = $table_reply;
		$this->fields = array('gid','tid','content','username','passport','addtime','editor','edittime','ip','status');
    }

    function reply() {
		$this->__construct();
    }

	function pass($post) {
		if(!is_array($post)) return false;
		if(!$post['content']) return $this->_(lang('message->pass_club_reply'));
		return true;
	}

	function set($post) {
		global $_username;
		$post['status'] = $post['status'] == 3 ? 3 : 2;
		if($this->itemid) {
			$post['edittime'] = DT_TIME;
			$post['editor'] = $_username;
			$new = $post['content'];
			$r = $this->get_one();
			$old = $r['content'];
			delete_diff($new, $old);
		} else {
			$post['addtime'] = DT_TIME;
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
		$post['content'] = dsafe($content);
		$post['content'] = addslashes(save_remote(save_local(stripslashes($post['content']))));
		return array_map("trim", $post);
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
	}

	function get_list($condition = 'status=3', $order = 'itemid DESC') {
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
			$r['adddate'] = timetodate($r['addtime'], 5);
			if(strpos($r['content'], '<hr class="club_break" />') !== false) $r['content'] = substr($r['content'], strpos($r['content'], '<hr class="club_break" />'));
			$r['title'] = get_intro($r['content'], 50);
			$r['alt'] = get_intro($r['content'], 500);
			$r['linkurl'] = $MOD['linkurl'].'goto.php?itemid='.$r['itemid'];
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		global $MOD, $table, $_username, $_passport;
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = DB::insert_id();
		DB::query("UPDATE {$table} SET replyuser='$_username',replyer='$_passport',replytime=".DT_TIME."".($post['status'] == 3 ? ',reply=reply+1' : '')." WHERE itemid=$post[tid]");
		if($post['status'] == 3 && $post['username'] && $MOD['credit_reply']) {
			credit_add($post['username'], $MOD['credit_reply']);
			credit_record($post['username'], $MOD['credit_reply'], 'system', lang('my->credit_record_reply_add'), 'ID:'.$this->itemid);
		}
		clear_upload($post['content'], $this->itemid, $this->table);
		$this->tohtml($post['tid']);
		return $this->itemid;
	}

	function edit($post) {
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			$sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
		clear_upload($post['content'], $this->itemid, $this->table);
		$this->tohtml($post['tid']);
		return true;
	}

	function tohtml($itemid = 0) {
		global $module, $MOD;
		if($MOD['show_html'] && $itemid) tohtml('show', $module, "itemid=$itemid");
	}

	function recycle($itemid) {
		global $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			if($MOD['show_html']) {
				$this->itemid = $itemid;
				$item = $this->get_one();
				$this->tohtml($item['tid']);
			}
			return true;
		}		
	}

	function restore($itemid) {
		global $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->restore($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3 WHERE itemid=$itemid");
			if($MOD['show_html']) {
				$this->itemid = $itemid;
				$item = $this->get_one();
				$this->tohtml($item['tid']);
			}
			return true;
		}		
	}

	function delete($itemid) {
		global $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->delete($v); 
			}
		} else {
			$this->itemid = $itemid;
			$item = $this->get_one();
			if($item) {
				DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				if($item['content']) delete_local($item['content'], get_user($item['username']));
				if($item['username'] && $MOD['credit_del_reply']) {
					credit_add($item['username'], -$MOD['credit_del_reply']);
					credit_record($item['username'], -$MOD['credit_del_reply'], 'system', lang('my->credit_record_reply_del'), 'ID:'.$itemid);
				}
				$this->tohtml($item['tid']);
			}
		}
	}

	function check($itemid, $status = 3) {
		global $_username, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->check($v, $status); 
			}
		} else {
			$this->itemid = $itemid;
			$item = $this->get_one();
			if($MOD['credit_reply'] && $status == 3) {
				if($item['username'] && $item['addtime'] >= $item['edittime']) {
					credit_add($item['username'], $MOD['credit_reply']);
					credit_record($item['username'], $MOD['credit_reply'], 'system', lang('my->credit_record_reply_add'), 'ID:'.$itemid);
				}
			}
			DB::query("UPDATE {$this->table} SET status=$status,editor='$_username',edittime=".DT_TIME." WHERE itemid=$itemid");
			$this->tohtml($item['tid']);
		}
	}

	function reject($itemid) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->reject($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=1,editor='$_username' WHERE itemid=$itemid");
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