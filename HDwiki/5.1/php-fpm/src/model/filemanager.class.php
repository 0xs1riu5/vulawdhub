<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class filemanagermodel {

	var $db;
	var $base;

	function filemanagermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	function getRelativePath($mainpath, $relativepath)
	{
		$dir = "./";
		$mainpath_info           = array_filter(explode('/', $mainpath));
		$relativepath_info       = array_filter(explode('/', $relativepath));
		$relativepath_info_count = count($relativepath_info);
		for ($i=0; $i<$relativepath_info_count; $i++) 
		{
			if ($relativepath_info[$i] == '.' || $relativepath_info[$i] == '') continue;
			if ($relativepath_info[$i] == '..')
			{
				$mainpath_info_count = count($mainpath_info);
				unset($mainpath_info[$mainpath_info_count-1]);
				continue;
			}
			$mainpath_info[count($mainpath_info)+1] = $relativepath_info[$i];
		}
		return implode('\\', $mainpath_info);
	}

	function fileext($filename)
	{
		return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
	}
	function is_ie()
	{
		$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)) return false;
		if(strpos($useragent, 'msie ') !== false) return true;
		return false;
	}

	function file_down($filepath, $filename = '')
	{
		if(!$filename) $filename = basename($filepath);
		//if($this->is_ie()) $filename = rawurlencode($filename);
		$filetype = $this->fileext($filename);
		$filesize = sprintf("%u", filesize($filepath));
		if(ob_get_length() !== false) @ob_end_clean();
		header('Pragma: public');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding: binary');
		header('Content-Encoding: none');
		header('Content-type: '.$filetype);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-length: '.$filesize);
		readfile($filepath);
		exit;
	}
}
?>
