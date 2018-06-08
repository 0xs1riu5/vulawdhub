<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		if(!isset($this->post['submit'])){
			$this->view->assign("relatedoc",$this->setting['relateddoc']);
			$this->view->assign("isrelate",$this->setting['isrelate']);
			$this->view->display("admin_relation");
			exit;
		}
		
		$isrelate = $this->post['isrelate'];
		$setting = array();
		$relatedoc = trim($this->post['relatedoc']);
		$relatelist = array_unique(explode(';',$relatedoc));
		foreach($relatelist as $relate){
			$relate=trim($relate);
			$relate = string::stripscript($relate);
			if(empty($relate)){
				unset($relate);
			}else{
				$relate = string::haddslashes($relate);
				$relatelists[] = $relate;
			}
		}
		if(count($relatelist)>10){
			$this->message($this->view->lang['relatedtitlemore'],'index.php?admin_relation');
		}
		$setting['relateddoc']=implode(";", $relatelists);
		$setting['isrelate']=$isrelate;
		$_ENV['setting']->update_setting($setting);
		$this->cache->removecache('setting');
		$this->message($this->view->lang['relatedtitlesuccess'],'index.php?admin_relation');
	}
}