<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class dirmodel {

	var $db;
	var $base;
	var $error = '';
	var $conn_id;
	function dirmodel(&$base) {
		$this->base = $base;
		$base->load('ftp');
		$this->db = $base->db;
	}
	function dir_create($path, $mode = 0777)
	{
		if(is_dir($path)) return TRUE;
		$ftp_enable = 0;
		if($this->setting['FTP_ENABLE'] && extension_loaded('ftp'))
		{
			$_ENV['ftp']->ftp($this->setting['FTP_HOST'],$this->setting['FTP_PORT'], $this->setting['FTP_USER'], $this->setting['FTP_PW'], $this->setting['FTP_PATH']);
			if($_ENV['ftp']->error) return false;
			$ftp_enable = 1;
		}
		$path = $this->dir_path($path);
		$temp = explode('/', $path);
		$cur_dir = '';
		$max = count($temp) - 1;
		for($i=0; $i<$max; $i++)
		{
			$cur_dir .= $temp[$i].'/';
			if(is_dir($cur_dir)) continue;
			if(!@mkdir($cur_dir, 0777) && $ftp_enable)
			{
				$dir = str_replace(HDWIKI_ROOT, '', $cur_dir);
				$ftp->mkdir($dir);
				$ftp->chmod($mode, $dir);
			}
			@chmod($cur_dir, 0777);
		}
		return is_dir($path);
	}
	function dir_delete($dir)
	{
		if(!is_dir($dir)) return FALSE;
		$systemdirs = array('', HDWIKI_ROOT.'\\control', HDWIKI_ROOT.'\\lang\\zh', HDWIKI_ROOT.'\\data', HDWIKI_ROOT.'\\model', HDWIKI_ROOT.'\\view', HDWIKI_ROOT.'\\install', HDWIKI_ROOT.'\\js', HDWIKI_ROOT.'lib', HDWIKI_ROOT.'\\plugins', HDWIKI_ROOT.'\\block', HDWIKI_ROOT.'\\api', HDWIKI_ROOT.'\\lang', HDWIKI_ROOT.'\\ss');
		if(substr($dir, 0, 1) == '.' || in_array($dir, $systemdirs)) exit("Cannot remove system dir $dir !");
		$dir=$dir."\\";
		$list = glob($dir.'*');
		foreach($list as $v){
			is_dir($v) ? $this->dir_delete($v) : @unlink($v);
		}
		@rmdir($dir);
	}
	function dir_path($path)
	{
		$path = str_replace('\\', '/', $path);
		if(substr($path, -1) != '/') $path = $path.'/';
		return $path;
	}
}
?>
