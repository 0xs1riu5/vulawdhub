<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class bannedmodel {

	var $db;
	var $base;

	function bannedmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_ip_list($start_limit=0,$num=0){
		$bannedlist = array();
		$this->refresh_ip();
		if(!empty($start_limit) || !empty($num)){
			$sqladd = "LIMIT $start_limit,$num";
		}
		$query = $this->db->query("SELECT * FROM `".DB_TABLEPRE."banned` ORDER BY id DESC $sqladd");
		while($banned = $this->db->fetch_array($query)){
			$banned['endtime'] = $this->base->date($banned['time']+$banned['expiration']);
			$banned['starttime'] = $this->base->date($banned['time']);
			if($banned['ip1']<0)$banned['ip1'] = '*';
			if($banned['ip2']<0)$banned['ip2'] = '*';
			if($banned['ip3']<0)$banned['ip3'] = '*';
			if($banned['ip4']<0)$banned['ip4'] = '*';
			$banned['ip'] = $banned['ip1'].'.'.$banned['ip2'].'.'.$banned['ip3'].'.'.$banned['ip4'];
			$bannedlist[] = $banned;
		}
		return $bannedlist;
	}
	
	function add_ip($alluploadips, $expiration, $username){
		$expiration = ($expiration) ? $expiration*3600*24 : 0;
		$num = count($alluploadips);
		$start = 0;
		$step = $end = 100;
		do{
			$alluploadip = array_slice($alluploadips, $start, $step);
			$query = "INSERT INTO `".DB_TABLEPRE."banned` (`ip1`,`ip2`,`ip3`,`ip4`,`admin`,`time`,`expiration`) VALUES ";
			foreach($alluploadip as $banip){
				$ip = explode('.', $banip);
				$query .= "('{$ip[0]}','{$ip[1]}','{$ip[2]}','{$ip[3]}','{$username}','{$this->base->time}','{$expiration}'),";
			}
			$query = substr($query,0,-1) . ';';
			$this->db->query($query);
			if($end >= $num){
				break;
			}
			$end += $step;
			$start += $step;
		}while(true);
		$this->updatebannedip();
	}
	
	function del_ip($ips){
		$this->db->query("DELETE FROM `".DB_TABLEPRE."banned` WHERE id IN (' ".implode("','",$ips)."')");
		$this->updatebannedip();
	}
	
	function refresh_ip(){
		$this->db->query("DELETE FROM `".DB_TABLEPRE."banned` WHERE (`time`+`expiration`)<{$this->base->time}");
	}
	
	function updatebannedip(){
		$ips=$this->get_ip_list();
		$this->base->cache->writecache('bannedip', $ips);
	}
	
	function get_allnum(){
		return $this->db->fetch_total('banned');
	}
	
	function singleip($alluploadips, $post){
		$ip1 = $post['ip1new'] !='*' ? intval($post['ip1new']) : -1;
		$ip2 = $post['ip2new'] !='*' ? intval($post['ip2new']) : -1;
		$ip3 = $post['ip3new'] !='*' ? intval($post['ip3new']) : -1;
		$ip4 = $post['ip4new'] !='*' ? intval($post['ip4new']) : -1;		
		if($ip1<=255 || $ip2<=255 || $ip3<=255 || $ip4<=255){
			$ip = $ip1 .',' . $ip2 .','. $ip3 .','. $ip4;
			if($ip != '0,0,0,0'){
				$alluploadips[] = str_replace(',', '.', $ip);
			}
		}
		return $alluploadips;
	}
		
	function textip($alluploadips, $regular, $data){
		preg_match_all($regular, $data, $matchtexts, PREG_PATTERN_ORDER);
		if($matchtexts[0]){
			foreach ($matchtexts[0] as $matchtext){
				$alluploadips[] = str_replace('*', '-1', $matchtext);
			}
		}
		return $alluploadips;
	}
	
	function fileip($alluploadips, $regular, $attachment_size){
		$filetype = strtolower(substr($_FILES['file_path']['name'],strrpos($_FILES['file_path']['name'],".")+1));
		$destfile = 'data/tmp/'.time().'.'.$filetype;
		$result=file::uploadfile($_FILES['file_path']['tmp_name'],$destfile,$attachment_size,0);
		if($result){
			if( file_exists($destfile) ){
				$data = file::readfromfile($destfile);
			}
			$alluploadips = $this->textip($alluploadips, $regular, $data);
		@unlink($destfile);
		}
		return $alluploadips;
	}
}
?>