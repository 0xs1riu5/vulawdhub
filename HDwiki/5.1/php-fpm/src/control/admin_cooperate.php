<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	/*set cooperate*/
	function dodefault(){
		if(!isset($this->post['submit'])){
			$cooperatedoc=$this->setting['cooperatedoc'];
			$this->view->assign("cooperatedoc",$cooperatedoc);
			$this->view->display("admin_cooperate");
			exit;
		}
		$setting=array();
		$setting['cooperatedoc'] = trim($this->post['cooperate']);
		$_ENV['setting']->update_setting($setting);
		$this->cache->removecache('setting');
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['cooperatesuccse'],'index.php?admin_cooperate');
	}
}