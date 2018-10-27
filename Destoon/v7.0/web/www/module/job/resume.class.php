<?php 
defined('IN_DESTOON') or exit('Access Denied');
class resume {
	var $moduleid;
	var $itemid;
	var $table;
	var $table_data;
	var $errmsg = errmsg;

    function __construct($moduleid) {
		global $MOD, $table_resume, $table_resume_data;
		$this->moduleid = $moduleid;
		$this->table = $table_resume;
		$this->table_data = $table_resume_data;
		$this->fields = array('catid','areaid','level','title','style','fee','introduce','truename','gender','birthday','age','marriage','height', 'weight','education','school','major','skill','language','minsalary','maxsalary','situation','type','experience','mobile','telephone','address','email','qq','wx','ali','skype','thumb','username','addtime','editor','edittime','ip','template','status','hits','open','note');
    }

    function resume($moduleid) {
		$this->__construct($moduleid);
    }

	function pass($post) {
		global $MOD;
		if(!is_array($post)) return false;
		if(!$post['title']) return $this->_(lang('message->pass_resume_title'));
		if(!$post['catid']) return $this->_(lang('message->pass_resume_catid'));
		if(strlen($post['truename']) < 3) return $this->_(lang('message->pass_resume_truename'));
		if(!$post['areaid']) return $this->_(lang('message->pass_resume_areaid'));
		if(intval($post['byear']) > 9999 || intval($post['byear']) < 1900 || date('Y', DT_TIME) - intval($post['byear']) > 100) return $this->_(lang('message->pass_resume_byear'));
		if(!$post['school']) return $this->_(lang('message->pass_resume_school'));
		if(strlen($post['mobile']) < 7) return $this->_(lang('message->pass_resume_mobile'));
		if(!is_email(trim($post['email']))) return $this->_(lang('message->pass_email'));
		if(!$post['content']) return $this->_(lang('message->pass_resume_content'));
		if(DT_MAX_LEN && strlen(clear_img($post['content'])) > DT_MAX_LEN) $this->_(lang('message->pass_max'));
		return true;
	}

	function set($post) {
		global $MOD, $TYPE, $_username, $_userid, $GENDER, $MARRIAGE, $EDUCATION;
		is_url($post['thumb']) or $post['thumb'] = '';
		$post['editor'] = $_username;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		$post['fee'] = dround($post['fee']);
		$post['birthday'] = intval($post['byear']).'-'.intval($post['bmonth']).'-'.intval($post['bday']);
		$post['age'] = date('Y', DT_TIME) - intval($post['byear']);
		$post['minsalary'] = intval($post['minsalary']);
		$post['maxsalary'] = intval($post['maxsalary']);
		$post['type'] = intval($post['type']);
		$post['marriage'] = intval($post['marriage']);
		$post['height'] = intval($post['height']);
		$post['height'] = intval($post['height']);
		$post['gender'] = intval($post['gender']);
		$post['education'] = intval($post['education']);
		$post['experience'] = intval($post['experience']);
		$post['situation'] = intval($post['situation']);
		$post['status'] = intval($post['status']);
		$post['open'] = intval($post['open']);
		$post['content'] = stripslashes($post['content']);
		$post['content'] = save_local($post['content']);
		if($MOD['clear_link']) $post['content'] = clear_link($post['content']);
		if($MOD['save_remotepic']) $post['content'] = save_remote($post['content']);
		if($MOD['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $MOD['introduce_length']));
		if($this->itemid) {
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'"/>';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'"/>';
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
			$t = DB::get_one("SELECT content FROM {$this->table_data} WHERE itemid=$this->itemid");
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
		DB::query("INSERT INTO {$this->table_data} (itemid,content) VALUES ('$this->itemid', '$post[content]')");
		$this->update($this->itemid);
		if($post['status'] == 3 && $post['username'] && $MOD['credit_add_resume']) {
			credit_add($post['username'], $MOD['credit_add_resume']);
			credit_record($post['username'], $MOD['credit_add_resume'], 'system', lang('my->credit_record_resume_add'), 'ID:'.$this->itemid);
		}
		clear_upload($post['content'].$post['thumb'], $this->itemid, $this->table);
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
	    DB::query("UPDATE {$this->table_data} SET content='$post[content]' WHERE itemid=$this->itemid");
		$this->update($this->itemid);
		clear_upload($post['content'].$post['thumb'], $this->itemid, $this->table);
		return true;
	}

	function update($itemid) {
		global $GENDER, $MARRIAGE, $EDUCATION;
		$item = DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$itemid");
		$update = '';
		$keyword = $item['title'].','.$item['truename'].','.$item['major'].','.strip_tags(cat_pos(get_cat($item['catid']), ',')).strip_tags(area_pos($item['areaid'], ',')).','.$item['skill'].','.$item['language'].','.$item['school'].','.$GENDER[$item['gender']].','.$MARRIAGE[$item['marriage']].','.$EDUCATION[$item['education']];
		if($keyword != $item['keyword']) {
			$keyword = str_replace("//", '', addslashes($keyword));
			$update .= ",keyword='$keyword'";
		}
		$linkurl = rewrite('resume.php?itemid='.$itemid);
		if($linkurl != $item['linkurl']) $update .= ",linkurl='$linkurl'";
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
			if($all) {
				$userid = get_user($r['username']);
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['content']) delete_local($r['content'], $userid);
				DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				DB::query("DELETE FROM {$this->table_data} WHERE itemid=$itemid");
				if($r['username'] && $MOD['credit_del_resume']) {
					credit_add($r['username'], -$MOD['credit_del_resume']);
					credit_record($r['username'], -$MOD['credit_del_resume'], 'system', lang('my->credit_record_resume_del'), 'ID:'.$this->itemid);
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
				credit_record($item['username'], $MOD['credit_add'], 'system', lang('my->credit_record_resume_add'), 'ID:'.$this->itemid);
			}
			DB::query("UPDATE {$this->table} SET status=3,editor='$_username',edittime=".DT_TIME." WHERE itemid=$itemid");
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
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		DB::query("UPDATE {$this->table} SET edittime='".DT_TIME."' WHERE itemid IN ($itemids)");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>