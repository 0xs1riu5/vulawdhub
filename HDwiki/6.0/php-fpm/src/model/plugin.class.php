<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class pluginmodel {

	var $db;
	var $base;

	function pluginmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	/*Description: This method has already expired*/
	function  get_plugin_by_id($id){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."plugin  WHERE  pluginid=$id");
	}
	
	/*Description: This method has already expired*/
	function  get_plugin_by_identifier($identifier){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."plugin  WHERE  identifier='$identifier' ");
	}

	function get_all_list(){
		$pluginlist=array();
		$query=$this->db->query('SELECT * FROM '.DB_TABLEPRE.'plugin  ORDER BY  pluginid ASC ');
		while($plugin=$this->db->fetch_array($query)){
			$pluginlist[]=$plugin;
		}
		return $pluginlist;
	}

	function add_plugin($plugin){
		$version=isset($plugin['version'])?$plugin['version']:'v1.0';
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."plugin (name,identifier,description,datatables,type,copyright,homepage,version,suit,modules) VALUES ('$plugin[name]','$plugin[identifier]','$plugin[description]','$plugin[datatables]',$plugin[type],'$plugin[copyright]','$plugin[homepage]','$version','$plugin[suit]','$plugin[modules]') ");
		$pluginid=$this->db->insert_id();
		$vars=$plugin['vars'];
		if(isset($vars)){
			foreach($vars as $var){
				$this->db->query("INSERT INTO  ".DB_TABLEPRE."pluginvar (pluginid,displayorder,title,description,variable,type,value,extra) VALUES ('$pluginid','$var[displayorder]','$var[title]','$var[description]','$var[variable]','$var[type]','$var[value]','$var[extra]') ");
			}
		}
		$hooks=$plugin['hooks'];
		if(isset($hooks)){
			foreach($hooks as $hook){
				$this->db->query("INSERT INTO  ".DB_TABLEPRE."pluginhook (pluginid,available,title,description,code) VALUES ('$pluginid','$hook[available]','$hook[title]','$hook[description]','$hook[code]') ");
			}
		}
	}

	function update_plugin($id,$available=1){
		$this->db->query("UPDATE ".DB_TABLEPRE."plugin SET available='$available' WHERE pluginid=$id ");
	}

	function update_pluginvar($newvar,$pluginid){
		if(is_array($newvar)){
			foreach($newvar as $variable => $value) {
				$this->db->query("UPDATE ".DB_TABLEPRE."pluginvar SET value='$value' WHERE pluginid='$pluginid' AND variable='$variable'");
			}
		}
	}

	function remove_plugin($id){
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."plugin WHERE pluginid =$id ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."pluginvar WHERE pluginid =$id ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."pluginhook WHERE pluginid =$id ");
	}

	function get_pluginvar($pluginid){
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."pluginvar WHERE pluginid=$pluginid  ORDER BY  displayorder ASC ");
		$pluginvars=array();
		while($pluginvar=$this->db->fetch_array($query)){
			if($pluginvar['type'] == 'select'){
				$options=array();
				foreach(explode("\n", $pluginvar['extra']) as $key => $option) {
					$option = trim($option);
					if(strpos($option, '=') === FALSE) {
						$key = $option;
					} else {
						$item = explode('=', $option);
						$key = trim($item[0]);
						$option = trim($item[1]);
					}
					$options["$key"]=$option;
				}
				$pluginvar[extra]=$options;
			}
			$pluginvars[]=$pluginvar;
		}
		return $pluginvars;
	}

	function get_pluginhook($pluginid){
		$pluginhooks=array();
		$plugin=$this->get_plugin_by_id($pluginid);
		$identifier=$plugin['identifier'];
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."pluginhook WHERE pluginid=$pluginid AND available=1 ORDER BY  pluginhookid ASC ");
		while($pluginhook=$this->db->fetch_array($query)){
			$pluginhook['code']='eval($this->plugin[\''.$identifier.'\'][\'hooks\'][\''.$pluginhook['title'].'\']);';
			$pluginhooks[]=$pluginhook;
		}
		return $pluginhooks;
	}

	function var_value($pluginid){
		$pluginvars=array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."pluginvar WHERE pluginid=$pluginid ");
		while($pluginvar=$this->db->fetch_array($query)){
			$pluginvars[$pluginvar['variable']]=$pluginvar['value'];
		}
		return $pluginvars;
	}

	function hook_value($pluginid, $available){
		$pluginhooks=array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."pluginhook WHERE pluginid=$pluginid ");
		while($pluginhook=$this->db->fetch_array($query)){
			if($available){
				$pluginhooks[$pluginhook['title']]=$pluginhook['code'];
			}else{
				$pluginhooks[$pluginhook['title']]='';
			}
		}
		return $pluginhooks;
	}

	function read_all(){
		$pluginlist=array();
		$plugins=$this->get_all_list();
		if(! empty ($plugins) ){
			foreach($plugins as $plugin){
				if($plugin['available']){
					$pluginlist[$plugin['identifier']]=$plugin;
					$pluginlist[$plugin['identifier']]['vars']=$this->var_value($plugin['pluginid']);
					$pluginlist[$plugin['identifier']]['hooks']=$this->hook_value($plugin['pluginid'], $plugin['available']);
				}
			}
		}
		return $pluginlist;
	}

	function extract_all(){
		require HDWIKI_ROOT.'/lib/zip.class.php';
		$zip=new zip();
		if(!$zip->chk_zip){
			$this->base->message($this->view->lang['chkziperror'],'');
		}
		$plugindir = dir(HDWIKI_ROOT.'/plugins');
		while ($entry = $plugindir->read()) {
			$filename = HDWIKI_ROOT.'/plugins/'.$entry;
			if (is_file($filename)) {
				$ziplist=@$zip->get_List($filename);
				if($ziplist){
					$lastpos=strpos($ziplist[0]['filename'], '/');
					$identifier=substr($ziplist[0]['filename'],0,$lastpos);
					if(!$identifier){continue;}
					$plugin=$_ENV['plugin']->get_plugin_by_identifier($identifier);
					if( !$plugin && !file_exists(HDWIKI_ROOT.'/plugins/'.$identifier) ){
						$zip->Extract($filename,HDWIKI_ROOT.'/plugins');
					}
				}
			}
		}
		$plugindir->close();
	}

	function find_plugins(){
		$plugins=array();
		$plugindir = dir(HDWIKI_ROOT.'/plugins');
		while ($identifier = $plugindir->read() ) {
			$filename = HDWIKI_ROOT.'/plugins/'.$identifier;
			if (is_dir($filename)) {
				$installfile=$filename."/model/$identifier.class.php";
				if( file_exists($installfile) ){
					$plugin=$_ENV['plugin']->get_plugin_by_identifier($identifier);
					if( !$plugin ){
						$pluginfile=file::readfromfile($installfile);
						preg_match('/\$plugin=array\([\s\S]+?\);/i', $pluginfile, $matches);
						@$codeval=eval($matches[0]);
						if(false!==$codeval ){
							$plugin['version']=empty($plugin['version'])?'1.0':$plugin['version'];
							$plugins[]=$plugin;
						}
					}
				}
			}
		}
		$plugindir->close();
		return $plugins;
	}
	
	function find_remote_plugins($page,$orderby){
		$remote_plugins=array('data'=>array(),'count'=>0);
		$url=$this->base->setting['app_url'].'/hdapp.php?action=download&type=plugin&charset='.WIKI_CHARSET.'&page='.$page.'&version='.urlencode(HDWIKI_VERSION).'&orderby='.$orderby;
		$content=@util::hfopen($url);
		if($content){
			$remote_plugins=unserialize(base64_decode($content));
		}
		return $remote_plugins;
	}
	
	function share_plugin($plugin){
		$identifier=$plugin['identifier'];
		$descxml="<?xml version=\"1.0\" encoding=\"".WIKI_CHARSET."\"?>\n".
			"<theme>\n".
			"<author><![CDATA[".$plugin['author']."]]></author>\n".
			"<authorurl><![CDATA[".$plugin['authorurl']."]]></authorurl>\n".
			"<name><![CDATA[".$plugin['name']."]]></name>\n".
			"<tag><![CDATA[".$plugin['tag']."]]></tag>\n".
			"<desc><![CDATA[".$plugin['description']."]]></desc>\n".
			"<weburl><![CDATA[".$plugin['weburl']."]]></weburl>\n".
			"<version><![CDATA[".$plugin['version']."]]></version>\n".
			"<hdversion><![CDATA[".$plugin['hdversion']."]]></hdversion>\n".
			"<copyright><![CDATA[".$plugin['copyright']."]]></copyright>\n".
			"<charset><![CDATA[".WIKI_CHARSET."]]></charset>\n".
			"</theme>";
		file::writetofile(HDWIKI_ROOT.'/plugins/'.$identifier.'/desc.xml',$descxml);
		require_once HDWIKI_ROOT.'/lib/zip.class.php';
		$zip = new zip;
		$filedir=array('plugins/'.$identifier);
		$zipdir=array($identifier);
		$tmpname=HDWIKI_ROOT.'/data/tmp/'.util::random(6).'.zip';
		@$zip->zip_dir($filedir,$tmpname,$zipdir);
		$zip_content=file::readfromfile($tmpname);
		$upload_url=$this->base->setting['app_url'].'/hdapp.php?action=upload&type=plugin';
		$data='data='.base64_encode($zip_content);
		if('1'==@util::hfopen($upload_url,0,$data)){
			unlink($tmpname);
			return true;
		}
		return false;
	}
	
	
 
	
}
?>
