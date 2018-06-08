<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('dbcheck');
		$this->load('db');
		
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	function dodefault() {
		$this->view->display('admin_dbcheck');
	}

	function dodbcheck(){
		$out_info = $_ENV['dbcheck']->dbcheck();
		echo $out_info;
		return true;
	}

	function dodbcompare(){
		$out_info = $_ENV['dbcheck']->dbcompare();
		echo $out_info;
		return true;
	}

	function dodbrepair_struct(){
		$out_info = $_ENV['dbcheck']->dbrepair_struct();
		echo $out_info;
		return true;
	}


		
}
?>