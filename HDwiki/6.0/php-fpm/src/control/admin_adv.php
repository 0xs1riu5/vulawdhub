<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load("adv");
		$this->load('setting');
		$this->load("category");
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count = $_ENV['adv']->search_adv_num();
		$departstr=$this->multi($count, $num, $page,'admin_adv-default');
		$advlist=$_ENV['adv']->search_adv($start_limit,$num);
		$this->view->assign("advsum",$count);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("advlist",$advlist);
		$this->view->display('admin_adv');
	}
	
	function doconfig(){
		if(isset($this->post['advsubmit'])){
			$settings['advmode']=$this->post['advmode'];
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['adv_config_sucess'],'BACK');
		}else{
			$this->view->assign('advmode',isset($this->setting['advmode'])?$this->setting['advmode']:0);
			$this->view->display('admin_advconfig');
		}
	}
	
	function dounion(){
		$this->view->display('admin_adv_union');
	}
	
	function dosearch(){
		$title=isset($this->post['title'])?trim($this->post['title']):urldecode(trim($this->get[2]));
		$time=isset($this->post['time'])?$this->post['time']:urldecode(trim($this->get[3]));
		$type=isset($this->post['type'])?$this->post['type']:urldecode(trim($this->get[4]));
		$orderby=isset($this->post['orderby'])?$this->post['orderby']:urldecode(trim($this->get[5]));
		$orderby=!empty($orderby)?$orderby:'type';

		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count = $_ENV['adv']->search_adv_num($title,$time,$type);
		
		$advlist=$_ENV['adv']->search_adv($start_limit,$num,$title,$time,$type,$orderby);
		$departstr=$this->multi($count, $num, $page,"admin_adv-search-".urlencode("$title-$time-$type-$orderby"));
		$this->view->assign("advsum",$count);
		
		$titles=stripslashes($title);
		$this->view->assign("title",$titles);
		$this->view->assign("type",$type);
		$this->view->assign("time",$time);
		$this->view->assign("orderby",$orderby);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("advlist",$advlist);
		$this->view->display('admin_adv');
	}

	function doadd(){
		if(isset($this->post['advsubmit'])){
			$type=$this->post[type];
			$advnew=$_ENV['adv']->advnew_filter($this->post[advnew],$type);
			$advid = $_ENV['adv']->add_adv($type);
			if($advid && $advnew){
				$_ENV['adv']->update_adv($advid,$advnew);
				$this->cache->removecache('advertisement');
				$this->cache->removecache('setting');
				$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			}
			$this->message($this->view->lang['adv_add_sucess'],'index.php?admin_adv');
		}else{
			$position=isset($this->get[2])?$this->get[2]:0;
			$view_type=$_ENV['adv']->view_filter($position);
			if(isset($view_type['adv_range'])){$this->view->assign('adv_range',$view_type['adv_range']);}
			if(isset($view_type['dis_pos'])){$this->view->assign('dis_pos',$view_type['dis_pos']);}
			if(isset($view_type['isfloat'])){$this->view->assign('isfloat',$view_type['isfloat']);}
			$this->view->assign('position',$position);
			$this->view->assign('adv_position',$this->view->lang['adv_position_'.$position]);
			$this->view->assign('adv_note',$this->view->lang['adv_note_'.$position]);
			$this->view->display('admin_advadd');
		}
	}
	
	function doedit(){
		if(isset($this->post['advsubmit'])){
			$type=$this->post[type];
			$advnew=$_ENV['adv']->advnew_filter($this->post[advnew],$type);
			$advid =isset($this->post['advid'])?$this->post['advid']:$_ENV['adv']->add_adv($type);
			if($advid && $advnew){
				$_ENV['adv']->update_adv($advid,$advnew);
				$this->cache->removecache('advertisement');
				$this->cache->removecache('setting');
				$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			}
			$this->message($this->view->lang['adv_edit_sucess'],'index.php?admin_adv');
		}elseif(isset($this->get[2])){
			$adv=$_ENV['adv']->adv_admin_filter($this->db->fetch_by_field('advertisement','advid',$this->get[2]));
			$position=$adv['type'];
			$view_type=$_ENV['adv']->view_filter($position);
			if(isset($view_type[adv_range])){$this->view->assign('adv_range',$view_type[adv_range]);}
			if(isset($view_type[dis_pos])){$this->view->assign('dis_pos',$view_type[dis_pos]);}
			if(isset($view_type[isfloat])){$this->view->assign('isfloat',$view_type[isfloat]);}
			
			$this->view->assign('adv',$adv);
			$this->view->assign('position',$position);
			$this->view->assign('adv_position',$this->view->lang['adv_position_'.$position]);
			$this->view->assign('adv_note',$this->view->lang['adv_note_'.$position]);

			$this->view->display('admin_advadd');
		}elseif(isset($this->post['advid'])){
			$advid=$this->post['advid'];
			$_ENV['adv']->update_available($advid);
			$this->cache->removecache('advertisement');
			$this->message('ok','',2);
		}
	}

	function doremove(){
		@$advids=$this->post['advid'];
		if(is_array($advids) && !empty($advids)){
			if($_ENV['adv']->removeadv($advids)){
				file::cleardir(HDWIKI_ROOT.'/data/cache');
				file::cleardir(HDWIKI_ROOT.'/data/view');
				$this->message($this->view->lang['del_adv_sucess'],'index.php?admin_adv');
			}else{
				$this->message($this->view->lang['del_adv_faile'],'index.php?admin_adv');
			}
		}else{
			$this->message($this->view->lang['docRemoveAdvNull']);
		}
	}
	
}
?>