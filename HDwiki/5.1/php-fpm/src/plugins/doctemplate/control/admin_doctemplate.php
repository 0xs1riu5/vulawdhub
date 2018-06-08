<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('plugin');
		$this->load("category");
		$this->loadplugin('doctemplate');
		$this->view->setlang('zh','back');
	}

	function dodefault() {
		$plugin=$_ENV['plugin']->get_plugin_by_identifier('doctemplate');
		$pluginid=$plugin['pluginid'];

		$doctemplate = $_ENV['doctemplate']->get_doctemplate($this->get[2]);
		$doctemplate=$doctemplate['dcontent'];

		if(isset($this->post['suresubmit'])){
			if(isset($this->post['ctemplate']) && isset($this->post['qcattype']) && is_numeric($this->post['qcattype'])){
				$dcontent=$this->post['ctemplate'];
				$cid=$this->post['qcattype'];
				$_ENV['doctemplate']->doctemplate_operation($cid,$dcontent);
			}
		}

		//分类列表
		$pid =isset($this->get[2])?intval($this->get[2]):0;
		$pcat = $_ENV['category']->get_category($pid);
		$catnavi = unserialize($pcat['navigation']);
		$cats = $_ENV['category']->get_subcate($pid);
		$_ENV['doctemplate']->doctemplate_operation($cid,$dcontent);
		$isset_doctemplate=$_ENV['doctemplate']->get_doctemplate_list();
		$this->view->assign('cats',$cats);
		$this->view->assign('catnavi',$catnavi);
		$this->view->assign('pid',$pid);
		$this->view->assign('isset_doctemplate',$isset_doctemplate);


		//词条分类catstr
		$all_category=$this->cache->getcache('category',$this->setting['index_cache_time']);
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->cache->writecache('category',$all_category);
		}
		for($i=0;$i<count($all_category);$i++){
			$all_category_js[]="{'cid':'".$all_category[$i]['cid']."','pid':'".$all_category[$i]['pid']."','name':'".$all_category[$i]['name']."'}";
		}
		$this->view->assign("cid",$this->get[2]);
		$this->view->assign("all_category",implode(",",$all_category_js));

		$this->view->assign("pluginid",$pluginid);
		$this->view->assign("doctemplate",$doctemplate);
		$this->view->display('file://plugins/doctemplate/view/admin_doctemplate');
	}

}

?>
