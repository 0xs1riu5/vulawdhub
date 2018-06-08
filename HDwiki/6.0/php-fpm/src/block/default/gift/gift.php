<?php
	
class gift{
	var $db;
	var $base;
	
	function gift(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->base->load('gift');
	}
	
	function giftlist(){
		$beginprice=$setting['beginprice']?$setting['beginprice']:0;
		$endprice=$setting['endprice']?$setting['endprice']:500;
		$limit=$setting['limit']?$setting['limit']:10;
		$giftlist=$_ENV['gift']->get_list($title='',$beginprice ,$endprice ,$begintime='',$endtime='',0,$limit);	
		return $giftlist;
	}
	
	function giftpricerange(){
		$page = max(1, intval($this->base->get[3]));
		$gift_range=unserialize($this->base->setting['gift_range']);
		$minprice=array_keys($gift_range);
		$maxprice=array_values($gift_range);
		return array('minprice'=>$minprice,'maxprice'=>$maxprice,'page'=>$page);
	}
	function giftnotice(){
		return $this->base->setting['gift_notice'];
	}

}
?>