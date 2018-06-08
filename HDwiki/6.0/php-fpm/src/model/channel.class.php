<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class channelmodel {

	var $db;
	var $base;

	function channelmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	/*Description: This method has already expired*/
	function  get_channel_by_id($id){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."channel WHERE id=$id");
	}
	
	function get_all_list(){
		$channellist=array();
		$query=$this->db->query('SELECT * FROM '.DB_TABLEPRE.'channel ORDER BY displayorder,id DESC');
		while($channel=$this->db->fetch_array($query)){
			if(substr($channel['url'],0,7)!="http://"){
				$channel['url']='http://'.$channel['url'];
			}
			$channellist[]=$channel;
		}
		return $channellist;
	}

	function add_channel($channel){
		$displayorder = $this->db->fetch_first("SELECT displayorder FROM ".DB_TABLEPRE."channel  WHERE position=1 ORDER  BY displayorder DESC LIMIT 1");
		$displayorder = $displayorder['displayorder']+1;
		return $this->db->query("INSERT INTO  ".DB_TABLEPRE."channel (name,url,available,displayorder,position) VALUES ('$channel[name]','$channel[url]',$channel[available],$displayorder,$channel[position]) ");
	}
	
	function edit_channel($channel){
		return $this->db->query("UPDATE ".DB_TABLEPRE."channel SET name='$channel[name]',url='$channel[url]',available='$channel[available]',position='$channel[position]' WHERE id=$channel[id] ");
	}
	
	function remove_channel($channelid){
		if(is_array($channelid)){
			$id=implode(",",$channelid);
		}else{
			$id=$channelid;
		}
		$this->db->query("DELETE FROM ".DB_TABLEPRE."channel WHERE id IN ($id)");
	}
	
	function updateorder($order){
		$count=count($order);
		for($i=0;$i<$count;$i++){
			if(intval($order[$i]) != 0){
				$this->db->query("UPDATE ".DB_TABLEPRE."channel SET displayorder=$i WHERE id=".$order[$i]);
			}
		}
	}
}
?>