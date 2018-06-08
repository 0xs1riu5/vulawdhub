<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->view->setlang($this->setting['lang_name'], 'back');
		$this->load("nav");
		$this->load("navmodel");
		$this->load("doc");
	}

	function dodefault(){
		$this->dosearch();
	}
	
	/**
	 * 搜索
	 */
	function dosearch(){
		$name = isset($this->post['name']) ? trim($this->post['name']) : urldecode(trim($this->get[2]));
		$postion = isset($this->post['postion'])?trim($this->post['postion']):urldecode(trim($this->get[3]));
		
		$starttime = isset($this->post['qstarttime']) ? strtotime($this->post['qstarttime']) : (int)$this->get[4];
		$endtime = isset($this->post['qendtime']) && $this->post['qendtime'] ? (strtotime($this->post['qendtime'])+24*3600) : (int)$this->get[5];

		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage']) ? $this->setting['list_prepage'] : 20;
		$start_limit = ($page - 1) * $num;
		
		$count = $_ENV['nav']->search_nav_num($name, $postion, $starttime, $endtime);
		$searchdata='admin_nav-search-'.urlencode("$name-$postion-$starttime-$endtime");
		$departstr = $this->multi($count, $num, $page, $searchdata);
		$navlist = $_ENV['nav']->search_nav($start_limit, $num, $name, $postion, $starttime, $endtime);

		$name=stripslashes($name);
		//echo $names;
		$this->view->assign("searchdata", $searchdata.'-'.$page);
		$this->view->assign("navum", $count);
		$this->view->assign("name", $name);
		$this->view->assign("postion", $postion);
		$this->view->assign("qstarttime", $starttime?date("Y-m-d", $starttime):"");
		$this->view->assign("qendtime", $endtime?date("Y-m-d", $endtime-24*3600):"");
		$this->view->assign("departstr", $departstr);
		$this->view->assign("navlist", $navlist);
		$this->view->display('admin_nav');
	}
	
	/**
	 * 添加
	 */
	function doadd(){
		$step = isset($this->get[2]) ? intval($this->get[2]) : 1;
		switch($step){
			case 1:
				$navmodellist = $_ENV['navmodel']->get_all(array('status'=>1));
				$this->view->assign("navmodellist", $navmodellist);
				break;
			case 2:
				$navname = string::stripscript(trim($this->post['navname']));
				if(!$navname){
					$this->message('该导航模块名称不能为空!','BACK');
				}
				if($_ENV['nav']->get_by_navname($navname)){
					$this->message('该导航名称已经存在!','BACK');
				}
				//取分类信息
				$this->load('category');
				$all_category = $_ENV['category']->get_category_cache();
				$catstr = $_ENV['category']->get_categrory_tree($all_category);			
				$content = trim($this->post['content']);
				$position = intval($this->post['position']);				
				$nav = array('name'=>$navname, 'position'=>$position, 'code'=>$content, 'time'=>$this->time, 'lastedit'=> $this->time, 'lasteditor'=> $this->user['username'], 'lasteditorid'=> $this->user['uid']);
				$navid = $_ENV['nav']->add($nav);
				$this->view->assign("navid", $navid);
				$this->view->assign("catstr",$catstr);
				break;
			case 3:
				$navid = intval($this->post['navid']);
				$content = trim($this->post['content']);
				$docs = explode(';', $content);
				$_ENV['nav']->addlink($navid, $docs);
				$this->message('添加成功','index.php?admin_nav');
				break;
			default:
				break;
		}
		$this->view->assign("step", $step);
		$this->view->display('admin_navadd');
	}
	
	/**
	 * 热词
	 */
	function dohotdocs(){
		$titles = '';
		$docs = $_ENV['nav']->get_hotdocs(2);
		if($docs){
			$titles = implode('; ', $docs);
		}
		//echo json_encode($titles);
		$this->message($titles,'',2);
	}
	
	/**
	 * 搜索词条
	 */
	function dosearchdocs(){
		$titles = '';
		$tag = trim($this->post['tag']);
		if($tag){
			$docs = $_ENV['nav']->get_doc_by_title($tag);
		}
		if($docs){
			$titles = implode('; ', $docs);
		}
		$this->message($titles,'',2);	
	}
	
	/**
	 * 分类词条
	 */	
	function docatedoc(){
		$cid = intval($this->post['tag']);
		$this->load('category');
		$all_category = $_ENV['category']->get_category_cache();
		$cidstr = $_ENV['category']->get_all_subcate($cid, $all_category);
		$cid = $cidstr ? $cid.$cidstr : $cid;
		$docs= $_ENV['nav']->get_catedoc($cid);
		if($docs){
			$titles = implode('; ', $docs);
		}
		$this->message($titles,'',2);	
	}
	
	/**
	 * 检查是否存在
	 */
	function docheck(){
		$tag = trim($this->post['tag']);
		$name = $_ENV['nav']->get_by_navname($tag);
		$message = $name ? 1 : 0;
		$this->message($message,'',2);	
	}
	
	/**
	 * 删除
	 */	
	function dodel(){
		$navid = intval(($this->get['2']));
		$_ENV['nav']->del($navid);
		$this->message('删除成功','index.php?admin_nav');
	}
	
	/**
	 * 编辑关联词条
	 */
	function doeditdoc(){
		$this->load('category');
		$navid = intval(($this->get['2']));
		$docs = $_ENV['nav']->get_nav_docs($navid);
		$docs = implode("; ", $docs);
		$this->view->assign("navid", $navid);
		$this->view->assign("docs", $docs);
		$this->view->assign("step", 2);
		$all_category = $_ENV['category']->get_category_cache();
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		$this->view->assign("catstr",$catstr);
		$this->view->display('admin_navadd');
	}
	
	/**
	 * 编辑导航模块
	 */
	function doeditnav(){
		if(!isset($this->post['submit'])){
			$navmodellist = $_ENV['navmodel']->get_all(array('status'=>1));
			$navid = intval($this->get['2']);
			$nav = $_ENV['nav']->get_by_id($navid);
			$this->view->assign("code", $nav['code']);
			$this->view->assign("navname", $nav['name']);
			$this->view->assign("position", $nav['position']);
			$this->view->assign("navid", $nav['id']);
			$this->view->assign("edit", 1);
			$this->view->assign("step", 1);
			$this->view->assign("navmodellist", $navmodellist);
			$this->view->display('admin_navadd');
		}else{
			$navname = trim($this->post['navname']);
			$oldnavname = trim($this->post['oldnavname']);
			$navid = trim($this->post['navid']);
			if(($navname!=$oldnavname) && $_ENV['nav']->get_by_navname($navname)){
				$this->message('JAVASCRIPT脚本会被过滤,过滤后名称不能为空!','BACK');
			}
			$content = trim($this->post['content']);
			$position = intval($this->post['position']);
			$updatenav = array('name'=>$navname, 'position'=>$position, 'code'=>$content, 'lastedit'=> $this->time, 'lasteditor'=> $this->user['username'], 'lasteditorid'=> $this->user['uid']);
			$navid = $_ENV['nav']->update($updatenav, array('id'=>$navid));
			$this->message('编辑成功','index.php?admin_nav');
		}
	}
}
?>