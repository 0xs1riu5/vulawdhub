<?php 
defined('IN_DESTOON') or exit('Access Denied');
class style {
	var $itemid;
	var $table;
	var $fields;
	var $errmsg = errmsg;

    function __construct() {
		$this->table = DT_PRE.'style';
		$this->fields = array('typeid','title','skin','template','author','groupid','fee','currency','hits', 'addtime','editor','edittime');
    }

    function style() {
		$this->__construct();
    }

	function pass($post) {
		global $CFG, $MODULE, $L;
		if(!is_array($post)) return false;
		if(!$post['title']) return $this->_($L['style_pass_title']);
		if(!$post['skin']) return $this->_($L['style_pass_skin']);
		if(!preg_match("/^[a-z0-9\-_]+$/i", $post['skin'])) return $this->_($L['style_pass_skin_match']);
		if(!is_file(DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/'.$post['skin'].'/style.css')) return $this->_($L['style_pass_css']);
		if(!$post['template']) return $this->_($L['style_pass_template']);
		if(!preg_match("/^[a-z0-9\-_]+$/i", $post['template'])) return $this->_($L['style_pass_template_match']);
		if(!is_file(DT_ROOT.'/template/'.$CFG['template'].'/'.$post['template'].'/side_search.htm')) return $this->_($L['style_pass_dir']);
		if(!isset($post['groupid'])) return $this->_($L['style_pass_groupid']);
		return true;
	}

	function set($post) {
		global $MOD, $_username, $_userid;
		$post['addtime'] = (isset($post['addtime']) && is_time($post['addtime'])) ? strtotime($post['addtime']) : DT_TIME;
		$post['edittime'] = DT_TIME;
		$post['editor'] = $_username;		
		$post['groupid'] = (isset($post['groupid']) && $post['groupid']) ? ','.implode(',', $post['groupid']).',' : '';
		$post['fee'] = dround($post['fee']);
		$post = dhtmlspecialchars($post);
		return array_map("trim", $post);
	}

	function get_one($condition = '') {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid' $condition");
	}

	function get_list($condition = '1', $order = 'listorder DESC, itemid DESC') {
		global $MODULE, $MOD, $pages, $page, $pagesize, $offset, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();
		$GROUP = cache_read('group.php');
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['thumb'] = is_file(DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/'.$r['skin'].'/thumb.gif') ? $MODULE[4]['linkurl'].'skin/'.$r['skin'].'/thumb.gif' : $MODULE[4]['linkurl'].'image/nothumb.gif';
			$groupid = explode(',', substr($r['groupid'], 1, -1));
			$group = array();
			foreach($groupid as $gid) {
				$group[] = $GROUP[$gid]['groupname'];
			}
			$r['group'] = implode('<br/>', $group);
			$lists[] = $r;
		}
		return $lists;
	}

	function add($post) {
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		DB::query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
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
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->delete($v); }
		} else {
			$r = DB::get_one("SELECT * FROM {$this->table} WHERE itemid=$itemid");
			DB::query("UPDATE ".DT_PRE."company SET skin='',template='' WHERE skin='".$r['skin']."' AND template='".$r['template']."'");
			DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
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