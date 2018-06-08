<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('category');
		$this->load('doc');
	}

	function dodefault(){
		$this->doview();
	}
	
	function doview(){
		$allcategory=$_ENV['category']->get_category_cache();
		if(!is_numeric($this->get[2])){
			$subcategory=$_ENV['category']->get_site_category(0,$allcategory);
			$this->view->assign('subcategory',$subcategory);
			$indexcache=$this->cache->getcache('indexcache',$this->setting['index_cache_time']);
			if(!(bool)$indexcache){
			    @$hottag=unserialize($this->setting['hottag']);
			    $cooperatedocs=empty($this->setting['cooperatedoc'])?array():explode(";", $this->setting['cooperatedoc']);
			    $this->view->assign('navtitle',$this->view->lang['wikiCategory'].'-');
			    $this->view->assign('hottag',$hottag);
			    $this->view->assign('cooperatedocs',$cooperatedocs);
			}
			$_ENV['block']->view('categorylist');
			//$this->view->display("categorylist");
		}else{
			$category=$_ENV['category']->get_category($this->get[2]);
			if(!(bool)$category){
				$this->message($this->view->lang['categoryNotExist'],'BACK',0);
			}
			$this->get[3] = empty($this->get[3]) ?NULL : $this->get[3] ;
			$page = max(1, intval($this->get[3]));
			$start_limit = ($page - 1) * $this->setting['category_view'];
		
			$cids=$this->get[2].$_ENV['category']->get_all_subcate($this->get[2],$allcategory);
			$count=$_ENV['doc']->get_totalnum_by_cid($cids);
			$list=$_ENV['doc']->get_docs_by_cid($cids,$start_limit,$this->setting['category_view']);
			$departstr=$this->multi($count, $this->setting['category_view'], $page,'category-view-'.$this->get[2]);
			
			$subcategory=$_ENV['category']->get_site_category($this->get[2],$allcategory);
			$category['navigation']=unserialize($category['navigation']);
			$this->view->assign('list',$list);
			$this->view->assign('navtitle',$category['name'].'-');
			$this->view->assign('subcategory',$subcategory);
			$this->view->assign('category',$category);
			$this->view->assign('departstr',$departstr);
			$this->view->assign('count',$count);
			$_ENV['block']->view('category');
			//$this->view->display("category");
		}
	}

	function doletter(){
		$allcategory=$_ENV['category']->get_category_cache();
		if(0==$this->get[2]||!preg_match("/^[\w]|\*$/i",$this->get[3])){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}else{
			$category=$_ENV['category']->get_category($this->get[2]);
			if(!(bool)$category){
				$this->message($this->view->lang['categoryNotExist'],'BACK',0);
			}
			$this->get[4] = empty($this->get[4]) ? NULL : $this->get[4];
			$page = max(1, intval($this->get[4]));
			$start_limit = ($page - 1) * $this->setting['category_letter'];
			
			$count=$_ENV['doc']->get_totalnum_by_cid($this->get[2],$this->get[3]);
			$list=$_ENV['doc']->get_docs_by_cid($this->get[2],$start_limit,$this->setting['category_letter'],$this->get[3]);			
			$departstr=$this->multi($count, $this->setting['category_letter'], $page,'category-letter-'.$this->get[2].'-'.$this->get[3]);
			$subcategory=$_ENV['category']->get_site_category($this->get[2],$allcategory);
			$category['navigation']=unserialize($category['navigation']);
			$this->view->assign('list',$list);
			$this->view->assign('subcategory',$subcategory);
			$this->view->assign('category',$category);
			$this->view->assign('letter',strtoupper($this->get[3]));
			$this->view->assign('departstr',$departstr);
			$_ENV['block']->view('category');
			//$this->view->display("category");
		}
	}
	
	function doajax(){
		$catid = isset($this->get[2])?$this->get[2]:0;
		$catid = intval($catid);
		$content = $_ENV['category']->get_cat($catid);
		$this->message($content,'',2);
	}
}
?>