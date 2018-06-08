<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class languagemodel {

	var $db;
	var $base;

	function languagemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_all_list(){
		$languagelist=array();
		$query=$this->db->query('SELECT * FROM '.DB_TABLEPRE.'language  ORDER BY  id ASC ');
		while($language=$this->db->fetch_array($query)){
			$languagelist[]=$language;
		}
		return $languagelist;
	}
	
	function add_language($language){
		$this->db->query("INSERT INTO ".DB_TABLEPRE."language (name,available,path,copyright)VALUES ('".$language['addlangname']."','1','".$language['addlangpath']."','".$language['addlangcopyright']."')");
	}
	
	function add_check_language($language){
		$languagelist=array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."language WHERE name LIKE '".$language['addlangname']."' OR path LIKE '".$language['addlangpath']."'");
		while($language=$this->db->fetch_array($query)){
			$languagelist[]=$language;
		}
		return $languagelist;
				
	}
	
	function update_language($name,$path,$state,$id){
		$this->db->query("UPDATE ".DB_TABLEPRE."language SET name='$name',path='$path',available='$state' WHERE id =$id LIMIT 1");
	}
	
	function default_language($path){
		$this->db->query("UPDATE ".DB_TABLEPRE."setting SET value = '$path' WHERE variable = 'lang_name'");
	}
	
	function remove_language($id){
		$this->db->query("DELETE FROM ".DB_TABLEPRE."language WHERE id=$id");
	}
	
	function choose_language_name($id){
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."language WHERE id=$id");
		return $this->db->fetch_array($query);
	}
}
?>