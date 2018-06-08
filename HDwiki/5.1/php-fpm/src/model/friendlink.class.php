<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class friendlinkmodel {

	var $db;
	var $base;

	function friendlinkmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function  get_link_list(){
		$list=array();
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."friendlink ORDER BY displayorder,id DESC");
		while($friendlink=$this->db->fetch_array($query)){
			if(substr($friendlink['url'],0,7)!="http://"){
				$friendlink['url']='http://'.$friendlink['url'];
			}
			$list[]=$friendlink;
		}
		return $list;
	}
	
	/*Description: This method has already expired*/
	function  get_link_by_id($id){
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."friendlink WHERE id='$id'");
		return $this->db->fetch_array($query);
	}
	
	function addlink($link){
		$this->db->query("INSERT INTO ".DB_TABLEPRE."friendlink (name,url,logo,description) VALUES ('$link[name]','$link[url]','$link[logourl]','$link[description]')");
	}
	
	function editlink($link){
		$this->db->query("UPDATE ".DB_TABLEPRE."friendlink SET name='$link[name]',url='$link[url]',logo='$link[logourl]',description='$link[description]' WHERE id='$link[id]'");
	}
	
	function removelink($linkid){
		if(is_array($linkid)){
			$id=implode(",",$linkid);
		}else{
			$id=$linkid;
		}
		$this->db->query("DELETE FROM ".DB_TABLEPRE."friendlink WHERE id IN ($id)");
	}
	
	function updateorder($order){
		$count=count($order);
		for($i=0;$i<$count;$i++){
			$this->db->query("UPDATE ".DB_TABLEPRE."friendlink SET displayorder=$i WHERE id=".$order[$i]);
		}
	}
}


?>