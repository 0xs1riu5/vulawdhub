<?php 
defined('IN_DESTOON') or exit('Access Denied');
class mall {
	var $moduleid;
	var $itemid;
	var $table;
	var $table_data;
	var $split;
	var $errmsg = errmsg;

    function __construct($moduleid) {
		global $table, $table_data, $MOD;
		$this->moduleid = $moduleid;
		$this->table = $table;
		$this->table_data = $table_data;
		$this->split = $MOD['split'];
		$this->fields = array('catid','mycatid','areaid','level','title','style','fee','introduce','brand','price','step','amount','unit','thumb','thumb1','thumb2','tag','status','hits','username','editor','addtime','adddate','edittime','editdate','ip','template','linkurl','filepath','elite','note','company','truename','telephone','mobile','address','email','qq','wx','ali','skype','n1','n2','n3','v1','v2','v3','express_1','express_name_1','fee_start_1','fee_step_1','express_2','express_name_2','fee_start_2','fee_step_2','express_3','express_name_3','fee_start_3','fee_step_3','cod');
    }

    function mall($moduleid) {
		$this->__construct($moduleid);
    }

	function pass($post) {
		global $MOD;
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_(lang('message->pass_cate'));
		if(strlen($post['title']) < 3) return $this->_(lang('message->pass_title'));
		if(dround($post['step']['p1']) < 0.1) return $this->_(lang('message->pass_mall_price'));
		if(intval($post['amount']) < 1) return $this->_(lang('message->pass_mall_amount'));
		if(!is_url($post['thumb'])) return $this->_(lang('message->pass_thumb'));
		if(!$post['content']) return $this->_(lang('message->pass_content'));
		if(DT_MAX_LEN && strlen(clear_img($post['content'])) > DT_MAX_LEN) $this->_(lang('message->pass_max'));
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		is_url($post['thumb']) or $post['thumb'] = '';
		is_url($post['thumb1']) or $post['thumb1'] = '';
		is_url($post['thumb2']) or $post['thumb2'] = '';
		$post['filepath'] = (isset($post['filepath']) && is_filepath($post['filepath'])) ? file_vname($post['filepath']) : '';
		$post['editor'] = $_username;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['adddate'] = timetodate($post['addtime'], 3);
		$post['edittime'] = DT_TIME;
		$post['editdate'] = timetodate($post['edittime'], 3);
		$post['fee'] = dround($post['fee']);
		$post['step']['a1'] = intval($post['step']['a1']);
		$post['step']['p1'] = dround($post['step']['p1'], 2, 1);
		$post['step']['a2'] = intval($post['step']['a2']);
		$post['step']['p2'] = dround($post['step']['p2'], 2, 1);
		$post['step']['a3'] = intval($post['step']['a3']);
		$post['step']['p3'] = dround($post['step']['p3'], 2, 1);
		$post['price'] = $post['step']['p1'];
		if(($post['step']['a2'] && $post['step']['a2'] <= $post['step']['a1']) || ($post['step']['p2'] && $post['step']['p2'] >= $post['step']['p1'])) $post['step']['a2'] = $post['step']['a3'] = $post['step']['p2'] = $post['step']['p3'] = 0;
		if(($post['step']['a3'] && $post['step']['a3'] <= $post['step']['a2']) || ($post['step']['p3'] && $post['step']['p3'] >= $post['step']['p2']))  $post['step']['a3'] = $post['step']['p3'] = 0;
		$post['step']['is'] = $post['step']['a2'] ? 'Y' : 'N';
		count($post['step'] == 7) or exit;
		$post['amount'] = intval($post['amount']);
		$post['mycatid'] = intval($post['mycatid']);
		$post['elite'] = $post['elite'] ? 1 : 0;
		if(strpos($post['v1'], '|') === false) $post['n1'] = $post['v1'] = '';
		if(strpos($post['v2'], '|') === false) $post['n2'] = $post['v2'] = '';
		if(strpos($post['v3'], '|') === false) $post['n3'] = $post['v3'] = '';
		$post['express_1'] = intval($post['express_1']);
		$post['fee_start_1'] = dround($post['fee_start_1']);
		$post['fee_step_1'] = dround($post['fee_step_1']);
		$post['express_2'] = intval($post['express_2']);
		$post['fee_start_2'] = dround($post['fee_start_2']);
		$post['fee_step_2'] = dround($post['fee_step_2']);
		$post['express_3'] = intval($post['express_3']);
		$post['fee_start_3'] = dround($post['fee_start_3']);
		$post['fee_step_3'] = dround($post['fee_step_3']);
		$post['cod'] = intval($post['cod']);
		$post['content'] = stripslashes($post['content']);
		$post['content'] = save_local($post['content']);
		if($MOD['clear_link']) $post['content'] = clear_link($post['content']);
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		if($this->itemid) {
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'"/>';
			if($post['thumb1']) $new .= '<img src="'.$post['thumb1'].'"/>';
			if($post['thumb2']) $new .= '<img src="'.$post['thumb2'].'"/>';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'"/>';
			if($r['thumb1']) $old .= '<img src="'.$r['thumb1'].'"/>';
			if($r['thumb2']) $old .= '<img src="'.$r['thumb2'].'"/>';
			delete_diff($new, $old);
		} else {
			$post['ip'] = DT_IP;
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
		$post['step'] = serialize($post['step']);
		$post['content'] = addslashes(dsafe($content));
		return array_map("trim", $post);
	}

	function get_one() {
		$r = DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$this->itemid");
		if($r) {
			$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
			$t = DB::get_one("SELECT content FROM {$content_table} WHERE itemid=$this->itemid");
			$r['content'] = $t ? $t['content'] : '';
			return $r;
		} else {
			return array();
		}
	}

	function get_list($condition = 'status=3', $order = 'edittime DESC', $cache = '') {
		global $MOD, $pages, $page, $pagesize, $offset, $items, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
			$items = $r['num'];
		}
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = $catids = $CATS = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = DB::fetch_array($result)) {
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['userurl'] = userurl($r['username']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$catids[$r['catid']] = $r['catid'];
			$lists[] = $r;
		}
		if($catids) {
			$result = DB::query("SELECT catid,catname,linkurl FROM ".DT_PRE."category WHERE catid IN (".implode(',', $catids).")");
			while($r = DB::fetch_array($result)) {
				$CATS[$r['catid']] = $r;
			}
			if($CATS) {
				foreach($lists as $k=>$v) {
					$lists[$k]['catname'] = $v['catid'] ? $CATS[$v['catid']]['catname'] : '';
					$lists[$k]['caturl'] = $v['catid'] ? $MOD['linkurl'].$CATS[$v['catid']]['linkurl'] : '';
				}
			}
		}
		return $lists;
	}

	function add($post) {
		global $MOD;
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = DB::insert_id();
		$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
		DB::query("REPLACE INTO {$content_table} (itemid,content) VALUES ('$this->itemid', '$post[content]')");
		$this->update($this->itemid);
		if($post['status'] == 3 && $post['username'] && $MOD['credit_add']) {
			credit_add($post['username'], $MOD['credit_add']);
			credit_record($post['username'], $MOD['credit_add'], 'system', lang('my->credit_record_add', array($MOD['name'])), 'ID:'.$this->itemid);
		}
		clear_upload($post['content'].$post['thumb'].$post['thumb1'].$post['thumb2'], $this->itemid);
		return $this->itemid;
	}

	function edit($post) {
		$this->delete($this->itemid, false);
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    DB::query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
		$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
		DB::query("REPLACE INTO {$content_table} (itemid,content) VALUES ('$this->itemid', '$post[content]')");
		$this->update($this->itemid);
		clear_upload($post['content'].$post['thumb'].$post['thumb1'].$post['thumb2'], $this->itemid);
		if($post['status'] > 2) $this->tohtml($this->itemid, $post['catid']);
		return true;
	}

	function tohtml($itemid = 0, $catid = 0) {
		global $module, $MOD;
		if($MOD['show_html'] && $itemid) tohtml('show', $module, "itemid=$itemid");
	}

	function update($itemid) {
		$item = DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$itemid");
		$update = '';
		$keyword = $item['title'].','.($item['brand'] ? $item['brand'].',' : '').strip_tags(cat_pos(get_cat($item['catid']), ','));
		if($keyword != $item['keyword']) {
			$keyword = str_replace("//", '', addslashes($keyword));
			$update .= ",keyword='$keyword'";
		}
		$item['itemid'] = $itemid;
		$linkurl = itemurl($item);
		if($linkurl != $item['linkurl']) $update .= ",linkurl='$linkurl'";
		$member = $item['username'] ? userinfo($item['username']) : array();
		if($member) $update .= update_user($member, $item);
		if($update) DB::query("UPDATE {$this->table} SET ".(substr($update, 1))." WHERE itemid=$itemid");
	}

	function recycle($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			$this->delete($itemid, false);
			return true;
		}		
	}

	function restore($itemid) {
		global $module, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->restore($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3 WHERE itemid=$itemid");
			if($MOD['show_html']) tohtml('show', $module, "itemid=$itemid");
			return true;
		}		
	}

	function delete($itemid, $all = true) {
		global $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) {
				$this->delete($v, $all);
			}
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			if($MOD['show_html']) {
				$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$r['linkurl'];
				if(is_file($_file)) unlink($_file);
			}
			if($all) {
				$userid = get_user($r['username']);
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['thumb1']) delete_upload($r['thumb1'], $userid);
				if($r['thumb2']) delete_upload($r['thumb2'], $userid);
				if($r['content']) delete_local($r['content'], $userid);
				DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
				DB::query("DELETE FROM {$content_table} WHERE itemid=$itemid");
				if($MOD['cat_property']) DB::query("DELETE FROM ".DT_PRE."category_value WHERE moduleid=$this->moduleid AND itemid=$itemid");
				if($r['username'] && $MOD['credit_del']) {
					credit_add($r['username'], -$MOD['credit_del']);
					credit_record($r['username'], -$MOD['credit_del'], 'system', lang('my->credit_record_del', array($MOD['name'])), 'ID:'.$this->itemid);
				}
			}
		}
	}

	function check($itemid) {
		global $_username, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v); }
		} else {
			$this->itemid = $itemid;
			$item = $this->get_one();
			if($MOD['credit_add'] && $item['username'] && $item['hits'] < 1) {
				credit_add($item['username'], $MOD['credit_add']);
				credit_record($item['username'], $MOD['credit_add'], 'system', lang('my->credit_record_add', array($MOD['name'])), 'ID:'.$this->itemid);
			}
			$editdate = timetodate(DT_TIME, 3);
			DB::query("UPDATE {$this->table} SET status=3,editor='$_username',edittime=".DT_TIME.",editdate='$editdate' WHERE itemid=$itemid");
			$this->tohtml($itemid);
			return true;
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

	function unsale($itemid) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->unsale($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=4,editor='$_username' WHERE itemid=$itemid");
			return true;
		}
	}

	function onsale($itemid) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->onsale($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3,editor='$_username' WHERE itemid=$itemid");
			return true;
		}
	}

	function clear($condition = 'status=0') {		
		$result = DB::query("SELECT itemid FROM {$this->table} WHERE $condition");
		while($r = DB::fetch_array($result)) {
			$this->delete($r['itemid']);
		}
	}

	function level($itemid, $level) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET level=$level WHERE itemid IN ($itemids)");
	}

	function type($itemid, $typeid) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET typeid=$typeid WHERE itemid IN ($itemids)");
	}

	function refresh($itemid) {
		$editdate = timetodate(DT_TIME, 3);
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET edittime='".DT_TIME."',editdate='$editdate' WHERE itemid IN ($itemids)");
	}

	function relate_add($M, $A, $N) {
		if($M['relate_id']) {
			$itemids = $M['relate_id'];
			if(strpos(','.$M['relate_id'].',', ','.$A['itemid'].',') === false) {
				$itemids = $M['relate_id'].','.$A['itemid'];
			}
		} else {
			$itemids = $M['itemid'].','.$A['itemid'];
		}
		DB::query("UPDATE {$this->table} SET relate_id='$itemids',relate_name='$N' WHERE itemid IN ($itemids)");
	}

	function relate_del($M, $A) {
		if($M['relate_id'] == $A['relate_id']) {
			$ids = explode(',', $M['relate_id']);
			foreach($ids as $k=>$v) {
				if($v == $A['itemid']) unset($ids[$k]);
			}
			DB::query("UPDATE {$this->table} SET relate_id='',relate_name='',relate_title='' WHERE itemid=$A[itemid]");
			$itemids = implode(',', $ids);
			if(is_numeric($itemids)) {
				DB::query("UPDATE {$this->table} SET relate_id='',relate_name='',relate_title='' WHERE itemid=$itemids");
			} else {
				DB::query("UPDATE {$this->table} SET relate_id='$itemids' WHERE itemid IN ($itemids)");
			}
		}
	}

	function relate($M, $P, $N) {
		$S = $I = array();
		foreach($P as $k=>$v) {
			$k = intval($k);
			$S[$k] = intval($v['listorder']);
		}
		asort($S);
		foreach($S as $k=>$v) {
			$I[] = $k;
		}
		$itemids = implode(',', $I);
		foreach($P as $k=>$v) {
			$k = intval($k);
			$T = dhtmlspecialchars(trim($v['relate_title']));
			DB::query("UPDATE {$this->table} SET relate_id='$itemids',relate_name='$N',relate_title='$T' WHERE itemid=$k");
		}
	}

	function relate_list($M) {
		if($M['relate_id']) {
			$ids = $M['relate_id'];
			$lists = $tags = array();
			$result = DB::query("SELECT * FROM {$this->table} WHERE itemid IN ($ids)");
			while($r = DB::fetch_array($result)) {
				$tags[$r['itemid']] = $r;
			}
			foreach(explode(',', $ids) as $v) {
				$lists[] = $tags[$v];
			}
			return $lists;
		} else {
			return array(0 => $M);
		}
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>