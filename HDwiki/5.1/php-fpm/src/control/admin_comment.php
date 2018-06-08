<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load("comment");
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
		
		$doc=(''!=$title)?$this->db->fetch_by_field('doc','title',$title):'';
		$did=$doc['did']?$doc['did']:0;
		if(''==$title||$did){
			$user=$this->db->fetch_by_field('user','username',$author);
			$authorid=$user['uid']?$user['uid']:0;
		}
		if((''==$title||$did) && (''==$author||$authorid)){
			$page = max(1, intval(end($this->get)));
			$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
			$start_limit = ($page - 1) * $num;
			$count = $_ENV['comment']->search_comment_num($cid,$did,$author,$starttime,$endtime);
			$commentlist=$_ENV['comment']->search_comment($start_limit,$num,$cid,$did,$author,$starttime,$endtime);
			$departstr=$this->multi($count, $num, $page,"admin_comment-search-".urlencode("$cid-$title-$author-$starttime-$endtime"));
		}
		$all_category=$this->cache->getcache('category',$this->setting['index_cache_time']);
		$this->load("category");
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		$titles=stripslashes($title);
		$authors=stripslashes($author);
	
		$this->view->assign("catstr",$catstr);
		$this->view->assign("commentsum",$count);
		$this->view->assign("qtitle",$titles);
		$this->view->assign("qauthor",$authors);
		$this->view->assign("qstarttime",$starttime?date("Y-m-d",$starttime):"");
		$this->view->assign("qendtime",$endtime?date("Y-m-d",$endtime-24*3600):"");
		$this->view->assign("departstr",$departstr);
		$this->view->assign("commentlist",$commentlist);
		$this->view->display('admin_comment');
	}

	function dodelete(){
		$ids=isset($this->post['id'])?$this->post['id']:'';
		if(is_array($ids)){
			foreach($ids as $id){
				$delid[]=substr($id,0,strpos($id,'_'));
				$deldid[]=substr($id,strpos($id,'_')+1);
			}
			if($_ENV['comment']->remove_comment_by_id($delid)){
				foreach($deldid as $did){
					$_ENV['doc']->update_field('comments',-1,$did,0);
				}
				$this->message($this->view->lang['commentSucess'],'index.php?admin_comment');
			}else{
				$this->message($this->view->lang['commentFaile'],'index.php?admin_comment');
			}
		}else{
			$this->message($this->view->lang['docRemoveDocNull']);
		}
	}

}
?>