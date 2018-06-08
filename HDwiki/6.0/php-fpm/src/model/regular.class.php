<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class regularmodel {

	var $db;
	var $base;

	function regularmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function  get_regular_by_id($id){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."regular WHERE id=$id");
	}
	
	function get_all_list(){
		$regularlist=array();
		$query=$this->db->query('SELECT * FROM '.DB_TABLEPRE.'regular  ORDER BY  `type` ASC, regulargroupid ASC, id ASC ');
		while($regular=$this->db->fetch_array($query)){
			$regularlist[]=$regular;
		}
		return $regularlist;
	}
	
	function add_regular($name,$expr, $groupid,$type=2){
		return $this->db->query("INSERT INTO ".DB_TABLEPRE."regular(name,regular,type,regulargroupid) VALUES('$name','$expr',$type,$groupid) ");
	}
		
	function edit_regular($name,$expr,$gid,$id){
		return $this->db->query("UPDATE ".DB_TABLEPRE."regular SET name='$name',regular='$expr',regulargroupid=$gid WHERE id=$id ");
	}
	
	/*Description: This method has already expired*/
	function edit_groupid($id, $groupid){
		return $this->db->query("UPDATE ".DB_TABLEPRE."regular SET regulargroupid='$groupid' WHERE id=$id ");
	}
	
	function edit_regulargroup($post){
		$sqladd = '';
		if (!is_numeric($post['id'])){
			return false;;
		}
		if (isset($post['title'])){
			$sqladd .= " `title`='".$post['title']."',";
		}
		if (isset($post['type'])){
			$sqladd .= ' `type`='.$post['type'].',';
		}
		if (isset($post['size'])){
			$sqladd .= ' ,`size`='.$post['size'].',';
		}
		if($sqladd){
			$sqladd=substr($sqladd,0,-1);
			return $this->db->query("UPDATE ".DB_TABLEPRE."regulargroup SET $sqladd WHERE id=$post[id] ");
		}
		return false;
	}
	
	function remove_regulargroup($groupid){
		$this->db->query("UPDATE ".DB_TABLEPRE."regular SET regulargroupid=0 WHERE regulargroupid=$groupid ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."regulargroup WHERE id=$groupid ");
	}
	
	function remove_regular($ids){
		if(is_array($ids)) {$ids = implode($ids,"','");}
		if(preg_match("/^[\d,]+$/", $ids)){
			$this->db->query("DELETE FROM  ".DB_TABLEPRE."regular WHERE id IN ('$ids') ");
		}
	}
	
	function get_group_list(){
		$grouplist=array();
		$query=$this->db->query('SELECT * FROM '.DB_TABLEPRE.'regulargroup  ORDER BY  `type` ASC, `size` DESC ');
		while($regular=$this->db->fetch_array($query)){
			$grouplist[]=$regular;
		}
		return $grouplist;
	}
	
	function add_regulargroup($titles){
		$sqladd='';
		$titles = explode("\n", $titles);
		foreach($titles as $title){
			if ('' !== $title){
				$sqladd .= "('$title'),";
			}
		}
		if($sqladd){
			return $this->db->query("INSERT INTO  ".DB_TABLEPRE."regulargroup (`title`) VALUES ".substr($sqladd,0,-1));
		}
		return false;
	}
	
	function add_relation($idlist){
		$sqladd = '';
		foreach($idlist as $idleft=>$idright){
			$sqladd .= "($idleft,$idright),";
		}
		if($sqladd){
			return $this->db->query("INSERT INTO  ".DB_TABLEPRE."regular_relation(idleft,idright) VALUES ".substr($sqladd,0,-1));	
		}
		return false;
	}
	
	function remove_relation($idlist){
		foreach($idlist as $idleft=>$idright){
			$this->db->query("DELETE FROM  ".DB_TABLEPRE."regular_relation WHERE idleft=$idleft and idright=$idright");
		}
	}
	
	function get_relation($id, $action='right'){
		$idlist=array('idleft'=>array(), 'idright'=>array());
		if ($action=='right' || $action=='both'){
			$query=$this->db->query('SELECT idright FROM '.DB_TABLEPRE.'regular_relation where idleft='.$id);
			while($idright=$this->db->fetch_array($query)){
				$idlist['idright'][] = $idright['idright'];
			}
		}
		if ($action=='left' || $action=='both'){
			$query=$this->db->query('SELECT idleft FROM '.DB_TABLEPRE.'regular_relation where idright='.$id);
			while($idleft=$this->db->fetch_array($query)){
				$idlist['idleft'][] = $idleft['idleft'];
			}
		}		
		return $idlist;
	}
}
?>