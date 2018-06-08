<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load("attachment");
		$this->load("doc");
		$this->load("user");
		$this->load("category");
	}

	function dodefault(){
		$this->dosearch();
	}

	function dosearch(){
		$cid=isset($this->post['qcattype'])?$this->post['qcattype']:$this->get[2];
		$title=isset($this->post['qtitle'])?trim($this->post['qtitle']):urldecode(trim($this->get[3]));
		$author=isset($this->post['qauthor'])?trim($this->post['qauthor']):trim($this->get[4]);
		$starttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[5];
		$endtime=isset($this->post['qendtime'])&&$this->post['qendtime']?(strtotime($this->post['qendtime'])+24*3600):(int)$this->get[6];
		$type=isset($this->post['qfiletype'])?$this->post['qfiletype']:$this->get[7];
		if($title)$doc=$this->db->fetch_by_field('doc','title',$title);
		$did=$doc['did']?$doc['did']:0;
		if(''==$title||$did){
			$user=$this->db->fetch_by_field('user','username',$author);
			$authorid=$user['uid']?$user['uid']:0;
		}
		if((''==$title||$did) && (''==$author||$authorid)){
			$page = max(1, intval(end($this->get)));
			$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
			$start_limit = ($page - 1) * $num;
			$count = $_ENV['attachment']->search_attach_num($cid,$did,$authorid,$starttime,$endtime,$type);
			
			$attachlist=$_ENV['attachment']->search_attachment($start_limit,$num,$cid,$did,$authorid,$starttime,$endtime,$type);
			$departstr=$this->multi($count, $num, $page,"admin_attachment-search-".urlencode("$cid-$title-$author-$starttime-$endtime-$type"));
		}
		$all_category=$this->cache->getcache('category',$this->setting['index_cache_time']);
		$this->load("category");
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		$filetype=array_unique(explode('|',$this->setting['attachment_type']));
		$titles=stripslashes($title);
		$authors=stripslashes($author);
	
		$this->view->assign("catstr",$catstr);
		$this->view->assign("filetype",$filetype);
		$this->view->assign("attachsum",$count);
		$this->view->assign("qtitle",$titles);
		$this->view->assign("qauthor",$authors);
		$this->view->assign("qstarttime",$starttime?date("Y-m-d",$starttime):"");
		$this->view->assign("qendtime",$endtime?date("Y-m-d",$endtime-24*3600):"");
		$this->view->assign("departstr",$departstr);
		$this->view->assign("attachlist",$attachlist);
		$this->view->display('admin_attach');
	}

	function doremove(){
		$ids=$this->post['attach'];
		if(is_array($ids) && $ids){
			foreach($ids as $id){
				$delid[]=substr($id,0,strpos($id,'_'));
			}
			if($_ENV['attachment']->remove($delid)){
				$this->message($this->view->lang['attachDelSucc'],'index.php?admin_attachment');
			}else{
				$this->message($this->view->lang['attachDelFail'],'index.php?admin_attachment');
			}
		}else{
			$this->message($this->view->lang['attachChoose'],'BACK');
		}
	}

	function dodownload(){
		if(!isset($this->get[2]) || !is_numeric($this->get[2])){
			$this->message($this->view->lang['parameterError'],'BACK');
		}
		$result=$_ENV['attachment']->get_attachment('id',$this->get[2],$this->get[3]);
		if(!(bool)$attachment=$result[0]){
			$this->message($this->view->lang['attachIsNotExist'],'BACK');
		}
		$_ENV['attachment']->update_downloads($attachment['id']);
		file::downloadfile($attachment['attachment'],$attachment['filename']);
	}
}
?>