<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('regular');
		$this->load('usergroup');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$regularlist=$_ENV['regular']->get_all_list();
		$regulargrouplist=$_ENV['regular']->get_group_list();
		$regulargroup = array();
		foreach($regulargrouplist as $value){
			$regulargroup[$value['id']] = $value['title'];
		}
		foreach($regularlist as $key => $value){
			$regularlist[$key]['groupname'] = $regulargroup[$value['regulargroupid']];
		}
		
		$this->view->assign('regulargrouplist', $regulargrouplist);
		$this->view->assign('regularlist', $regularlist);
		$this->view->display('admin_regularlist');
	}
	
	/*regular list*/
	function dolist(){
		$regularlist=$_ENV['regular']->get_all_list();
		$regulargrouplist=$_ENV['regular']->get_group_list();
		
		$regulargroup = array(0=>'<a href="javascript:;">'.$this->view->lang['regularGroupSelect'].'</a>');
		foreach($regulargrouplist as $value){
			$regulargroup[$value['id']] = '<a href="javascript:;">'.$value['title'].'</a>';
		}
		
		foreach($regularlist as $key => $value){
			$regularlist[$key]['groupname'] = $regulargroup[$value['regulargroupid']];
		}
		
		$this->view->assign('regulargrouplist', $regulargrouplist);
		$this->view->assign('regularlist', $regularlist);
		$this->view->display('admin_regular');
	}
	
	/*add regular*/
	function doadd(){
		if(isset($this->post['submit'])){
			$regularname=trim($this->post['regularname']);
			$regularexpr = trim($this->post['regularexpr']);
			$regulargroupid = $this->post['regulargroupid'];
			$regulargroupid=is_numeric($regulargroupid)?intval($regulargroupid):0;
		    $_ENV['regular']->add_regular($regularname, $regularexpr, $regulargroupid);
			$this->message($this->view->lang['regularAddSuccess'],'index.php?admin_regular');
		}else{
			$this->dolist();
		}
	}
	
	/*edit regular*/
	function doedit(){
		$regularname=string::hiconv(trim($this->post['regularname']));
		$regularexpr = trim($this->post['regularexpr']);
		$regulargroupid = $this->post['regulargroupid'];
		$regulargroupid=is_numeric($regulargroupid)?intval($regulargroupid):0;
		$regularname = string::hiconv($regularname);
	    $_ENV['regular']->edit_regular($regularname, $regularexpr, $regulargroupid,$this->post['id']);
		$this->message($this->view->lang['regularEditSuccess'],'index.php?admin_regular');
	}
	
	/*remove regular*/
	function doremove(){
		if(!empty($this->post['id'])){
			$_ENV['regular']->remove_regular($this->post['id']);
			$this->message($this->view->lang['regularDelSuccess'],'index.php?admin_regular');
		}else{
			$this->message($this->view->lang['regularChooseNull']);
		}
	}
	
	function doremovegroup(){
		if(!is_numeric($this->post['id'])){
			exit('FALSE');
		}
		$_ENV['regular']->remove_regulargroup($this->post['id']);
		echo 'OK';
	}
	
	function dogrouplist(){
		$regulargrouplist=$_ENV['regular']->get_group_list();
		$i=1;
		foreach($regulargrouplist as $key=>$value){
			$regulargrouplist[$key]['listid'] = $i++;
		}
		
		$this->view->assign('regulargrouplist', $regulargrouplist);

		$this->view->display('admin_regulargroup');	}
	
	/* edit one groupid */
	function doeditone(){
		$id = $this->post['id'];
		$groupid = $this->post['groupid'];
		$id=is_numeric($id)?intval($id):0;
		$groupid=is_numeric($groupid)?intval($groupid):0;
		if(!$id || !$groupid){
			exit;
		}
		$this->db->update_field('regular','regulargroupid',$groupid,"id=$id");
		echo 'OK';
	}
	
	function doeditgroup(){
		$bool = $_ENV['regular']->edit_regulargroup($this->post);
		if ($bool) echo 'OK';
	}
	
	function doaddgroup(){
		$titles = $this->post['titles'];
		$titles = trim($titles);
		if($titles==''){
			exit($this->view->lang['regularGroupAddTip1']);
		}
		$bool = $_ENV['regular']->add_regulargroup($titles);
		if ($bool) echo 'OK';
	}
	
	function dogroupset(){
		$groupid = $this->get[2];
		if(is_numeric($groupid)){
			$groupid =intval($groupid);
		}else{
			$groupid =2;
		}
		
		$usergroup = $_ENV['usergroup']->get_usergroup($groupid);
		$regularlist=$_ENV['regular']->get_all_list();
		$regulargrouplist=$_ENV['regular']->get_group_list();
		$usergrouplist=$_ENV['usergroup']->get_all_list(-1, 'g.`type` desc, g.creditslower');
		
		foreach($regularlist as $key =>$row){
			$regular = explode('|',$row['regular']);
			if (strpos('|'.$usergroup['regulars'].'|', '|'.$regular[0].'|') !== false){
				$regularlist[$key]['checked']=true;
			}
		}
		
		foreach($usergrouplist as $key =>$row){
			if (empty($row['regulars']) && $row['type'] == 1){
				unset($usergrouplist[$key]);
			}
		}
		foreach($regulargrouplist as $key =>$row){
			if (($usergroup['groupid'] == 1 || $usergroup['groupid'] == 16 ||$usergroup['type'] != 1) && $row['type'] ==1){
				unset($regulargrouplist[$key]);
			}			
		}
		
		$this->view->assign('ISOFFICIAL', false);
		$this->view->assign('groupid', $groupid);
		$this->view->assign('usergrouplist', $usergrouplist);
		$this->view->assign('regulargrouplist', $regulargrouplist);
		$this->view->assign('regularlist', $regularlist);
		$this->view->display('admin_regularset');
	}
	
	function dosetrelation(){
		$action = $this->get[2];
		$idleft = $this->get[3];
		$idright = $this->get[4];
		
		$idleft=is_numeric($idleft)?intval($idleft):0;
		$idright=is_numeric($idright)?intval($idright):0;
		if(!$idleft || !$idright){
			exit;
		}
		
		$idlist=array($idleft => $idright);
		if ('add' == $action){
			$_ENV['regular']->add_relation($idlist);
		}else if ('remove' == $action){
			$_ENV['regular']->remove_relation($idlist);
		}
		echo'OK';
	}
	
	function dogetrelation(){
		$id = $this->get[2];
		$action = $this->get[3];
		$id=is_numeric($id)?intval($id):0;
		if($action != 'left' && $action != 'right' && $action != 'both'){
			exit;
		}
		
		$idlist = $_ENV['regular']->get_relation($id, $action);
		
		echo 'OK';
		if ($idlist['idright']){ echo implode($idlist['idright'], ',');}
		echo '|';
		if ($idlist['idleft']){ echo implode($idlist['idleft'], ',');}
	}
}
?>