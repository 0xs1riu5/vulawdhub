<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('word');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

 	function dodefault(){
		if(isset($this->post['submit'])){
			$ids = isset($this->post['word_id'])?$this->post['word_id']:'';
			$wordids = $this->post['upword_id'];
			$find = $this->post['find'];
			$replacement = $this->post['replacement'];
			$newfind = string::haddslashes(htmlspecialchars($this->post['newfind']));
			$muliword = string::haddslashes(htmlspecialchars($this->post['muliword']));
			$newreplacement = string::substring(string::haddslashes(htmlspecialchars($this->post['newreplacement'])), 0, 18);
			$words = NULL;
			if(is_array($wordids)){
				foreach($wordids as $id => $wordid){
					$find[$id] = string::substring($find[$id], 0, 18);
					$replacement[$id] = string::substring($replacement[$id], 0, 18);
					$words[] = array('id'=>$wordid,'find'=>$find[$id],'replacement'=>$replacement[$id]);
				}
			}
			if($ids){
				$_ENV['word']->del_words($ids);
			}
			if($words){
				$_ENV['word']->edit_words($words,$this->user['username']);
			}
			$havebannedwords = array();
			$cachewords = $this->cache->getcache('word');
			if($cachewords){
				foreach($cachewords as $cachekey=>$cacheword){
					$havebannedwords[] = $cachekey;
				}
			}
			$alluploadwords = array();
			if($newfind){
				$alluploadwords[] = str_replace('，', ',', $newfind);
			}
			if($muliword){
				$textwords = explode(',', str_replace('，', ',', $muliword));
				$alluploadwords = array_merge($alluploadwords, $textwords);
			}
			if(!empty($_FILES['file_path']['name'])){
				$allowexts = array('txt', 'csv');
				$ext = substr($_FILES['file_path']['name'],strrpos($_FILES['file_path']['name'], '.')+1);
				if(!in_array($ext, $allowexts)){
					$this->message($this->view->lang['allowext'], 'index.php?admin_word');
				}
				$alluploadwords = $_ENV['word']->filewords($alluploadwords, $this->setting['attachment_size']);
			}
			if($alluploadwords){
				array_walk($alluploadwords, create_function('&$v, $k', '$v = string::substring($v, 0, 18);'));
				$alluploadwords = array_diff($alluploadwords, $havebannedwords);
				$alluploadwords = array_unique($alluploadwords);
				$alluploadwords = array_values($alluploadwords);
				$_ENV['word']->add_word($alluploadwords,$newreplacement,$this->user['username']);
			}
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_word');
		}else{
			$page = max(1, intval($this->get[2]));
			$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
			$start_limit = ($page - 1) * $num;
			$allnum = $_ENV['word']->get_word_num();
			$words = $_ENV['word']->get_word_list($start_limit,$num);
			$departstr=$this->multi($allnum, $num, $page,'admin_word-default');
			$this->view->assign('departstr',$departstr);
			$this->view->assign("docsum",$allnum);
			$this->view->assign('words',$words);
			$this->view->display('admin_word');
		}
	}
}

?>