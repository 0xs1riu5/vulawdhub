<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('setting');
		$this->load('doc');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	/*set hottag*/
	function dohottag(){
		if(!isset($this->post['submit'])){
			if(isset($this->post['type']) && $this->post['type']==1){
				$this->message($_ENV['doc']->get_hottags(),"",2);
				exit;
			}
			$hottag=unserialize($this->setting['hottag']);
			if((bool)$hottag){
				foreach($hottag as $tags){
					$tag[]=$tags['tagname'];
				}
				$hottag = implode(';',$tag);
			}else{
				$hottag=$_ENV['doc']->get_hottags();
			}
			$this->view->assign("hottag",$hottag);
			$this->view->display("admin_hottag");
			exit;
		}
		$setting=array();
		$hottag=$this->post['hottag'];
		$hottag = $_ENV['doc']->get_colortag($hottag);
		$setting['hottag']=$hottag;
		$_ENV['setting']->update_setting($setting);
		$this->cache->removecache('setting');
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['hottagSaveSuccess'],'index.php?admin_tag-hottag');
	}
}