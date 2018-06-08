<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class tagmodel {

	var $db;
	var $base;

	function tagmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function doclist($taglist=''){
		$doclist = array();
		$tag=preg_split("/\s+/",trim($taglist));
		foreach($tag as $t){
			$t=explode("=",$t);
			$field[$t[0]]=$t[1];
		}
		$orderby = $field['orderby']?$field['orderby']:"time";
		$limit=$field['rows']?$field['rows']:10;
		$query = $this->db->query("SELECT did,cid,title,letter,tag,summary,author,authorid,time,lasteditor,lasteditorid,views,edits,editions,comments,votes FROM ".DB_TABLEPRE."doc where 1 order by $orderby desc LIMIT 0,$limit ");
		while($doc = $this->db->fetch_array($query)){
			$doclist[] = $doc;
		} 
		return $doclist;
	}
	
	function docnumber($taglist=''){
 		$count = $this->db->fetch_first("SELECT count(*) num FROM ".DB_TABLEPRE."doc where 1");
 		$docnumber = $count['num'];
		return $docnumber;
	}

	function usernumber($taglist=''){
		$count = $this->db->fetch_first("SELECT count(*) num FROM ".DB_TABLEPRE."user where 1");
		return $count['num'];
	}
	
	function toplist($taglist=''){
		$toplist = array();
		$tag=preg_split("/\s+/",trim($taglist));
		foreach($tag as $t){
			$t=explode("=",$t);
			$field[$t[0]]=$t[1];
		}
		$orderby = $field['orderby']?$field['orderby']:"credit2";
		$limit=$field['rows']?$field['rows']:10;
		$query = $this->db->query("SELECT uid, username,credit2 FROM ".DB_TABLEPRE."user where 1 order by $orderby desc LIMIT 0,$limit ");
		while($user = $this->db->fetch_array($query)){
			$toplist[] = $user;
		} 
		return $toplist;
	}
	
	function recentupdate($taglist=''){
		$doclist = array();
		$tag=preg_split("/\s+/",trim($taglist));
		foreach($tag as $t){
			$t=explode("=",$t);
			$field[$t[0]]=$t[1];
		}
	//	$orderby = $field['orderby']?$field['orderby']:"time";
		$limit=$field['rows']?$field['rows']:10;
		$this->base->load('doc');
		$doclist = $_ENV['doc']->get_list(1,'',0,$limit);
		return $doclist;
	}

 	function commentlist($taglist=''){
		$commlist = array();
		$tag=preg_split("/\s+/",trim($taglist));
		foreach($tag as $t){
			$t=explode("=",$t);
			$field[$t[0]]=$t[1];
		}
		$title=string::hiconv($field['title'],WIKI_CHARSET);
		$limit=$field['rows']?$field['rows']:10;
		if($title){
			$this->base->load('doc');
			$doclist=$_ENV['doc']->get_doc_by_title($title);
			$docid=$doclist['did'];
		}
		if(!$docid){
			$docid=$field['docid']?$field['docid']:1;
		}
		$this->base->load('comment');
		$commlist=$_ENV['comment']->get_comments($docid,0,$limit);
		return $commlist;
	}
	
	function catedoclist($taglist=''){
		$catedoclist = array();
		$tag=preg_split("/\s+/",trim($taglist));
		foreach($tag as $t){
			$t=explode("=",$t);
			$field[$t[0]]=$t[1];
		}
		$limit=$field['rows']?$field['rows']:10;
		$catename=string::hiconv($field['catename'],WIKI_CHARSET);
		if($catename){
			$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."category WHERE name='$catename' ");
			$cateinfo = $this->db->fetch_array($query);
			$cid = $cateinfo['cid'];
		}
		if(!$cid){
			$cid=$field['cid']?$field['cid']:1;
		}
		$this->base->load('doc');
		$catedoclist=$_ENV['doc']->get_docs_by_cid($cid,0,$limit);
		return $catedoclist;
	}

	function userlist($taglist=''){
		$userlist = array();
		$tag=preg_split("/\s+/",trim($taglist));
		foreach($tag as $t){
			$t=explode("=",$t);
			$field[$t[0]]=$t[1];
		}
		$groupid = $field['groupid']?$field['groupid']:2;
		$limit=$field['rows']?$field['rows']:10;	
		$this->base->load('user');
		$userlist=$_ENV['user']->get_list('',$groupid,0,$limit); ;
		return $userlist;
	}

	function catelist($taglist=''){
		$catelist = array();
		$this->base->load('category');
		$catelist=$_ENV['category']->get_subcate(0);
		return $catelist;
	}

}
?>