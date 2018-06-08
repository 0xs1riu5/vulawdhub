<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load('setting');
		$this->load("category");
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	function dodefault(){
		$this->dolist();
	}

	/*Manage category*/
	function dolist(){
		$pid =isset($this->get[2])?intval($this->get[2]):0;
		$pcat = $_ENV['category']->get_category($pid);
		$catnavi = unserialize($pcat['navigation']);
		$cats = $_ENV['category']->get_subcate($pid);
		$this->view->assign('catnavi',$catnavi);
		$this->view->assign('pid',$pid);
		$this->view->assign('cats',$cats);
		$this->view->display('admin_category');
	}

	/*add category*/
	function doadd(){
		if(isset($this->post['submit']) && $this->post['submit']){
			$pid = intval($this->post['pcid']);
			$catnames = $this->post['catname'];
			$ico='';
			$discrib='';
			if($catnames){
				foreach($catnames as $catname){
					$catname = trim(string::stripscript($catname));
					if($catname){
						if(!$_ENV['category']->add_category($pid,$catname,$ico,$discrib)){
						//	$this->message($this->view->lang['CateExsit'],'index.php?admin_category-list-'.$pid);
						}
					}
				}
				$this->cache->removecache('category');
			}
			$this->message($this->view->lang['addCateSuccess'],'index.php?admin_category-list-'.$pid);
		}else{
			$cid =isset($this->get[2]) ? intval($this->get[2]) : 0;
			$cats = $_ENV['category']->get_all_category();
			if($cats){
				$cats = $_ENV['category']->get_categrory_tree($cats);
			}
			$this->view->assign('tcat',$_ENV['category']->get_category($cid));
			$this->view->assign('cats',$cats);
			$this->view->display('admin_catadd');
		}
	}

	function dobatchedit(){
		$pid =isset($this->get[2])?intval($this->get[2]):0;
		$hiddencid = $this->post['hiddencid'];
		$cid = $this->post['cateid'];
		if(is_array($cid)){
			foreach($cid as $key=>$value){
				$cpid = explode('-',$key);
				$_ENV['category']->edit_category($cpid[0],$cpid[1],$value,'','');
			}
		}
		$orders = array();
		$orders = $this->post['order'];
		$catnames = $this->post['catname'];
		if($catnames){
			foreach($catnames as $catname){
				$catname = trim(string::stripscript($catname));
				if($catname){
					$_ENV['category']->add_category($hiddencid,$catname,'','');
					$orders[] = $_ENV['category']->get_cate_info($hiddencid,$catname);
				}
			}
		}
		if($orders){
			foreach($orders as $order => $cid){
				$_ENV['category']->order_category(intval($cid),$order);
			}
		}
		$this->cache->removecache('category');
		$this->message($this->view->lang['editCateSuccess'],'index.php?admin_category-list-'.$hiddencid);
	}

	/*edit category*/
	function doedit(){
		if(isset($this->post['submit'])){
			$cid = intval($this->post['catid']);
			$pid = intval($this->post['pcid']);
			$catname = $this->post['catname'];
			$ico='';
			$discrib='';
			$allcategory=$_ENV['category']->get_category_cache();
			$subcid=substr($_ENV['category']->get_all_subcate($cid,$allcategory),1);
			if($cid==$pid || ($subcid && in_array($pid,explode(",",$subcid)))){
				$this->message($this->view->lang['editCateWrong'],'BACK');
			}
			$catname = string::stripscript($catname);
			$_ENV['category']->edit_category($cid,$pid,$catname,$ico,$discrib);
			$this->cache->removecache('category');
			$this->message($this->view->lang['editCateSuccess'],'index.php?admin_category-list-'.$pid);
		}else{
			$cid = intval($this->get[2]);
			$pid = intval($this->get[3]);
			if(!$cid)$this->message($this->view->lang['CateParaWrong'],'index.php?admin_category-list');
			$cat = $_ENV['category']->get_category($cid);
			$cats = $_ENV['category']->get_all_category();
			$cats = $_ENV['category']->get_categrory_tree($cats);
			$this->view->assign('pid',$pid);
			$this->view->assign('cat',$cat);
			$this->view->assign('cats',$cats);
			$this->view->display('admin_editcategory');
		}
	}

	/*remove category*/
	function doremove(){
		$cid = intval($this->get[2]);
		$tcat = $_ENV['category']->get_category($cid);
		$_ENV['category']->remove_category($cid);
		$this->cache->removecache('category');
		header('location:index.php?admin_category-list-'.$tcat['pid']);
	}

	/*Category order*/
	function doreorder(){
		$orders = explode(",",$this->post['order']);
		$hid = intval($this->post['hiddencid']);
		foreach($orders as $order => $cid){
			$_ENV['category']->order_category(intval($cid),$order);
		}
		$this->cache->removecache('category');
		header('location:index.php?admin_category-list-'.$hid);
	}

	/*Merger category*/
	function domerge(){
		if(isset($this->post['submit'])){
			$sourceid = $this->post['source'];
			$objectid = $this->post['object'];
			if($objectid == $sourceid){
				$this->message($this->view->lang['CateNameSame'],'BACK');
			}else{
				if($_ENV['category']->merge_category($sourceid,$objectid)){
					$this->cache->removecache('category');
					$this->message($this->view->lang['CateMergerSuccess'],'index.php?admin_category-list-'.$sourceid);
				}else{
					$this->message($this->view->lang['CateMergerFalse'],'BACK');
				}
			}
		}else{
			$cats = $_ENV['category']->get_all_category();
			$cats = $_ENV['category']->get_categrory_tree($cats);
			$this->view->assign('cats',$cats);
			$this->view->display('admin_joincategoy');
		}
	}
}

?>