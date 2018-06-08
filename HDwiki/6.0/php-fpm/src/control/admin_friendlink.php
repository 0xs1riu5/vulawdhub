<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('friendlink');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$linklist=$_ENV['friendlink']->get_link_list();
		$total=$this->db->fetch_total('friendlink','1');
		$this->view->assign('total',$total);
		$this->view->assign('linklist',$linklist);
		$this->view->display('admin_link');
		
	}

	function doadd(){
		if(!isset($this->post['linksubmit'])){
			$this->view->display('admin_addlink');
		}else{
			$flink['name']=htmlspecial_chars(trim($this->post['website']));
			$flink['url']=trim($this->post['siteurl']);
			if(trim($this->post['logourl'])!="http://")
				$flink['logourl']=trim($this->post['logourl']);
			$flink['description']=htmlspecial_chars(trim($this->post['description']));
			if(!empty($flink['name']) && preg_match("/^\w[\w\&\=\?\:\/\.\-]+$/i",$flink['url']) && !empty($flink['description'])){
				if(substr($flink['url'],0,7)!="http://"){
					$flink['url']="http://".$flink['url'];
				}
				$_ENV['friendlink']->addlink($flink);
				$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
				$this->message($this->view->lang['friendLinkAddSuccess'],'index.php?admin_friendlink');
			}else{
				$this->message($this->view->lang['pluginParErrorRewrite'],'index.php?admin_friendlink');
			}
		}
	}

	function doedit(){
		if(!isset($this->post['linksubmit'])){
			$link=$this->db->fetch_by_field('friendlink','id',(int)$this->get[2]);
			$this->view->assign('link',$link);
			$this->view->display('admin_addlink');
		}else{
			$flink['name']=htmlspecial_chars(trim($this->post['website']));
			$flink['url']=trim($this->post['siteurl']);
			if(trim($this->post['logourl'])!="http://"){
				$flink['logourl']=trim($this->post['logourl']);
			}
			$flink['id']=$this->post['id'];
			$flink['description']=htmlspecial_chars(trim($this->post['description']));
			if(!empty($flink['name']) && preg_match("/^\w[\w\&\=\?\:\/\.\-]+?$/i",$flink['url']) && is_numeric($flink['id']) && !empty($flink['description'])){
				if(substr($flink['url'],0,7)!="http://"){
					$flink['url']="http://".$flink['url'];
				}
				$_ENV['friendlink']->editlink($flink);
				$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
				$this->message($this->view->lang['friendLinkEditSuccess'],'index.php?admin_friendlink');
			}else{
				$this->message($this->view->lang['pluginParErrorRewrite'],'index.php?admin_friendlink');
			}
		}
	}
	
	function doremove(){
		if(count($this->post['link_id'])!=0){
			$_ENV['friendlink']->removelink($this->post['link_id']);
			$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			$this->message($this->view->lang['friendLinkRemoveSuccess'],'index.php?admin_friendlink');
		}else{
			$this->message($this->view->lang['pluginParErrorRewrite'],'index.php?admin_friendlink');
		}
	}
	
	function dochangeorder(){
		$link_num=string::stripspecialcharacter(trim($this->post['order']));
		$order=explode(",",$link_num);
		$_ENV['friendlink']->updateorder($order);
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
	}
}
?>