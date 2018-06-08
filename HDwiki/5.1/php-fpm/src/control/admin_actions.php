<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load("actions");
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$keywords = trim($this->post['keywords']);
		$results=array();
		$nums=array();//匹配次数，作为排序权重
		
		if($keywords){
			$keywords = preg_replace("/\s+/", ' ', $keywords);
			$kws = explode(' ', $keywords);
			$kws = array_map('trim', $kws);
			$kws = array_unique($kws);
			$keywords = implode(' ', $kws);

			$i=0;
			foreach($_ENV['actions']->data  as $action => $text){
				foreach($kws as $kw) {
					if(strpos(strtolower($text), strtolower($kw)) !== FALSE) {
						$nums[$i]=0;
						$results[$i]=$_ENV['actions']->getHTML($action, $text, $kws, $nums[$i]);
						$i++;
						break;
					}
				}
			}
		}
		
		arsort($nums);
		
		$results2=array();
		foreach($nums as $key => $value) {
			$results2[]=$results[$key];
		}
		
		$this->view->assign("keywords", $keywords);
		$this->view->assign("list", $results2);
		$this->view->display('admin_actions');
	}

	function domap(){
		$liststep1 = $liststep2 = $liststep3 = array();
		$map = $_ENV['actions']->getMap();
		$this->view->assign("map", $map);
		$this->view->display('admin_map');
	}
}