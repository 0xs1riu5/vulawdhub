<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class picmodel {

	var $db;
	var $base;

	function picmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_pic_cache($type=1){
		$cache=$this->base->cache->getcache("pic_{$type}",$this->base->setting['list_cache_time']);
		if(!(bool)$cache){
			$cache=$this->get_pic($type,0,100);
			$this->base->cache->writecache("pic_{$type}",$cache);
		}
		return $cache;
	}

	function get_pic($type=1,$start=0,$limit=20){
		$piclist=array();
		$pictype=array('1'=>'AND focus=1 ORDER BY time DESC','2'=>'ORDER BY time DESC','3'=>'ORDER BY downloads DESC');
		$sql=" SELECT p.*,d.title  FROM ".DB_TABLEPRE."attachment p INNER JOIN ".DB_TABLEPRE."doc d ON p.did=d.did  WHERE p.state=0  AND p.isimage=1 AND p.did<>0 ".$pictype[$type]." LIMIT $start,$limit";
		$query = $this->db->query($sql);
		while($pic=$this->db->fetch_array($query)){
			$piclist[]=$this->get_info($pic);;
		}
		return $piclist;
	}
	
	function search_pic_num($keyword){
		$sql="SELECT count(*) num FROM ".DB_TABLEPRE."attachment p LEFT JOIN ".DB_TABLEPRE."doc d ON p.did=d.did WHERE p.isimage=1 AND d.title LIKE '%$keyword%' OR p.description LIKE '%$keyword%' ORDER BY p.time DESC";
		return $this->db->result_first($sql);
	}
	
	function search_pic($keyword,$start=0,$limit=15){
		$piclist=array();
		$sql=" SELECT p.*,d.title,d.author,d.summary FROM ".DB_TABLEPRE."attachment p LEFT JOIN ".DB_TABLEPRE."doc d ON p.did=d.did WHERE p.isimage=1 AND d.title LIKE '%$keyword%' OR p.description LIKE '%$keyword%' ";
		$sql.=" ORDER BY p.time DESC LIMIT $start,$limit ";
		$query=$this->db->query($sql);
		while($pic=$this->db->fetch_array($query)){
			$piclist[]=$this->get_info($pic);
		}
		return $piclist;
	}
	
	function get_info($pic){
		$pic['time']=$this->base->date($pic['time']);
		$picinfo=@getimagesize($pic['attachment']);
		$pic['rawtitle']=$pic['title'];
		$pic['sizeinfo']=$picinfo[0].'*'.$picinfo[1].' - '.round($pic['filesize']/1024).'k - '.$pic['filetype'];
		$pic['description']=empty($pic['description'])?$pic['title']:$pic['description'];
		$pic['subdescription']=strlen($pic['description'])>18?string::substring($pic['description'],0,8).'...':$pic['description'];
		/*
		if(false===strpos($pic['attachment'],'hdpic')){
			$pathinfo=pathinfo($pic['attachment']);
			$pic['attachment']=$pathinfo['dirname'].'/'.$pathinfo['filename'].'_140'.'.'.$pathinfo['extension'];
		}*/
		$pic['attachment_normal']=$pic['attachment'];
		$pic_140=$this->get_140($pic['attachment']);
		if(is_file($pic_140)){
			$pic['attachment']=$pic_140;
		}
		$pic['summary'] = empty($pic['summary']) ? NULL : $pic['summary'];
		$pic['summary']=htmlspecial_chars($pic['summary']);
		return $pic;
	}
	
	function get_pic_by_id($id){
		$sql=" SELECT p.*,d.title,d.summary,u.username FROM ".DB_TABLEPRE."attachment p , ".DB_TABLEPRE."doc d ,".DB_TABLEPRE."user u WHERE p.did=d.did AND p.uid=u.uid AND p.id='$id'";
		$pic=$this->db->fetch_first($sql);
		$picinfo=@getimagesize($pic['attachment']);
		$pic['rawtitle']=$pic['title'];
		$pic['fileborder']=$picinfo[0].'*'.$picinfo[1].' px';
		$pic['filesize']=round($pic['filesize']/1024).' k';
		$pic['description']=empty($pic['description'])?$pic['title']:$pic['description'];
		$pic['summary']=htmlspecial_chars($pic['summary']);
		return 	$pic;
	}
	
	function get_pic_by_did($did){
		$piclist=array();
		$sql=" SELECT id,did,attachment  FROM ".DB_TABLEPRE."attachment WHERE did='$did' AND isimage=1";
		$query = $this->db->query($sql);
		while($pic=$this->db->fetch_array($query)){
			$pic_140=$this->get_140($pic['attachment']);
			if(is_file($pic_140)){
			    $pic['attachment2']=$pic['attachment'];
				$pic['attachment']=$pic_140;
			}
			$piclist[]=$pic;
		}
		return $piclist;
	}
	
	function get_140($file_url){
		$ext=strrchr($file_url, '.');
		return str_replace($ext,'_140'.$ext,$file_url);
	}
}
?>
