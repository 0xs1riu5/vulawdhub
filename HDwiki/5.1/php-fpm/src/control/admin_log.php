<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('log');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	function dodefault(){
		$page = max(1, intval($this->get[2]));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$content=$_ENV['log']->readlog($start_limit,$num);
		$endline=$start_limit+$num;
		if($endline>$content[1]){
			$endline=$content[1];
		}
		$departstr=$this->multi($content[1], $num, $page,'admin_log-default');
		$this->view->assign('getlog',$content[0]);
		$this->view->assign('lognum',$content[1]);
		$this->view->assign('prdate',$content[2]);
		$this->view->assign('page',$page);
		$this->view->assign('line',$start_limit+1);
		$this->view->assign('endline',$endline);
		$this->view->assign('departstr',$departstr);
		$this->view->assign('num',$num);
		$this->view->display('admin_log');
	}
	
	function dophpinfo(){
		if (function_exists('phpinfo')){phpinfo();}
		else{$this->message($this->view->lang['serverInfoTip'],'BACK'); }
	}
}
?>