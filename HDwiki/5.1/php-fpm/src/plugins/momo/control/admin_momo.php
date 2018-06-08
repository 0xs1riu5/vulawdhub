<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{
	var $pluginid;
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('plugin');
		$this->loadplugin('momo');
		$this->view->setlang('zh','back');
		$this->load('setting');
		$plugin=$_ENV['plugin']->get_plugin_by_identifier('momo');
		$this->pluginid=$plugin['pluginid'];
	}
	
	
	function dodefault() {
		$plugin=$_ENV['plugin']->get_plugin_by_identifier('momo');
		$pluginid=$plugin['pluginid'];
		$momocode=$_ENV['momo']->get_momo($pluginid);
		$counts=count($momocode);
		for($i=0;$i<$counts;$i++){
			if($momocode[$i][variable]=='momolength')$momolength=$momocode[$i][value];
			if($momocode[$i][variable]=='momourl') $momourl=$momocode[$i][value];
			if($momocode[$i][variable]=='momoid') $momoid=$momocode[$i][value];
			if($momocode[$i][variable]=='momotype') $momotype=$momocode[$i][value];
		}
		
		if($momolength=='')$momolength=16;
		if($momourl=='')$momourl=$this->setting['site_url'];
		$momocodecon='<script type="text/javascript">
var momourl = "'.$momourl.'";
var momoid = "'.$momoid.'";
var momolength = "'.$momolength.'";
var momotype = "'.$momotype.'";
</script>
';
		$momocodecon.='<script type="text/javascript" src="'.$this->setting['site_url'].'/plugins/momo/momo.js"></script>';
		
		$this->view->assign('pluginid',$this->pluginid);
		$this->view->assign('momocode',$momocodecon);
		$this->view->display('file://plugins/momo/view/admin_momo');
	}
}
?>