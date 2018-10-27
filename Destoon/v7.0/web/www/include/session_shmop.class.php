<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dsession {
	var $shmop_key;
	var $shmop_id;

    function __construct() {
		if(DT_DOMAIN) @ini_set('session.cookie_domain', '.'.DT_DOMAIN);
    	session_set_save_handler(array(&$this,'open'), array(&$this,'close'), array(&$this,'read'), array(&$this,'write'), array(&$this,'destroy'), array(&$this,'gc'));
		session_cache_limiter('private, must-revalidate');
		session_start();
		header("cache-control: private");
    }

    function dsession() {
		$this->__construct();
    }

	function open($path, $name) {
		$this->shmop_key = ftok(__FILE__);
		return true;
	}

	function close() {
		return shmop_close($this->shmop_id);
	}

	function read($sid) {
		$this->shmop_id = shmop_open($this->shmop_key, 'w', 0644, 0);
        return $this->shmop_id ? shmop_read($this->shmop_id, 0, shmop_size($this->shmop_id)) : '';
	}

	function write($sid, $data) {
		$this->shmop_id = shmop_open($this->shmop_key, 'c', 0644, strlen($data));
		return shmop_write($this->shmop_id, $data, 0);
	}

	function destroy($sid) {
	    return shmop_delete($this->shmop_id);
	}

	function gc() {
	    return true;
	}
}
?>