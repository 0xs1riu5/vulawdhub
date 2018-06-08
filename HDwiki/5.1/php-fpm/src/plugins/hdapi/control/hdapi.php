<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->loadplugin('hdapi');
	}

	function dodefault(){
		$len=strlen('plugin-hdapi-hdapi-default-');
		$title=substr($_SERVER['QUERY_STRING'],$len);
		$title = urldecode($title);
		if(strtolower(WIKI_CHARSET) == 'gbk'){$title = string::hiconv($title,'gbk','utf-8');}
		if($title){
			$doc = $_ENV['hdapi']->get_doc_by_title($title);
			if(is_array($doc)){
				$this->setting['date_format'] = "Y-m-d";
				$this->setting['time_format'] = "H:i:s";
				//$doc['content'] = $_ENV['hdapi']->filter_external($doc['content']);
				$his = $_ENV['hdapi']->get_recent_editon_info($doc['did']);
				if(strtolower(WIKI_CHARSET) == 'gbk'){
					$doc['content'] = string::hiconv($doc['content'],'utf-8','gbk',true);
					$doc['author'] = string::hiconv($doc['author'],'utf-8','gbk',true);
					$his['reason'] = string::hiconv($his['reason'],'utf-8','gbk',true);
					$doc['lasteditor'] = string::hiconv($doc['lasteditor'],'utf-8','gbk',true);
					$doc['title'] = string::hiconv($doc['title'],'utf-8','gbk',true);
				}
				$output = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
	            <ROOT>
	            <DOC_TITLE><![CDATA[".$doc['title']."]]></DOC_TITLE>
	            <DOC_TEXT><![CDATA[".$doc['content']."]]></DOC_TEXT>
	            <DOC_LATEST_EDITION><![CDATA[".$his["eid"]."]]></DOC_LATEST_EDITION>
	            <DOC_CREATOR_USER_NICK><![CDATA[".$doc['author']."]]></DOC_CREATOR_USER_NICK>
	            <DOC_CREATED_TIME><![CDATA[".$this->date($doc['time'])."]]></DOC_CREATED_TIME>
	            <DOC_LATEST_EDITION_TIME><![CDATA[".$this->date($doc['lastedit'])."]]></DOC_LATEST_EDITION_TIME>
				<DOC_HIS_EDIT_REASON><![CDATA[".$his['reason']."]]></DOC_HIS_EDIT_REASON>
				<DOC_HIS_EDITOR_USER_NICK><![CDATA[".$doc['lasteditor']."]]></DOC_HIS_EDITOR_USER_NICK>
				</ROOT>";
				echo $output;
			}else{
				exit();
			}
		}else{
			exit();
		}
	}
	
	function douniontitle(){
		@header('Content-type: application/json; charset='.WIKI_CHARSET);
		$len=strlen('plugin-hdapi-hdapi-uniontitle-');
		$did=substr($_SERVER['QUERY_STRING'],$len);
		$doc = $_ENV["hdapi"]->get_doc_title_by_id($did, 'title');
		if (!isset($doc['title'])) exit;
		
		$is_private_title = $_ENV['hdapi']->is_private_title($doc['title']);
		if (!empty($is_private_title)) exit;
		
		$uniontitle = $_ENV["hdapi"]->get_uniontitle_by_did($did);
		if (empty($uniontitle)){
			$uniontitle = $_ENV["hdapi"]->get_tit_url($doc['title']);
			if(isset($uniontitle['docdeclaration']) && strtolower(WIKI_CHARSET) == 'gbk'){
				$uniontitle['docdeclaration'] = string::hiconv($uniontitle['docdeclaration'], 'gbk', 'utf-8');
			}
			if (!empty($uniontitle['docdeclaration'])){
				$_ENV["hdapi"]->save_uniontitle_by_did($did, $uniontitle['docdeclaration']);
			}
		}
		
		if (is_array($uniontitle) && isset($uniontitle['docdeclaration'])){
			$uniontitle = $uniontitle['docdeclaration'];
		}else{
			$uniontitle = '';
		}
		echo $uniontitle;
	}
}

?>
