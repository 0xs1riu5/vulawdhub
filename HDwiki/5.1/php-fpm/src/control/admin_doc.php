<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load("doc");
		$this->load("user");
		$this->load("category");
		$this->load("search");
	}

	function dodefault(){
		$this->dosearch();
	}

	function dosearch(){
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
		$searchdata='admin_doc-search-'.urlencode("$cid-$title-$author-$starttime-$endtime-$typename");
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
		$this->view->display('admin_doc');
	}

	function doaudit(){
		$searchdata = $this->get[2];
		$searchdata = str_replace(',', '-', $searchdata);
		$dids=$this->post['chkdid'];
		$_ENV['doc']->audit_doc($dids);
		if(1 == $this->setting['cloud_search']) {
			// 审核通过 通知云搜索
			$_ENV['search']->cloud_change(array('dids'=>$dids,'mode'=>'1'));
		}
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['docAuditSuccess'],'index.php?'.$searchdata);
	}

	function dorecommend(){
		$searchdata = $this->get[3];	
		$searchdata = str_replace(',', '-', $searchdata);
		$dids=$this->post['chkdid'];
		$_ENV['doc']->set_focus_doc($dids,$this->get[2]);
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['setdoctypeSuccess'],'index.php?'.$searchdata);
	}
	
	function docancelrecommend(){
		$searchdata = $this->get[2];	
		$searchdata = str_replace(',', '-', $searchdata);
		$dids=$this->post['chkdid'];
		$_ENV['doc']->remove_focus($dids);
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['canceldoctypeSuccess'],'index.php?'.$searchdata);
	}
	
	function dolock(){
		$searchdata = $this->get[2];
		$searchdata = str_replace(',', '-', $searchdata);
		$dids=$this->post['chkdid'];
		$_ENV['doc']->lock($dids);
		$this->message($this->view->lang['docLockSuccess'],'index.php?'.$searchdata);
	}

	function dounlock(){
		$searchdata = $this->get[2];
		$searchdata = str_replace(',', '-', $searchdata);
		$dids=$this->post['chkdid'];
		$_ENV['doc']->lock($dids,0);
		$this->message($this->view->lang['docUnlockSuccess'],'index.php?'.$searchdata);
	}

	function doremove(){
		$searchdata = $this->get[2];
		$searchdata = str_replace(',', '-', $searchdata);
		$dids=$this->post['chkdid'];
		$_ENV['doc']->remove_doc($dids);
		if(1 == $this->setting['cloud_search']) {
			// 删除词条 通知云搜索
			$dids=implode(',', $this->post['chkdid']);
			$_ENV['search']->cloud_change(array('dids'=>$dids,'mode'=>'0'));
		}
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->message($this->view->lang['docRemoveSuccess'],'index.php?'.$searchdata);
	}

	function domove(){
		$chkdid=string::hiconv(trim($this->post['chkdid']));
		$dids=array_unique(explode(',',$chkdid));
		$chkcid=string::hiconv(trim($this->post['cid']));
		if($_ENV['doc']->change_category($dids,$chkcid)){
			echo '1';
		}else{
			echo '0';
		}
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
	}

	function dorename(){
		$title=string::hiconv(trim($this->post['newname']));
		$title=string::substring(string::stripspecialcharacter($title),0,80);
		if($title==''){
			echo "-1";
		}else if($this->db->fetch_by_field('doc','title',$title)){
			echo "-2";
		}elseif($_ENV['doc']->change_name($this->post['did'],$title)){
			if(1 == $this->setting['cloud_search']) {
				// 编辑标题 通知云搜索
				$_ENV['search']->cloud_change(array('dids'=>$this->post['did'],'mode'=>'2'));
			}
			echo "1";
		}else{
			echo "0";
		}
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
	}
}
?>