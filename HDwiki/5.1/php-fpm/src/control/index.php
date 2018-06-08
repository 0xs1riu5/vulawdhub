<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('category');
	}

 	function dodefault() {
		if($this->setting['compatible']){
		    $all_category=$_ENV['category']->get_category_cache();
		    $categorylist=$_ENV['category']->get_site_category(0,$all_category);
		    $indexcache=$this->cache->getcache('indexcache',$this->setting['index_cache_time']);
		    if(!(bool)$indexcache){
			    $this->load('doc');
			    $recentupdatelist=$_ENV['doc']->get_list_cache(1,'',0,$this->setting['index_recentupdate']);
			    $hotdocs=$_ENV['doc']->get_focus_list(0,$this->setting['index_hotdoc'],2);
			    $wonderdocs=$_ENV['doc']->get_focus_list(0,$this->setting['index_wonderdoc'],3);
			    if(count($wonderdocs)>0){
				    $fistwonderdoc=is_array($wonderdocs)?array_shift($wonderdocs):array();
				    $fistwonderdoc['image'] = str_replace('s_','',$fistwonderdoc['image']);
			    }
			    $commenddocs=$_ENV['doc']->get_focus_list(0,$this->setting['index_commend'],1);
			    $this->load('pic');
			    $piclist=$_ENV['pic']->get_pic(1,0,$this->setting['index_picture']);
			    $cooperatedocs = $_ENV['doc']->cooperatedocs($this->setting['index_cooperate']);
			    @$hottag=unserialize($this->setting['hottag']);
			    $hottag=is_array($hottag)?$hottag:array();
			    $this->load('friendlink');
			    $friendlinklist=$_ENV['friendlink']->get_link_list();
			    $this->load('comment');
			    $recentcommentlist=$_ENV['comment']->recent_comment(0,$this->setting['index_recentcomment']);
			    $indexcache=array(
				    'commenddocs'=>$commenddocs,
				    'hotdocs'=>$hotdocs,
				    'hotdocounts'=>$this->setting['index_hotdoc'],
				    'fistwonderdoc'=>$fistwonderdoc,
				    'wonderdocs'=>$wonderdocs,
				    'piclist'=>$piclist,
				    'cooperatedocs'=>$cooperatedocs,
				    'recentupdatelist'=>$recentupdatelist,
				    'hottag'=>$hottag,
				    'friendlink'=>$friendlinklist,
				    'recentcommentlist'=>$recentcommentlist
				    );
			    $this->cache->writecache('indexcache',$indexcache);
		    }

		    $indexnewscache=$this->cache->getcache('indexnewscache',300);
		    if(!(bool)$indexnewscache){
			    $this->load('doc');
			    $newslist=$_ENV['doc']->getnews();
			    $indexnewscache=array(
				    'newslist'=>$newslist
				    );
			    $this->cache->writecache('indexnewscache',$indexnewscache);
		    }
		    $this->view->assign('indexcache',$indexcache);
		    $this->view->assign('indexnewscache',$indexnewscache);
		    //login
		    if(isset($this->setting['checkcode'])){
			    $this->view->assign('checkcode',$this->setting['checkcode']);
		    }else{
			    $this->view->assign('checkcode',"0");
		    }
		    $loginTip2 = $this->view->lang['loginTip2'];

		    $loginTip2 = str_replace(array('3','15'),array($this->setting['name_min_length'],$this->setting['name_max_length']),$loginTip2);
		    $this->view->assign('name_min_length',$this->setting['name_min_length']);
		    $this->view->assign('name_max_length',$this->setting['name_max_length']);
		    $this->view->assign('passport',defined('PP_OPEN')&&PP_OPEN);
		    $this->view->assign('loginTip2',$loginTip2);
		}
		$_ENV['block']->view('index');
	}

 	function dosettheme() {
		$this->hsetcookie('theme',$this->get[2],24*3600*365);
		$this->header();
	}
}
?>