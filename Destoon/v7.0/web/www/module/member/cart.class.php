<?php 
defined('IN_DESTOON') or exit('Access Denied');
class cart {
	var $table;
	var $userid;
	var $max;

    function __construct() {
		global $_userid;
		$this->userid = $_userid;
		$this->table = DT_PRE.'cart';
    }

    function cart() {
		$this->__construct();
    }

	function set($cart) {
		set_cookie('cart', count($cart), DT_TIME + 30*86400);
		$data = addslashes(serialize($cart));
		DB::query("REPLACE INTO {$this->table} (userid,data,edittime) VALUES ('$this->userid', '$data', '".DT_TIME."')");
	}

	function add($cart, $mid, $itemid, $s1, $s2, $s3, $a) {
		global $_username;
		if(is_array($itemid) && count($itemid) == 1) {
			$id = $itemid[0];
			$itemid = $id;
		}
		$id = 0;
		if(is_array($itemid)) {
			$tags = array();
			$itemids = implode(',', $itemid);
			$result = DB::query("SELECT itemid,username,status FROM ".get_table($mid)." WHERE itemid IN ($itemids)");
			while($r = DB::fetch_array($result)) {		
				$tags[$r['itemid']] = $r;
			}
			foreach($itemid as $v) {
				if(!isset($tags[$v])) continue;
				if($tags[$v]['status'] != 3) continue;
				if($tags[$v]['username'] == $_username) continue;
				$k = $mid.'-'.$v.'-0-0-0';
				if(isset($cart[$k])) {
					$cart[$k] = $cart[$k] + 1;
				} else {
					$cart[$k] = 1;
				}
				$id = $v;
			}
			if($id == 0) return -3;
		} else {
			$r = DB::get_one("SELECT username,status FROM ".get_table($mid)." WHERE itemid=$itemid");
			if(!$r) return -1;
			if($r['status'] != 3) return -1;
			if($r['username'] == $_username) return -4;
			$k = $mid.'-'.$itemid.'-'.$s1.'-'.$s2.'-'.$s3;
			if(isset($cart[$k])) {
				$cart[$k] = $cart[$k] + $a;
			} else {
				$cart[$k] = $a;
			}
			$id = $itemid;
		}
		$max = $this->max > 1 ? $this->max : 30;
		while(count($cart) > $max) {
			$cart = array_shift($cart);
		}
		$this->set($cart);
		return $id;
	}

	function get() {
		$r = DB::get_one("SELECT data FROM {$this->table} WHERE userid=$this->userid");
		return ($r && $r['data']) ? unserialize($r['data']) : array();
	}

	function clear() {
		set_cookie('cart', '0', DT_TIME + 30*86400);
		DB::query("DELETE FROM {$this->table} WHERE userid=$this->userid");
	}

	function get_list($cart) {
		global $MODULE, $_username;
		$lists = $tags = $ids = $data = $_cart = array();
		foreach($cart as $k=>$v) {
			$t = array_map('intval', explode('-', $k));
			$mid = $t[0];
			$ids[$mid] = isset($ids[$mid]) ? $ids[$mid].','.$t[1] : $t[1];
			$r = array();
			$r['itemid'] = $t[1];
			$r['s1'] = $t[2];
			$r['s2'] = $t[3];
			$r['s3'] = $t[4];
			$r['a'] = $v;
			$r['mid'] = $mid;
			$data[$k] = $r;
		}
		if($ids) {
			foreach($ids as $_mid=>$itemids) {
				$result = DB::query("SELECT * FROM ".get_table($_mid)." WHERE itemid IN ($itemids)");
				while($r = DB::fetch_array($result)) {
					if($r['username'] == $_username || $r['status'] != 3 || $r['price'] < 0.01 || $r['amount'] < 1) continue;
					$r['mid'] = $_mid;
					$r['alt'] = $r['title'];
					$r['title'] = dsubstr($r['title'], 40, '..');
					$r['mobile'] = $MODULE[$_mid]['mobile'].$r['linkurl'];
					$r['linkurl'] = $MODULE[$_mid]['linkurl'].$r['linkurl'];
					$r['P1'] = get_nv($r['n1'], $r['v1']);
					$r['P2'] = get_nv($r['n2'], $r['v2']);
					$r['P3'] = get_nv($r['n3'], $r['v3']);
					if($MODULE[$_mid]['module'] == 'sell') $r['step'] = '';
					if($r['step']) {
						$s = unserialize($r['step']);
						foreach(unserialize($r['step']) as $k=>$v) {
							$r[$k] = $v;
						}
					} else {
						$r['a1'] = 1;
						$r['p1'] = $r['price'];
						$r['a2'] = $r['a3'] = 0;
						$r['p2'] = $r['p3'] = 0.00;
					}
					$tags[$r['mid'].'-'.$r['itemid']] = $r;
				}
			}
			if($tags) {
				foreach($data as $k=>$v) {
					if(isset($tags[$v['mid'].'-'.$v['itemid']])) {
						$r = $tags[$v['mid'].'-'.$v['itemid']];
						$r['key'] = $k;
						$r['s1'] = $v['s1'];
						$r['s2'] = $v['s2'];
						$r['s3'] = $v['s3'];
						$r['a'] = $v['a'];
						if($r['a'] > $r['amount']) $r['a'] = $r['amount'];
						if($r['a'] < $r['a1']) $r['a'] = $r['a1'];
						$r['price'] = get_price($r['a'], $r['price'], $r['step']);
						$r['m1'] = isset($r['P1'][$r['s1']]) ? $r['P1'][$r['s1']] : '';
						$r['m2'] = isset($r['P2'][$r['s2']]) ? $r['P2'][$r['s2']] : '';
						$r['m3'] = isset($r['P3'][$r['s3']]) ? $r['P3'][$r['s3']] : '';
						$_cart[$k] = $r['a'];
						$lists[$r['username']][] = $r;
					}
				}
			}
		}
		if(count($_cart) != count($cart) || count($_cart) != get_cookie('cart')) $this->set($_cart);
		return $lists;
	}
}
?>