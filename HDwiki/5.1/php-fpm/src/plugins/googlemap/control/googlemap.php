<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base($get, $post);
		$this->loadplugin('googlemap');
	}
	
	function dodefault() {
		$did = intval($this->post['did']);
		if($did) {
			$marker['title'] = string::substring(strip_tags($this->post['title']), 0, 14);
			$marker['description'] = string::substring(strip_tags($this->post['description']), 0, 60);
			$marker['lat'] = floatval($this->post['lat']);
			$marker['lng'] = floatval($this->post['lng']);
			$marker['zoom'] = intval($this->post['zoom']);
			$marker['did'] = $did;
			
			$_ENV['googlemap']->edit_marker($did, $marker);
			
			$marker['title'] = stripslashes($marker['title']);
			$marker['description'] = stripslashes($marker['description']);
			echo json_encode($marker);
		}
	}
}