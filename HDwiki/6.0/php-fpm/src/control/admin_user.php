<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	var $WIKI_FOUNDER;

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('user');
		$this->load('usergroup');
		$this->view->setlang($this->setting['lang_name'],'back');
		$WIKI_FOUNDER = defined('WIKI_FOUNDER')?WIKI_FOUNDER:1;
		$WIKI_FOUNDER = is_numeric($WIKI_FOUNDER)?intval($WIKI_FOUNDER):1;
		$this->WIKI_FOUNDER = is_numeric($WIKI_FOUNDER)?$WIKI_FOUNDER:1;
	}

	function dodefault(){
		$this->dolist();
	}

	/*user list*/
	function dolist($checkup = 1){
		$page = isset($this->get[2]) ? $this->get[2] : '';
		$page = max(1, intval($page));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		
		$username=isset($this->post['username'])?$this->post['username']:'';
		$groupid=isset($this->post['groupid'])?$this->post['groupid']: 0;
		
		$usercount=$_ENV['user']->get_total_num($username,$groupid, $checkup);
		$userlist=$_ENV['user']->get_list($username,$groupid,$start_limit,$num, $checkup);
		
		
		foreach($userlist as $key => $user){
			if($this->WIKI_FOUNDER == $this->user['uid']){
				if ($this->user['uid'] == $user['uid']){
					$userlist[$key]['disabled'] = true;
				}
			}else{
				if (4 == $user['groupid'] || $this->user['uid'] == $user['uid']){
					$userlist[$key]['disabled'] = true;
				}
			}
		}
		$pagename =	$checkup>0 ? 'admin_user-list' : 'admin_user-uncheckeduser';
		$departstr=$this->multi($usercount, $num, $page, $pagename);
		$usergrouplist=$_ENV['usergroup']->get_all_list();
		$usergrouplist = array_slice($usergrouplist, 1);
		$username = stripslashes(trim($username));
		
		$this->view->assign('checkup', $checkup);
		$this->view->assign('usergrouplist', $usergrouplist);
		$this->view->assign('username', $username);
		$this->view->assign('groupid', $groupid);
		$this->view->assign('usercount', $usercount);
		$this->view->assign('departstr', $departstr);
		$this->view->assign('userlist', $userlist);
		$this->view->display('admin_user');
	}
	
	function douncheckeduser(){
		$this->dolist(0);
	}

	/*add user*/
	function doadd(){
		if(!isset($this->post['submit'])){
			$usergrouplist=$_ENV['usergroup']->get_all_list();
			$usergrouplist = array_slice($usergrouplist, 1);
			$this->view->assign('usergrouplist', $usergrouplist);
			$this->view->display('admin_adduser');
		}else{
			//eval($this->plugin["ucenter"]["hooks"]["admin_register"]);
			UC_OPEN && $_ENV['ucenter']->admin_register();
			
			$email = trim($this->post['email']);
			$username = trim($this->post['username']);
			$password = md5(trim($this->post['password']));
			if(!(bool)$this->db->fetch_by_field('user','email',$email) && !(bool)$this->db->fetch_by_field('user','username',$username)){
				$uid=$_ENV['user']->add_user($username,$password,$email,$this->time,$this->ip,$this->post['groupid']);
			}
			if($uid){
				$this->cache->removecache('usergroup_empty_'.$this->post['groupid']);
				$_ENV['user']->add_credit($uid,'user-register',$this->setting['credit_register'], $this->setting['coin_register']);
				$this->message($this->view->lang['adduserSuccess'],'index.php?admin_user-list');
			}else{
				$this->message($this->view->lang['adduserFailure'],'index.php?admin_user-add');
			}
		}
	}

	/*edit user*/
	function doedit(){
		$uid=isset($this->get[2])?$this->get[2]:$this->post['uid'];
		$user=$this->db->fetch_by_field('user','uid',$uid);
		if($user['groupid']==1 or ($user['groupid']==4 && $user['uid'] == $this->WIKI_FOUNDER) || $uid==$this->user['uid']){
			$this->message($this->view->lang['userNoCompetence']);
		}
		if(!isset($this->post['submit'])){
			$user=$this->db->fetch_by_field('user','uid',$uid);
			$usergrouplist=$_ENV['usergroup']->get_all_list();
			$usergrouplist = array_slice($usergrouplist, 1);
			if(4 != $this->user['groupid']){
				foreach($usergrouplist as $key => $value){
					if(1 == $value['type']){
						unset($usergrouplist[$key]);
					}
				}
			}
			$this->view->assign('user', $user);
			$this->view->assign('usergrouplist', $usergrouplist);
			$this->view->display('admin_edituser');
		}else{
			//eval($this->plugin["ucenter"]["hooks"]["edituser"]);
			$this->cache->removecache('usergroup_empty_'.$this->post['groupid']);
			$this->cache->removecache('usergroup_empty_'.$user['groupid']);
			UC_OPEN && $_ENV['ucenter']->edituser($uid);
			$_ENV['user']->edit_user($uid,$this->post['password'],$this->post['email'],$this->post['groupid']);
			$this->message($this->view->lang['edituserSuccess'],'index.php?admin_user-list');
		}
	}

	/*remove user*/
	function doremove(){
		if(!empty($this->post['uid'])){
			foreach($this->post['uid'] as $uid){
				$user=$this->db->fetch_by_field('user','uid',$uid);
				if(1==$user['groupid'] ){
					$this->message($this->view->lang['userNoCompetenceDel']);
				}
			}
			if($this->user['uid']==$user['uid'] ){
				$this->message($this->view->lang['userCannotRemoveSelf']);
			}
			//eval($this->plugin["ucenter"]["hooks"]["delete"]);
			UC_OPEN && $_ENV['ucenter']->delete();
	   		$_ENV['user']->remove_user($this->post['uid']);
			$this->message($this->view->lang['deluserSuccess'],'index.php?admin_user-list');
		}else{
			$this->message($this->view->lang['userNoneChoose']);
		}
	}
	
	function docheckup(){
		if(!empty($this->post['uid'])){
	   		$_ENV['user']->checkup_user($this->post['uid']);
			$this->message($this->view->lang['checkuserSuccess'],'index.php?admin_user-uncheckeduser');
		}else{
			$this->message($this->view->lang['userNoneChoose']);
		}
	}
	
	function doaddcoins(){
		$this->load('pms');
		$uid=$this->post['uid'];
		$names=$this->post['names'];
		$coin=$this->post['coin'];
		$ispms=$this->post['ispms'];
		$content=$this->post['content'];
		
		$_ENV['user']->add_coin($uid, $coin);
		
		if(WIKI_CHARSET == 'GBK'){
			$names = string::hiconv($names);
			$content = string::hiconv($content);
		}
		
		$return = 1;
		if ($ispms){
			$subject = $this->view->lang['coinManage'];
			$sendarray = array(
				'sendto'=>$names,
				'subject'=>$subject,
				'content'=>$content,
				'isdraft'=>0,
				'user'=>$this->user
			);
			
			if($names){
				$return = $_ENV['pms']->send_ownmessage($sendarray);
			}
		}
		echo ($return) ? 'OK' : '0';
	}
}
?>