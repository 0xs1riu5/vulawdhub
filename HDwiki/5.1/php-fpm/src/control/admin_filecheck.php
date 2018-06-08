<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('filecheck');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	function dodefault() {
		$this->view->display('admin_filecheck_file');
	}

	function dofilecheck(){
		$out_info = $_ENV['filecheck']->file_check();
		echo $out_info;
		return true;
	}


	function docreate(){
		if(isset($this->post['submit'])){
			$exts = $this->post['filetype'];
			$_ENV['filecheck']->set();
			$_ENV['filecheck']->make($exts);
			$this->message('创建成功','index.php?admin_filecheck-create');
		}else{
			$dirs = $_ENV['filecheck']->dirs();
			$checked_dirs = $_ENV['filecheck']->checked_dirs();
			$this->view->assign("dirs",$dirs);
			$this->view->assign("checked_dirs",$checked_dirs);
			$this->view->display('admin_filecheck');
		}
	}



		
}
?>