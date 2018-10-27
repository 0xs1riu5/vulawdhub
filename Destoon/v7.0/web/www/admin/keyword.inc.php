<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('已启用', '?file='.$file),
    array('待审核', '?file='.$file.'&status=2'),

);
$status = isset($status) ? intval($status) : 3;
$do = new keyword;
switch($action) {
	case 'letter':
		if(!$word) exit('');
		exit(gb2py($word));
	break;
	default:
		if($submit) {
			$do->update($post);
			dmsg('更新成功', '?file='.$file.'&status='.$status);
		} else {
			$sfields = array('按条件', '关键词', '相关词', '拼音');
			$dfields = array('word', 'word', 'keyword', 'letter');
			isset($fields) && isset($dfields[$fields]) or $fields = 0;
			$fields_select = dselect($sfields, 'fields', '', $fields);
			$sorder  = array('结果排序方式', '总搜索量降序', '总搜索量升序', '本月搜索降序', '本月搜索升序', '本周搜索降序', '本周搜索升序', '今日搜索降序', '今日搜索升序', '信息数量降序', '信息数量升序', '更新时间降序', '更新时间升序');
			$dorder  = array('itemid DESC', 'total_search DESC', 'total_search ASC', 'month_search DESC', 'month_search ASC', 'week_search DESC', 'week_search ASC', 'today_search DESC', 'today_search ASC', 'items DESC', 'items ASC', 'updatetime DESC', 'updatetime ASC');
			isset($order) && isset($dorder[$order]) or $order = 0;
			$order_select  = dselect($sorder, 'order', '', $order);
			$condition = "status=$status";
			if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
			if($mid) $condition .= " AND moduleid=$mid";
			$lists = $do->get_list($condition, $dorder[$order]);
			include tpl('keyword');
		}
	break;
}

class keyword {
	var $table;

	function __construct() {
		$this->table = DT_PRE.'keyword';
	}

	function keyword() {
		$this->__construct();
	}

	function get_list($condition, $order) {
		global $pages, $page, $pagesize, $offset, $pagesize;
		$pages = pages(DB::count($this->table, $condition), $page, $pagesize);
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$lists[] = $r;
		}
		return $lists;
	}

	function update($post) {
		$this->add($post[0]);
		unset($post[0]);
		foreach($post as $k=>$v) {
			if(isset($v['delete'])) {
				$this->delete($k);
				unset($post[$k]);
			}
		}
		$this->edit($post);
	}

	function add($post) {
		if(!$post['word']) return false;
		$post['status'] = $post['status'] == 3 ? 3 : 2;
		DB::query("INSERT INTO {$this->table} (moduleid,word,keyword,letter,items,total_search,month_search,week_search,today_search,updatetime,status) VALUES('$post[moduleid]','$post[word]','$post[keyword]','$post[letter]','$post[items]','$post[total_search]','$post[month_search]','$post[week_search]','$post[today_search]','".DT_TIME."', '$post[status]')");
	}

	function edit($post) {
		foreach($post as $k=>$v) {
			if(!$v['word']) continue;
			$v['status'] = $v['status'] == 3 ? 3 : 2;
			DB::query("UPDATE {$this->table} SET word='$v[word]',keyword='$v[keyword]',letter='$v[letter]',total_search='$v[total_search]',month_search='$v[month_search]',week_search='$v[week_search]',today_search='$v[today_search]',status='$v[status]' WHERE itemid='$k'");
		}
	}

	function delete($itemid) {
		DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid");
	}
}
?>