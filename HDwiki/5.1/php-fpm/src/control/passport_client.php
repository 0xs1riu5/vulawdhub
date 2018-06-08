<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	var $userdb;
	var $forward;
	var $verify;
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('user');
		$this->userdb=urldecode($this->get[2]);
		$this->forward=urldecode(substr($_SERVER['QUERY_STRING'],strpos($_SERVER['QUERY_STRING'],'-',strlen($this->get[0].$this->get[1].$this->get[2]))+1,-33));
		$this->verify=substr($_SERVER['QUERY_STRING'],-32);
		$ppfile=HDWIKI_ROOT.'/data/passport.inc.php';
		if(file_exists($ppfile)){
			include($ppfile);
		}else{
			exit($this->view->lang["pp_nofile"]);
		}
		if(!PP_OPEN || PP_TYPE != 'client'){
			exit($this->view->lang["pp_noopen"]);
		}
		if(md5($this->get[1].$this->userdb.$this->forward.PP_KEY) != $this->verify){
			exit($this->view->lang["pp_verify"]);
		}
		if (empty($this->forward)){
			$this->forward = PP_API;
		}
	}

	function dologin(){
		parse_str($this->authcode($this->userdb,'DECODE',PP_KEY),$userdb);
		foreach($userdb as $key => $val){
			$userdb[$key] = addslashes($val);
		}
		if(!$userdb['time'] || !$userdb['username'] || !$userdb['password']){
			exit($this->view->lang["pp_failverify"]);
		}
		if($this->time > $userdb['time']+3600){
			exit($this->view->lang["pp_faildata"]);
		}
		$user=$this->db->fetch_by_field('user','username',$userdb['username']);
		if(is_array($user)){
			if($user['password']!==$userdb['password']){
				$_ENV['user']->update_field('password',$userdb['password'],$user['uid']);
			}
			$lasttime=$user['lasttime'];
			if($this->time>($lasttime+24*3600)){
				$_ENV['user']->add_credit($user['uid'],'user-login',$this->setting['credit_login']);
			}
			$_ENV['user']->update_user($user['uid'],$this->time,$this->ip);
			$_ENV['user']->refresh_user($user['uid']);
		}else{
			$uid=$_ENV['user']->add_user($userdb['username'], $userdb['password'], $userdb['email'],$this->time,$this->ip);
			if($uid){
				$_ENV['user']->refresh_user($uid);
				$_ENV['user']->add_credit($this->user['uid'],'user-register',$this->setting['credit_register']);
			}
		}
		header("Location:$this->forward");exit();
	}

 	function dologout(){
		$_ENV['user']->user_logout();
		header("Location:".$this->forward);exit();
	}
	
}
?>