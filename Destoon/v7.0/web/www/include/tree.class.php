<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class tree {
	var $arr;
	var $icon;
	var $ret;

	function __construct($arr = array()) {
		$this->arr = $arr;
		$this->icon = array('&#9474;', '&#9500;', '&#9492;');
		$this->ret = '';
		return is_array($arr);
	}

	function tree($arr = array()) {
		$this->__construct($arr);
	}

	function get_parent($myid) {
		$newarr = array();
		if(!isset($this->arr[$myid])) return false;
		$pid = $this->arr[$myid]['parentid'];
		$pid = $this->arr[$pid]['parentid'];
		if(is_array($this->arr)) {
			foreach($this->arr as $id => $a) {
				if($a['parentid'] == $pid) $newarr[$id] = $a;
			}
		}
		return $newarr;
	}

	function get_child($myid) {
		$a = $newarr = array();
		if(is_array($this->arr)) {
			foreach($this->arr as $id => $a) {
				if($a['parentid'] == $myid) $newarr[$id] = $a;
			}
		}
		return $newarr ? $newarr : false;
	}

	function get_pos($myid, &$newarr) {
		$a = array();
		if(!isset($this->arr[$myid])) return false;
        $newarr[] = $this->arr[$myid];
		$pid = $this->arr[$myid]['parentid'];
		if(isset($this->arr[$pid])) $this->get_pos($pid,$newarr);
		if(is_array($newarr)) {
			krsort($newarr);
			foreach($newarr as $v) {
				$a[$v['id']] = $v;
			}
		}
		return $a;
	}

	function get_tree($myid, $str, $sid = 0, $adds = '') {
		$number=1;
		$child = $this->get_child($myid);
		if(is_array($child)) {
		    $total = count($child);
			foreach($child as $id=>$a) {
				$j = $k = '';
				if($number == $total) {
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';
				$selected = $id == $sid ? 'selected' : '';
				extract($a);
				eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$this->get_tree($id, $str, $sid, $adds.$k.'&nbsp;');
				$number++;
			}
		}
		return $this->ret;
	}
}
?>