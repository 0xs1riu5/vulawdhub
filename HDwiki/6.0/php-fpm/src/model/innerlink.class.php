<?php
/*
* 内链URL处理类，将内链由index.php?doc-innerlink-title 的形式处理为和SEO设置一致的形式。
*/
!defined('IN_HDWIKI') && exit('Access Denied');

class innerlinkmodel {
	
	var $db;
	var $base;
	var $re;
	var $titles=array();

	function innerlinkmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		//$this->re = '/[^\'\"]+?innerlink-([^\'\"]+)/';//存在严重效率问题
		
		//这种方式，当内容当中的两个内链，前面的某个是后面的某个内链的前部分时，
		//在替换前面这个内容时，将也把后面那个内链给替换，导致出现类似http://domain/doc-view-60SD 内链
		//$this->re = '/(?:http:[\/\w-\.]{8,80}|index\.php\?)doc-innerlink-([^\'\"]+)/';
		$this->re = '/[\'\"]?(?:http:[\/\w-\.]{8,80}|index\.php\?)doc-innerlink-([^\'\"]+)[\'\"]?/';
	}
	
	/*
	* 在内容显示之前调用此方法
	*/
	function get($did, &$content){
		
		//从数据库获取该词条的相关内链信息
		$rows=$this->db->get_array("SELECT title,titleid FROM ".DB_TABLEPRE."innerlinkcache WHERE did='$did'");
		$rows2=array();
		if(!empty($rows)){
			foreach($rows as $i=> $row){
				$rows2[ $row['title'] ] = $row['titleid'];
			}
		}
		$rows = $rows2;
		
		//从内容当中分析所有内链，保存到变量 $matchs 当中
		preg_match_all($this->re, $content, $matchs);
		
		//将内容当中的内链和数据库的相关内链进行比较，将内容当中的新内链放到 $new_titles 当中。
		$new_titles=array();
		
		if($matchs){
			//在PHP4.3版本，在 foreach 语句当中使用 & 引用符号导致语法错误
			foreach($matchs[1] as $i=>$title){
				$title2=trim(urldecode($title));
				if('gbk' == strtolower(WIKI_CHARSET)) {$title2 = string::hiconv($title2,'gbk','utf-8');}
				$title2=addslashes($title2);
				
				if(isset($rows[$title2])){//titleid
					$this->titles[$title]=array($rows[$title2], $matchs[0][$i]);
				}else{ 
					if(!in_array($title2, $new_titles)) $new_titles[]=$title2;
					$this->titles[$title]=array(0, $matchs[0][$i]);
				}
			}
		}
		//将新出现的内链保存到数据库当中
		if(!empty($new_titles)){
			$this->save($did, $new_titles);
		}
		
		//修改内链URL
		return $this->change($did, $content);
	}
	
	//对内容当中的内链进行处理
	function change($did, $content){
		$setting = $this->base->setting;
		foreach($this->titles as $title=>$row){
			if($row[0] == -1){
				$content = str_replace($row[1], WIKI_URL.'/?doc-innerlink-'.$title, $content);
			}elseif($row[0]){
				//内链存在，根据SEO设置进行相应调整
				if($setting['seo_type_doc'] && $setting['seo_type']){
					//使用title的rewrite
					$content = str_replace($row[1], WIKI_URL.'/wiki/'.$title, $content);
				}else if($setting['seo_type']){
					//使用did的rewrite
					$content = str_replace($row[1], WIKI_URL.'/doc-view-'.$row[0].$setting['seo_suffix'], $content);
				}else{
					//不支持rewrite
					$content = str_replace($row[1], WIKI_URL.'/'.$setting['seo_prefix'].'doc-view-'.$row[0].$setting['seo_suffix'], $content);
				}
			}else{
				//内链不存在
				$content = str_replace($row[1], "javascript:innerlink('$title')", $content);
			}
		}
		
		return $content;
	}
	
	//保存数据库，并更新 $this->titles
	function save($did, &$titlelist){
		if(empty($titlelist)){
			return;
		}
		//词条列表和同义词列表
		$doclist = $this->_getdoc($titlelist);
		$synonymlist = $this->_getsynonym($titlelist);
		$sql = "insert INTO ".DB_TABLEPRE."innerlinkcache (`did`,`title`,`titleid`)VALUES";
		$data=array();
		foreach($titlelist as $key=>$title){
			$title2 = urlencode($title);
			$titleid=isset($doclist[$title2]) ? $doclist[$title2]:0;
			if(!$titleid){
				$titleid = isset($synonymlist[$title2]) ? -1 : 0;
			}
			$data[]="('$did','$title','$titleid')";
			
			$title2= ('gbk'==strtolower(WIKI_CHARSET))?string::hiconv($title,'utf-8','gbk'): $title;
			$title2=urlencode($title2);
			$this->titles[$title2][0]=$titleid;
		}
		
		$sql .= implode(',', $data);
		
		$this->db->query($sql);
	}

	/**
	 * 取词条
	 * @param array $titlelist
	 */
	function _getdoc($titlelist){
		$titles = implode("','", $titlelist);
		//从数据库查询是否存在
		$rows=$this->db->get_array("select did, title from  ".DB_TABLEPRE."doc where title in('$titles')");
		if(!empty($rows)){
			$doclist = array();
			foreach($rows as $i=> $row){
				$doclist[ urlencode($row['title']) ] = $row['did'];
			}
		}
		return $doclist;
	}
	
	/**
	 * 取同义词
	 * @param array $titlelist
	 */
	function _getsynonym($titlelist){
		$titles = implode("','", $titlelist);
		//从数据库查询是否存在
		$rows = $this->db->get_array("select srctitle from  ".DB_TABLEPRE."synonym where srctitle in('$titles')");
		if(!empty($rows)){
			$doclist = array();
			foreach($rows as $i=> $row){
				$doclist[ urlencode($row['srctitle']) ] = -1;
			}
		}
		return empty($doclist) ? NULL : $doclist ;
	}
	
	/*
	* 在创建词条保存时调用此方法，以更新对应的内链修信息
	*/
	function update($title, $titleid){
		$this->db->query("update ".DB_TABLEPRE."innerlinkcache set titleid='$titleid' where title='$title'");
	}
	
}
