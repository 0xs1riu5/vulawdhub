<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base(& $get,& $post);
		$this->load('banned');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		if(isset($this->post['submit'])){
			if( isset($this->post['id']) ){
				$_ENV['banned']->del_ip($this->post['id']);
			}else{
				$havebannedips = array();
				$cacheips = $this->cache->getcache('bannedip');
				if($cacheips){
					foreach($cacheips as $cacheip){
						$havebannedips[] = $cacheip["ip"];
					}
				}
				$alluploadips = array();
				$alluploadips = $_ENV['banned']->singleip($alluploadips, $this->post);
				if(!empty($this->post['muliip']) || !empty($_FILES['file_path'])){
					$regular = '/(([01]?\d\d?|2[0-4]\d|25[0-5]|\*)\.){3}([01]?\d\d?|2[0-4]\d|25[0-5]|\*)/ixs';
					if(!empty($this->post['muliip'])){
						$alluploadips = $_ENV['banned']->textip($alluploadips, $regular, $this->post['muliip']);
					}
					if(!empty($_FILES['file_path'])){
						$alluploadips = $_ENV['banned']->fileip($alluploadips, $regular, $this->setting['attachment_size']);
					}
				}
				$alluploadips = array_diff($alluploadips, $havebannedips);
				$alluploadips = array_unique($alluploadips);
				$alluploadips = array_values($alluploadips);
			}
			if($alluploadips){
				$expiration = intval($this->post['validitynew']);
				$_ENV['banned']->add_ip($alluploadips,$expiration,$this->user['username']);
				$this->cache->removecache('setting');
			}
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_banned');
		}else{
			$count = $_ENV['banned']->get_allnum();
			$page = max(1, intval($this->get[2]));
			$num = isset($this->setting['list_prepage']) ? $this->setting['list_prepage'] : 20;
			$start_limit = ($page - 1) * $num;
			$baniplist = $_ENV['banned']->get_ip_list($start_limit,$num);
			$departstr = $this->multi($count, $num, $page,'admin_banned-default');
			$this->view->assign('ips',$baniplist);
			$this->view->assign('departstr',$departstr);
			$this->view->display('admin_ipban');
		}
	}
}
?>