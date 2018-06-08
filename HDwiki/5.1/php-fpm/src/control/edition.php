<?php

!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('doc');
		$this->load('usergroup');
		$this->load('category');
	}
	
	function dolist() {
		if(!@is_numeric($this->get[2])){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}
		$doc=$this->db->fetch_by_field('doc','did',$this->get[2]);
		if(!(bool)$doc){
			$this->message($this->view->lang['docNotExist'],'BACK',0);
		}
		$doc['time']=$this->date($doc['time']);
		$doc['tag']=$_ENV['doc']->spilttags($doc['tag']);
		$doc['rawtitle'] = $doc['title'];
		$doc['title']=htmlspecialchars(stripslashes($doc['title']));
		$category=$_ENV['category']->get_category($doc['cid']);
		$navigation=unserialize($category['navigation']);
		$editionlist=$_ENV['doc']->get_edition_list($this->get[2], '`eid`,`time`,`author`,`authorid`,`big`,`excellent`,`reason`,`coins`');
		if(is_array($editionlist) && !empty($editionlist)){
			$uids = array();
			foreach($editionlist as $value){
				$id = $value['authorid'];
				$uids[$id] = $id;
			}
			$userstars = $_ENV['usergroup']->get_userstar($uids);
			foreach($editionlist as $key => $value){
				$editionlist[$key]['grouptitle'] = $userstars[$value['author']]['grouptitle'];
				$editionlist[$key]['stars'] = $userstars[$value['author']]['stars'];
				$editionlist[$key]['editorstar'] = $userstars[$value['author']]['userstars'];
			}
		}
		$checkable['copy']=$this->checkable('edition-copy');
		$checkable['remove']=$this->checkable('edition-remove');
		$checkable['excellent']=$this->checkable('edition-excellent');
		$biglist = false;
		if (is_array($editionlist) && count($editionlist) > 20) $biglist = true;
		$this->view->assign('biglist',$biglist);
		$this->view->assign('checkable',$checkable);
		$this->view->assign('doc',$doc);
		$this->view->assign('editionlist',$editionlist);
		$this->view->assign('navigation',$navigation);
		$this->view->assign('navtitle',$doc['title'].'-'.$this->view->lang['edition'].'-');
		//$this->view->display('editionlist');
		$_ENV['block']->view('editionlist');
	}
	
	function doview(){
		if(!empty($this->setting['check_useragent'])) {
			$this->load('anticopy');
			if(!$_ENV['anticopy']->check_useragent()){
				$this->message('禁止访问','',0);
			}
		}
		if(!empty($this->setting['check_visitrate'])) {
			$this->load('anticopy');
			$_ENV['anticopy']->check_visitrate();
		}
		if(!@is_numeric($this->get[2])||!@is_numeric($this->get[3])){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}
		$edition=$_ENV['doc']->get_edition($this->get[2]);
		if(!$edition){
			$this->message($this->view->lang['edtionNotExist'],'index.php?category',0);
		}
		$nav_edition=$_ENV['doc']->get_nav_edition($this->get[2]);
		$edition['previous']=$nav_edition['previous'];
		$edition['next']=$nav_edition['next'];
		$edition['latest']=$nav_edition['latest'];
		$doc=$this->db->fetch_by_field('doc','did',$edition['did']);
		$doc['editions']=$nav_edition['count'];
		if(!(bool)$edition){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}
		if($doc['visible']=='0'&&!$this->checkable('admin_doc-audit')){
			if(date($doc['lastedit'])==$edition['comtime'])
			{
			$this->message($this->view->lang['viewDocTip4'],'index.php',0);
			}
		}
		$this->view->vars['setting']['seo_keywords']=$doc['tag'];
		$this->view->vars['setting']['seo_description']=$doc['summary'];
		$doc['version']=$this->get[3];
		if ($doc['version'] == '0'){
			$doc['version'] = $doc['editions'];
		}
		$doc['lastedit']=date('Y-m-d',$doc['lastedit']);
		$doc['time']=date('Y-m-d',$doc['time']);
		$doc['tag']=$_ENV['doc']->spilttags($doc['tag']);

		$category=$_ENV['category']->get_category($doc['cid']);
		$navigation=unserialize($category['navigation']);
		$edition['sectionlist']=$_ENV['doc']->splithtml($edition['content']);
		$sectionlist=$_ENV['doc']->getsections($edition['sectionlist']);
		$doc['editors']=$_ENV['doc']->get_editor_num($doc['did']);
		
		$author = $_ENV['usergroup']->get_groupinfo($edition['authorid']);
		
		$audit=$this->checkable('admin_doc-audit');
		$this->view->assign('audit',$audit);
		$this->view->assign('author',$author);
		$this->view->assign('doc',$doc);
		$this->view->assign('edition',$edition);
		$this->view->assign('sectionlist',$sectionlist);
		$this->view->assign('navigation',$navigation);
		$this->view->assign('navtitle',$doc['title']);
		$_ENV['block']->view('viewedition');
		//$this->view->display('viewedition');
	}
	
	function docompare(){
		if(!empty($this->setting['check_useragent'])) {
			$this->load('anticopy');
			if(!$_ENV['anticopy']->check_useragent()){
				$this->message('禁止访问','',0);
			}
		}
		if(!empty($this->setting['check_visitrate'])) {
			$this->load('anticopy');
			$_ENV['anticopy']->check_visitrate();
		}
		if ($this->get[4] == 'box'){
			@header('Content-type: text/html; charset='.WIKI_CHARSET);
			if(!@is_numeric($this->get[2])||!@is_numeric($this->get[3])){
				$this->message($this->view->lang['parameterError'],'index.php',0);
			}
			$did = $this->get[2];
			$eid = $this->get[3];
			$edition = array();	
			$editions=$_ENV['doc']->get_edition_list($did,'`time`,`authorid`,`author`,`words`,`images`,`content`', $eid);
			
			$this->view->assign('edition',$editions);
			$this->view->display('comparebox');
			exit;
		}
		if(@!is_numeric($this->post['eid'][0])||@!is_numeric($this->post['eid'][1])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$edition=$_ENV['doc']->get_edition($this->post['eid']);
		if($edition[0]['did']!=$edition[1]['did']){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$doc=$this->db->fetch_by_field('doc','did',$edition[0]['did']);
		$doc['rawtitle']=$doc['title'];
		if(@$doc['visible']=='0'&&!$this->checkable('admin_doc-audit')){
			$this->message($this->view->lang['viewDocTip4'],'index.php',0);
		}
		$edition[0]['tag']=$_ENV['doc']->spilttags($edition[0]['tag']);
		$edition[0]['editions']=$this->post['editions_'.$edition[0]['eid']];
		$edition[1]['tag']=$_ENV['doc']->spilttags($edition[1]['tag']);
		$edition[1]['editions']=$this->post['editions_'.$edition[1]['eid']];
		$doc['title']=$edition[0]['title'];
		$doc['did']=$edition[0]['did'];
		$this->view->assign('doc',$doc);
		$this->view->assign('edition',$edition);
		//$this->view->display('compare');
		$_ENV['block']->view('compare');
	}
	
	function doremove(){
		$did=isset($this->post['did'])?$this->post['did']:$this->get[2];
		$eids=isset($this->post['eid'])?$this->post['eid']:array($this->get[3]);
		foreach($eids as $eid){
			if(!is_numeric($eid)&&!is_numeric($did)){
				$this->message($this->view->lang['parameterError'],'BACK',0);
			}
		}
		$result=$_ENV['doc']->remove_edition($eids, $did);
		if(!(bool)$result){
			$this->message($this->view->lang['viewDocTip3'],'BACK',0);
		}else{
			$doc=$_ENV['doc']->get_edition_list($did);
			if((bool)$doc){
				$this->header("edition-list-".$did);
			}else{
				$this->header('doc-view-'.$did);
			}
		}
	}
	
	function doexcellent(){
		foreach(@$this->post['eid'] as $eid){
			if(!is_numeric($eid)){
				$this->message($this->view->lang['parameterError'],'BACK',0);
			}
		}
		$result=$_ENV['doc']->set_excellent_edition($this->post['eid']);
		if(!(bool)$result){
			$this->message($this->view->lang['viewDocTip3'],'BACK',0);
		}else{
			$this->header("edition-list-".$this->post['did']);
		}
	}
	
	function dounexcellent(){
		if(!is_numeric($this->post['eid'])){
			$this->message("-1","",2);
		}
		$result=$_ENV['doc']->set_excellent_edition($this->post['eid'],0);
		if(!(bool)$result){
			$this->message("0","",2);
		}else{
			$this->message("1","",2);
		}
	}
	
	function docopy(){
		if(!is_numeric($this->post['eid'][0])){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}
		$result=$_ENV['doc']->copy_edition($this->post['eid'][0]);
		if(!(bool)$result){
			$this->message($this->view->lang['viewDocTip3'],'BACK',0);
		}else{
			$this->header("edition-list-".$this->post['did']);
		}
	}
}
?>