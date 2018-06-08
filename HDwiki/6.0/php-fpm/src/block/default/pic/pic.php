<?php
class pic{
	
	var $db;
	var $base;

	function pic(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function recentpics($setting){
		$this->base->load('pic');
		$num=$setting['num']?$setting['num']:$this->base->setting['index_picture'];
		return array('config'=>$setting, 'list'=>$_ENV['pic']->get_pic(1,0,$num));
	}
}
?>