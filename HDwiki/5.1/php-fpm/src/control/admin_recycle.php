<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('recycle');
		$this->load('search');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count = $_ENV['recycle']->get_count();
		
		$recyclelist=$_ENV['recycle']->get_list($start_limit,$num);
		$departstr=$this->multi($count, $num, $page,"admin_recycle-default");
		
		$this->view->assign("count",$count);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("recyclelist",$recyclelist);
		$this->view->display('admin_recycle');
	}
	
	function dosearch(){
		$title=isset($this->post['qtitle'])?trim($this->post['qtitle']):urldecode(trim($this->get[2]));
		$author=isset($this->post['qauthor'])?trim($this->post['qauthor']):trim($this->get[3]);
		$starttime=isset($this->post['qstarttime'])?strtotime($this->post['qstarttime']):(int)$this->get[4];
		$endtime=isset($this->post['qendtime'])&&$this->post['qendtime']?(strtotime($this->post['qendtime'])+24*3600):(int)$this->get[5];
		$type=isset($this->post['qtype'])?$this->post['qtype']:$this->get[6];
		
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count = $_ENV['recycle']->search_recycle_num($title,$author,$starttime,$endtime,$type);
		
		$recyclelist=$_ENV['recycle']->search_recycle($start_limit,$num,$title,$author,$starttime,$endtime,$type);
		$departstr=$this->multi($count, $num, $page,"admin_recycle-search-".urlencode("$title-$author-$starttime-$endtime-$type"));
		$titles=stripslashes($title);
		$authors=stripslashes($author);

		$this->view->assign("qtitle",$titles);
		$this->view->assign("qauthor",$authors);
		$this->view->assign("qtype",$type);
		$this->view->assign("qstarttime",$starttime?date("Y-m-d",$starttime):"");
		$this->view->assign("qendtime",$endtime?date("Y-m-d",$endtime-24*3600):"");
		$this->view->assign("count",$count);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("recyclelist",$recyclelist);
		$this->view->display('admin_recycle');
	}
	
	function doremove(){
		$rid=implode(",",$this->post['chkdid']);
		if(!preg_match("/^[,\d+]+$/",$rid)){
			$this->message($this->view->lang['commonParametersInvalidTip'],'index.php?admin_recycle');
		}
		$_ENV['recycle']->remove($rid);
		$this->header("admin_recycle");
	}
	
	function dorecover(){
		$rid=implode(",",$this->post['chkdid']);
		if(!preg_match("/^[,\d+]+$/",$rid)){
			$this->message($this->view->lang['commonParametersInvalidTip'],'index.php?admin_recycle');
		}
		$return = $_ENV['recycle']->recover($rid);
		$message = '';
		if(!empty($return)) {
			foreach($return as $k => $v){
				$message .= $k.'中，';
				foreach($v as $n) {
					foreach($n as $m=> $l){
						$message .= $m.'='.$l.',';
					}
				}
			}
		}
		if(!empty($message)) {
			$message = trim($message, ',');
			$message .= ' 已经存在，无法恢复！';
			$this->message($message,'index.php?admin_recycle');
		} else{
			$this->header("admin_recycle");
		}
		
	}
	
	function doclear(){
		$page = max(1, intval(end($this->get)));
		$count = $_ENV['recycle']->get_count();
		$pernum = 100;
		if($count>0 && $page<100){
			$_ENV['recycle']->clear($pernum);
			$this->message("<image src='style/default/loading.gif'><br />第".($page-1)*$pernum."条至".$page*$pernum."条已删除,正在进行下一步操作...<script type=\"text/JavaScript\">setTimeout(\"window.location.replace('index.php?admin_recycle-clear-".($page+1)."');\", 2000);</script>",'');
		}else{
			$this->message('回收站已清空,请返回','index.php?admin_recycle');
		}
	}
}