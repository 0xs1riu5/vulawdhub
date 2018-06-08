<?php
class links{
	var $db;
	function links(&$base) {
	  $this->base = $base;
	}
	function friendlinks($setting){
		$this->base->load('friendlink');
		return array('config'=>$setting, 'links'=>$_ENV['friendlink']->get_link_list());
	}
}
?>