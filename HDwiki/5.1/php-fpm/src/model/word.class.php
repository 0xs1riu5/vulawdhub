<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class wordmodel {

	var $db;
	var $base;

	function wordmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function get_word_list($start=0, $limit=0){
		$wordlist = array();
		if(!empty($start) || !empty($limit)){
			$sqladd = "LIMIT $start,$limit";
		}
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."word ORDER BY id ASC $sqladd ");
		while($word = $this->db->fetch_array($query)){
			$wordlist[] = $word;
		}
		return $wordlist;
	}

	function get_word_num(){
		return $this->db->result_first("SELECT COUNT(*) AS num FROM `".DB_TABLEPRE."word`");
	}
	
	function add_word($alluploadwords,$replacement,$username){
		$username = addslashes(htmlspecialchars($username));
		$num = count($alluploadwords);
		$start = 0;
		$step = $end = 100;
		do{
			$alluploadword = array_slice($alluploadwords, $start, $step);
			if($alluploadword){
				$query = "INSERT INTO `".DB_TABLEPRE."word` (`admin`,`find`,`replacement`) VALUES ";
				foreach($alluploadword as $uploadword){
					$query .= "('{$username}','{$uploadword}','{$replacement}'),";
				}
				$query = substr($query,0,-1) . ';';
				$this->db->query($query);
			}
			if($end >= $num){
				break;
			}
			$end += $step;
			$start += $step;
		}while(true);
		$this->update_bannedword();
	}
	
	function del_words($id){
		if(is_array($id)){
			$ids=implode(',',$id);
		}else{
			$ids=$id;
		}
		$this->db->query("DELETE FROM `".DB_TABLEPRE."word` WHERE id IN ($ids)");
		$this->update_bannedword();
	}
	
	function edit_words($words,$username){
		if(is_array($words)){
			$username = addslashes(htmlspecialchars($username));
			foreach($words as $word){
				$word['find'] = addslashes(htmlspecialchars($word['find']));
				$word['replacement'] = addslashes(htmlspecialchars($word['replacement']));
				$sql = "SELECT * FROM `".DB_TABLEPRE."word` WHERE find = '$word[find]' AND replacement = '$word[replacement]'";
				if(!is_array($this->db->fetch_first($sql))){
					$sql = "UPDATE `".DB_TABLEPRE."word` SET find = '$word[find]',replacement = '$word[replacement]',admin = '$username' WHERE id = '$word[id]'";
					$this->db->query($sql);
				}
			}
			$this->update_bannedword();
			return true;
		}else {
			return false;
		}
	}
	
	function update_bannedword(){
		$words=$this->get_word_list();
		$bannedwords = array();
		if($words){
			foreach ($words as $key=>$word){
				$bannedwords[$word['find']] = $word['replacement'];
			}
		}
		$this->base->cache->writecache('word', $bannedwords);
	}
	
	function filewords($alluploadwords, $attachment_size){
		$filetype = strtolower(substr($_FILES['file_path']['name'],strrpos($_FILES['file_path']['name'],".")+1));
		$destfile = 'data/tmp/'.time().'.'.$filetype;
		$result=file::uploadfile($_FILES['file_path']['tmp_name'],$destfile,$attachment_size,0);
		if($result){
			if( file_exists($destfile) ){
				if(WIKI_CHARSET == 'UTF-8'){
					$data = string::hiconv(file::readfromfile($destfile), 'utf-8');
				}else{
					$data = string::hiconv(file::readfromfile($destfile), 'gbk');
				}
			}
			$filewords = array_filter(explode(',', str_replace('，', ',', addslashes($data))),array($this,"trimwords"));
			$alluploadwords = array_merge($alluploadwords, $filewords);;
			@unlink($destfile);
		}
		return $alluploadwords;
	}
	
	function trimwords($words){
		return trim($words);
	}
}
?>