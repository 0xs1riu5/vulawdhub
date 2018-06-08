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
		$cid=isset($this->post['qcattype'])?$this->post['qcattype']:$this->get[2];
		$title=isset($this->post['qtitle'])?trim($this->post['qtitle']):trim($this->get[3]);
		$author=isset($this->post['qauthor'])?trim($this->post['qauthor']):trim($this->get[4]);
		$starttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[5];
		$endtime=isset($this->post['qendtime'])&&$this->post['qendtime']?(strtotime($this->post['qendtime'])+24*3600):(int)$this->get[6];
		$typename=isset($this->post['typename'])?$this->post['typename']:$this->get[7];
		
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		
		$count = $_ENV['doc']->search_edition_num($cid,$title,$author,$starttime,$endtime,$typename);
		$searchdata='admin_edition-search-'.urlencode("$cid-$title-$author-$starttime-$endtime-$typename");
		$departstr=$this->multi($count, $num, $page,$searchdata);
		$doclist=$_ENV['doc']->search_edition($start_limit,$num,$cid,$title,$author,$starttime,$endtime, $typename);
		
		$all_category=$this->cache->getcache('category',$this->setting['index_cache_time']);
		$this->load("category");
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		$qtitles=stripslashes($this->post['qtitle']);
		$qauthors=stripslashes($this->post['qauthor']);
		$this->view->assign("searchdata", $searchdata.'-'.$page);
		$this->view->assign("catstr",$catstr);
		$this->view->assign("docsum",$count);
		$this->view->assign("qtitle",$qtitles);
		$this->view->assign("qauthor",$qauthors);
		$this->view->assign("qstarttime",$starttime?date("Y-m-d",$starttime):"");
		$this->view->assign("qendtime",$endtime?date("Y-m-d",$endtime-24*3600):"");
		$this->view->assign("departstr",$departstr);
		$this->view->assign("doclist",$doclist);
		$this->view->display('admin_edition');
	}

	function doaddcoin(){
		$eids = trim($this->post['eids']);
		$coin = trim($this->post['coin']);
		$uids = array();
		
		if(!preg_match("/^[\d,]+$/", $eids)){
			exit('data error');
		}
		if(is_numeric($coin)){
			$coin=intval($coin);
		}else{
			$coin = 0;
		}
		
		$_ENV['doc']->add_edition_coin($eids, $coin);
		$list = $_ENV['doc']->get_edition_user($eids);
		if(!empty($list)){
			foreach($list as $user){
				if (isset($uids[$user['authorid']])){
					$uids[$user['authorid']] += $coin;
				}else{
					$uids[$user['authorid']] = $coin;
				}
			}
		}
		
		if (!empty($uids) && is_array($uids)){
			foreach($uids as $uid => $coin){
				$_ENV['user']->add_coin($uid, $coin);
			}
		}
		echo 'OK';
	}

	function doexcellent(){
		$eids = $this->post['eids'];
		$flag = $this->post['flag'];
		$data = array('excellent'=>1);
		if(!preg_match("/^[\d,]+$/", $eids)){
			exit('data error');
		}
		
		$data['excellent'] = ($flag == 'excellent')?1:0;
		$_ENV['doc']->update_edition($eids, $data);
		echo 'OK';
	}
	
	function doremove(){
		$eids = $this->post['eids'];
		if(!preg_match("/^[\d,]+$/", $eids)){
			exit('data error');
		}
		if($_ENV['doc']->remove_edition($eids)){
			echo 'OK';
		}
	}
	
	function donewusernewdoc() {
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		
		$page = max(1, intval(end($this->get)));
		$perpage = 20;
		$start_limit = ($page - 1) * $perpage;
		
		$count = 0;
		$url = 'admin_edition-newusernewdoc';
		$doclist = $_ENV['doc']->get_newusernewdoc($start_limit, $perpage, false, $count);
		$departstr = $this->multi($count, $perpage, $page, $url);
		
		//按用户名合并，并统计每个用户名所拥有的词条数以在view中输出 rowspan
		$lastgrouped = array('listkey'=>0, 'lasteditorid'=>'');
		foreach($doclist as $key=>$doc) {
			if($doc['lasteditorid'] != $lastgrouped['lasteditorid']) {
				$doclist[$key]['groupeduser'] = array('id'=>$doc['lasteditorid'], 'username'=>$doc['lasteditor'], 'count'=>1);
				$lastgrouped = array('listkey'=>$key, 'lasteditorid'=>$doc['lasteditorid']);
			} else {
				$doclist[$lastgrouped['listkey']]['groupeduser']['count']++;
			}
		}
		
		//print_r($doclist);
		
		
		$this->view->assign('count', $count);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("doclist",$doclist);
		$this->view->display('admin_edition_newunewd');
	}
	
	function dofirstreview() {
		$uid = intval($this->get[2]);
		if(!empty($this->post)) {
			if($this->post['action'] == 'approved') {
				$_ENV['doc']->audit_all_docs($uid);
				$add_credit1 = $this->post['credit1'] == 'other' ? intval($this->post['credit1_other']) : intval($this->post['credit1']);
				$add_credit2 = $this->post['credit2'] == 'other' ? intval($this->post['credit2_other']) : intval($this->post['credit2']);
				$_ENV['user']->newdocs_approved($uid);
				$_ENV['user']->add_credit($uid,'firstreview',$add_credit2,$add_credit1);
				$this->message($this->view->lang['commonSuccess'], 'index.php?admin_edition-newusernewdoc');
			} elseif($this->post['action'] == 'rejected') {
				$_ENV['doc']->remove_all_docs($uid);
				$_ENV['user']->remove_user(array($uid));
				$this->message($this->view->lang['commonSuccess'], 'index.php?admin_edition-newusernewdoc');
			} else {
				$this->message($this->view->lang['commonNoOperate'], 'index.php?admin_edition-newusernewdoc');
			}
		}
		
		$totalcount = 0;
		$doclist = $_ENV['doc']->get_newusernewdoc(0, 30, $uid, $totalcount);
		
		$this->view->assign('count', $totalcount);
		$this->view->assign("doclist", $doclist);
		$this->view->display('admin_edition_firstreview');
	}
}
?>