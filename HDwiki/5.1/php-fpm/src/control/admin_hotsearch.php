<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	/*set hotsearch*/
	function dodefault(){
		if(!isset($this->post['submit'])){
			$hotsearch=unserialize($this->setting['hotsearch']);
			$this->view->assign('hotsearch',$hotsearch);
			$this->view->display("admin_hotsearch");
			exit;
		}else{
			$hotnames = $this->post['hotname'];
			foreach($hotnames as $key => $name){
				if(trim($name['name'])){
					$hotnamelist[$key]['name'] = htmlspecialchars(trim($name['name']));
					$hotnamelist[$key]['url'] = htmlspecialchars(trim($name['url']));
				}else{
					unset($name['name']);	
					unset($name['url']);	
				}
			}
			$setting=array();
			$setting['hotsearch'] = addslashes(serialize($hotnamelist));
			$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			$this->message($this->view->lang['hot_searchsuccse'],'index.php?admin_hotsearch');
		}
	}
}