<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class synonymmodel {
	var $db;
	var $base;
	function synonymmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	function get_synonym_by_dest($did='',$title){
		$synonymlist=array();
		if(!empty($did)){
			$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."synonym WHERE destdid=$did ORDER BY id");
		}elseif(!empty($title)){
			$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."synonym WHERE desttitle='$title' ORDER BY id");
		}else{
			return false;
		}
		while($synonym=$this->db->fetch_array($query)){
			$synonym['desttitle']=htmlspecial_chars(stripslashes($synonym['desttitle']));
			$synonym['srctitle']=stripslashes($synonym['srctitle']);
			$synonymlist[]=$synonym;
		}
		return $synonymlist;
	}
	
	function get_synonym_by_src($title,$id=''){
		if(!empty($title)){			
			//判断有无词条名称和该同义词名称相同
			$has_same_title = $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."doc WHERE title='$title'");
			if($has_same_title){
				return false;
			}else{
				return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."synonym WHERE srctitle='$title'");
			}
		}elseif(is_numeric($id)){
			return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."synonym WHERE id=$id");
		}else{
			return false;
		}
	}
	
	function savesynonym($destdid,$desttitle,$srctitles){
		$this->removesynonym($destdid);
		$sql="REPLACE INTO ".DB_TABLEPRE."synonym (srctitle,destdid,desttitle) VALUES";
		foreach($srctitles as $srctitle){
			if(!empty($srctitle)){
				$srctitle = trim($srctitle);
				$sql.="('$srctitle',$destdid,'$desttitle'),";
			}

				
		}
		$sql=substr($sql,0,-1);
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	function removesynonym($destdid){
		$titles = '';
		$dids = is_array($destdid) ? implode(',',$destdid) : $destdid;
		$query = $this->db->query("SELECT srctitle FROM ".DB_TABLEPRE."synonym WHERE destdid IN ($dids)");
		while($row=$this->db->fetch_array($query)){
			$titles[] = addslashes($row['srctitle']);
		}
		$titles = join("','", $titles);
		$this->db->query("update ".DB_TABLEPRE."innerlinkcache set titleid='0' where title in('$titles')");
		$this->db->query("DELETE FROM ".DB_TABLEPRE."synonym WHERE destdid IN ($dids) ");
		return $this->db->affected_rows();
	}
	
	function synonym_change_doc($did,$title){
		$this->db->query("UPDATE ".DB_TABLEPRE."synonym SET desttitle='$title' WHERE destdid=$did");
		return $this->db->affected_rows();
	}
	
	function search_synonym_num($cid,$title,$author,$starttime,$endtime){
		$did=array();
		$sql="SELECT  count(*)  FROM ".DB_TABLEPRE."synonym s LEFT JOIN ".DB_TABLEPRE."doc d ON d.did = s.destdid where 1=1 ";
		if($cid){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink  WHERE cid = $cid");
			while($doc=$this->db->fetch_array($query)){
				$dids[]=$doc['did'];
			}
			$dids=is_array($dids)?implode(',',$dids):'';
			if($dids){
				$sql=$sql." AND s.destdid IN ($dids) ";
			}else{
				$sql=$sql." AND 1!=1 ";
			}
		}
		if($title){
			$synonym=$this->db->fetch_first("SELECT destdid FROM ".DB_TABLEPRE."synonym WHERE srctitle='$title'");
			if($synonym[destdid]){
				$sql=$sql." AND (s.desttitle ='$title' OR s.destdid =$synonym[destdid]) ";
			}else{
				$sql=$sql." AND s.desttitle ='$title' ";
			}
		}
		if($author){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."doc WHERE author='$author'");
			while($doc=$this->db->fetch_array($query)){
				$did[]=$doc['did'];
			}
			if(!empty($did)){
				$sql=$sql." AND s.destdid IN (".implode(',',$did).") ";
			}
		}
		if($starttime){
			$sql=$sql." AND d.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND d.time<=$endtime ";
		}
		return $this->db->result_first($sql);
	}
	
	function search_synonym($start=0,$limit=10, $cid='', $title='',$author='', $starttime='', $endtime='' ){
		$doclist=array();
		$i=0;
		$sql="SELECT  s.*,d.title title,d.author author,d.authorid authorid,d.time time FROM ".DB_TABLEPRE."synonym s LEFT JOIN ".DB_TABLEPRE."doc d ON d.did = s.destdid where 1=1 ";
		if($cid){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink  WHERE cid = $cid");
			while($doc=$this->db->fetch_array($query)){
				$dids[]=$doc['did'];
			}
			$dids=is_array($dids)?implode(',',$dids):'';
			if($dids){
				$sql=$sql." AND s.destdid IN ($dids) ";
			}else{
				$sql=$sql." AND 1!=1 ";
			}
		}
		if($title){
			$synonym=$this->db->fetch_first("SELECT destdid FROM ".DB_TABLEPRE."synonym WHERE srctitle='$title'");
			if($synonym[destdid]){
				$sql=$sql." AND (s.desttitle ='$title' OR s.destdid =$synonym[destdid]) ";
			}else{
				$sql=$sql." AND s.desttitle ='$title' ";
			}
		}
		if(!empty($author)){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."doc  WHERE author='$author'");
			while($doc=$this->db->fetch_array($query)){
				$destdid[]=$doc['did'];
			}
			if(!empty($destdid)){
				$sql=$sql." AND s.destdid IN (".implode(',',$destdid).") ";
			}
		}
		if($starttime){
			$sql=$sql." AND d.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND d.time<=$endtime ";
		}
		$sql=$sql." ORDER BY s.desttitle,s.id LIMIT $start,$limit ";
		$query=$this->db->query($sql);
		while($synonym=$this->db->fetch_array($query)){
			$synonym['title']=htmlspecial_chars($synonym['title']);
			$synonym['time'] = $this->base->date($synonym['time']);
			if($synonymlist[$i-1]['destdid']!==$synonym['destdid']){
				$synonymlist[$i]=$synonym;
				$i++;
			}else{
				$synonymlist[$i-1]['srctitle'].=",".$synonym['srctitle'];
				$synonymlist[$i-1]['did'].=",".$synonym['did'];
			}
		}
		return $synonymlist;
	} 
	
	function is_filter($srctitles,$desttitle='',$type=false){
		foreach($srctitles as $srctitle){
			if($srctitle===$desttitle)
				return array(-2,$srctitle);
			if($_ENV['doc']->have_danger_word($srctitle))
				return array(-3,$srctitle);
			if(!empty($desttitle)){
				if($type){
					if($synonym=$this->get_synonym_by_dest('',$desttitle)){
						return array(-4,$desttitle);
					}
				}
				if($synonym=$this->get_synonym_by_src($srctitle)){
					 if(string::haddslashes($synonym['desttitle'],1)!=$desttitle)
						return array(-5,$srctitle,$synonym['desttitle']);
				}
				if($synonym=$this->get_synonym_by_dest('',$srctitle)){
					return array(-6,$srctitle);
				}
			}
		}
		return array(1,'');
	}
	
	function encode_data($datas){
		foreach($datas as $data){
			$return_data.=$data.";";
		}
		return $return_data;
	}
}
?>
