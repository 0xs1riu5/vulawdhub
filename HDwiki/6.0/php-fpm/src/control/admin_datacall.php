<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load("datacall");
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$this->dolist();
	}

	function dolist(){
		$params = array();
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$params['start'] = $start_limit;
		$params['limit'] = $num;

		$count = $_ENV['datacall']->get_datacall_num($params);
		$datacalllist = $_ENV['datacall']->get_datacall_info($params);
		if(!empty($datacalllist)) {
			foreach($datacalllist as $k => $v){
				$datacalllist[$k]['desc'] = nl2br($v['desc']);
			}
		}
		$departstr=$this->multi($count, $num, $page, 'admin_datacall-list');
		// 分类信息
		$category = $_ENV['datacall']->get_datacall_category();
		$this->view->assign('departstr', $departstr);
		$this->view->assign('datacalllist', $datacalllist);
		$this->view->assign('categorycss', 'all');
		$this->view->assign('category', $category);
		$this->view->display("admin_datacall_li");
	}
	
	function dosearch(){
		$params = array();
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;

		$params['category']=isset($this->post['category'])?$this->post['category']:$this->get[2];
		$params['search_name']=isset($this->post['search']['name'])?$this->post['search']['name']:$this->get[3];
		$params['start'] = $start_limit;
		$params['limit'] = $num;

		$count = $_ENV['datacall']->get_datacall_num($params);
		$datacalllist = $_ENV['datacall']->get_datacall_info($params);
		if(!empty($datacalllist)) {
			foreach($datacalllist as $k => $v){
				$datacalllist[$k]['desc'] = nl2br($v['desc']);
			}
		}

		if(empty($params['category'])) $params['category'] = 0;
		if(empty($params['search_name'])) $params['search_name'] = 0;
		$searchdata ='admin_datacall-search-'.urlencode("$params[category]-$params[search_name]");
		$departstr = $this->multi($count, $num, $page, $searchdata);

		$category = $_ENV['datacall']->get_datacall_category();
		$categorystr = $_ENV['datacall']->get_datacall_category(array('is_str'=>1, 'category'=>$params['category']));
		$this->view->assign('category', $category);
		$this->view->assign('categorystr', $categorystr);
		$this->view->assign('departstr', $departstr);
		$this->view->assign('datacalllist', $datacalllist);
		$this->view->assign('categorycss', $params['category']);
		
		$this->view->display("admin_datacall_li");
	}
	
	function doview(){
		$datacallinfo = array();
		if(isset ($this->get[2]) && is_numeric($this->get[2])) {
			$params['id'] = $this->get[2];
			$datacallinfo = $_ENV['datacall']->get_datacall_info($params);
			if(!empty($datacallinfo)) {
				$datacallinfo = $datacallinfo[0];
			}
		}
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$this->view->assign('url', $url);
		$this->view->assign('datacallinfo', $datacallinfo);
		$this->view->display("admin_datacall_view");
	}

	function dooperate(){
		if(isset($this->post['act'])) {
			switch ($this->post['act']){
				case 'nameuniqueness':
					$existflag = 0;
					if(!empty($this->post['name'])) {
						$datacall = $_ENV['datacall']->get_datacall_num(array('name'=>$this->post['name']));
						$existflag = $datacall;
					}
					echo $existflag;
					break;
				default:
					break;
			}
		}
	}

	function doremove(){
		if(!empty($this->post['callid'])) {
			$id = implode(',',$this->post['callid'] );
			if(!empty($id)) {
				$_ENV['datacall']->remove_call($id);
			}
		}
		$this->header('admin_datacall');
	}
	
	function doaddsql(){
		if(isset($this->post['datacallsubmit'])){
			// 添加调用信息
			if(empty($this->post['datacall']['name'])){
				// 信息不完整
				$this->message('缺少函数调用名称！','index.php?admin_datacall-addsql',0);
			}else {
				if($this->post['datacall']['desc']!=""){
					$this->post['datacall']['desc'] = string::stripscript($this->post['datacall']['desc']);
				}
				if(false === $_ENV['datacall']->editsql($this->post['datacall'])){
					// 插入出问题
					$this->message('数据库插入失败，请重新操作！','index.php?admin_datacall-addsql',0);
				}else{
					// 跳转
					$this->header('admin_datacall');
				};
			}
		}
		// 分类信息
		$category = $_ENV['datacall']->get_datacall_category(array('is_str'=>1));
		$this->view->assign('category', $category);
		$this->view->assign('posturl', 'admin_datacall-addsql');
		$this->view->display("admin_datacall_sql");
	}

	function doeditsql(){
		if(isset($this->post['datacalledit'])){
			// 添加调用信息
			if(empty($this->post['datacall']['name'])){
				$this->message('信息不完整！','index.php?admin_datacall-editsql-'.$this->post[2],0);
			}else {
				$this->post['datacall']['editflag'] = 1;
				if($this->post['datacall']['desc']!=""){
					$this->post['datacall']['desc'] = string::stripscript($this->post['datacall']['desc']);
				}
				if(false === $_ENV['datacall']->editsql($this->post['datacall'])){
					$this->message('数据库更新失败，请重新操作！','index.php?admin_datacall-editsql-'.$this->post[2],0);
				}else{
					// 跳转
					$this->header('admin_datacall');
				};
			}
		}
		if(isset ($this->get[2]) && is_numeric($this->get[2])) {
			$datacallinfo = array();
			$datacallinfo = $_ENV['datacall']->get_datacall_info(array('id'=>$this->get[2]));
			
			if(!empty($datacallinfo)) {
				$datacallinfo = $datacallinfo[0];
				$datacallinfo['param'] = unserialize($datacallinfo['param']);
			} else {
				// 无相关数据
				$this->message('无相关数据','index.php?admin_datacall-list',0);
			}
		} else {
			// 参数错误
			$this->message('参数错误','index.php?admin_datacall',0);
		}
		// 分类信息
		$category = $_ENV['datacall']->get_datacall_category(array('is_str'=>1, 'category'=>$datacallinfo['category']));
		$this->view->assign('category', $category);
		$this->view->assign('datacallinfo', $datacallinfo);
		$this->view->assign('posturl', 'admin_datacall-editsql');
		$this->view->display("admin_datacall_sql");
	}	
}
?>