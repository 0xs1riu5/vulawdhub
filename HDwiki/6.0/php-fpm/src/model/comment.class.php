<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class commentmodel {

	var $db;
	var $base;

	function commentmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/*Description: This method has already expired*/
	function get_comment_by_id($id){
		$id = is_numeric($id) ? $id : 0;
		$comment=array();
		$comment=$this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."comment WHERE id='$id'");
		return $comment;
	}
	
	function isip($ip){
		if(!strcmp(long2ip(sprintf("%u",ip2long($ip))),$ip)){
			return true;
		}
		return false;
	}
	function get_sub_ip($ip){
		if($ip==''){
			$ip=$this->base->ip;
		}
		$reg = '/(\d+\.)(\d+\.)(\d+)\.(\d+)/';
		return preg_replace($reg, "$1$2*.*", $ip);;
	}
	
	function ip_show($username){
		return $this->isip($username)?$this->base->view->lang['commentAnonymity'].$this->get_sub_ip($username):$username;
	}
	
	function is_in_cookie($type,$id){
		$id = is_numeric($id) ? $id : 0;
		$ids=base::hgetcookie($type);
		$already_ids=explode('|',$ids);
		if(in_array($id,$already_ids)){
			return true;
		}
		$this->base->hsetcookie($type,$ids.'|'.$id,24*3600);
		return false;
	}
	
	function get_re_comment_by_id($id){
		$id = is_numeric($id) ? $id : 0;
		$comment=$this->get_comment_by_id($id);
		$comment['author']=$this->ip_show($comment['author']);
		return "<div class='build'>".$comment['reply']."<p class='re_user'>".$comment['author']."&nbsp;&nbsp;".$this->base->date($comment['time'])."&nbsp;&nbsp;".$this->base->view->lang['commentCom'].":</p>"."&nbsp;".$comment['comment']."</div>";
	}

	function update_field($field,$value,$id,$type=1){
		$id = is_numeric($id) ? $id : 0;
		if($type){
			$sql="UPDATE ".DB_TABLEPRE."comment SET $field='$value' WHERE id= $id ";
		}else{
			$sql="UPDATE ".DB_TABLEPRE."comment SET $field=$field+$value WHERE id= $id ";
		}
		$this->db->query($sql);
	} 

	function get_comments($did,$start=0,$limit=10){
		$did = is_numeric($did) ? $did : 0;
		$comments=array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."comment WHERE did='$did' ORDER BY `time` DESC LIMIT $start,$limit");
		while($comment=$this->db->fetch_array($query)){
			$comment['time']=$this->base->date($comment['time']);
			$comment['author']=$this->ip_show($comment['author']);
			$comment['comment']=$comment['comment'];
			$comments[]=$comment;
		}
		return $comments;
	}
	
	function remove_comment_by_id($id){
		if(is_array($id)){
			$ids = @implode(',',$id);
			$sql="id IN ($ids)";
		}else{
			$sql="id =$id";
		}
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."comment WHERE ".$sql);
		while($comment=$this->db->fetch_array($query)){
			$sqladd.=" ('comment','".addslashes($comment['comment'])."','".addslashes(serialize($comment))."','N;','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->time."'),";
		}
		$this->db->query("INSERT INTO ".DB_TABLEPRE."recycle (type,keyword,content,file,adminid,admin,dateline) values ".substr($sqladd,0,-1));
		
		$query="DELETE FROM ".DB_TABLEPRE."comment WHERE ".$sql;
		return $this->db->query($query);
	}
	
	function add_comment($did,$comment,$reply='',$anonymity=1){
		$did = is_numeric($did) ? $did : 0;
		if($anonymity){
			$sql="INSERT INTO ".DB_TABLEPRE."comment(did,comment,reply,author,authorid,time) VALUES($did,'$comment','$reply','".$this->base->ip."',".'0'.",".$this->base->time.")";
		}else{
			$sql="INSERT INTO ".DB_TABLEPRE."comment(did,comment,reply,author,authorid,time) VALUES($did,'$comment','$reply','".$this->base->user['username']."',".$this->base->user[uid].",".$this->base->time.")";
		}
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	function edit_comment_by_id($id,$comment){
		$id = is_numeric($id) ? $id : 0;
		$this->db->query("UPDATE ".DB_TABLEPRE."comment SET comment='$comment' WHERE id=$id");
	}
	
	function search_comment_num($cid='', $did='',$author='', $starttime='', $endtime='' ){
		$cid = is_numeric($cid) ? $cid : 0;
		$did = is_numeric($did) ? $did : 0;
		$sql="SELECT  count(*)  FROM ".DB_TABLEPRE."comment m LEFT JOIN ".DB_TABLEPRE."doc d ON d.did = m.did WHERE 1=1 ";		
		if($cid){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink  WHERE cid = $cid");
			while($ids=$this->db->fetch_array($query)){
				$dids[]=$ids['did'];
			}
			$dids=is_array($dids)?implode(',',$dids):'';
			if($dids){
				$sql=$sql." AND m.did IN ($dids) ";
			}else{
				$sql=$sql." AND 1!=1 ";
			}
		}
		if($did){
			$sql=$sql." AND m.did ='$did' ";
		}
		if($author){
			$sql=$sql." AND m.author='$author' ";
		}
		if($starttime){
			$sql=$sql." AND m.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND m.time<=$endtime ";
		}
		return $this->db->result_first($sql);
	}
	
	function search_comment($start=0,$limit=10, $cid='', $did='',$author='', $starttime='', $endtime='' ){
		$cid = is_numeric($cid) ? $cid : 0;
		$did = is_numeric($did) ? $did : 0;
		$commentlist=array();
		$sql="SELECT  m.id,m.did,m.comment,m.author,m.authorid,m.time,d.title title FROM ".DB_TABLEPRE."comment m LEFT JOIN ".DB_TABLEPRE."doc d ON d.did = m.did WHERE 1=1 ";
		if($cid){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink  WHERE cid = $cid");
			while($ids=$this->db->fetch_array($query)){
				$dids[]=$ids['did'];
			}
			$dids=is_array($dids)?implode(',',$dids):'';
			if($dids){
				$sql=$sql." AND m.did IN ($dids) ";
			}else{
				$sql=$sql." AND 1!=1 ";
			}
		}
		if($did){
			$sql=$sql." AND m.did ='$did' ";
		}
		if($author){
			$sql=$sql." AND m.author='$author' ";
		}
		if($starttime){
			$sql=$sql." AND m.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND m.time<=$endtime ";
		}
		$sql=$sql." ORDER BY m.time DESC LIMIT $start,$limit ";
		$query=$this->db->query($sql);
		while($comment=$this->db->fetch_array($query)){
			$comment['time'] = $this->base->date($comment['time']);
			$comment['comment']=$comment['comment'];
			$comment['title']=htmlspecial_chars($comment['title']);
			$comment['partcomment']=(strlen($comment['comment'])>28)?string::substring($comment['comment'],0,28)."......":$comment['comment'];
			$commentlist[]=$comment;
		}
		return $commentlist;
	}
	
	function recent_comment($start=0,$limit=10){
		$comments=array();
		$query=$this->db->query('SELECT u.image,c.* FROM '.DB_TABLEPRE.'comment c LEFT JOIN '.DB_TABLEPRE.'user u ON c.authorid=u.uid ORDER BY `time` DESC LIMIT '."$start,$limit");
		while($comment=$this->db->fetch_array($query)){
			$comment['comment']=$comment['comment'];
			$comment['image'] = $comment['image'] ? $comment['image'] : 'style/default/user.jpg';
			$comment['image'] = $_ENV['global']->uc_api_avatar($comment['image'], $comment['authorid'], 'small');
			
			$comment['tipcomment']=(string::hstrlen($comment['comment'])>12)?string::substring($comment['comment'],0,12)."...":$comment['comment'];
			$comment['time']=$this->base->date($comment['time']);
			$comments[]=$comment;
		}
		return $comments;
	}
	
	function hot_comment_cache($num=20){
		$cache=$this->base->cache->getcache("hot_comment",$this->base->setting['list_cache_time']);
		if(!(bool)$cache){
			$cache=$this->get_hot_comment(0,$num);
			$this->base->cache->writecache("hot_comment",$cache);
		}
		return $cache;
	}

	function get_hot_comment($start=0,$limit=20){
		$doclist=array();
		$limit||$limit = 20;
		$sql=" SELECT d.title,c.did,count(*) num FROM ".DB_TABLEPRE."comment c left join ".DB_TABLEPRE."doc d ON c.did=d.did GROUP BY c.did ";
		$sql .=" ORDER BY num DESC LIMIT $start,$limit";
		$query = $this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			$doc['rawtitle']=$doc['title'];
			$doc['title'] = htmlspecial_chars(stripslashes($doc['title']));
			$doclist[]=$doc;
		}
		return $doclist;
	}
	
	function recover($data){
		$data=string::haddslashes($data,1);
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."comment (id,did,comment,reply,author,authorid,oppose,aegis,time) 
					VALUES ('".$data['id']."','".$data['did']."','".$data['comment']."','".$data['reply']."','".$data['author']."','".$data['authorid']."','".$data['oppose']."','".$data['aegis']."','".$data['time']."')");
	}
}
?>