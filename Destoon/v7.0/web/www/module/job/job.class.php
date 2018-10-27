<?php 
defined('IN_DESTOON') or exit('Access Denied');
class job {
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
		$this->fields = array('catid','areaid','level','title','style','fee','introduce','department','total','minsalary','maxsalary','type','gender','marriage','education','minage','maxage','experience','status','hits','username','truename','telephone', 'mobile','address','email','qq','wx','ali','skype','sex','totime','editor','addtime','adddate','edittime','editdate','ip','template','linkurl','filepath','note','company');
    }

    function job($moduleid) {
		$this->__construct($moduleid);
    }

	function pass($post) {
		global $MOD;
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_(lang('message->pass_cate'));
		if(strlen($post['title']) < 3) return $this->_(lang('message->pass_job_title'));
		if(!$post['areaid']) return $this->_(lang('message->pass_job_areaid'));
		if(!$post['content']) return $this->_(lang('message->pass_job_content'));
		if($post['totime']) {
			if(!is_time($post['totime'])) return $this->_(lang('message->pass_date'));
			if(strtotime($post['totime']) < DT_TIME) return $this->_(lang('message->pass_todate'));
		}
		if(!$post['truename']) return $this->_(lang('message->pass_truename'));
		if(!$post['telephone']) return $this->_(lang('message->pass_telephone'));
		if(!is_email(trim($post['email']))) return $this->_(lang('message->pass_email'));
		if(DT_MAX_LEN && strlen(clear_img($post['content'])) > DT_MAX_LEN) $this->_(lang('message->pass_max'));
		return true;
	}

	function set($post) {
		global $MOD, $TYPE, $_username, $_userid;
		$post['filepath'] = (isset($post['filepath']) && is_filepath($post['filepath'])) ? file_vname($post['filepath']) : '';
		$post['editor'] = $_username;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['adddate'] = timetodate($post['addtime'], 3);
		$post['edittime'] = DT_TIME;
		$post['editdate'] = timetodate($post['edittime'], 3);
		$post['totime'] = is_time($post['totime']) ? strtotime($post['totime']) : 0;
		$post['fee'] = dround($post['fee']);
		$post['total'] = intval($post['total']);
		$post['minsalary'] = intval($post['minsalary']);
		$post['maxsalary'] = intval($post['maxsalary']);
		$post['type'] = intval($post['type']);
		$post['gender'] = intval($post['gender']);
		$post['education'] = intval($post['education']);
		$post['experience'] = intval($post['experience']);
		$post['minage'] = intval($post['minage']);
		$post['maxage'] = intval($post['maxage']);
		$post['content'] = stripslashes($post['content']);
		$post['content'] = save_local($post['content']);
		if($MOD['clear_link']) $post['content'] = clear_link($post['content']);
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		if($this->itemid) {
			$new = $post['content'];
			$r = $this->get_one();
			$old = $r['content'];
			delete_diff($new, $old);
		} else {
			$post['ip'] = DT_IP;
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
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
		global $MOD, $pages, $page, $pagesize, $offset, $CATEGORY, $items, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
			$items = $r['num'];
		}
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = DB::fetch_array($result)) {
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['userurl'] = userurl($r['username']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$r['parentid'] = $CATEGORY[$r['catid']]['parentid'] ? $CATEGORY[$r['catid']]['parentid'] : $r['catid'];
			$lists[] = $r;
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
		clear_upload($post['content'], $this->itemid);
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
		clear_upload($post['content'], $this->itemid);
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
		$keyword = $item['title'].','.$item['department'].','.strip_tags(cat_pos(get_cat($item['catid']), ',')).strip_tags(area_pos($item['areaid'], ','));
		if($keyword != $item['keyword']) {
			$keyword = str_replace("//", '', addslashes($keyword));
			$update .= ",keyword='$keyword'";
		}
		$item['itemid'] = $itemid;
		$linkurl = itemurl($item);
		if($linkurl != $item['linkurl']) $update .= ",linkurl='$linkurl'";
		$member = $item['username'] ? userinfo($item['username']) : array();
		if($member) {
			foreach(array('groupid','vip','validated','company') as $v) {
				if($item[$v] != $member[$v]) $update .= ",$v='".addslashes($member[$v])."'";
			}
		}
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

	function expire($condition = '') {
		DB::query("UPDATE {$this->table} SET status=4 WHERE status=3 AND totime>0 AND totime<".DT_TIME." $condition");
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

	function refresh($itemid) {
		$editdate = timetodate(DT_TIME, 3);
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET edittime='".DT_TIME."',editdate='$editdate' WHERE itemid IN ($itemids)");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>