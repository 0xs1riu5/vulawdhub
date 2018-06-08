<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('ftp');
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->safe = $this->cache->getcache('safe');
	}

	function dodefault(){
		$status=0;
		if(!function_exists('ftp_connect')){
			$status=1;
		}
		if(isset($this->post['sure']) && $this->post['sure']!=""){
			$setting['FTP_ENABLE'] = $this->post['FTP_ENABLE'];
			$setting['FTP_HOST'] = $this->post['FTP_HOST'];
			$setting['FTP_PORT'] = $this->post['FTP_PORT'];
			$setting['FTP_USER'] = $this->post['FTP_USER'];
			$setting['FTP_PW'] = $this->post['FTP_PW'];
			$setting['FTP_PATH'] = $this->post['FTP_PATH'];
			$setting=$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->message('设置成功保存','index.php?admin_ftpsetting');
		}
		$this->view->assign("setting",$this->setting);
		$this->view->assign("status",$status);
		$this->view->display('admin_ftpsetting');
	}
	function dotestftp(){
		//链接ftp
		if ($_ENV['ftp']->ftp($this->post['ftp_host'])) {
			if ($_ENV['ftp']->login($this->post['ftp_user'],$this->post['ftp_pw'])) {
				$message = 'FTP 连接成功';
			} else {
				$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
			}
			$_ENV['ftp']->disconnect();
		}else{
			$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
		}
		if($error!="")
			$message = 'FTP 连接失败';
		exit($message);
	}
}
?>