<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array(
    array('问题验证', '?file='.$file),
);
$do = new question;
if($submit) {
	$do->update($post);
	dmsg('更新成功', '?file='.$file);
} else {
	$condition = "1";
	if($kw) $condition .= " AND (question LIKE '%$keyword%' OR answer LIKE '%$keyword%')";
	$lists = $do->get_list($condition);
	include tpl('question');
}

class question {
	var $table;

	function __construct() {
		$this->table = DT_PRE.'question';
	}

	function question() {
		$this->__construct();
	}

	function get_list($condition) {
		global $pages, $page, $pagesize, $offset, $pagesize;
		$pages = pages(DB::count($this->table, $condition), $page, $pagesize);
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE $condition ORDER BY qid DESC LIMIT $offset,$pagesize");
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
		return true;
	}

	function add($post) {
		if(!$post['question'] || !$post['answer']) return false;
		$Q = explode("\n", $post['question']);
		$A = explode("\n", $post['answer']);
		foreach($Q as $k=>$q) {
			$q = trim($q);
			if($q) {
				$a = isset($A[$k]) ? trim($A[$k]) : '';
				if($q && $a) DB::query("INSERT INTO {$this->table} (question,answer) VALUES('$q','$a')");
			}
		}
	}

	function edit($post) {
		foreach($post as $k=>$v) {
			if(!$v['question'] || !$v['answer']) continue;
			DB::query("UPDATE {$this->table} SET question='$v[question]',answer='$v[answer]' WHERE qid='$k'");
		}
	}

	function delete($qid) {
		DB::query("DELETE FROM {$this->table} WHERE qid=$qid");
	}
}
?>