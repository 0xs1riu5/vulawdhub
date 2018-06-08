<?php

	define("FTP_TIMEOUT",90);

	// FTP Statuscodes
	define("FTP_COMMAND_OK",200);
	define("FTP_FILE_ACTION_OK",250);
	define("FTP_FILE_TRANSFER_OK",226);
	define("FTP_COMMAND_NOT_IMPLEMENTED",502);
	define("FTP_FILE_STATUS",213);
	define("FTP_NAME_SYSTEM_TYPE",215);
	define("FTP_PASSIVE_MODE",227);
	define("FTP_PATHNAME",257);
	define("FTP_SERVICE_READY",220);
	define("FTP_USER_LOGGED_IN",230);
	define("FTP_PASSWORD_NEEDED",331);
	define("FTP_USER_NOT_LOGGED_IN",530);
	
	if (!defined("FTP_ASCII")) define("FTP_ASCII",0);
	if (!defined("FTP_BINARY")) define("FTP_BINARY",1);
	!defined('IN_HDWIKI') && exit('Access Denied');

class ftpmodel {

		var $passiveMode = TRUE;
		var $lastLines = array();
		var $lastLine = "";
		var $controlSocket = NULL;
		var $newResult = FALSE;
		var $lastResult = -1;
		var $pasvAddr = NULL; 
		
		var $error_no = NULL;
		var $error_msg = NULL;
		
		function ftpmodel(&$base) {
			$this->base = $base;
			$this->db = $base->db;
		}

		function ftp($host, $port=21, $timeout=FTP_TIMEOUT) { //Opens an FTP connection
			$this->_resetError();
			$err_no = 0;
			$err_msg = "";
			$this->controlSocket = @fsockopen($host, $port, $err_no, $err_msg, $timeout) or $this->_setError(-1,"fsockopen failed"); 
			if ($err_no<>0) $this->setError($err_no,$err_msg);

			if ($this->_isError()) return false;
			
			@socket_set_timeout($this->controlSocket,$timeout) or $this->_setError(-1,"socket_set_timeout failed");
			if ($this->_isError()) return false;
			
			$this->_waitForResult();
			if ($this->_isError()) return false;
			
			return $this->getLastResult() == FTP_SERVICE_READY;
		}
		
		function isConnected() {
			return $this->controlSocket != NULL;
		}
		
		function disconnect() {
			if (!$this->isConnected()) return;
			@fclose($this->controlSocket);
		}

		function close() { //Closes an FTP connection
			$this->disconnect();
		}
		
		function login($user, $pass) {  //Logs in to an FTP connection
			$this->_resetError();

			$this->_printCommand("USER $user");
			if ($this->_isError()) return false;

			$this->_waitForResult();
			if ($this->_isError()) return false;

			if ($this->getLastResult() == FTP_PASSWORD_NEEDED){
				$this->_printCommand("PASS $pass");
				if ($this->_isError()) return FALSE;

				$this->_waitForResult();
				if ($this->_isError()) return FALSE;
			}
			
			$result = $this->getLastResult() == FTP_USER_LOGGED_IN;
			return $result;
		}

		function cdup() { //Changes to the parent directory
			$this->_resetError();

			$this->_printCommand("CDUP");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return ($lr==FTP_FILE_ACTION_OK || $lr==FTP_COMMAND_OK);
		}
		
		function cwd($path) {
			$this->_resetError();

			$this->_printCommand("CWD $path");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return ($lr==FTP_FILE_ACTION_OK || $lr==FTP_COMMAND_OK);
		}

		function cd($path) {
			return $this->cwd($path);
		}

		function chdir($path) { //Changes directories on a FTP server
			return $this->cwd($path);
		}

		function chmod($mode,$filename) { //Set permissions on a file via FTP
			return $this->site("CHMOD $mode $filename");
		}



		function exec($cmd) { //Requests execution of a program on the FTP server
			return $this->site("EXEC $cmd");
		}

		function fget($fp,$remote,$mode=FTP_BINARY,$resumepos=0) { //Downloads a file from the FTP server and saves to an open file
			$this->_resetError();
			
			$type = "I";
			if ($mode==FTP_ASCII) $type = "A";
			
			$this->_printCommand("TYPE $type");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			
			$result = $this->_download("RETR $remote");
			if ($result) {
				fwrite($fp,$result);
			}
			return $result;
		}

		function fput($remote,$resource,$mode=FTP_BINARY,$startpos=0) { //Uploads from an open file to the FTP server
			$this->_resetError();
			
			$type = "I";
			if ($mode==FTP_ASCII) $type = "A";
			
			$this->_printCommand("TYPE $type");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			
			if ($startpos>0) fseek($resource,$startpos);
			$result = $this->_uploadResource("STOR $remote",$resource);
			return $result;
		}

		function get_option($option) { //Retrieves various runtime behaviours of the current FTP stream
			$this->_resetError();

			switch ($option) {
				case "FTP_TIMEOUT_SEC" : return FTP_TIMEOUT;
				case "PHP_FTP_OPT_AUTOSEEK" : return FALSE;
			}
			setError(-1,"Unknown option: $option");
			return false;
		}

		function get($locale,$remote,$mode=FTP_BINARY,$resumepos=0) { //Downloads a file from the FTP server
			if (!($fp = @fopen($locale,"wb"))) return FALSE;
			$result = $this->fget($fp,$remote,$mode,$resumepos);
			@fclose($fp);
			if (!$result) @unlink($locale);
			return $result;
		}
		function mdtm($name) { //Returns the last modified time of the given file
			$this->_resetError();

			$this->_printCommand("MDTM $name");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			if ($lr!=FTP_FILE_STATUS) return FALSE;
			$subject = trim(substr($this->lastLine,4));
			$lucifer = array();
			if (preg_match("/([0-9][0-9][0-9][0-9])([0-9][0-9])([0-9][0-9])([0-9][0-9])([0-9][0-9])([0-9][0-9])/",$subject,$lucifer)) return mktime($lucifer[4],$lucifer[5],$lucifer[6],$lucifer[2],$lucifer[3],$lucifer[1],0);
			return FALSE;
		}


		function nb_continue() { //Continues retrieving/sending a file (non-blocking)
			$this->_resetError();
			// todo
		}

		function nb_fget() { //Retrieves a file from the FTP server and writes it to an open file (non-blocking)
			$this->_resetError();
			// todo
		}

		function nb_fput() { //Stores a file from an open file to the FTP server (non-blocking)
			$this->_resetError();
			// todo
		}

		function nb_get() { //Retrieves a file from the FTP server and writes it to a local file (non-blocking)
			$this->_resetError();
			// todo
		}

		function nb_put() { //Stores a file on the FTP server (non-blocking)
			$this->_resetError();
			// todo
		}

		function nlist($remote_filespec="") { //Returns a list of files in the given directory
			$this->_resetError();
			$result = $this->_download(trim("NLST $remote_filespec"));
			return ($result !== FALSE) ? explode("\n",str_replace("\r","",trim($result))) : $result;
		}
		
		function pasv($pasv) { //Turns passive mode on or off
			if (!$pasv) {
				$this->_setError("Active (PORT) mode is not supported");
				return false;
			}
			return true;
		}

		function put($remote,$local,$mode=FTP_BINARY,$startpos=0) { //Uploads a file to the FTP server
			if (!($fp = @fopen($local,"rb"))) return FALSE;
			$result = $this->fput($remote,$fp,$mode,$startpos);
			@fclose($fp);
			return $result;
		}

		function pwd() { //Returns the current directory name
			$this->_resetError();

			$this->_printCommand("PWD");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			if ($lr!=FTP_PATHNAME) return FALSE;
			$subject = trim(substr($this->lastLine,4));
			$lucifer = array();
			if (preg_match("/\"(.*)\"/",$subject,$lucifer)) return $lucifer[1];
			return FALSE;
		}

		function quit() { //Alias of close
			$this->close();
		}

		function raw($cmd) { //Sends an arbitrary command to an FTP server
			$this->_resetError();

			$this->_printCommand($cmd);
			$this->_waitForResult();
			$this->getLastResult();
			return array($this->lastLine);
		}
		function rawlist($remote_filespec="") { //Returns a detailed list of files in the given directory
			$this->_resetError();
			$result = $this->_download(trim("LIST $remote_filespec"));
			return ($result !== FALSE) ? explode("\n",str_replace("\r","",trim($result))) : $result;
		}
		function size($name) { //Returns the size of the given file
			$this->_resetError();

			$this->_printCommand("SIZE $name");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return $lr==FTP_FILE_STATUS ? trim(substr($this->lastLine,4)) : FALSE;
		}

		function mkdir($name) { //Creates a directory
			$this->_resetError();

			$this->_printCommand("MKD $name");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return ($lr==FTP_PATHNAME || $lr==FTP_FILE_ACTION_OK || $lr==FTP_COMMAND_OK);
		}
		function delete($filename) { //Deletes a file on the FTP server
			$this->_resetError();

			$this->_printCommand("DELE $filename");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return ($lr==FTP_FILE_ACTION_OK || $lr==FTP_COMMAND_OK);
		}
		function rmdir($name) { //Removes a directory
			$this->_resetError();

			$this->_printCommand("RMD $name");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return ($lr==FTP_FILE_ACTION_OK || $lr==FTP_COMMAND_OK);
		}	
		function rename($from,$to) { //Renames a file on the FTP server
			$this->_resetError();

			$this->_printCommand("RNFR $from");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			$this->_printCommand("RNTO $to");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return ($lr==FTP_FILE_ACTION_OK || $lr==FTP_COMMAND_OK);
		}

		function set_option() { //Set miscellaneous runtime FTP options
			$this->_resetError();
			$this->_setError(-1,"set_option not supported");
			return false;
		}

		function site($cmd) { //Sends a SITE command to the server
			$this->_resetError();

			$this->_printCommand("SITE $cmd");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return true;
		}

		function ssl_connect() { //Opens an Secure SSL-FTP connection
			$this->_resetError();
			$this->_setError(-1,"ssl_connect not supported");
			return false;
		}

		function systype() { // Returns the system type identifier of the remote FTP server
			$this->_resetError();

			$this->_printCommand("SYST");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			return $lr==FTP_NAME_SYSTEM_TYPE ? trim(substr($this->lastLine,4)) : FALSE;
		}

		function getLastResult() {
			$this->newResult = FALSE;
			return $this->lastResult;
		}
		
		/* private */
		function _hasNewResult() {
			return $this->newResult;
		}
		
		/* private */
		function _waitForResult() {
			while(!$this->_hasNewResult() && $this->_readln()!==FALSE && !$this->_isError()) { /* noop  */ }
		}
		
		/* private */
		function _readln() {
			$line = fgets($this->controlSocket);
			if ($line === FALSE) {
				$this->_setError(-1,"fgets failed in _readln");
				return FALSE;
			}
			if (strlen($line)==0) return $line;

			$lucifer = array();
			if (preg_match("/^[0-9][0-9][0-9] /",$line,$lucifer)) {
				//its a resultline
				$this->lastResult = intval($lucifer[0]);
				$this->newResult = TRUE;
				if (substr($lucifer[0],0,1)=='5') {
					$this->_setError($this->lastResult,trim(substr($line,4)));
				}
			}
	
			$this->lastLine = trim($line);
			$this->lastLines[] = "< ".trim($line);
			return $line;
		}
		
		/* private */
		function _printCommand($line) {
			$this->lastLines[] = "> ".$line;
			fwrite($this->controlSocket,$line."\r\n");
			fflush($this->controlSocket);
		}
		
		/* private */
		function _pasv() {
			$this->_resetError();
			$this->_printCommand("PASV");
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if ($this->_isError()) return FALSE;
			if ($lr!=FTP_PASSIVE_MODE) return FALSE;
			$subject = trim(substr($this->lastLine,4));
			$lucifer = array();
			if (preg_match("/\\((\d{1,3}),(\d{1,3}),(\d{1,3}),(\d{1,3}),(\d{1,3}),(\d{1,3})\\)/",$subject,$lucifer)) {
				$this->pasvAddr=$lucifer;
				
				$host = sprintf("%d.%d.%d.%d",$lucifer[1],$lucifer[2],$lucifer[3],$lucifer[4]);
				$port = $lucifer[5]*256 + $lucifer[6];
				
				$err_no=0;
				$err_msg="";
				$passiveConnection = fsockopen($host,$port,$err_no,$err_msg, FTP_TIMEOUT);
				if ($err_no!=0) {
					$this->_setError($err_no,$err_msg);
					return FALSE;
				}

				return $passiveConnection;
			}
			return FALSE;
		}
		
		/* private */
		function _download($cmd) {
			if (!($passiveConnection = $this->_pasv())) return FALSE;
			$this->_printCommand($cmd);
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if (!$this->_isError()) {
				$result = "";
				while (!feof($passiveConnection)) {
					$result .= fgets($passiveConnection);
				}
				fclose($passiveConnection);
				$this->_waitForResult();
				$lr = $this->getLastResult();
				return ($lr==FTP_FILE_TRANSFER_OK) || ($lr==FTP_FILE_ACTION_OK) || ($lr==FTP_COMMAND_OK) ? $result : FALSE;
			} else {
				fclose($passiveConnection);
				return FALSE;
			}
		}

		/* upload */
		function _uploadResource($cmd,$resource) {
			if (!($passiveConnection = $this->_pasv())) return FALSE;
			$this->_printCommand($cmd);
			$this->_waitForResult();
			$lr = $this->getLastResult();
			if (!$this->_isError()) {
				$result = "";
				while (!feof($resource)) {
					$buf = fread($resource,1024);
					fwrite($passiveConnection,$buf);
				}
				fclose($passiveConnection);
				$this->_waitForResult();
				$lr = $this->getLastResult();
				return ($lr==FTP_FILE_TRANSFER_OK) || ($lr==FTP_FILE_ACTION_OK) || ($lr==FTP_COMMAND_OK) ? $result : FALSE;
			} else {
				fclose($passiveConnection);
				return FALSE;
			}
		}
		
		/* private */
		function _resetError() {
			$this->error_no = NULL;
			$this->error_msg = NULL;
		}

		/* private */
		function _setError($no,$msg) {
			if (is_array($this->error_no)) {
				$this->error_no[] = $no;
				$this->error_msg[] = $msg;
			} else if ($this->error_no!=NULL) {
				$this->error_no = array($this->error_no,$no);
				$this->error_msg = array($this->error_msg,$msg);
			} else {
				$this->error_no = $no;
				$this->error_msg = $msg;
			}
		}
		
		/* private */
		function _isError() {
			return ($this->error_no != NULL) && ($this->error_no !== 0);
		}
		/*
		 * 修改文件和子文件权限
		 * $newprem 参数权限，前不加0
		 * $path    要加权限的文件path
		 */
		function ftp_filechmod($newperm,$path){
			
		   $flag=false;
		   $ar_files = $this->nlist($path);
		   if (count($ar_files)){ // makes sure there are files
			   for ($i=0;$i<sizeof($ar_files);$i++){ // for each file
					$st_file = $ar_files[$i];
				   if($st_file == '.' || $st_file == '..') continue;
				   if ($this->size($path."/".$st_file)) {// check directory
					   $this->chmod($newperm,$path."/".$st_file);//the file chmod
				   }
				   else{
					   $this->chmod($newperm,$path."/".$st_file); 
					   $this->ftp_filechmod($newperm,$path."/".$st_file); // select directory and chmod
				   }
			   }
		   } 
		   // chmod directories
		   if($this->chmod($newperm,$path))
			   $flag=true;
		   return $flag;
		}
		/*
		 * 上传目录，上传文件
		 * $src_dir = "/from";
		 * $dst_dir = "/to";
		 * ftp_copy($src_dir, $dst_dir);
		 */
		function ftp_copy($src_dir, $dst_dir) {
			if ($handle = @opendir($src_dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						if (is_dir($src_dir."/".$file)) {
							if (!$this->chdir($dst_dir."/".$file))
								$this->mkdir($dst_dir."/".$file);
							$this->ftp_copy($src_dir."/".$file, $dst_dir."/".$file);
						}
						else{
							$upload = $this->put($dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY);
						}
					}
				}
			}else{
				$this->put($dst_dir, $src_dir, FTP_BINARY);
			}
		}
		/*
		 * 下载文件函数	
		 * $filename 下载的文件路径
		*/
		function php_ftp_download($filename) {
			$ftp_path = dirname($filename);
			$ftp_path = str_replace('\\',"/",$ftp_path);
			// 获取路径
			$select_file = basename($filename);
			// 获取文件名
			if($this->chdir($ftp_path)){
				// 进入指定路径
				$tmpfile = tempnam( getcwd()."/", "temp" );
				// 创建唯一的临时文件
				if($this->get($tmpfile, $select_file, FTP_BINARY)) {
					// 下载指定的文件到临时文件
					header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=" . $select_file);
					readfile($tmpfile);
					unlink($tmpfile );
					// 删除临时文件
					exit;
				}
				unlink($tmpfile);
			}
		}
		//重命名文件
		function ftp_renamef($old_file,$new_file)
		{
			if($this->chdir($new_file)){
				return "ishas";
			}else{
				if(@$this->rename( $old_file, $new_file))
					return "ok";
				else
					return "no";
			}
		}
		//删除目录及子目录
		function ftp_rmAll($dst_dir){
		   $flag=false;
		   $ar_files = $this->nlist($dst_dir);
		   $systype = $this->systype();
		   $is_windows = stristr($systype,"UNIX Type: L8")!==FALSE;
		   if (count($ar_files)>1){ // makes sure there are files
			   for ($i=0;$i<sizeof($ar_files);$i++){ // for each file
					$st_file = $ar_files[$i];
					if($st_file == '.' || $st_file == '..') continue;
					if($is_windows)
						$this_file = $dst_dir."/".$ar_files[$i];
					else
						$this_file = $ar_files[$i];
					if ($this->size($this_file)){ // check if it is a directory
							$this->delete($this_file);
					}else{
						if(!$this->rmdir($this_file))
							$this->ftp_rmAll($this_file);
					}
			   }
		   }else{
				$this->delete($dst_dir);
		   }
		   if($this->rmdir($dst_dir))
				$flag=true;
		   return $flag;
		} 
		//创建文件夹
		function tmkdir($directory)
		{
			if($this->chdir($directory)){
				return "ishas";
			}else{
				if($this->mkdir($directory))
					return "ok";
				else
					return "no";
			}
		}
		//获取目录下的子目录、文件和链接文件
		function get_files($path = '')
		{
			$array = $this->rawlist($path);
			if (is_array($array))
			foreach ($array as $folder)
			 {
				$struc = array();
				$current = preg_split("/[\s]+/",$folder,9);
				
				$struc['perms']    = $current[0];
				$struc['permsn']= $this->chmodnum($current[0]);
				$struc['number']= $current[1];
				$struc['owner']    = $current[2];
				$struc['group']    = $current[3];
				$struc['size']    = $this->get_size($current[4]);
				$struc['month']    = $current[5];
				$struc['day']    = $current[6];
				$struc['time']    = $current[7];
				$struc['name']    = str_replace('//','',$current[8]);
				if($current[7]==date('Y'))
					$struc['date']=date('Y-m-d',strtotime($current[6].$current[5].$current[7]));
				else
					$struc['date']=date('m-d',strtotime($current[6].$current[5]))." ".$current[7];
				//$struc['raw']    = $folder;
				if ($struc['name'] != '.' && $struc['name'] != '..' && $this->get_type($struc['perms']) == "folder")
					$folders[] = $struc;
				elseif ($struc['name'] != '.' && $struc['name'] != '..' && $this->get_type($struc['perms']) == "link")
					$links[] = $struc;
				elseif ($struc['name'] != '.' && $struc['name'] != '..')
					$files[] = $struc;
			 }
			$this->close();
			return array($folders,$links,$files);
		 }
		 //获取文件权限
		function chmodnum($mode) {
		   $realmode = "";
		   $legal =  array("","w","r","x","-");
		   $attarray = preg_split("//",$mode);
		   for($i=0;$i<count($attarray);$i++){
			   if($key = array_search($attarray[$i],$legal))
				   $realmode .= $legal[$key];
		   }
		   $mode = str_pad($realmode,9,'-');
		   $trans = array('-'=>'0','r'=>'4','w'=>'2','x'=>'1');
		   $mode = strtr($mode,$trans);
		   $newmode = '';
		   $newmode .= $mode[0]+$mode[1]+$mode[2];
		   $newmode .= $mode[3]+$mode[4]+$mode[5];
		   $newmode .= $mode[6]+$mode[7]+$mode[8];
		   return '0'.$newmode;
		}
		//获取文件大小
		function get_size($size)
		 {
			 if ($size < 1024)
				  return round($size,2).' Byte';
			 elseif ($size < (1024*1024))
				  return round(($size/1024),2).' MB';
			 elseif ($size < (1024*1024*1024))
				  return round((($size/1024)/1024),2).' GB';
			 elseif ($size < (1024*1024*1024*1024))
				  return round(((($size/1024)/1024)/1024),2).' TB';
		 }
		//获取文件类别d=目录文件夹 l=链接 其他为文件
		function get_type($perms)
		{
			if (substr($perms, 0, 1) == "d")
				return 'folder';
			elseif (substr($perms, 0, 1) == "l")
				return 'link';
			else
				return 'file';
		}
		function stripstr($str)
		{
			return str_replace(array('..', "\n", "\r"), array('', '', ''), $str);
		}
	}
?>