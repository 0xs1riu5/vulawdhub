<?php 
defined('IN_DESTOON') or exit('Access Denied');
class honor {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'honor';
		$this->fields = array('title','style','content', 'authority','thumb','status','username','addtime','editor','edittime','fromtime','totime','note');
    }

    function honor() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['title']) return $this->_($L['honor_pass_title']);
		if(!$post['authority']) return $this->_($L['honor_pass_authority']);
		if(!$post['thumb']) return $this->_($L['honor_pass_thumb']);
		if(!$post['fromtime'] || !is_date($post['fromtime'])) return $this->_($L['honor_pass_fromdate']);
		if(datetotime($post['fromtime'].' 00:00:00') > DT_TIME) return $this->_($L['honor_pass_fromdate_error']);
		if($post['totime']) {
			if(!is_date($post['totime'])) return $this->_($L['honor_pass_todate']);
			if(datetotime($post['totime'].' 23:59:59') < DT_TIME) return $this->_($L['honor_pass_todate_error']);
		}
		if(DT_MAX_LEN && strlen(clear_img($post['content'])) > DT_MAX_LEN) $this->_(lang('message->pass_max'));
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? datetotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		$post['fromtime'] = datetotime($post['fromtime'].' 00:00:00');
		$post['totime'] = $post['totime'] ? datetotime($post['totime'].' 23:59:59') : 0;
		if($this->itemid) {
			$post['editor'] = $_username;
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'">';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'">';
			delete_diff($new, $old);
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
		$post['content'] = dsafe($content);
		if($MOD['credit_clear'] || $MOD['credit_save']) {
			$post['content'] = stripslashes($post['content']);
			$post['content'] = save_local($post['content']);
			if($MOD['credit_clear']) $post['content'] = clear_link($post['content']);
			if($MOD['credit_save']) $post['content'] = save_remote($post['content']);
			$post['content'] = addslashes($post['content']);
		}
		return array_map("trim", $post);
	}

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
	}

	function get_list($condition = 'status=3', $order = 'addtime DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $L, $items, $sum;
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
			$r['fromdate'] = timetodate($r['fromtime'], 3);
			$r['todate'] = $r['totime'] ? timetodate($r['totime'], 3) : $L['forever'];
			$r['image'] = str_replace('.thumb.'.file_ext($r['thumb']), '', $r['thumb']);
			$r['title'] = set_style($r['title'], $r['style']);
			$r['linkurl'] = userurl($r['username'], 'file=honor&itemid='.$r['itemid']);
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
		if($post['username'] && $MOD['credit_add_credit']) {
			credit_add($post['username'], $MOD['credit_add_credit']);
			credit_record($post['username'], $MOD['credit_add_credit'], 'system', $L['honor_reward_reason'], 'ID:'.$this->itemid);
		}
		clear_upload($post['content'].$post['thumb'], $this->itemid, $this->table);
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
		clear_upload($post['content'].$post['thumb'], $this->itemid, $this->table);
		return true;
	}

	function recycle($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			return true;
		}		
	}

	function restore($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->restore($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3 WHERE itemid=$itemid");
			return true;
		}		
	}

	function delete($itemid, $all = true) {
		global $MOD, $L;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			$userid = get_user($r['username']);
			if($r['thumb']) delete_upload($r['thumb'], $userid);
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
			if($r['username'] && $MOD['credit_del_credit']) {
				credit_add($r['username'], -$MOD['credit_del_credit']);
				credit_record($r['username'], -$MOD['credit_del_credit'], 'system', $L['honor_punish_reason'], 'ID:'.$this->itemid);
			}
		}
	}

	function check($itemid) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3,editor='$_username',edittime=".DT_TIME." WHERE itemid=$itemid");
			return true;
		}
	}

	function reject($itemid) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->reject($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=1,editor='$_username',edittime=".DT_TIME." WHERE itemid=$itemid");
			return true;
		}
	}

	function expire($condition = '') {
		DB::query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime>0 AND totime<".DT_TIME." $condition");
	}

	function clear() {		
		$result = DB::query("SELECT itemid FROM {$this->table} WHERE status=0");
		while($r = DB::fetch_array($result)) {
			$this->delete($r['itemid']);
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

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>