<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base {

    function control(& $get,& $post) {
        $this->base($get, $post);
        $this->load('plugin');
        $this->loadplugin('googlemap');
        $this->view->setlang('zh','back');
    }

    function dodefault() {
        $plugin=$_ENV['plugin']->get_plugin_by_identifier('googlemap');
        $this->view->assign('pluginid',$plugin['pluginid']);
        $this->view->display('file://plugins/googlemap/view/admin_googlemap');
    }   
}