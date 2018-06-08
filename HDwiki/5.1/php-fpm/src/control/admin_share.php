<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load("doc");
		$this->load("user");
		$this->load("category");
	}

	function dodefault(){
		$this->dosearch();
	}

	function dosearch(){
		$site_appkey =$this->setting['site_appkey'];
		
		if($site_appkey){
			$this->view->assign('is_login', 'login');
			
			if($this->setting['hdapi_sharetosns']){
				$this->view->assign('is_open', 1);
			}else{
				$this->view->assign('is_open', 0);
			}
		}else{
			$this->view->display('admin_bklm');
			exit;
		}
		
		$cid=isset($this->post['qcattype'])?$this->post['qcattype']:$this->get[2];
		$title=isset($this->post['qtitle'])?trim($this->post['qtitle']):urldecode(trim($this->get[3]));
		$author=isset($this->post['qauthor'])?trim($this->post['qauthor']):urldecode(trim($this->get[4]));
		$starttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[5];
		$endtime=isset($this->post['qendtime'])&&$this->post['qendtime']?(strtotime($this->post['qendtime'])+24*3600):(int)$this->get[6];
		$typename=isset($this->post['typename'])?$this->post['typename']:$this->get[7];
		
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		
		$count = $_ENV['doc']->search_doc_num($cid,$title,$author,$starttime,$endtime,$typename);
		$searchdata='admin_share-search-'.urlencode("$cid-$title-$author-$starttime-$endtime-$typename");
		$departstr=$this->multi($count, $num, $page,$searchdata);
		$doclist=$_ENV['doc']->search_doc($start_limit,$num,$cid,$title,$author,$starttime,$endtime,$typename);
		$all_category=$this->cache->getcache('category',$this->setting['index_cache_time']);
		$this->load("category");
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		$titles=stripslashes($title);
		$authors=stripslashes($author);
		$this->view->assign("searchdata", $searchdata.'-'.$page);
		$this->view->assign("catstr",$catstr);
		$this->view->assign("docsum",$count);
		$this->view->assign("qtitle",$titles);
		$this->view->assign("qauthor",$authors);
		$this->view->assign("qstarttime",$starttime?date("Y-m-d",$starttime):"");
		$this->view->assign("qendtime",$endtime?date("Y-m-d",$endtime-24*3600):"");
		$this->view->assign("departstr",$departstr);
		$this->view->assign("doclist",$doclist);
		$this->view->display('admin_share');
	}

	function doset(){
		if(isset($this->post['hdapi_bklm'])){
			$this->load('setting');			
			$settings = array(
				'hdapi_bklm'=>$this->post['hdapi_bklm'],
				'hdapi_sharetosns'=>$this->post['hdapi_sharetosns'],
				
				'hdapi_autoshare_edit'=>$this->post['hdapi_autoshare_edit'],
				'hdapi_autoshare_create'=>$this->post['hdapi_autoshare_create'],
				'hdapi_autoshare_comment'=>$this->post['hdapi_autoshare_comment'],
				'hdapi_autoshare_ding'=>$this->post['hdapi_autoshare_ding']
				
			);
			$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			echo 'OK';
		}else{
			$this->view->assign('settings',$this->setting);
			$this->view->display('admin_shareset');
		}
	}
}
?>