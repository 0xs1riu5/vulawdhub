<?php 
defined('IN_DESTOON') or exit('Access Denied');
class express {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

   function __construct($mid) {
		$this->table = DT_PRE.'mall_express_'.$mid;
		$this->fields = array('parentid','areaid','title','express','fee_start','fee_step','username','addtime','listorder','note');
    }

    function express($mid) {
		$this->__construct($mid);
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['express']) return $this->_($L['pass_express']);
		return true;
	}

	function set($post) {
		global $_username;
		$post['parentid'] = $post['areaid'] = 0;
		$post['fee_start'] = dround($post['fee_start']);
		$post['fee_step'] = dround($post['fee_step']);
		$post['listorder'] = intval($post['listorder']);
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

	function get_list($condition, $order = 'listorder ASC,itemid ASC') {
		global $MOD, $pages, $page, $pagesize, $offset, $items;
		$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();	
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
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

	function delete($itemid, $all = true) {
		global $MOD, $L;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			$this->itemid = $itemid;
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
		}
	}

	function area($post) {
		foreach($post as $k=>$v) {
			$k = intval($k);
			$v['listorder'] = intval($v['listorder']);
			$v['fee_start'] = dround($v['fee_start']);
			$v['fee_step'] = dround($v['fee_step']);
			if($k == 0) {
				$v['areaid'] = intval($v['areaid']);
				if($v['areaid'] && $v['fee_start']) {					
					$T = DB::get_one("SELECT itemid FROM {$this->table} WHERE parentid=$this->itemid AND areaid=$v[areaid]");
					if(!$T) DB::query("INSERT INTO {$this->table} (parentid,areaid,fee_start,fee_step,listorder) VALUES('$this->itemid','$v[areaid]','$v[fee_start]','$v[fee_step]','$v[listorder]')");
				}
			} else {
				$T = DB::get_one("SELECT parentid FROM {$this->table} WHERE itemid=$k");
				if($T['parentid'] == $this->itemid) {
					if(isset($v['delete'])) {
						DB::query("DELETE FROM {$this->table} WHERE itemid=$k");
					} else {
						DB::query("UPDATE {$this->table} SET fee_start='$v[fee_start]',fee_step='$v[fee_step]',listorder='$v[listorder]' WHERE itemid=$k");
					}
				}
			}
		}
		return true;
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>