<?php
class html{
	
	var $db;
	var $base;

	function html(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function myhtml($setting){
		return array('config'=>$setting);
	}
	
}
?>