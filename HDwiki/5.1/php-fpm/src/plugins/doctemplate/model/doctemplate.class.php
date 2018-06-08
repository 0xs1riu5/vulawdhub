<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class doctemplatemodel {

	var $db;
	var $base;
	
	function doctemplatemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function install(){
		$plugin=array(
			'name'=>'词条分类模版',
			'identifier'=>'doctemplate',
			'description'=>'创建词条时可按装此插件设置添加词条分类模版，版本编码为UTF8',
			'datatables'=>'wiki_doctemplate',
			'type'=>'0',
			'copyright'=>'binger',
			'homepage'=>'http://403247021.qzone.qq.com',
			'version'=>'1.0(UTF8)',
			'suit'=>'4.0.1',
			'modules'=>''
		);

		$sql='DROP TABLE IF EXISTS '.DB_TABLEPRE.'doctemplate;';
		$this->db->query($sql);
		$sqlcreate='CREATE TABLE `'.DB_TABLEPRE.'doctemplate`(`tid` INT(11) NOT NULL AUTO_INCREMENT,`cid` INT(11) NOT NULL ,`dcontent` TEXT NOT NULL ,PRIMARY KEY (`tid`)) TYPE = MYISAM ;';
		$this->db->query($sqlcreate);
		//加钩子
		$hooks=array(
			array('available'=>"1",
			'title'=>'doctemplate',
			'description'=>'词条分类模版载入，二次开发需将代码copy到doc.php创建词条的docreate函数中。',
			'code'=>'$categorycount=count(explode(",",$doc["cid"]));
					if(1 === $categorycount){
						$this->loadplugin("doctemplate");
						$doctemplate=$_ENV["doctemplate"]->get_doctemplate($doc["cid"]);
						if($doc["content"]==""){
							$doctemplate["dcontent"] = str_replace("[h1]","<div class=hdwiki_tmml>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[/h1]","</div>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[b]","<b>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[/b]","</b>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[u]","<u>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[/u]","</u>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[i]","<i>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[/i]","</i>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[br]","<br>",$doctemplate["dcontent"]);
							$doctemplate["dcontent"] = str_replace("[hr]","<hr>",$doctemplate["dcontent"]);
							$doc["content"]=$doctemplate["dcontent"];
						}
					}')
		);
		$plugin['hooks'] = $hooks;
		$plugin['vars']=array();
		return $plugin;
	}

	function uninstall(){
		$this->db->query('DROP TABLE `'.DB_TABLEPRE.'doctemplate`');
		$this->db->query("DELETE FROM `".DB_TABLEPRE."pluginhook` WHERE title='doctemplate'");
	}

	function doctemplate_operation($cid,$dcontent)
	{
		if(is_numeric($cid) && $cid>0){
			$tid=$this->db->result_first("select tid from ".DB_TABLEPRE."doctemplate where cid=$cid");
			if(is_numeric($tid) && $tid>0)
				$this->db->query("UPDATE ".DB_TABLEPRE."doctemplate SET dcontent='$dcontent' WHERE cid=$cid");
			else
				$this->db->query("INSERT INTO ".DB_TABLEPRE."doctemplate SET dcontent='$dcontent',cid=$cid");
		}
	}
	function get_doctemplate($cid){
		if(is_numeric($cid)>0)
			$doctemplate=$this->db->fetch_first("SELECT * FROM `".DB_TABLEPRE."doctemplate` WHERE cid='$cid'");
		return $doctemplate;
	}
	
	function get_doctemplate_list(){
		$query=$this->db->query("SELECT * FROM  ".DB_TABLEPRE."doctemplate");
		while($catetemplate=$this->db->fetch_array($query)){
			$istemplate[]=$catetemplate;
		}
		return $istemplate;
	}
}	

?>
