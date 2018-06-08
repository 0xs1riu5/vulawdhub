<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	
	function control(& $get,& $post){
		$this->base($get, $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load('upgrade');
		if($this->hgetcookie('querystring')) {
			$this->prev_querystring = $this->hgetcookie('querystring');
		}
	}
	
	function dodefault(){
		$this->view->display('admin_upgrade');
	}
	
	function docheck() {
		$this->hsetcookie('querystring', null, 3600);
		$avaliable_count = $_ENV['upgrade']->get_available_count();
		echo (empty($avaliable_count) || $avaliable_count === 'false') ? 'false' : $avaliable_count;
	}
	
	function doinitpage() {
		$this->hsetcookie('querystring', $this->prev_querystring, 3600);
		$available_packages = $_ENV['upgrade']->get_available_packages();
		if($available_packages) {
			$this->view->assign('packages', $available_packages);
		} 
		$this->view->display('admin_upgrade_initpage');
	}
	
	function doinstall() {
		$this->hsetcookie('querystring', $this->prev_querystring, 3600);
		set_time_limit(0);
		$proceed = true;
		$first_package = $_ENV['upgrade']->get_available_packages(1);
		if(!$first_package) {
			$this->view->display('admin_upgrade_initpage');
			return;
		}
	
		$_ENV['upgrade']->start_install($first_package);
		
		$this->show_header($first_package);

		$this->flush( '<li>正在检查可用内存 ...');
		$this->show_status($_ENV['upgrade']->check_memory(), $proceed);

		if($proceed) {
			$this->flush( '<li>正在下载文件 ...');
			$this->show_status($_ENV['upgrade']->download_package(), $proceed);
		}

		if($proceed) {
			$this->flush( '<li>正在校验文件MD5 ...');
			$this->show_status($_ENV['upgrade']->check_md5(), $proceed);
		}
		
		if($proceed) {
			$this->flush( '<li>正在释放文件 ...');
			$this->show_status($_ENV['upgrade']->extract_zip(), $proceed);
		}

		if($proceed) {
			$this->flush( '<li>正在检查目标权限 ...');
			$this->show_status($_ENV['upgrade']->check_permission(), $proceed);
		}
		
		if($proceed) {
			$this->flush( '<li>正在复制新文件 ...');
			$this->show_status($_ENV['upgrade']->copy_newfile(), $proceed);
		}
		
		//判断是否存在 upgrade_{release_code}.php 文件，如果存在，转给此文件处理
		if($proceed) {
			$upgrade_php = "upgrade_{$first_package['release_code']}.php";
			if(file_exists(HDWIKI_ROOT.'/'.$upgrade_php)) {
				
				//转向  upgrade_{release_code}.php
				echo("<li>新文件复制完成。<span style='color:red'>请点击“下一步”完成后续升级操作,或等待 10 秒钟自动进入下一步</span></li>");
				echo '<script type="text/javascript">
					window.parent.set_next({action:"'.$upgrade_php.'", btn_html:"下一步", timer: 10});
					</script>';
			} else {
				
				//清理安装程序临时文件
				$this->flush( '<li>正在清理临时文件 ...');
				$this->show_status($_ENV['upgrade']->make_clean(), $proceed);
				
				echo("<li>{$first_package['release_code']} 升级完成</li>");
				echo '<script type="text/javascript">window.parent.install_finish();</script>';
			}
			
		} else {
			echo '<script type="text/javascript">window.parent.install_retry();</script>'; //重试
		}
		
		
		$this->show_footer();
		$this->flush();
	}

	function flush($string='') {
		echo $string;
		@ob_end_flush();
	    @ob_flush();
	    @flush();
	    @ob_start();
	}

	function show_header(&$package) {
		echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
HTML;
		echo '<meta http-equiv="Content-Type" content="text/html;charset='.WIKI_CHARSET.'">';
		echo <<<HTML
<link href="style/default/admin/admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.head_title { line-height: 30px; padding-top: 2px; margin-bottom: 20px; border-bottom: solid 1px #CCC; }
ul li { line-height: 24px; border-bottom: 1px solid #EEE; padding: 0 2px; position: relative; }
ul span { position: absolute; right: 0; top: 0; }
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">window.parent.install_start();</script>
</head>
<body>
<div class="head_title">正在安装升级包 {$package['release_code']}</div>
<ul>
HTML;
	}

	function show_footer() {
		echo '</ul>';
		echo '<script type="text/javascript">window.parent.set_next({disabled: false});</script> ';
		echo '</body></html>';
	}

	function show_status($status, &$proceed) {
		if($status) {
			echo '<span style="color: green">成功</span>';
		} else {
			echo '<span style="color: red">失败</span>';
			$proceed = false;
		}
		$this->flush('</li>');
 	}


}