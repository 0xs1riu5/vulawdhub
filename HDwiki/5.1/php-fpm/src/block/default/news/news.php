<?php
class news{
	
	var $db;
	var $base;

	function news(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function text($setting){
		return array('config'=>$setting);
	}
	
	function recentnews($setting){
		$indexnewscache=$this->base->cache->getcache('indexnewscache',300);
		if(!$indexnewscache){
			$this->base->load('doc');
			$newslist=$_ENV['doc']->getnews();
			$indexnewscache=array(
				'newslist'=>$newslist
				);
			$this->base->cache->writecache('indexnewscache',$indexnewscache);
		}
		return array('config'=>$setting, 'list'=>$indexnewscache['newslist']);
	}
	
}
?>