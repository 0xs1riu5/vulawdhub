<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load("datacall");
	}
	function dojs(){
		if(!isset($this->get['2'])) {
			$datastr = $this->view->lang['parameterError'];
			return false;
		}
		$datastr = $_ENV['datacall']->call($this->get['2'], 2);
		if(empty($datastr)) {
			$datastr = $this->view->lang['noDate'];
		}
		header("content-type:text/html; charset=".WIKI_CHARSET);
		$datastr = nl2br($datastr);
		$datastr = str_replace("\n", "", $datastr);
		$datastr = str_replace("\r", "", $datastr);
		$datastr = string::haddslashes($datastr,1);
		echo "document.write('".$datastr."')";
	}

}
?>
