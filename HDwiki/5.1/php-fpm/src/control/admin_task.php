<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('task');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

 	/*task*/
	function dodefault(){
		if(isset($this->post['submit'])){
			$ids = isset($this->post['task_id'])?$this->post['task_id']:'';
			$taskname = string::haddslashes(htmlspecialchars($this->post['taskname']));
			if($ids){$_ENV['task']->del_task($ids);}
			if($taskname){$_ENV['task']->add_task($taskname);}
			$this->message($this->view->lang['taskSuccess'],'index.php?admin_task');
		}else{
			$tasks = $_ENV['task']->get_task_list();
			$this->view->assign('tasks',$tasks);
			$this->view->display('admin_task');
		}
	}

	function dotaskstatus(){
		$id = intval($this->get[2]);
		if($_ENV['task']->edit_task_status($id)){
			$this->message($this->view->lang['taskSuccess'],'index.php?admin_task');
		}else{
			$this->message($this->view->lang['taskFail'],'index.php?admin_task');
		}
	}

	function doedittask(){
		if(isset($this->post['submit'])){
			$name = string::haddslashes(htmlspecialchars($this->post['newname']));
			$w = intval($this->post['weekday']);
			$d = intval($this->post['day']);
			$h = intval($this->post['hour']);
			$i = intval($this->post['minute']);
			$id = intval(@$this->post['id']);
			$_ENV['task']->edit_task($id,$name,$w,$d,$h,$i);
			$this->message($this->view->lang['taskSuccess'],'index.php?admin_task');
		}else{
			$id = intval($this->get[2]);
			$task = $_ENV['task']->get_task($id);
			$this->view->assign('task',$task);
			$this->view->display('admin_edittask');
		}
	}
}

?>