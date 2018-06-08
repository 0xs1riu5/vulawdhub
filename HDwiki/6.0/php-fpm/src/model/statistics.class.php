<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class statisticsmodel {
	var $db;
	var $base;
	
	function statisticsmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function count_num($table,$where=''){
		if(!(bool)$where){
			$num=$this->db->fetch_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE.$table);
		}else{
			$num=$this->db->fetch_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."$table WHERE $where");
		}
		return $num['num'];
	}
	
	function first_register_time(){
		$time=$this->db->fetch_first("SELECT  regtime AS time FROM ".DB_TABLEPRE."user ORDER BY regtime");
		return $time['time'];
	}
	
	function first_create_time(){
		$time=$this->db->fetch_first("SELECT time FROM ".DB_TABLEPRE."doc ORDER BY did");
		return $time['time'];
	}
	
	function new_user(){
		return $this->db->fetch_first("SELECT uid,username FROM ".DB_TABLEPRE."user ORDER BY regtime DESC");
	}
	
	function star_today(){
		$time=$this->base->time-3600*24;
		return $this->db->fetch_first("SELECT authorid,author,COUNT(authorid) AS authornum FROM ".DB_TABLEPRE."edition WHERE time >= $time GROUP BY authorid ORDER BY authornum DESC");
	}
	
	function hot_category(){
		return $this->db->fetch_first("SELECT cid,name FROM ".DB_TABLEPRE."category ORDER BY docs DESC");
	}
	
	function category_toplist($type,$limit=20){
		$categorylist=array();
		$sql="SELECT l.cid,COUNT(1) num,c.name ";
		switch($type){
		case 1:
			$sql="SELECT cid,docs num,name FROM ".DB_TABLEPRE."category ORDER BY docs DESC ";
			break;
		case 2:
			$sql.="FROM ".DB_TABLEPRE."edition d , ".DB_TABLEPRE."categorylink l , ".DB_TABLEPRE."category c WHERE l.did=d.did AND c.cid=l.cid GROUP BY l.cid ORDER BY num DESC ";
			break;
		case 3:
			$time=$this->base->time-3600*24*30;
			$sql.="FROM ".DB_TABLEPRE."doc d , ".DB_TABLEPRE."categorylink l , ".DB_TABLEPRE."category c WHERE l.did=d.did AND c.cid=l.cid AND d.time >= $time GROUP BY l.cid ORDER BY num DESC ";
			break;
		case 4:
			$time=$this->base->time-3600*24;
			$sql.="FROM ".DB_TABLEPRE."doc d , ".DB_TABLEPRE."categorylink l , ".DB_TABLEPRE."category c WHERE l.did=d.did AND c.cid=l.cid AND d.time >= $time GROUP BY l.cid ORDER BY num DESC ";
			break;
		}
		if((bool)$limit){
			$sql.="LIMIT ".$limit;
		}
		$query=$this->db->query($sql);
		while($category=$this->db->fetch_array($query)){
			$categorylist[]=$category;
		}
		return $categorylist;
	}
	
	function doc_toplist($field,$limit=20){
		$doclist=array();
		$sql="SELECT did,title,$field num FROM ".DB_TABLEPRE."doc WHERE $field>0 ORDER BY $field DESC ";
		if((bool)$limit){
			$sql.="limit ".$limit;
		}
		$query=$this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			$doclist[]=$doc;
		}
		return $doclist;
	}
	
	function user_toplist($parameter,$type=1,$limit=20){
		$userlist=array();
		if($type===1){
			$sql="SELECT uid,username,$parameter num FROM ".DB_TABLEPRE."user WHERE $parameter>0 ORDER BY $parameter DESC ";
		}else{
			$sql="SELECT authorid uid,author username,COUNT(authorid) num FROM ".DB_TABLEPRE."doc WHERE time >= $parameter GROUP BY authorid ORDER BY num DESC ";
		}
		if((bool)$limit){
			$sql.="limit ".$limit;
		}
		$query=$this->db->query($sql);
		while($user=$this->db->fetch_array($query)){
			$userlist[]=$user;
		}
		return $userlist;
	}
	
	function get_admin_team($start=0,$limit=20){
		$teamlist=array();
		$sql="SELECT uid,username,credit1,credit2,lasttime,regtime,groupid,creates+edits num FROM ".DB_TABLEPRE."user  WHERE groupid=3 OR groupid=4 OR groupid=15 ORDER BY groupid DESC,lasttime DESC LIMIT $start,$limit";
		$query=$this->db->query($sql);
		while($team=$this->db->fetch_array($query)){
			$teamlist[]=$team;
		}
		return $teamlist;
	}
	
	function http_array($data){
		$return = '';
		if(!is_array($data)) return '';
		foreach($data as $k=>$v){
			$return .= $k.'='.$v.'&';
		}
		return $return?substr($return,0,-1):'';
	}
	
}
?>
