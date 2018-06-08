<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		//$this->load('filemanager');
		$this->load('dir');
		$this->load('ftp');
		$this->load('upload');
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->safe = $this->cache->getcache('safe');
	}
	//目录和文件列表
	function dodefault(){
		$rootpath = $this->setting['FTP_PATH'];
		if(isset($this->get[2])){
			if(isset($this->get[3]))
				$rootpath = $this->get[2].$this->get[3]."/";
			else
				$rootpath = $this->get[2];
		}else{
			if(isset($this->post['newchangedir']))
				$rootpath = $this->post['newchangedir'];
		}
		$thispath=urldecode(str_replace('//',"/",$_ENV['upload']->set_savepath(HDWIKI_ROOT).$rootpath));
		$rootpath=urldecode($rootpath);
		//链接ftp
		if ($_ENV['ftp']->ftp($this->setting['FTP_HOST'])) {
			if ($_ENV['ftp']->login($this->setting['FTP_USER'],$this->setting['FTP_PW'])) {
				if(!$_ENV['ftp']->chdir($rootpath))
					$this->message($this->view->lang['file_not_exist'],'index.php?admin_fileftpmanage-default');
				$array=$_ENV['ftp']->get_files($rootpath);
				$dirs=$array[0];
				$filelist=$array[2];
			} else {
				$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
			}
			$_ENV['ftp']->disconnect();
		} else {
			$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
		}
		if($error!="")
			$this->message($error,'index.php?admin_fileftpmanage-default');
		$this->view->assign("dirs", $dirs);
		$this->view->assign("filelist", $filelist);
		$this->view->assign("thispath", $thispath);
		$this->view->assign("rootpath", $rootpath);
		$this->view->display('admin_fileftpmanage');
	}
	//删除目录和子目录nono
	function dormdir(){
		//链接ftp
		if ($_ENV['ftp']->ftp($this->setting['FTP_HOST'])) {
			if ($_ENV['ftp']->login($this->setting['FTP_USER'],$this->setting['FTP_PW'])) {
				if(isset($this->get[2]) && isset($this->get[3]))
					$rootpath = $this->get[2].$this->get[3];
				else
					$this->message($this->view->lang['taskParametersInvalid'],'index.php?admin_fileftpmanage-default');
				if(isset($this->get[4]) && $this->get[4]==1){
					if($_ENV['ftp']->ftp_rmAll(urldecode($rootpath)))
						$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_fileftpmanage-default');
					else
						$this->message('操作失败','index.php?admin_fileftpmanage-default');
				}else{
					$rootpath = str_replace('*',".",$rootpath);
					if($_ENV['ftp']->delete(urldecode($rootpath)))
						$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_fileftpmanage-default');
					else
						$this->message('操作失败','index.php?admin_fileftpmanage-default');
				}
			} else {
				$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
			}
			$_ENV['ftp']->disconnect();
		}else{
			$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
		}
		if($error!="")
			$this->message($error,'index.php?admin_fileftpmanage-default');
		
	}
	//创建目录
	function donewdir(){
		//链接ftp
		if ($_ENV['ftp']->ftp($this->setting['FTP_HOST'])) {
			if ($_ENV['ftp']->login($this->setting['FTP_USER'],$this->setting['FTP_PW'])) {
				$currentdir=$this->post['currentdir'];
				$newdir=$this->post['newdir'];
				$directory = urldecode($currentdir.$newdir."/");
				if (!isset($newdir) || empty($newdir)) 
					$this->message($this->view->lang['input_new_file'],'index.php?admin_fileftpmanage-default');
				$reflag = $_ENV['ftp']->tmkdir($directory);
				if($reflag=='ok')
					$this->message($this->view->lang['dir_create_success'],"index.php?admin_fileftpmanage-default-".$directory."/");
				elseif($reflag=='ishas')
					$this->message($this->view->lang['directory_existed_change_name'],"index.php?admin_fileftpmanage-default-".$directory."/");
				else
					$this->message($this->view->lang['dir_create_fail'],"index.php?admin_fileftpmanage-default-".$directory."/");
			} else {
				$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
			}
			$_ENV['ftp']->disconnect();
		}else{
			$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
		}
		if($error!="")
			$this->message($error,'index.php?admin_fileftpmanage-default');	
	}
	//改名
	function dorename(){
		if(isset($this->post['dosubmit'])){
			$newname = $this->post['newname'];
			if (!isset($newname) || empty($newname)) 
				$this->message($this->view->lang['nothing'],'index.php?admin_fileftpmanage-default');
			$fname = $this->post['fname'];
			$rootpath = $this->post['rootpath'];
			if($newname!= "" && $fname!="" && $rootpath!=""){
				$oldname = $rootpath.$fname;
				$new=$rootpath.$newname;
				//链接ftp
				if ($_ENV['ftp']->ftp($this->setting['FTP_HOST'])) {
					if ($_ENV['ftp']->login($this->setting['FTP_USER'],$this->setting['FTP_PW'])) {
						$reflag = $_ENV['ftp']->ftp_renamef($oldname,$new);
						if($reflag == 'ok'){
							if(isset($this->post['isdir']) && $this->post['isdir'] == 1)
								$this->message($fname." ".$this->view->lang['success_change_name']."：".$newname,"index.php?admin_fileftpmanage-default-".$new."/");
							if(isset($this->post['isdir']) && $this->post['isdir'] == 0)
								$this->message($fname." ".$this->view->lang['success_change_name']."：".$newname,"index.php?admin_fileftpmanage-default");
						}elseif($reflag == 'ishas')
							$this->message($this->view->lang['exist_refill'],"index.php?admin_fileftpmanage-default-".$new."/");
						else
							$this->message($this->view->lang['operation_failure'],"index.php?admin_fileftpmanage-default-".$new."/");
					} else {
						$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
					}
					$_ENV['ftp']->disconnect();
				}else{
					$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
				}
				if($error!="")
					$this->message($error,'index.php?admin_fileftpmanage-default');
			}
		}
		if(isset($this->get[2]) && isset($this->get[3])){
			$this->get[3] = str_replace('*','.',$this->get[3]);
			$this->view->assign("rootpath", $this->get[2]);
			$this->view->assign("fname", urldecode($this->get[3]));
			$this->view->assign("isdir", $this->get[4]);
		}
		$this->view->display('admin_fileftprename');
	}
	//下载
	function dodownload(){
		if(isset($this->get[2]) && isset($this->get[3])){
			$thispath = $this->get[2].$this->get[3];
			$thispath = urldecode(str_replace('*','.',$thispath));
			//链接ftp
			if ($_ENV['ftp']->ftp($this->setting['FTP_HOST'])) {
				if ($_ENV['ftp']->login($this->setting['FTP_USER'],$this->setting['FTP_PW']))
					$_ENV['ftp']->php_ftp_download($thispath);
				else
					$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
				$_ENV['ftp']->disconnect();
			}else{
				$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
			}
			if($error!="")
				$this->message($error,'index.php?admin_fileftpmanage-default');
		}
	}
	//上传文件
	function douploadfile(){
		if(isset($this->post['dosubmit']))
		{
			$currentdir= $this->post['currentdir'];
			$rootpath= $this->post['updir'];
			$dir=$this->post['dir'];
			$overfile=$this->post['overfile'];
			$newname=$this->post['newname'];
			$upfile_size = '4000000';
			$savepath = $currentdir;
			$fileArr = array(
					'file'=>$_FILES['uploadfile']['tmp_name'],
					'name'=>$_FILES['uploadfile']['name'],
					'size'=>$_FILES['uploadfile']['size'],
					'type'=>$_FILES['uploadfile']['type'],
					'error'=>$_FILES['uploadfile']['error']
			);
			if(file_exists(HDWIKI_ROOT.$savepath.$fileArr['name']) && !isset($newname))
				$this->message($this->view->lang['find_same_file'],'index.php?admin_fileftpmanage-default');
			$newname = $newname ? $newname : $_FILES['uploadfile']['name'] ;
			$isftp=0;
			if ($this->setting['FTP_ENABLE']==1)
				$isftp=1;
			$_ENV['upload']->upload('uploadfile', $savepath, $newname, $upfile_size, $overfile,$isftp);
			if($_ENV['upload']->up($isftp,$rootpath))
				$this->message($this->view->lang['file']." <a href=\"".$savepath.$upload->savename."\" >{$upload->savename}</a> ".$this->view->lang['upload_success'],"index.php?admin_fileftpmanage-default");
			else
				$this->message($this->view->lang['cannot_upload_error'].$_ENV['upload']->error(),'index.php?admin_fileftpmanage-default');
		}
	}
	//权限设置
	function dochmod(){
		if(isset($this->get[2]) && isset($this->get[3]) && isset($this->get[4]) && isset($this->get[5])){
			$thispath = $this->get[2].$this->get[3];
			$thispath = str_replace('*','.',$thispath);
			$isdir=$this->get[4];
			$currentperm=$this->get[5];
		}
		if(isset($this->post['dosubmit'])){
			$chmodstr = $this->post['chmodstr'];
			if (!isset($chmodstr) || empty($chmodstr)) 
				$this->message($this->view->lang['nothing'],'index.php?admin_fileftpmanage-default');
			$thispath = $this->post['thispath'];
			$currentperm = $this->post['currentperm'];
			$isdir = $this->post['isdir'];
			$isall = $this->post['isall'];
			//链接ftp
			if ($_ENV['ftp']->ftp($this->setting['FTP_HOST'])) {
				if ($_ENV['ftp']->login($this->setting['FTP_USER'],$this->setting['FTP_PW'])) {
					$thispath=urldecode($thispath);
					if($isall==1){//应用到子文件夹
						if($_ENV['ftp']->ftp_filechmod($chmodstr,$thispath))
							$this->message($this->view->lang['operation_success'],'index.php?admin_fileftpmanage-default');
					}else{
						if($_ENV['ftp']->chmod($chmodstr,$thispath))
							$this->message($this->view->lang['operation_success'],'index.php?admin_fileftpmanage-default');
					}
				} else {
					$error="login failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
				}
				$_ENV['ftp']->disconnect();
			}else{
				$error="connection failed: ".$_ENV['ftp']->error_no.$_ENV['ftp']->error_msg;
			}
			if($error!="")
				$this->message($error,'index.php?admin_fileftpmanage-default');
		}
		$this->view->assign("thispath", $thispath);
		$this->view->assign("currentperm", $currentperm);
		$this->view->assign("isdir", $isdir);
		$this->view->display('admin_fileftpchmod');
	}
}
?>