<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class categorymodel {

	var $db;
	var $base;

	function categorymodel(&$base) {
		$this->base = $base;
		$this->db = $base->db; 
	}

	function get_category($cidstr, $one=1) {
		$categorys = null; 
		$categorylist=$this->get_category_cache();
		$cids = explode(",", $cidstr);
		sort($cids);
		foreach($cids as $cid){
			foreach($categorylist as $category){
				if($cid==$category['cid']){
					if($one == 1){
						return $category;
					}
					$categorys[] = $category;
				}
			}
		}
		return $categorys;
	}

	function get_subcate($pid) {
		$subcategory = array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."category WHERE pid='$pid' ORDER BY displayorder ASC");
		while($category=$this->db->fetch_array($query)){
			$subcategory[]=$category;
		}
		return $subcategory;
	}

	function get_all_subcate($pid,$allcategory) {
		$sublist='';
		foreach($allcategory as $category){
			if($pid==$category['pid']){
				$sublist .=",".$category['cid'];
				$sublist .= $this->get_all_subcate($category['cid'], $allcategory);
			}
		}
		return $sublist;
	}

	function get_all_category() {
		$categorylist=array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."category ORDER BY displayorder ASC");
		while($category=$this->db->fetch_array($query)){
			$categorylist[]=$category;
		}
		return $categorylist;
	}
	

	function get_category_cache() {
		$categorylist=$this->base->cache->getcache('category',$this->base->setting['index_cache_time']);
		if( !is_array($categorylist) ){
			$categorylist=$_ENV['category']->get_all_category(); 
			$this->base->cache->writecache('category',$categorylist);
		}
		return $categorylist;
	}
	

	function get_site_category($pid,$categorylist){
		$sitecategory=array();
		if(!empty($categorylist)) {
			foreach($categorylist as $i => $category){
				if($pid==$category['pid']){
					$parentid=$category['cid'];
					$sitecategory[$i]['parent']=$category;
					foreach($categorylist as $j => $subcategory){
						if($parentid==$subcategory['pid']){
							$sitecategory[$i]['child'][]=$subcategory;
						}
					}
				}
			}
		}
		return $sitecategory;
	}

	function update_category_docs($cid,$docsnum=1){
		$category=$this->db->fetch_first("SELECT docs FROM ".DB_TABLEPRE."category WHERE cid='$cid' ");
		$docsnum=($category['docs']+$docsnum > 0)?$category['docs']+$docsnum:0;
		$this->db->query("UPDATE ".DB_TABLEPRE."category SET docs=$docsnum WHERE cid='$cid' ");
	}

	function get_child_tree($allcategory,$pid,$depth=1){
		$childtree='';
		foreach($allcategory as $category){
			if($pid==$category['pid']){
				$childtree .= "<option value=\"{$category['cid']}\">";
				$depthstr=str_repeat("--", $depth);
				$childtree .= $depth ? "&nbsp;&nbsp;|{$depthstr}&nbsp;{$category['name']}</option>" :"{$category['name']}</option>";
				$childtree .= $this->get_child_tree($allcategory, $category['cid'],$depth+1);
			}
		}
		return $childtree;
	}

	function get_child_string($allcategory,$pid,$depth=1){
		$childstring='';
		foreach($allcategory as $category){
			if($pid==$category['pid']){
				$childstring .= ','.$category['cid'];
				$childstring .= $this->get_child_string($allcategory, $category['cid'],$depth+1);
			}
		}
		return $childstring;
	}

	function get_categrory_tree($allcategory){
		$categrorytree='';
		$total=count($allcategory);
		for($i=0;$i<$total;$i++){
			if($allcategory[$i]['pid']==0){
				$categrorytree .= "<option value=\"{$allcategory[$i]['cid']}\">{$allcategory[$i]['name']}</option>";
				$categrorytree .=$this->get_child_tree($allcategory,$allcategory[$i]['cid'],1);
			}
		}
		return $categrorytree;
	}

	function add_category($pid,$name,$image,$discrib){
		$cid = $this->db->result_first("SELECT cid FROM `".DB_TABLEPRE."category` WHERE pid='{$pid}' AND name='$name'");
		if( ! $cid){
			$pcat = $this->get_category($pid);
			$this->db->query("INSERT INTO `".DB_TABLEPRE."category` (`pid`,`name`,`image`,`description`) VALUES ('{$pid}','{$name}','{$image}','{$discrib}')");			
			$cid = $this->db->insert_id();
			$path = unserialize($pcat['navigation']);
			$path[] = array('cid'=>$cid,'name'=>$name);
			$navigation = addslashes(serialize($path));
			$this->db->query("UPDATE `".DB_TABLEPRE."category` SET navigation = '{$navigation}' WHERE cid = '{$cid}'");
			return true;
		}else{
			return false;
		}
	}

	function order_category($cid,$order){
		$this->db->query("UPDATE `".DB_TABLEPRE."category` SET displayorder = '{$order}' WHERE cid = '{$cid}'");
	}

	function edit_category($cid,$pid,$name,$ico,$discrib){
		//$name = string::stripscript($name);
		$this->db->query("UPDATE `".DB_TABLEPRE."category` SET pid = '{$pid}',image = '{$ico}', name = '{$name}', description = '{$discrib}' WHERE cid = '{$cid}'");
		$allcat = $this->get_all_category();
		$child =$cid.$this->get_child_string($allcat,$cid);
		$query = $this->db->query("SELECT * FROM `".DB_TABLEPRE."category` WHERE cid IN ({$child})");
		while($category = $this->db->fetch_array($query)){
			$pcategory = $this->db->fetch_by_field('category','cid',$category['pid']);
			$navigation = unserialize($pcategory['navigation']);
			$navigation[] = array('cid'=>$category['cid'],'name'=>$category['name']);
			$navigation = addslashes(serialize($navigation));
			$this->db->query("UPDATE `".DB_TABLEPRE."category` SET navigation = '{$navigation}' WHERE cid = '{$category['cid']}'");
		}
	}
	

	function remove_category($cid){
		$allcategory = $this->get_all_category();
		$cidlist = $cid.$this->get_child_string($allcategory,$cid);
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."category WHERE cid IN ($cidlist)");
		while($category=$this->db->fetch_array($query)){
			$keyword .=addslashes($category['name']).";";
			$categorylist['category'][]=$category;
		}
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."categorylink WHERE cid IN ($cidlist)");
		while($categorylink=$this->db->fetch_array($query)){
			$categorylist['categorylink'][]=$categorylink;
		}
		$this->db->query("INSERT INTO ".DB_TABLEPRE."recycle (type,keyword,content,file,adminid,admin,dateline) values  ('category','$keyword','".addslashes(serialize($categorylist))."','N;','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->time."')");
		
		$this->db->query("DELETE FROM `".DB_TABLEPRE."category` WHERE cid IN ($cidlist)");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."categorylink` WHERE cid IN ($cidlist)");
	}
	
	function recover($data){
		$data=string::haddslashes($data,1);
		if(count($data['category'])){
			foreach($data['category'] as  $category){
				$csqladd.="('".$category['cid']."','".$category['pid']."','".$category['name']."','".$category['displayorder']."','".$category['docs']."','".$category['image']."','".$category['navigation']."','".$category['description']."'),";
			}
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."category (cid,pid,name,displayorder,docs,image,navigation,description) VALUES ".substr($csqladd,0,-1));
		}
		if(count($data['categorylink'])){
			foreach($data['categorylink'] as $categorylink){
				$clsqladd.="('".$categorylink['id']."','".$categorylink['did']."','".$categorylink['cid']."'),";
			}
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."categorylink (id,did,cid) VALUES ".substr($clsqladd,0,-1));
		}
	}

	function merge_category($sourceid,$objectid){
		$allcat = $this->get_all_category();
		$child =$objectid.$this->get_child_string($allcat,$objectid);
		$childarray = explode(',',$child);
		if(in_array($sourceid,$childarray)) return false;
		else{
			$s = $this->get_category($sourceid);
			$j = $this->get_subcate($objectid);
			if(is_array($j)){
				foreach($j as $p){
					$path = unserialize($s['navigation']);
					$path[] = array('cid'=>$p['cid'],'name'=>$p['name']);
					$tpath = addslashes(serialize($path));
					unset($path);
					$this->db->query("UPDATE `".DB_TABLEPRE."category` SET pid = '{$sourceid}',navigation = '{$tpath}' WHERE cid = '{$p['cid']}'");
				}
			}
			$this->db->query("DELETE FROM `".DB_TABLEPRE."category` WHERE cid = '{$objectid}'");
			$query = $this->db->query("SELECT * FROM `".DB_TABLEPRE."category` WHERE cid IN ({$child})");
			while($r = $this->db->fetch_array($query)){
				$t = $this->get_category($r['pid']);
				$path = NULL;
				$path = unserialize($t['navigation']);
				$path[] = array('cid'=>$r['cid'],'name'=>$r['name']);
				$path = addslashes(serialize($path));
				$this->db->query("UPDATE `".DB_TABLEPRE."category` SET navigation = '{$path}' WHERE cid = '{$r['cid']}'");
			}
			$this->merge_category_doc($sourceid,$objectid);
			return true;
		}
	}
	//$objectid=>$sourceid
	function merge_category_doc($sourceid,$objectid){
		$objectidstring = '';
		$query = $this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink WHERE cid='{$objectid}'");
		while($categorylink = $this->db->fetch_array($query)){
			$objectidstring .= $categorylink['did'].',';
		}
		$objectidstring = substr($objectidstring, 0, -1);
		if($objectidstring){
			$this->db->query("DELETE FROM ".DB_TABLEPRE."categorylink WHERE did IN ({$objectidstring}) AND cid='{$sourceid}'");
			$this->db->query("UPDATE ".DB_TABLEPRE."categorylink SET cid='{$sourceid}' WHERE cid='{$objectid}' AND did IN ({$objectidstring})");
		}
	}

	function vilid_category($cidstr){
		$cids = explode(",", $cidstr);
		$allcategory = $this->get_all_category();
		$allcategoryid = array();
		if(!empty($allcategory)) {
			foreach($allcategory as $category){
				$allcategoryid[] = $category['cid'];
			}
		}
		if($cids == array_intersect($cids, $allcategoryid)){
			return true;
		}
		return false;
	}

	function get_cat($catid){
		$cats = $this->get_subcate($catid);
		$content = '<h3 class="red"><span class="bold gray">'.$this->base->view->lang['categoryHaveCategory'].'</span><span id="scnames"></span></h3>';
		if(is_array($cats))	{
			if($catid == 0){
				foreach($cats as $cat){
					//名字不需要转译;元素里面的是需要转译的
					$catname = htmlspecial_chars($cat['name']);
					$cat['name'] = htmlspecial_chars(string::haddslashes($cat['name'],1));
					$img = $this->get_subcate($cat['cid']) ? '<input id="cat'.$cat['cid'].'" onclick="openclose(this);" type="image" src="style/default/close.gif"/>' : '';
					$content .= '<dl class="col-dl" id="cat'.$cat['cid'].'"><dt class="bold"><label><input type="checkbox" id='.$cat['cid'].' name='.$cat['name'].' onclick="javascript:catevalue.cateOk('.$cat['cid'].',\''.$cat['name'].'\',this.checked)" />'.$catname.'</label>'.$img.'</dt>';
					$subcats = $this->get_subcate($cat['cid']);
					if($subcats){
						$content .= $this->get_catitem($catid,$subcats);
					}
					$content .= '</dl>';
				}
			}else{
				$cat = $this->get_category($catid);
				foreach (unserialize($cat['navigation']) as $nav){
					$catname = $nav['name'];
					$nav['name'] = htmlspecial_chars(string::haddslashes($nav['name']));
					$navlink .= '<label><input type="checkbox" id='.$nav['cid'].' name='.$nav['name'].' onclick="javascript:catevalue.cateOk('.$nav['cid'].',\''.$nav['name'].'\',this.checked)" />'.$catname.'</label>>';
				}
				$content .= '<dl class="col-dl"><dt class="bold">'.substr($navlink, 0, -1).'</dt>';
				$content .= $this->get_catitem($catid,$cats);
				$content .= '</dl><p class="a-r"><a href="javascript:catevalue.ajax(0)" class="m-lr8">'.$this->base->view->lang['categoryTopLevel'].'</a><a href="javascript:catevalue.ajax('.$cat['pid'].')"  class="m-lr8"><img src="style/default/sign_fl.gif" />'.$this->base->view->lang['categoryUpLevel'].'</a></p>';
			}
		}else{
			$content .= '<dl class="col-dl"><dd>'.$this->base->view->lang['categoryNotExist'].'</dd></dl>';
		}
		/*
		$content .= '<p class="a-c c-b"><input name="Submit1" type="submit" value="'.$this->base->view->lang['sure'].'" class="btn_inp m-lr8" onclick="catevalue.ok()"/><input name="Button1" type="button" value="'.$this->base->view->lang['cancel'].'" class="btn_inp m-lr8" onclick="catevalue.removeCateTree()"/></p>';
		$content .= '<script type="text/javascript">javascript:catevalue.selectCategory();</script>';
		*/
		return $content;
	}

	function get_catitem($catid, $cats){
		$content = '';
		foreach ($cats as $cat){
			$catname = htmlspecial_chars($cat['name']);
			$navname = htmlspecial_chars(string::haddslashes($catname,1));
			$style = $catid == 0 ? 'style="display:none;"' : '';
			$img = $this->get_subcate($cat['cid']) ? '<input onclick="javascript:catevalue.ajax('.$cat['cid'].')" type="image" src="style/default/sign_next.gif"/>' : '';
			$content .= '<dd '.$style.'><label><input type="checkbox" id='.$cat['cid'].' name='.$catname.' onclick="javascript:catevalue.cateOk('.$cat['cid'].',\''.$navname.'\',this.checked)"/>'.$catname.'</label>'.$img.'</dd>';
		}
		return $content;
	}
	
	function get_cate_info($pid,$name){
		$cid = $this->db->result_first("SELECT cid FROM `".DB_TABLEPRE."category` WHERE pid='{$pid}' AND name='$name'");
		return $cid;
	}
}

?>