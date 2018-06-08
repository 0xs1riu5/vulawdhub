<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class navmodel {

	var $db;
	var $base;

	function navmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function search_nav($start=0, $limit=10, $name, $postion, $starttime, $endtime){
		$sql = "SELECT * FROM ".DB_TABLEPRE."nav WHERE 1=1";
		if($name){
			$name = addslashes($name);
			$sql = $sql . " AND name like '%$name%' ";
		}
		if($postion){
			$sql = $sql . " AND position='$postion' ";
		}
		if($starttime){
			$sql = $sql." AND lastedit>=$starttime ";
		}
		if($endtime){
			$sql = $sql." AND lastedit<=$endtime ";
		}
		$sql = $sql." order by lastedit desc limit $start,$limit ";
		$navlist = array();
		$query = $this->db->query($sql);
		while($nav = $this->db->fetch_array($query)){
			$nav['lastedit'] = $this->base->date($nav['lastedit']);
			$navlist[]=$nav;
		}
		return $navlist;
	}
	
	function search_nav_num($name, $postion, $starttime, $endtime){
		$sql = "SELECT COUNT(*) FROM ".DB_TABLEPRE."nav WHERE 1=1";
		if($name){
			$name = addslashes($name);
			$sql = $sql . " AND name like '%$name%' ";
		}
		if($postion){
			$sql = $sql . " AND position='$postion' ";
		}
		if($starttime){
			$sql = $sql." AND lastedit>=$starttime ";
		}
		if($endtime){
			$sql = $sql." AND lastedit<=$endtime ";
		}
		return $this->db->result_first($sql);
	}
	
	function get_hotdocs($type=0){
		$focuslist=array();
		$sql = " SELECT * FROM ".DB_TABLEPRE."focus WHERE 1=1";
		if($type!=0){
			$sql.=" AND `type`=$type ";
		}
		$sql.="ORDER BY displayorder ASC,time DESC";
		$query = $this->db->query($sql);
		while($focus = $this->db->fetch_array($query)){
			$focuslist[] = $focus['title'];
		}
		return $focuslist;
	}
	
	function get_doc_by_title($title) {
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."doc WHERE title like '%$title%'");
		while($doc=$this->db->fetch_array($query)){
			$doclist[]=$doc['title'];
		}
		return $doclist;
	}
	
	function add($nav){
		foreach($nav as $key => $val) {
			$sqlset .= $pre."`$key`='".addslashes(stripcslashes($val))."'";
			$pre = ',';
		}
		$sql = "INSERT INTO ".DB_TABLEPRE."nav SET $sqlset";
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	function update($nav, $condition){
		foreach($nav as $key => $val) {
			$sqlset .= $pre."`$key`='".addslashes(stripcslashes($val))."'";
			$pre = ',';
		}
		$where = $this->db->cond_handle($condition);
		$sql = "UPDATE ".DB_TABLEPRE."nav SET $sqlset WHERE $where";
		$this->db->query($sql);
	}
		
	function addlink($navid, $docs){
		$this->db->query("DELETE FROM ".DB_TABLEPRE."navlink WHERE navid='$navid'");
		if($docs){
			foreach($docs as $key => $title) {
				$title = trim($title);
				$query = $this->db->query("SELECT did FROM ".DB_TABLEPRE."doc WHERE title='$title'");
				$doc = $this->db->fetch_array($query);
				if($doc){
					$sql = "REPLACE INTO ".DB_TABLEPRE."navlink (`navid`, `did`) values ('".$navid."', '".$doc['did']."')";
					$this->db->query($sql);
				}
			}
		}
	}
	
	function get_by_navname($navname) {
		$query=$this->db->query("SELECT name FROM ".DB_TABLEPRE."nav WHERE name='$navname'");
		return $this->db->fetch_array($query);
	}
	
	function get_by_id($navid) {
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."nav WHERE id='$navid'");
		return $this->db->fetch_array($query);
	}
	
	function del($navid) {
		$this->db->query("DELETE FROM ".DB_TABLEPRE."nav WHERE id='$navid'");
		$this->db->query("DELETE FROM ".DB_TABLEPRE."navlink WHERE navid='$navid'");
	}
	
	function get_nav_docs($navid) {
		$query = $this->db->query("SELECT title FROM ".DB_TABLEPRE."navlink n INNER JOIN ".DB_TABLEPRE."doc d ON n.did=d.did WHERE navid='$navid'");
		while($doc = $this->db->fetch_array($query)){
			$doclist[]=$doc['title'];
		}
		return $doclist;
	}
	
	function get_catedoc($cidstr){
		$query = $this->db->query("SELECT d.* FROM ".DB_TABLEPRE."categorylink c INNER JOIN ".DB_TABLEPRE."doc d ON c.did=d.did WHERE c.cid IN ($cidstr)");
		while($doc = $this->db->fetch_array($query)){
			$doclist[]=$doc['title'];
		}
		return $doclist;	
	}
}