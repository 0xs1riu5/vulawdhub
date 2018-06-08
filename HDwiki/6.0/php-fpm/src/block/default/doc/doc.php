<?php
class doc{
	
	var $db;
	var $base;

	function doc(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->plugin=$base->plugin;
	}

	function recentdocs($setting){
		$this->base->load('doc');
		$num=!empty($setting['num'])?$setting['num']:$this->base->setting['index_recentupdate'];
		$doclist=$_ENV['doc']->get_list_cache(1,'',0,$num);
		return array('config'=>$setting , 'doclist'=>$doclist);
	}
	
	function cooperatedocs($setting){
		$this->base->load('doc');
		$cooperatedocs = $_ENV['doc']->cooperatedocs($this->base->setting['index_cooperate']);
		return array('config'=>$setting , 'list'=>$cooperatedocs);
	}
	
	function commenddocs($setting){
		$this->base->load('doc');
		$num=!empty($setting['num'])?$setting['num']:$this->base->setting['index_commend'];
		$type = 1;
		$list = $_ENV["doc"]->get_focus_list(0,$num,$type);
		return array('config'=>$setting, 'list'=>$list);
	}
	
	function wonderdocs($setting){
		$this->base->load('doc');
		$num=!empty($setting['num'])?$setting['num']:$this->base->setting['index_wonderdoc'];
		$type = 3;
		$list = $_ENV["doc"]->get_focus_list(0,$num,$type);
		if(count($list)>0){
			$fistwonderdoc=is_array($list)?array_shift($list):array();
			$fistwonderdoc['image'] = str_replace('s_','',$fistwonderdoc['image']);
		}
		
		return array('config'=>$setting, 'list'=>$list, 'fistwonderdoc'=>$fistwonderdoc);
	}
	
	function hotdocs($setting){
		$this->base->load('doc');
		$num=!empty($setting['num'])?$setting['num']:$this->base->setting['index_hotdoc'];
		$type = 2;
		$list = $_ENV["doc"]->get_focus_list(0,$num,$type);
		return array('config'=>$setting, 'list'=>$list);
	}
	
	function hottags($setting){
		@$hottag=unserialize($this->base->setting['hottag']);
		return array('config'=>$setting, 'hottag'=>is_array($hottag)?$hottag:array());
	}
	
	function categorydocs($setting){
		$this->base->load('doc');
		$this->base->load('category');
		$allcategory=$_ENV['category']->get_category_cache();
		if($allcategory){
			foreach($allcategory as $category){
				if($category['cid'] == $setting['category']){
					$categorydocs[1] = $category['name'];
					break;
				}
			}
		}
		$num = intval($setting['num']);
		$setting['num'] = $num ? $num : 10;
		$categorydocs[0] = $_ENV['doc']->get_docs_by_cid(array($setting['category']),0,$setting['num']);
		return array('config'=>$setting, 'categorydocs'=>$categorydocs);
	}
	
	function hotcommentdocs($setting) {
		$this->base->load('comment');
		$hotcomment=$_ENV['comment']->hot_comment_cache($setting['num']);
		return array('config'=>$setting, 'hotcomment'=>$hotcomment);
	}
	
	function getletterdocs($setting){
		return array('config'=>$setting, '');
	}
}
?>