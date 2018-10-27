<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class upload {
    var $file;
    var $file_name;
    var $file_size;
    var $file_type;
	var $file_error;
    var $savename;
    var $savepath;
	var $saveto;
    var $fileformat = '';
    var $overwrite = false;
    var $maxsize;
    var $ext;
    var $errmsg = errmsg;
	var $userid;
	var $image;
	var $adduserid = true;

    function __construct($_file, $savepath, $savename = '', $fileformat = '') {
		global $DT, $_userid;
		foreach($_file as $file) {
			$this->file = $file['tmp_name'];
			$this->file_name = $file['name'];
			$this->file_size = $file['size'];
			$this->file_type = $file['type'];
			$this->file_error = $file['error'];
		}
		$this->userid = $_userid;
		$this->ext = file_ext($this->file_name);
		$this->fileformat = $fileformat ? $fileformat : $DT['uploadtype'];
		$this->maxsize = $DT['uploadsize'] ? $DT['uploadsize']*1024 : 2048*1024;
		$this->savepath = $savepath;
		$this->savename = $savename;
    }

    function upload($_file, $savepath, $savename = '', $fileformat = '') {
		$this->__construct($_file, $savepath, $savename, $fileformat);
    }

	function save() {
		include load('include.lang');
        if($this->file_error) return $this->_('Error(21)'.$L['upload_failed'].' ('.$L['upload_error_'.$this->file_error].')');
		if($this->maxsize > 0 && $this->file_size > $this->maxsize) return $this->_('Error(22)'.$L['upload_size_limit'].' ('.intval($this->maxsize/1024).'Kb)');
        if(!$this->is_allow()) return $this->_('Error(23)'.$L['upload_not_allow']);
        $this->set_savepath($this->savepath);
        $this->set_savename($this->savename);
        if(!is_writable(DT_ROOT.'/'.$this->savepath)) return $this->_('Error(24)'.$L['upload_unwritable']);
		if(!is_uploaded_file($this->file)) return $this->_('Error(25)'.$L['upload_failed']);
		if(!move_uploaded_file($this->file, DT_ROOT.'/'.$this->saveto)) return $this->_('Error(26)'.$L['upload_failed']);
		$this->image = $this->is_image();
		if(DT_CHMOD) @chmod(DT_ROOT.'/'.$this->saveto, DT_CHMOD);
        return true;
	}

    function is_allow() {
		if(!$this->fileformat) return false;
		if(!preg_match("/^(".$this->fileformat.")$/i", $this->ext)) return false;
		if(preg_match("/^(php|phtml|php3|php4|jsp|exe|dll|cer|shtml|shtm|asp|asa|aspx|asax|ashx|cgi|fcgi|pl)$/i", $this->ext)) return false;
		return true;
    }

    function is_image() {
        return preg_match("/^(jpg|jpeg|gif|png|bmp)$/i", $this->ext);
    }

    function set_savepath($savepath) {
		$savepath = str_replace("\\", "/", $savepath);
	    $savepath = substr($savepath, -1) == "/" ? $savepath : $savepath."/";
        $this->savepath = $savepath;
    }

    function set_savename($savename) {
        if($savename) {
            $this->savename = $this->adduserid ? str_replace('.'.$this->ext, $this->userid.'.'.$this->ext, $savename) : $savename;
        } else {
            $name = date('His', DT_TIME).mt_rand(10, 99);
            $this->savename = $this->adduserid ? $name.$this->userid.'.'.$this->ext : $name.'.'.$this->ext;
        }
		$this->saveto = $this->savepath.$this->savename;		
        if(!$this->overwrite && is_file(DT_ROOT.'/'.$this->saveto)) {
			$i = 1;
			while($i) {
				$saveto = str_replace('.'.$this->ext, '('.$i.').'.$this->ext, $this->saveto);
				if(is_file(DT_ROOT.'/'.$saveto)) {
					$i++;
					continue; 
				} else {
					$this->saveto = $saveto; 
					break;
				}
			}
        }
    }
	
	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>