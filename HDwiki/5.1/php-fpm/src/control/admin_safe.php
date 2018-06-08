<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	var $safe;
	var $hdwiki_root;
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('filecheck');
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->safe = $this->cache->getcache('safe');
		$this->hdwiki_root = HDWIKI_ROOT."\\";
	}

	function dodefault(){
		$dirs = $_ENV['filecheck']->dirs();
		if(!$this->safe)
		{
			$this->safe = array (
						'file_type' => 'php|js',
						'code' => '',
						'func' => 'com|system|exec|eval|escapeshell|cmd|passthru|base64_decode|gzuncompress',
						'dir' => $_ENV['filecheck']->checked_dirs()
			);
		}
		$md5_files = $_ENV['filecheck']->md5_files();
		$this->view->assign("md5_files",$md5_files);
		$this->view->assign("dirs",$dirs);
		$this->view->assign("safe",$this->safe);
		$this->view->display('admin_safe');
	}
	
	//保存用户检验条件
	function dosetting(){
		$data['file_type'] = $this->post['file_type'];
		$data['code'] = html_entity_decode(stripcslashes($this->post['code']));
		$data['func'] = html_entity_decode(stripcslashes($this->post['func']));
		$data['md5_file'] = $this->post['md5_file'];
		$data['dir'] = explode("|",$this->post['dirs']);
		$this->cache->writecache('safe', $data);
		echo 'ok';
	}
	
	//搜索文件类型
	function doscanfile(){
		$file_type = explode('|', $this->safe['file_type']);
		foreach ($this->safe['dir'] as $key=>$val)
		{
			$files = $_ENV['filecheck']->scan_dir($val, $file_type);
			foreach ($files as $key=>$val)
			{
				$file_list[$key] = $val;
			}
		}
		$this->cache->writecache('safe_file', $file_list);
		echo 'ok';
	}
	
	//和md5文件进行验证
	function dovalidate(){
		$file_list = $this->cache->getcache('safe_file');
		$file_md5 = file($this->hdwiki_root.'data/md5_file/'.$this->safe['md5_file']);
		foreach($file_md5 as $val)
		{
			$val = trim($val);
			$key = substr($val, 0, 32);
			$file = substr($val, 33);
			if($file_list[$file] == $key)
			{
				unset($file_list[$file]);
			}
		}
		$this->cache->writecache('safe_file', $file_list);
		echo 'ok';
	}
	//从剩余文件中扫描用户输入的函数
	function doscanfuns(){
		@set_time_limit(600);
		$file_list = $this->cache->getcache('safe_file');
		if($this->safe['func'])
		{
			foreach ($file_list as $key=>$val)
			{
				$html = file_get_contents($this->hdwiki_root.$key);
				if(stristr($key,'.php.') != false || preg_match_all('/[^a-z]?('.$this->safe['func'].')\s*\(/i', $html, $state, PREG_SET_ORDER))
				{
					$badfiles[$key]['func'] = $state;
				}
			}
		}
		if(!isset($badfiles)) $badfiles = array();
		$this->cache->writecache('safe_backdoor', $badfiles);
		echo 'ok';
	}
	
	//从剩余文件中扫描用户输入的代码
	function doscancodes(){
		@set_time_limit(600);
		$file_list = $this->cache->getcache('safe_file');
		$badfiles = $this->cache->getcache('safe_backdoor');
		if ($this->safe['code'])
		{
			foreach ($file_list as $key=>$val)
			{
				$html = file_get_contents($this->hdwiki_root.$key);
				if(stristr($key, '.php.') != false || preg_match_all('/[^a-z]?('.$this->safe['code'].')/i', $html, $state, PREG_SET_ORDER))
				{
					$badfiles[$key]['code'] = $state;
				}
				if(strtolower(substr($key, -4)) == '.php' && function_exists('zend_loader_file_encoded') && zend_loader_file_encoded($this->hdwiki_root.$key))
				{
					$badfiles[$key]['zend'] = 'zend encoded';
				}
			}
		}
		if(!isset($badfiles))$badfiles='';
		$this->cache->writecache('safe_backdoor', $badfiles);
		echo 'ok';
	}
	
	//显示结果
	function dolist(){
		$lists = $this->cache->getcache('safe_backdoor');
		$lists = $_ENV['filecheck']->getlist($lists);
		$this->view->assign("lists",$lists);
		$this->view->display('admin_safelist');
	}
	
	//编辑源代码
	function doeditcode(){
		if(isset($this->post['submit'])){
			if (file::writetofile($this->hdwiki_root.$this->post['file_path'], stripcslashes($this->post['code'])))
			{
				$this->message('修改成功','index.php?admin_safe-list');
			}else{
				$this->message('修改失败','index.php?admin_safe-list');
			}
		}else{
			$func = $code = array();
			$file_path = $_ENV['filecheck']->urlcode($this->get[2],1);
			if (empty($file_path)) 
			{
				$this->message('请选择文件','index.php?admin_safe-list');
			}
			$file_list = $this->cache->getcache('safe_backdoor');
			$html = file_get_contents($this->hdwiki_root.$file_path);
			if($file_list[$file_path]['func']){
				foreach ($file_list[$file_path]['func'] as $key=>$val)
				{
					$func[$key] = strtolower($val[1]);
				}
			}
			if($file_list[$file_path]['code']){
				foreach ($file_list[$file_path]['code'] as $key=>$val)
				{
					$code[$key] = strtolower($val[1]);
				}
			}
			$func = $_ENV['filecheck']->getjscode($func);
			$code = $_ENV['filecheck']->getjscode($code,1);
			$this->view->assign("code",$code);
			$this->view->assign("func",$func);
			$this->view->assign("html",$html);
			$this->view->assign("isedit",'true');
			$this->view->assign("file_path",$file_path);
			$this->view->display('admin_safelist');
		}
	}

	//删除文件
	function dodel(){
		$file_path = $_ENV['filecheck']->urlcode($this->get[2],1);
		if (empty($file_path)) 
		{
			$this->message('请选择文件','index.php?admin_safe-list');
		}
		$file_list = $this->cache->getcache('safe_backdoor');
		unset($file_list[$file_path]);
		$this->cache->writecache('safe_backdoor', $file_list);
		@unlink($this->hdwiki_root.$file_path);
		$this->message('文件删除成功!','index.php?admin_safe-list');
	}
}
?>