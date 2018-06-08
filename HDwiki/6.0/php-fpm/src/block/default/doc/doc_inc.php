<?php
class doc_inc{
	
	var $db;
	var $base;

	function doc_inc(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function categorydocs(){
		$all_category=$this->base->cache->getcache('category',$this->setting['index_cache_time']);
		$this->base->load("category");
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->base->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		return $catstr;
	}
}
?>