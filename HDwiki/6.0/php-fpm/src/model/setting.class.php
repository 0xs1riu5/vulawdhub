<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class settingmodel {

	var $db;
	var $base;

	function settingmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function update_setting($setting){
		foreach($setting as $key=>$value){
			if($key == 'site_url' && $value != WIKI_URI) { //site_url 存入 config.php 文件
				$cfg = file::readfromfile(HDWIKI_ROOT.'/config.php');
				$new_cfg = preg_replace("/WIKI_URL(['\"])\s*,\s*(['\"])(.*)\\1/", 'WIKI_URL${1}, ${2}'.$value.'${2}', $cfg);
				file::writetofile(HDWIKI_ROOT.'/config.php', $new_cfg, 0);
			} else {
				if($this->base->setting[$key] != $value){
					$this->db->query("REPLACE INTO ".DB_TABLEPRE."setting (variable,value) VALUES ('$key','$value')");
				}
			}
		}
		return $this->db->insert_id();
	}
	
	/*此方法已经过时，需要删除*/
	function update_cache($cachelist,$cachevalue){
		foreach($cachelist as $cache){
			$this->db->query("UPDATE ".DB_TABLEPRE."setting SET value = '".$cachevalue[$cache."_value"]."' WHERE variable = '".$cache."' LIMIT 1");
		}
	}
}
?>