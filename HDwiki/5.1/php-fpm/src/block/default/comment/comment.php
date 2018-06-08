<?php
class comment{
	
	var $db;
	var $base;

	function comment(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function recentcomment($setting){
		$this->base->load('comment');
		$num=$setting['num'] && is_numeric($setting['num'])?$setting['num']:$this->base->setting['index_recentcomment'];
		return array('config'=>$setting, 'list'=>$_ENV['comment']->recent_comment(0, $num));
	}
}
?>