<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('usergroup');
		$this->load('regular');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$this->dolist();
	}
	
	/*usergroup list*/
	function dolist(){
		$usergroupType = $this->hgetcookie('group');
		$displayMember = 'none';
		$displaySystem = 'none';
		$displaySpecial = 'none';
		$usergroupMember = '';
		$usergroupSystem = '';
		$usergroupSpecial = '';
		switch($usergroupType){
		case 'usergroupMember':
			$displayMember = '';
			$usergroupMember = 'current';
			break;
		case 'usergroupSystem':
			$displaySystem = '';
			$usergroupSystem = 'current';
			break;
		case 'usergroupSpecial':
			$displaySpecial = '';
			$usergroupSpecial = 'current';
			break;
		default:
			$displayMember = '';
			$usergroupMember = 'current';
		}
		$this->view->assign('usergroupMember', $usergroupMember);
		$this->view->assign('usergroupSystem', $usergroupSystem);
		$this->view->assign('usergroupSpecial', $usergroupSpecial);
		$this->view->assign('displayMember', $displayMember);
		$this->view->assign('displaySystem', $displaySystem);
		$this->view->assign('displaySpecial', $displaySpecial);
		$usergrouplist=$_ENV['usergroup']->get_all_list(1);
		$this->view->assign('usergroupSystemList', $usergrouplist);
		$usergrouplist=$_ENV['usergroup']->get_all_list(2, 'g.creditslower asc');
		if (is_array($usergrouplist)){
			$x = count($usergrouplist);
			for($i=0; $i<$x; $i++){
				if ($i == 0){
					$usergrouplist[$i]['creditshigher'] = 0;
				}else if($i > 0 && $i == $x -1){
					$usergrouplist[$i]['creditshigher'] = 999999999;
				}else{
					$usergrouplist[$i]['creditshigher'] = $usergrouplist[$i+1]['creditslower'];
				}
				
				if ($usergrouplist[$i]['groupid'] == 2 || $usergrouplist[$i]['groupid'] == 5){
					$usergrouplist[$i]['empty'] = 0;
				}else{
					$usergrouplist[$i]['empty'] = $_ENV['usergroup']->is_empty($usergrouplist[$i]['groupid']);
				}
			}
		}
		$this->view->assign('usergroupMemberList', $usergrouplist);
		$usergrouplist=$_ENV['usergroup']->get_all_list(0);
		if (is_array($usergrouplist)){
			$x = count($usergrouplist);
			for($i=0; $i<$x; $i++){
				$usergrouplist[$i]['empty'] = $_ENV['usergroup']->is_empty($usergrouplist[$i]['groupid']);
			}
		}
		$this->view->assign('usergroupSpecialList', $usergrouplist);
		$this->view->display('admin_usergroup');
	}
	
	/*add usergroup*/
	function doadd(){
	    $_ENV['usergroup']->add_usergroup( trim($this->post['grouptitle']) );
		$this->message($this->view->lang['usergroupAddSuc'],'index.php?admin_usergroup-list');
	}
	
	/* change usergroup */
	function dochange(){
		$usergroupType = $this->post['usergroupType'];
		$this->hsetcookie('group', $usergroupType);
		switch($usergroupType){
			case 'usergroupMember':
				unset($this->post['usergroupadd']['MEMBER_I']);
				$deleteids = $this->post['deleteids'];
				$usergroup = $this->post['usergroup'];
				if (is_array($deleteids)){
					foreach($deleteids as $groupid){
						unset($usergroup[$groupid]);
					}
					$_ENV['usergroup']->change_usergroup($deleteids, 2,'delete');
				}
				if (count($usergroup) > 0)
				$_ENV['usergroup']->change_usergroup($usergroup, 2,'update');
				$usergroupadd = $this->post['usergroupadd'];
				if (count($usergroupadd) > 0)
				$_ENV['usergroup']->change_usergroup($usergroupadd, 2,'insert');
				$_ENV['usergroup']->cache_memberlevel();
				break;
			case 'usergroupSystem':
				$usergroup = $this->post['usergroup'];
				$_ENV['usergroup']->change_usergroup($usergroup, 1,'update');
				break;
			case 'usergroupSpecial':
				unset($this->post['usergroupadd']['SPECIAL_I']);
				$usergroup = $this->post['usergroup'];
				$deleteids = $this->post['deleteids'];
				if (is_array($deleteids)){
					foreach($deleteids as $groupid){
						unset($usergroup[$groupid]);
					}
					$_ENV['usergroup']->change_usergroup($deleteids, 1,'delete');
				}
				if (count($usergroup) > 0)
				$_ENV['usergroup']->change_usergroup($usergroup, 0,'update');
				$usergroupadd = $this->post['usergroupadd'];
				if (count($usergroupadd) > 0)
				$_ENV['usergroup']->change_usergroup($usergroupadd, 0,'insert');
				break;	
		}
		$this->message($this->view->lang['usergroupEditSuc'],'index.php?admin_usergroup');
	}
	
	/*edit usergroup*/
	function doedit(){
		$groupid=isset($this->get[2])?$this->get[2]:$this->post['groupid'];
		if(4==$groupid){
			$this->message($this->view->lang['usergroupNotEdit']);
		}
		if(!isset($this->post['submit']) && !isset($this->post['default'])){
		 	$regularlist=$_ENV['regular']->get_all_list();
		 	$usergroup=$_ENV['usergroup']->get_usergroup($groupid);
		  	$groupregulars=explode('|', $usergroup['regulars']);
		 	$this->view->assign('regularlist', $regularlist);
		 	$this->view->assign('usergroup', $usergroup);
		 	$this->view->assign('groupregulars', $groupregulars);
			$this->view->display('admin_editusergroup');
		}else{
			if($this->post['default']){
				$_ENV['usergroup']->resume_usergroup($groupid);
			}else{
				$regular=isset($this->post['regular'])?$this->post['regular']:array();
				$groupregulars=implode('|', $regular);
				
		        $_ENV['usergroup']->edit_usergroup($groupid,$this->post['grouptitle'],$groupregulars);
		    }
			
			$this->message($this->view->lang['usergroupEditSuc'],'index.php?admin_regular-groupset-'.$groupid);
		}	
	}
	
	/*remove usergroup*/
	function doremove(){
		$groupid=isset($this->get[2])?$this->get[2]:$this->post['groupid'];
		if($groupid<5){
			$this->message($this->view->lang['usergroupCanNotDel']);
		}
		if(!isset($this->post['submit'])){
			$usergrouplist=$_ENV['usergroup']->get_all_list();
			$this->view->assign('usergrouplist', $usergrouplist);
			$this->view->assign('groupid', $groupid);
			$this->view->display('admin_usergrouptip');
		}else{
 			$_ENV['usergroup']->remove_usergroup($groupid,$this->post['destgroupid']);
			$this->message($this->view->lang['usergroupDelSuc'],'index.php?admin_usergroup-list');
		}
	}
}
?>