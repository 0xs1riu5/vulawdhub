<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load('filemanager');
		$this->load('dir');
		$this->load('upload');
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->safe = $this->cache->getcache('safe');
	}

	function dodefault(){
		$rootpath = HDWIKI_ROOT;
		if(isset($this->post['newchangedir']) && !empty($this->post['newchangedir']))
		{
			$newchangedir=$this->post['newchangedir'];
			if(!is_dir($newchangedir))
				$this->message($this->view->lang['file_not_exist'],"index.php?admin_filemanager-default");
			$newchangedir= $_ENV['dir']->dir_path($newchangedir);
			if($newchangedir[0] == '/'|| $newchangedir[0] == '.'){
				$dir = $newchangedir;
			}else if(strlen($newchangedir)<strlen($rootpath)){
				$newchangedir = './';
				echo "<font color=red>".$this->view->lang['illegal_directory']."</font>";
			}
			if(strpos($newchangedir,$rootpath)!= -1)
				$newchangedir = str_replace($rootpath,'./',$newchangedir);
			$dir = $newchangedir;
		}
		if(isset($this->get[2]) && !empty($this->get[2])){
			$dir=$this->get[2];
			$dir=str_replace('*', '.',$dir);
		}
		if (!isset($dir) or empty($dir)){
			$dir = "./";
			$currentdir = $_ENV['filemanager']->getRelativePath($rootpath, $dir);
		}else{
			$currentdir = $_ENV['filemanager']->getRelativePath($rootpath, $dir);
		}
		if(strlen($currentdir) < strlen($rootpath)){
			$currentdir = $rootpath;
			$dir = './';
		} 
		$dir = urldecode($dir);
		//判断文件目录是否可写入
		$writeable = is_writable($currentdir) ? $this->view->lang['writeable'] : $this->view->lang['cannot_write'];
		$dirhandle = @opendir($dir);
		$dirnum = '0';
		$dirs = array();
		while ($f = @readdir($dirhandle)) {
			$fpath = "$dir/$f";
			$a = @is_dir($fpath);
			$r = array();
			if($a=="1"){
				if($f!=".." && $f!="."){
						if (filectime($fpath) < filemtime($fpath)){
							$createtime = date("Y-m-d H:i:s",filectime($fpath));
							$modifytime = "<font color=\"red\">".date("Y-m-d H:i:s",filemtime($fpath))."</font>";
						}else{
							$createtime = date("Y-m-d H:i:s",@filectime($fpath));
							$modifytime = date("Y-m-d H:i:s",@filemtime($fpath));
						}
						$dirperm = substr(base_convert(fileperms($fpath),10,8),-4);
						$r['createtime'] = $createtime;
						$r['modifytime'] = $modifytime;
						$r['dirperm'] = $dirperm;
						$r['size'] = '<??>';
						$r['name'] = $f;
						$r['dir'] = $dir;
						$dirs[] = $r;
						$dirnum++;
					}else {
						if($f=="..") {
						}
					}
			}
		}
		@closedir($dirhandle);
		$dirhandle = @opendir($dir);
		$fnum = 0;
		$files = array();
		$basedir = str_replace($rootpath,'',$currentdir).'/';
		if($basedir['0'] == '/' && strlen($basedir) >1)
			$basedir = substr($basedir,1);
		else if($basedir== '/')
			$basedir = '';
		while ($f = @readdir($dirhandle)) {
			$fpath= "$dir/$f";
			$a = @is_dir($fpath);
			$r = array();
			if($a=="0"){
				$size = @filesize($fpath);
				$size = $size/1024 ;
				$size = number_format($size, 3);
				if (@filectime($fpath) < @filemtime($fpath)) {
					$createtime = date("Y-m-d H:i:s",filectime($fpath));
					$modifytime = "<font color=\"red\">".date("Y-m-d H:i:s",filemtime($fpath))."</font>";

				} else {
					$createtime = date("Y-m-d H:i:s",@filectime($fpath));
					$modifytime = date("Y-m-d H:i:s",@filemtime($fpath));
				}
				$fileperm = substr(base_convert(@fileperms($fpath),10,8),-4);

				$r['createtime'] = $createtime;
				$r['modifytime'] = $modifytime;
				$r['fileperm'] = $fileperm;
				$r['size'] = $size;
				$r['name'] = $f;
				$r['dir'] = $dir;


				$r['filepath'] = $basedir.$f;
				$r['fileext'] = $_ENV['filemanager']->fileext($fpath);
				//if(!key_exists($r['fileext'],$filetype)) $r['fileext'] = 'other';
				$nopicflash = isset($LANG['not_picture_flash']) ? $LANG['not_picture_flash'] : false;
				$r['preview'] = in_array($r['fileext'],array('gif','jpg','jpeg','png','bmp')) ? "<img src=".$r['filepath']." border=0>" : "&nbsp;".$nopicflash."&nbsp;";
				$files[] = $r;
				$fnum++;
			}
		}
		@closedir($dirhandle);
		$currentdir= $_ENV['dir']->dir_path($currentdir);
		$rootpath= $_ENV['dir']->dir_path($rootpath);
		$this->view->assign("dir", $dir);
		$this->view->assign("dirs", $dirs);
		$this->view->assign("files", $files);
		$this->view->assign("dirnum", $dirnum);
		$this->view->assign("fnum", $fnum);
		$this->view->assign("rootpath", urldecode($rootpath));
		$this->view->assign("writeable", $writeable);
		$this->view->assign("currentdir", urldecode($currentdir));
		$this->view->display('admin_filemanager');
	}
	//创建文件夹
	function donewdir(){
		$dir=$this->post['dir'];
		$currentdir=$this->post['currentdir']."/";
		$newdir=$this->post['newdir'];
		if (!isset($newdir) || empty($newdir)) 
			$this->message($this->view->lang['input_new_file'],'index.php?admin_filemanager-default');
		$mkdir = $currentdir.$newdir;
		if (file_exists($mkdir)){
			$this->message($this->view->lang['directory_existed_change_name'],"index.php?admin_filemanager-default-".urlencode($dir));
		}else{
			if(mkdir($mkdir,0777)){
				@chmod($mkdir,0777);
				$this->message($this->view->lang['dir_create_success'],"index.php?admin_filemanager-default-".urlencode($dir));
			}else {
				$this->message($this->view->lang['dir_create_fail'],"index.php?admin_filemanager-default-".urlencode($dir));
			}
		}
	}
	//删除文件或文件夹
	function dodelete(){
		if(isset($this->post['currentdir']) && isset($this->post['fname'])){
			$fname=string::hstripslashes($this->post['currentdir']).$this->post['fname'];
		}
		if(!file_exists($fname)){
			echo $this->view->lang['file_not_exist'];
		}else{
			if($this->post['isdir']== '0'){
				@unlink($fname);
				echo basename($fname).$this->view->lang['file_delete_success'];
			}else if($this->post['isdir'] == '1'){
				$str=$_ENV['dir']->dir_delete($fname);
				echo basename($fname).":".$this->view->lang['dir_delete_success'];
			}
		}
	}
	function dodown(){
		$fname=string::hstripslashes(str_replace('*', '.',$this->get[2]));
		$_ENV['filemanager']->file_down($fname);
	}
	//改名
	function dorename(){
		$isdir=$this->get[3];
		$fname=string::hstripslashes(str_replace('*', '.',$this->get[2]));
		$dir=str_replace('*', '.',$this->get[4]);
		if($this->post['dosubmit']){
			if ($this->post['newname']){
				$newname=$this->post['newname'];
				if(!isset($newname) || empty($newname)) 
					$this->message($this->view->lang['nothing'],'index.php?admin_filemanager-default');
				$fname=$this->post['fname'];
				$isdirs=$this->post['isdir'];
				if($isdirs == '0'){
					$newpath = dirname($fname)."/".$newname;
					if (file_exists($newpath)){
						$this->message($this->view->lang['dir_create_fail'],$newpath);
					}else{
						rename($fname,$newpath) ? $this->message(basename($fname).$this->view->lang['success_change_name'].$newname,"index.php?admin_filemanager-default") : $this->message($this->view->lang['file_change_name_fail'],"index.php?admin_filemanager-default");
					}
				}else if($isdirs == '1'){
					$fname = trim($fname);
					$newpath = ($fname[strlen($fname)-1] == '/' || $fname[strlen($fname)-1] == "/") ? substr($fname,0,-1) : $fname;
					$newpath = substr($newpath,0,strrpos($newpath, '/')+1);
					$newpath = string::hstripslashes($newpath.$newname);
					if (file_exists($newpath)){
						$this->message($this->view->lang['exist_refill'],"index.php?admin_filemanager-default");
					}else{
						rename($fname,$newpath) ? $this->message(basename($fname).$this->view->lang['success_change_name'].$newname,"index.php?admin_filemanager-default") : $this->message($this->view->lang['dir_change_name_fail'],"index.php?admin_filemanager-default");
					}
				}
			}else{
				$this->message($this->view->lang['please_input_new_directory_file_name'],"index.php?admin_filemanager-default");
			}
		}
		$this->view->assign("isdir", $isdir);
		$fname=urldecode($fname);
		$this->view->assign("fname", $fname);
		$this->view->assign("dir", $dir);
		$this->view->display('admin_filerename');
	}
	//修改权限
	function dochmod(){
		$fname=string::hstripslashes(str_replace('*', '.',$this->get[2]));
		$isdir=$this->get[3];
		$dir=str_replace('*', '.',$this->get[4]);
		if ($this->post['dosubmit']){
			$chmodstr = $this->post['chmodstr'];
			if(!isset($chmodstr) || empty($chmodstr)) 
				$this->message($this->view->lang['nothing'],'index.php?admin_filemanager-default');
			$dir=$this->post['dir'];
			$fname=string::hstripslashes($this->post['fname']);
			$fileperm = base_convert($chmodstr,8,10);
			@chmod($fname,$fileperm) ? $this->message($this->view->lang['chmod_change_success'],"index.php?admin_filemanager-default-".urlencode($dir)) : $this->message($this->view->lang['chmod_change_fail'],"index.php?admin_filemanager-default");
			
		}
		$currentperm = substr(base_convert(@fileperms($fname),10,8),-4);
		$fname=urldecode($fname);
		$this->view->assign("isdir", $isdir);
		$this->view->assign("fname", $fname);
		$this->view->assign("dir", $dir);
		$this->view->assign("currentperm", $currentperm);
		$this->view->display('admin_filechmod');
	}
	//新建文件
	function donewfile(){
		$newfile = $this->post['newfile'];
		$currentdir = $this->post['currentdir'];
		if (!isset($newfile) || empty($newfile)) $this->message($this->view->lang['nothing'],'index.php?admin_filemanager-default');
		$dir = $this->post['dir'];
		$mkfile = string::hstripslashes($currentdir)."/".$newfile;
		if (file_exists($mkfile)){
			$this->message($this->view->lang['file_exist_change_name'],"index.php?admin_filemanager-default-".urlencode($dir));
		}else{
			if(file_put_contents($mkfile,' ')){
				@chmod($mkfile,0777);
				$this->message($newfile.$this->view->lang['file_create_success_continue_edit'],"index.php?admin_filemanager-default-".urlencode($dir));
			}
			else $this->message($newfile.$this->view->lang['file_create_fail'],"index.php?admin_filemanager-default-".urlencode($dir));
		}
	}
	//上传文件
	function douploadfile(){
		if($this->post['dosubmit'])
		{
			$currentdir= string::hstripslashes($this->post['currentdir']);
			$dir=$this->post['dir'];
			$overfile=$this->post['overfile'];
			$newname=$this->post['newname'];
			$upfile_size = '4000000';
			$savepath = $currentdir."/";
			$fileArr = array(
					'file'=>$_FILES['uploadfile']['tmp_name'],
					'name'=>$_FILES['uploadfile']['name'],
					'size'=>$_FILES['uploadfile']['size'],
					'type'=>$_FILES['uploadfile']['type'],
					'error'=>$_FILES['uploadfile']['error']
			);
			if(file_exists($savepath.$fileArr['name']) && !isset($newname))
				$this->message($this->view->lang['find_same_file'],'index.php?admin_filemanager-default');
			$newname = $newname ? $newname : $_FILES['uploadfile']['name'] ;
			$_ENV['upload']->upload('uploadfile', $savepath, $newname, $upfile_size, $overfile);
			$isftp=0;
			if($_ENV['upload']->up($isftp))
				$this->message($this->view->lang['file']." <a href=\"".$savepath.$upload->savename."\" >{$upload->savename}</a> ".$this->view->lang['upload_success'],"index.php?admin_filemanager-default-".urlencode($dir));
			else
				$this->message($this->view->lang['cannot_upload_error'].$_ENV['upload']->error(),'index.php?admin_filemanager-default');
		}
	}
	//编辑文件内容
	function doedit(){
		$fname=string::hstripslashes(str_replace('*', '.',$this->get[2]));
		$dir=str_replace('*', '.',$this->get[3]);
		if($this->post['fname']!="")
			$fname = $this->post['fname'];
		if($this->post['dir']!="")
			$dir = $this->post['dir'];
		if(!is_writeable($fname)) $this->message($this->view->lang['file'].' '.$fname.' '.$this->view->lang['cannot_write_edit_online']);
		if($this->post['dosubmit']){
			$content = $this->post['content'];
			if($content) $content = str_replace(array('\n', '\r'), array(chr(10), chr(13)), $content);
			file_put_contents($fname, stripslashes($content));
	        $this->message($this->view->lang['operation_success'], "index.php?admin_filemanager-default-".urlencode($dir));
		}else{
			$content = file_get_contents($fname);
			$filemtime = date("Y-m-d H:i:s",filemtime($fname));
			$this->view->assign("fname", $fname);
			$this->view->assign("dir", $dir);
			$this->view->assign("content", $content);
			$this->view->display('admin_fileedit');
		}
	}
}
?>