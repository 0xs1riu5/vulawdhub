<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class navmodelmodel {

	var $db;
	var $base;

	function navmodelmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function add($nav){
		foreach($nav as $key => $val) {
			$sqlset .= $pre."`$key`='".addslashes(stripcslashes($val))."'";
			$pre = ',';
		}
		$sql = "INSERT INTO ".DB_TABLEPRE."navmodel SET $sqlset";
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	function update($nav, $condition){
		foreach($nav as $key => $val) {
			$sqlset .= $pre."`$key`='".addslashes(stripcslashes($val))."'";
			$pre = ',';
		}
		$where = $this->db->cond_handle($condition);
		$sql = "UPDATE ".DB_TABLEPRE."navmodel SET $sqlset WHERE $where";
		$this->db->query($sql);
	}
	
	function get_all($condition = array()) {
		$navmodellist=array();
		$where = $this->db->cond_handle($condition);
		$sql = "SELECT * FROM ".DB_TABLEPRE."navmodel WHERE $where";
		$query = $this->db->query($sql);
		while($navmodel = $this->db->fetch_array($query)){
			$navmodellist[] = $navmodel;
		}
		return $navmodellist;
	}
	
	function get_navmodel_num(){
		return $this->db->result_first("SELECT count(*) FROM ".DB_TABLEPRE."navmodel");
	}
	
	function get_navmodel($start=0, $limit=10){
		$navmodel = array();
		$sql = "SELECT * FROM ".DB_TABLEPRE."navmodel";
		$sql = $sql . " LIMIT $start,$limit ";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query)){
			$navmodel[] = $row;
		}
		return $navmodel;
	}
	
	function get_by_navname($name) {
		$query = $this->db->query("SELECT name FROM ".DB_TABLEPRE."navmodel WHERE name='$name'");
		return $this->db->fetch_array($query);
	}
	
	function get_by_id($navid) {
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."navmodel WHERE id='$navid'");
		return $this->db->fetch_array($query);
	}
	
	function del($id) {
		$this->db->query("DELETE FROM ".DB_TABLEPRE."navmodel WHERE id='$id'");
	}
}
?>