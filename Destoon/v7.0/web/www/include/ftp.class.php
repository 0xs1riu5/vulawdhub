<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dftp {
	var $fp;
	var $root;
	var $connected = 0;

	function __construct($ftphost, $ftpuser, $ftppass, $ftpport = 21, $root = '/', $pasv = 0, $ssl = 0) {
		if($ssl && function_exists('ftp_ssl_connect')) {
			$this->fp = @ftp_ssl_connect($ftphost, $ftpport);
		} else if(function_exists('ftp_connect')) {
			$this->fp = @ftp_connect($ftphost, $ftpport);
		} else {
			return false;
		}
		$this->connected = @ftp_login($this->fp, $ftpuser, $ftppass);
		@ftp_pasv($this->fp, $pasv);
		$this->root = dir_path($root);
	}

	function dftp($ftphost, $ftpuser, $ftppass, $ftpport = 21, $root = '/', $pasv = 0, $ssl = 0) {
		$this->__construct($ftphost, $ftpuser, $ftppass, $ftpport, $root, $pasv, $ssl);
	}

	function dftp_chdir($dir = '') {
		return @ftp_chdir($this->fp, $this->root.$dir);
	}

	function dftp_chmod($path, $mode = 0777) {
		$path = $this->root.$path;
		return function_exists('ftp_chmod') ? @ftp_chmod($this->fp, $mode, $path) : @ftp_site($this->fp, "CHMOD $mode $path");
	}

	function dftp_mkdir($dir, $mode = 0777) {
		$temp = explode('/', $dir);
		$cur_dir = '';
		$max = count($temp);
		for($i = 0; $i < $max; $i++) {
			$cur_dir .= $temp[$i].'/';
			if($this->dftp_chdir($cur_dir)) continue;
			@ftp_mkdir($this->fp, $this->root.$cur_dir);
			$this->dftp_chmod($cur_dir, $mode);
		}
		return $this->dftp_chdir($dir);
	}

	function dftp_rmdir($dir) {
		return @ftp_rmdir($this->fp, $this->root.$dir);
	}

	function dftp_delete($file) {
		return @ftp_delete($this->fp, $this->root.$file);
	}

	function dftp_put($local, $remote = '') {
		$remote or $remote = $local;
		$local = DT_ROOT.'/'.$local;
		$this->dftp_mkdir(dirname($remote));
		if(@ftp_put($this->fp, $this->root.$remote, $local, FTP_BINARY)) {
			$this->dftp_chmod($remote);
			return true;
		} else {
			return false;
		}
	}
}
?>