<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load("synonym");
		$this->load("doc");
	}
	
	function doremovesynonym(){
		$destdid=$this->post['destdid'];
		if(is_numeric($destdid)){
			$num=$_ENV['synonym']->removesynonym($destdid);
			$this->message($num,'',2);
		}else{
			$this->message(-1,'',2);
		}
	}
	
	function dosavesynonym(){
		$destdid=$this->post['destdid'];
		if(!is_numeric($destdid)){
			exit;
		}

		$synonyms=array();
		if(!empty($this->post['srctitles'])) {
			foreach($this->post['srctitles'] as $srctitle) {
				$srctitle = htmlspecial_chars(string::haddslashes(string::hiconv(trim(string::stripscript($srctitle)))));
				if('' != $srctitle) {
					$synonyms[] = $srctitle;
				}
			}
		}
		$desttitle=trim($this->post['desttitle']);
		
		if (WIKI_CHARSET == 'GBK'){
			$desttitle=string::hiconv($desttitle);
		}
		
		if(empty($synonyms)){
			$_ENV['synonym']->removesynonym($destdid);
			exit("empty");
		}
		$srctitles=$synonyms;
		$filter=$_ENV["synonym"]->is_filter($srctitles,$desttitle); 
		if($filter[0]<0){
			echo $filter[0];
			exit;
		}
		if(is_array($srctitles)&&!empty($desttitle)){
			$num=$_ENV['synonym']->savesynonym($destdid,$desttitle,$srctitles); 
			if($num > 0) {
				$synonyms_list=$_ENV['synonym']->get_synonym_by_dest($destdid,'');
				$str='<ul class="doc-list" id="str">';
				for($i=0;$i<count($synonyms_list);$i++){
					$str.="<li><a href='index.php?doc-innerlink-".urlencode($synonyms_list[$i]['srctitle'])."' name='synonym'> ".$synonyms_list[$i]['srctitle']."</a></li>";
				}
				$str .='</ul>';
				exit($str);
			} else {
				exit('0');
			}
		}else{
			echo $filter[0];
			exit;
		}
	}
}

?>
