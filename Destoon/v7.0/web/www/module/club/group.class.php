<?php 
defined('IN_DESTOON') or exit('Access Denied');
class group {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		global $table_group;
		$this->table = $table_group;
		$this->fields = array('catid','areaid','title','level','style','thumb','filepath','content','template','show_template','join_type','list_type','show_type','post_type','reply_type','status','manager','reason','username','addtime','editor','edittime');
    }

    function group() {
		$this->__construct();
    }

	function pass($post) {
		global $L;
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_(lang('message->pass_catid'));
		if(!$post['title']) return $this->_($L['group_pass_title']);
		if(!is_url($post['thumb'])) return $this->_($L['group_pass_thumb']);
		if(!check_name($post['username'])) return $this->_($L['group_pass_username']);
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		if($this->itemid) {
			$post['editor'] = $_username;
			$new = '';
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'"/>';
			$r = $this->get_one();
			$old = '';
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'"/>';
			delete_diff($new, $old);
		} else {
			$post['ip'] = DT_IP;
		}
		$post['join_type'] = $post['join_type'] ? 1 : 0;
		$post['list_type'] = $post['list_type'] ? 1 : 0;
		$post['show_type'] = $post['show_type'] ? 1 : 0;
		$post['post_type'] = $post['post_type'] ? 1 : 0;
		$post['reply_type'] = $post['reply_type'] ? 1 : 0;
		foreach(array('group_list',  'group_show', 'group_post', 'group_reply') as $v) {
			$post[$v] = isset($post[$v]) ? implode(',', $post[$v]) : '';
		}
		$post = dhtmlspecialchars($post);
		return array_map("trim", $post);
	}

	function get_one($condition = '') {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid' $condition");
	}

	function get_list($condition = '1', $order = 'addtime DESC') {
		global $MOD, $pages, $page, $pagesize, $offset, $items, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$lists = $catids = $CATS = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
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
		$t = get_cat($this->itemid);
		if($t) {
			$t = DB::get_one("SELECT MAX(catid) AS id FROM ".DT_PRE."category");
			$itemid = intval($t['id'] + 1);
			DB::query("UPDATE {$this->table} SET itemid=$itemid WHERE itemid=$this->itemid");
			$maxid = $itemid + 100;
			DB::query("INSERT ".DT_PRE."category (catid) VALUES ($maxid)");
			DB::query("DELETE FROM ".DT_PRE."category WHERE catid=$maxid");
			$this->itemid = $itemid;
		}
		$this->update($this->itemid);
		clear_upload($post['thumb'], $this->itemid, $this->table);
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
		$this->update($this->itemid);
		clear_upload($post['thumb'], $this->itemid, $this->table);
		return true;
	}

	function tohtml($itemid = 0) {
		global $module, $MOD;
		if($MOD['list_html'] && $itemid) tohtml('group', $module, "itemid=$itemid");
	}

	function update($itemid) {
		global $DT;
		$item = DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$itemid");
		$update = '';
		if(!$item['filepath']) {
			$item['filepath'] = $itemid;
			$update .= ",filepath='$item[filepath]'";
		}
		if($item['username']) {
			$passport = addslashes(get_user($item['username'], 'username', 'passport'));
			if($passport != $item['passport']) $update .= ",passport='$passport'";
		}
		$linkurl = listurl(array('catid' => $item['itemid'], 'catdir' => $item['filepath'], 'catname' => $item['title']));
		if($DT['index']) $linkurl = str_replace($DT['index'].'.'.$DT['file_ext'], '', $linkurl);
		if($linkurl != $item['linkurl']) $update .= ",linkurl='$linkurl'";
		if($update) DB::query("UPDATE {$this->table} SET ".(substr($update, 1))." WHERE itemid=$itemid");
		$this->tohtml($itemid);
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
			$this->tohtml($itemid);
			return true;
		}		
	}

	function delete($itemid, $all = true) {
		global $MOD, $table, $table_fans;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v, $all); }
		} else {
			$r = $this->get_one();
			if($MOD['list_html']) {
				$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$r['linkurl'].$DT['index'].'.'.$DT['file_ext'];
				if(is_file($_file)) unlink($_file);
				$i = 1;
				while($i) {
					$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl(array('catid' => $r['itemid'], 'catdir' => $r['filepath'], 'catname' => $r['title']), $i);
					if(is_file($_file)) {
						unlink($_file);
						$i++;
					} else {
						break;
					}
				}
			}
			if($all) {
				$userid = get_user($r['username']);
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				DB::query("DELETE FROM {$table_fans} WHERE gid=$itemid");
				DB::query("UPDATE {$table} SET status=0 WHERE gid=$itemid");
			}
		}
	}

	function check($itemid) {
		global $_username;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v); }
		} else {
			DB::query("UPDATE {$this->table} SET status=3,editor='$_username',edittime=".DT_TIME." WHERE itemid=$itemid");
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

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>