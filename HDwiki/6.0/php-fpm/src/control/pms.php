<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('pms');
		$this->load('user');
		$this->load('usergroup');
	}

	function dodefault(){
		$this->get[1] = 'box';
		$this->get[2] = 'inbox';
		$this->get[3] = 'owner';
		$this->dobox();
	}

	function dosendmessage(){
		$usergroups = '';
		$groupid = $this->user['groupid'];
		$this->showmessage();
		if(isset($this->post['submit'])){
			$sendto = htmlspecial_chars($this->post['sendto']);
			$subject = htmlspecial_chars($this->post['subject']);
			$content = htmlspecial_chars($this->post['content']);

			if(($groupid != 4 && isset($this->post['usergroup'])) || (empty($this->post['usergroup']) && empty($sendto))){
				$message = $this->view->lang['refuseAction'];
				$this->message($message,'index.php?pms-box-inbox-owner',0);
			}
			if(WIKI_CHARSET == 'GBK'){
				$sendto = string::hiconv($sendto);
				$subject = string::hiconv($subject);
				$content = string::hiconv($content);
			}

			$sendarray = array(
				'sendto'=>$sendto,
				'subject'=>$subject,
				'content'=>$content,
				'isdraft'=>$this->post['checkbox'],
				'user'=>$this->user,
				'togroupid'=>$this->post['usergroup']
			);
			if($sendto){
				$sendreturn = $_ENV['pms']->send_ownmessage($sendarray);
			}
			if($this->post['usergroup']){
				$this->dopublicmessage($sendarray);
			}
			if($sendreturn){
				$_ENV['user']->add_credit($this->user['uid'], 'user-pms', $this->setting['credit_pms'],$this->setting['coin_pms']);
				$message = $this->view->lang['viewDocTip1'];
			}else{
				$message = $this->view->lang['viewDocTip3'];
			}	
			if ($this->post['submit'] == 'ajax'){
				if ($sendreturn) {
					exit('OK');
				} else {
					exit('error');
				}
			} else {
				$this->message($message,'index.php?pms-box-inbox-owner',0);
			}
		}
					
		if($groupid == 4){
			$usergroups = $_ENV['usergroup']->get_all_list();
		}
		$this->view->assign('usergroups',$usergroups);
		$this->view->assign('groupid',$groupid);	
		//$this->view->display('sendmessage');
		$_ENV['block']->view('sendmessage');
	}

	function dopublicmessage($sendarray = ''){
		$begin = empty($this->get[2]) ? 0 : $this->get[2];		
		if(!$sendarray){
			$sendarray = $_ENV['pms']->get_pmsmessage();
		}else{
			$_ENV['pms']->add_pmsmessage($sendarray);
			$_ENV['user']->add_credit($this->user['uid'], 'user-pms', $this->setting['credit_pms'],$this->setting['coin_pms']);
		}		
		$sendarray['step'] = 100;		
		$sendarray['begin'] = $begin;		
		$end = $begin+$sendarray['step'];
		$message = $this->view->lang['nowsending'].$begin.'-'.$end.'</br><img src="style/default/loading.gif">';
		$begin = $_ENV['pms']->send_pubmessage($sendarray);
		if($begin){
			$this->message($message,'index.php?pms-publicmessage-'.$begin,0);
		}else{
			$message = $this->view->lang['viewDocTip1'];
			$this->message($message,'index.php?pms-box-inbox-system',0);		
		}
	}
	
	function docheckrecipient(){
		$sendto = $this->post['sendto'];
		if (WIKI_CHARSET == 'GBK'){
			$sendto = string::hiconv($sendto,'GBK','UTF-8',1);
		}
		$send = explode(',',$sendto);
		if(count($send)>10){
			$this->message($this->view->lang['fullsend'],'',2);
		}
		$checkreturn = $_ENV['pms']->check_recipient($sendto,0);
		$message = ($checkreturn === true)? 'OK' : ($checkreturn.' '.$this->view->lang['loginTip3']);
		$this->message($message,'',2);
	}
	
	function doblacklist(){
		if(isset($this->post['blacklist'])){
			$blacklist = htmlspecial_chars(string::stripscript($this->post['blacklist']));
			if(empty($blacklist)){
				$result = $_ENV['pms']->remove_blacklist($this->user['uid']);
			}else{
				$result = $_ENV['pms']->add_blacklist($blacklist,$this->user['uid']);
			}
			$message = $result ? 1 : 2;
			$this->message($message,'',2);
		}else{
			$this->view->assign('blacklist', $_ENV['pms']->get_blacklist($this->user['uid']));
			//$this->view->display('blacklist');
			$_ENV['block']->view('blacklist');
		}
	}
	
	function dobox(){
		$this->get[3] = empty($this->get[3]) ? NULL : $this->get[3];
		$page = max(1,isset($this->get[4]) ? $this->get[4] : $this->get[3]);
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;		
		$count = $_ENV['pms']->get_totalpms($this->user['uid'], $this->get[2]);
		$boxarray = array(
						  'user'=>$this->user,
						  'start_limit'=>$start_limit,
						  'num'=>$num,
						  'type'=>$this->get[2],
						  'group'=>$this->get[3]
						  );
		$pmslist = $_ENV['pms']->get_box($boxarray);
		if($this->get[2] == 'inbox'){
			$ownercount = $_ENV['pms']->get_totalpms($this->user['uid'], $this->get[2], 'owner');
			$publiccount = $count-$ownercount;
			$allnum = $this->get[3] == 'owner' ? $ownercount : $publiccount;
			$departstr = $this->multi($allnum, $num, $page,"pms-box-".$this->get[2].'-'.$this->get[3]);
			$this->view->assign('current',$this->get[3]);
			$this->view->assign('ownercount',$ownercount);
			$this->view->assign('publiccount',$publiccount);
		}else{
			$departstr = $this->multi($count, $num, $page,"pms-box-".$this->get[2]);			
		}
		$count = ($count > 200 && $this->get[2] == 'inbox') ? 200 : $count;
		$this->view->assign('departstr',$departstr);
		$this->view->assign('pmslist',$pmslist);
		$this->view->assign('type',$this->get[2]);
		$this->view->assign('group',$this->get[3]);
		$this->view->assign('count',$count);
		//$this->view->display('box');
		$_ENV['block']->view('box');
	}
	
	function doremove(){
		$messageids = '';
		if($this->get[2]=='single'){
			$alltype = array(1,2,3);
			if(is_numeric($this->post['id']) && in_array($this->post['type'], $alltype)){
				$_ENV['pms']->update_pms($this->post['id'],$this->post['type']);
			}
		}else{
			$removeid = $this->post['checkid'];
			$num = count($removeid);
			$allowlist = array('inbox', 'outbox', 'drafts');
			if(is_array($removeid) && $num>=1 && in_array($this->get[3], $allowlist)){
				switch ($this->get[3]){
					case inbox:
						$type = 1;
						break;
					case outbox:
						$type = 2;
						break;
					case drafts:
						$type = 3;
						break;
				}				
				for($i=0; $i<$num; $i++){
					$messageids .= $removeid[$i].',';
				}
				$messageids = substr($messageids, 0, -1);
				$result = $_ENV['pms']->update_pms($messageids, $type);
				if($result){
					if(!$this->get[4]){
						$this->header('pms-box-'.$this->get[3]);
					}else{
						$this->header('pms-box-'.$this->get[3].'-'.$this->get[4]);
					}
				}else{
					$this->message($this->view->lang['delpmserror'],'index.php?pms-box-'.$type,0);
				}
			}
		}
	}
	
	function dosetread(){
		if($this->post['id']){
			if($_ENV['pms']->set_read($this->post['id'])){
				$message = $_ENV['global']->newpms($this->user['uid']);
			}else{
				$message = false;
			}
			$this->message($message,'',2);
		}
	}
	
	function showmessage(){
		$this->get[3] = empty($this->get[3]) ? NULL : $this->get[3];
		$subject = '';
		$message = '';
		$sendto = '';
		if(is_numeric($this->get[3])){
			$pms = $_ENV['pms']->get_pms($this->get[3]);
			$message = $pms['message'];
			switch($this->get[2]){
				case 'reply':
					$subject = 'Re:'.$pms['subject'];
					$sendto = $pms['from'];
					break;
				case 'forward':
					$subject = $pms['subject'];
					$sendto = '';
					break;
				case 'drafts':
					$subject = $pms['subject'];
					$sendto = $pms['to'];
					break;
				default: 
					break;
			}

		}
			$this->view->assign('subject',$subject);
			$this->view->assign('message',$message);
			$this->view->assign('sendto',$sendto);		
	}
}
?>