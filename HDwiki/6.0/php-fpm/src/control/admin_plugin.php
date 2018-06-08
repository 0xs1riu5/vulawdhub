<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('plugin');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function dodefault(){
		$this->dolist();
	}
	
	/*plugin list*/
	function dolist(){
		$pluginlist=$_ENV['plugin']->get_all_list();
		$this->view->assign('pluginlist', $pluginlist);
		$this->view->display('admin_plugin');
	}
	
	/*plugin will to be installed*/
	function dowill(){
		$page = !empty($this->get[3]) ? max(1, intval($this->get[3])) : 1;
		$orderby = $this->get[2];
		if(!$orderby)$orderby='time';
		$remotes=$_ENV['plugin']->find_remote_plugins($page,$orderby);
		$remote_plugins=$remotes['data'];
		$remote_count=$remotes['count'];
		$departstr=$this->multi($remote_count, 10, $page,"admin_plugin-will-".$orderby);
		$this->view->assign('remote_plugins', $remote_plugins);
		$this->view->assign('departstr', $departstr);
		$this->view->assign('orderby', $orderby);
		$this->view->display('admin_plugininstall');
	}
	
	/*plugin find to be installed*/
	function dofind(){
		if(function_exists('gzopen')){
			$_ENV['plugin']->extract_all();
		}
		$findplugins=$_ENV['plugin']->find_plugins();
		$this->view->assign('findplugins', $findplugins);
		$this->view->display('admin_pluginfind');
	}
	
	/*plugin install*/
	function doinstall(){
		$appid=$this->get[2];
		if(is_numeric($appid)){
			$pluginurl=$this->setting['app_url'].'/hdapp.php?action=download&type=plugin&install=1&id='.$appid.'&url='.urlencode(WIKI_URL);
			$zipcontent=@util::hfopen($pluginurl);
			if(empty($zipcontent)){
				$this->message($this->view->lang['msgConnectFailed']);
			}
			$tmpname=HDWIKI_ROOT.'/data/tmp/'.util::random(6).'.zip';
			file::writetofile($tmpname,$zipcontent);
			if(function_exists('gzopen')){
				require HDWIKI_ROOT."/lib/zip.class.php";
				$zip=new zip();
				if(!$zip->chk_zip){
					$this->message($this->view->lang['pluginInstallNoZlib'],'');
				}
				$ziplist=@$zip->get_List($tmpname);
				if(!(bool)$ziplist){
				  unlink($tmpname);
				  $this->message($this->view->lang['pluginAddr'].$pluginurl.$this->view->lang['pluginAddrFail']);
				}
				$lastpos=strpos($ziplist[0]['filename'], '/');
				$identifier=substr($ziplist[0]['filename'],0,$lastpos);
				@$zip->Extract($tmpname,HDWIKI_ROOT.'/plugins');
			}else{
				$this->message($this->view->lang['pluginInstallNoZlib']);
			}
			unlink($tmpname);
		}else{
			$identifier=$this->get[2];
		}
		$plugin=$this->db->fetch_by_field('plugin','identifier',$identifier);
		if($plugin){
		  	$this->message($this->view->lang['pluginAddrName'].$identifier.$this->view->lang['pluginHasInstall'],'index.php?admin_plugin-list');
		}
		$this->loadplugin($identifier);
		$plugin=$_ENV["$identifier"]->install();
		$_ENV['plugin']->add_plugin($plugin);
		$this->cache->removecache('plugin');
		$this->message($this->view->lang['pluginInstallSuccess'],'index.php?admin_plugin-list');
	}
	
	/*uninstall plugin:delete data first,then delete file */
	function douninstall(){
		$plugin=$this->db->fetch_by_field('plugin','pluginid',$this->get[2]);
		if(!$plugin){
		  	$this->message($this->view->lang['pluginNotExist']);
		}
		$identifier=$plugin['identifier'];
		$this->loadplugin($identifier);
		$_ENV["$identifier"]->uninstall();
		@file::removedir(HDWIKI_ROOT."/plugins/$identifier");
		$_ENV['plugin']->remove_plugin($this->get[2]);
		$this->cache->removecache('plugin');
		$this->message($this->view->lang['pluginAddrName'].$plugin[identifier].$this->view->lang['pluginunInstallSuccess'],'index.php?admin_plugin-list');
	}
	
	/*start plugin*/
	function dostart(){
		$_ENV['plugin']->update_plugin($this->get[2],1);
		$this->cache->removecache('plugin');
		$this->message($this->view->lang['pluginStartInstallSuccess'],'index.php?admin_plugin-list');
	}
	
	/*stop plugin*/
	function dostop(){
		$_ENV['plugin']->update_plugin($this->get[2],0);
		$this->cache->removecache('plugin');
		$this->message($this->view->lang['pluginStopInstallSuccess'],'index.php?admin_plugin-list');
	}
	
	/*jump to plugin url */
	function domanage(){
		$pluginid=$this->get[2];
		$plugin=$this->db->fetch_by_field('plugin','pluginid',$pluginid);
		$identifier=$plugin['identifier'];
		$this->header('plugin-'.$identifier.'-admin_'.$identifier);
	}
	
	/*share the plugin */
	function doshare(){
		$pluginid=$this->get[2];
		if(!isset($this->post['submit'])){
			$plugin=$this->db->fetch_by_field('plugin','pluginid',$pluginid);
			if(file_exists(HDWIKI_ROOT.'/plugins/'.$plugin['identifier'].'/share.lock')){
				$this->message($this->view->lang['pluginRepeatShareFail'],'index.php?admin_plugin');
			}
			$plugin['authorurl']=empty($plugin['authorurl'])?'http://':$plugin['authorurl'];
			$plugin['weburl']=empty($plugin['weburl'])?'http://':$plugin['weburl'];
			$this->view->assign('plugin', $plugin);
			$this->view->display('admin_pluginshare');
		}else{
			$plugin['author']=$this->post['author'];
			$plugin['authorurl']=$this->post['authorurl'];
			$plugin['name']=$this->post['name'];
			$plugin['tag']=$this->post['tag'];
			$plugin['weburl']=$this->post['weburl'];
			$plugin['version']=$this->post['version'];
			$plugin['hdversion']=HDWIKI_VERSION;
			$plugin['copyright']=$this->post['copyright'];
			$plugin['description']=$this->post['description'];
			$plugin['identifier']=$this->post['identifier'];
			file::uploadfile($_FILES['pluginimg'],HDWIKI_ROOT.'/plugins/'.$plugin['identifier'].'/screenshot.jpg');
			$share=$_ENV['plugin']->share_plugin($plugin);
			if($share){
				@touch(HDWIKI_ROOT.'/plugins/'.$plugin['identifier'].'/share.lock');
				$this->message($this->view->lang['pluginShareSuccess'],'index.php?admin_plugin');
			}else{
				$this->message($this->view->lang['pluginShareFail'],'index.php?admin_plugin');
			}
		}
	}
	
	/*set plugin Variables*/
	function dosetvar(){
	if(!isset($this->post['submit'])){
			$pluginid=$this->get[2];
			@$pluginvars=$_ENV['plugin']->get_pluginvar($pluginid);
			$this->view->assign('pluginid', $pluginid);
			$this->view->assign('pluginvars', $pluginvars);
			$this->view->display('admin_pluginvar');
		}else{
			$pluginid=$this->post['pluginid'];
			$pluginvars=$_ENV['plugin']->get_pluginvar($pluginid);
			$plugin=$this->db->fetch_by_field('plugin','pluginid',$pluginid);
			$identifier=$plugin['identifier'];
			$this->loadplugin($identifier);
			if(method_exists($_ENV["$identifier"],'update')){
				$return=$_ENV["$identifier"]->update($this->post['newvar']);
				if($return!==true){
					$this->message($return,'BACK');
				}
			}
			$_ENV['plugin']->update_pluginvar($this->post['newvar'],$pluginid);
			$this->cache->removecache('plugin');
			$this->message($this->view->lang['pluginDateSuccess'],'index.php?admin_plugin-setvar-'.$pluginid);
		}
	}

	/*plugin hook*/
	function dohook(){
		$pluginid=$this->get[2];
		@$pluginhooks=$_ENV['plugin']->get_pluginhook($pluginid);
		$this->view->assign('pluginid', $pluginid);
		$this->view->assign('pluginhooks', $pluginhooks);
		$this->view->display('admin_pluginhook');
	}
}
?>