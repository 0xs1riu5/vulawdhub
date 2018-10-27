<?php 
defined('IN_DESTOON') or exit('Access Denied');
class manage {
	var $itemid;
	var $table;
	var $errmsg = errmsg;

    function __construct() {
		global $table_manage;
		$this->table = $table_manage;
    }

    function manage() {
		$this->__construct();
    }

	function get_one() {
        return DB::get_one("SELECT * FROM {$this->table} WHERE itemid='$this->itemid'");
	}

	function get_list($condition, $order = 'itemid DESC') {
		global $MOD, $TYPE, $pages, $page, $pagesize, $offset, $items, $sum, $L, $table_group;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		if($items < 1) return array();	
		$lists = $groupids = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			if($r['typeid'] == 1) {
				$r['linkurl'] = "javascript:alert('".$L['manage_has_del']."');";
			} else {
				$r['linkurl'] = $r['tid'] ? DT_PATH.'api/redirect.php?mid='.$MOD['moduleid'].'&itemid='.$r['tid'] : $MOD['linkurl'].'goto.php?itemid='.$r['rid'];
			}
			$r['value'] = '';
			if($r['typeid'] == 3) {
				$r['value'] = $r['content'] ? $L['manage_level'].$r['content'] : $L['manage_cancel'];
			} else if($r['typeid'] == 4) {
				$r['value'] = $r['content'] ? ($r['content'] == 1 ? $L['manage_ontop_1'] : $L['manage_ontop_2']) : $L['manage_cancel'];
			} else if($r['typeid'] == 5) {
				$r['value'] = $r['content'] ? '<div style="width:10px;height:10px;line-height:10px;background:'.$r['content'].';">&nbsp;</div>' : $L['manage_cancel'];
			}
			$groupids[$r['gid']] = $r['gid'];
			$lists[] = $r;
		}
		if($groupids) {
			$GRPS = array();
			$result = DB::query("SELECT itemid,title,linkurl FROM {$table_group} WHERE itemid IN (".implode(',', $groupids).")");
			while($r = DB::fetch_array($result)) {
				$GRPS[$r['itemid']] = $r;
			}
			if($GRPS) {
				foreach($lists as $k=>$v) {
					$lists[$k]['groupname'] = $v['gid'] ? $GRPS[$v['gid']]['title'] : '';
					$lists[$k]['groupurl'] = $v['gid'] ? $MOD['linkurl'].$GRPS[$v['gid']]['linkurl'] : '';
				}
			}
		}
		return $lists;
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>