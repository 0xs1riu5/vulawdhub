<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('setting');
		$this->load('attachment');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$cid=isset($this->post['qcattype'])?$this->post['qcattype']:$this->get[2];
		$title=isset($this->post['qtitle'])?trim($this->post['qtitle']):trim($this->get[3]);
		$author=isset($this->post['qauthor'])?trim($this->post['qauthor']):trim($this->get[4]);
		$starttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[5];
		$docinfo = null;
		$all_category = false;
		if($title)$docinfo = $this->db->fetch_by_field('doc','title',$title);
		if(!empty($author)){
			$userinfo = $this->db->fetch_by_field('user','username',$author);
		}
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;		
		$start_limit = ($page - 1) * $num;
		$count = 0;
		
		if(($title || $author) && !$docinfo  && !$userinfo){
			$imagewiki=array();
		}else{
			$count = $_ENV['attachment']->search_attach_num($cid,$docinfo['did'],$userinfo['uid'], $starttime, $endtime,'',1,0);		
			$imagewiki = $_ENV['attachment']->search_attachment($start_limit,$num,$cid, $docinfo['did'],$userinfo['uid'], $starttime, $endtime,'',1,0);
		}
		$searchdata='admin_image-default-'.urlencode("$cid-$title-$author-$starttime-$endtime");
		$departstr=$this->multi($count, $num, $page,$searchdata);
		
		$this->load("category");
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		$qtitle=stripslashes($title);
		$qauthor=stripslashes($author);
		$this->view->assign("catstr",$catstr);
		$this->view->assign("imagewiki",$imagewiki);
		$this->view->assign("searchdata", $searchdata.'-'.$page);
		$this->view->assign("catstr",$catstr);
		$this->view->assign("docsum",$count);
		$this->view->assign("qtitle",$qtitle);
		$this->view->assign("qauthor",$qauthor);
		$this->view->assign("qstarttime",$starttime?date("Y-m-d",$starttime):"");
		$this->view->assign("qendtime",$endtime?date("Y-m-d",$endtime-24*3600):"");		
		$this->view->assign("departstr",$departstr);
		$this->view->display("admin_image");
	}
	
	function doeditimage(){
		$_ENV['attachment']->editimage($this->post['chkdid'],$this->get['2'],$this->get['3']);
		$this->cache->removecache('setting');
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['image_success'],'index.php?admin_image');
	}
	
	function doremove(){
		$ids=$this->post['chkdid'];
		if(is_array($ids)){
			if($_ENV['attachment']->remove($ids)){
				$this->message($this->view->lang['imageremovesuc'],'index.php?admin_image');
			}else{
				$this->message($this->view->lang['imageremovefail'],'index.php?admin_image');
			}
		}else{
			$this->message($this->view->lang['imageSelectDoc'],'BACK');
		}
	}

}