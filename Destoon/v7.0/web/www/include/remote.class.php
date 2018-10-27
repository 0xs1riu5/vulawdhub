<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class remote {
    var $file;
    var $savename;
    var $savepath;
	var $saveto;
    var $overwrite = false;
    var $maxsize;
    var $ext;
    var $errmsg = errmsg;
	var $userid;
	var $image;
	var $uptime = 0;
	var $adduserid = true;

    function __construct($file, $savepath, $savename = '') {
		global $DT, $_userid;
		$this->file = strip_sql($file, 0);
		$this->userid = $_userid;
		$this->ext = file_ext($file);
		$this->maxsize = $DT['uploadsize'] ? $DT['uploadsize']*1024 : 2048*1024;
		$this->savepath = $savepath;
		$this->savename = $savename;
    }

    function remote($file, $savepath, $savename = '') {
		$this->__construct($file, $savepath, $savename);
    }

	function save() {
		include load('include.lang');
        if(!$this->is_allow()) return $this->_($L['upload_not_allow']);
        $this->set_savepath($this->savepath);
        $this->set_savename($this->savename);
		if(file_copy($this->file, DT_ROOT.'/'.$this->saveto)) {
			if(!@getimagesize(DT_ROOT.'/'.$this->saveto)) {
				file_del(DT_ROOT.'/'.$this->saveto);
				return $this->_($L['upload_bad']);
			}
			if($this->maxsize > 0 && filesize(DT_ROOT.'/'.$this->saveto) > $this->maxsize) {
				file_del(DT_ROOT.'/'.$this->saveto);
				return $this->_($L['upload_size_limit'].' ('.intval($this->maxsize/1024).'Kb)');
			}
			$this->image = 1;
			return true;
		} else {
			return $this->_($L['upload_failed']);
		}
	}

    function is_allow() {
		if($this->ext) {
			if(!in_array($this->ext, array('jpg', 'jpeg', 'gif', 'png', 'bmp'))) return false;
		} else {
			$this->ext = 'jpg';
		}
		return preg_match("/^(http|https)\:\/\/[A-Za-z0-9_\-\/\.\#\&\?\;\,\=\%\:]{10,}$/", $this->file);
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
			$this->uptime = DT_TIME;
            $name = date('His', $this->uptime).rand(10, 99);
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