<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dcache {
	var $pre;
	var $shmop_key;
	var $shmop_id;

    function __construct() {
		//
    }

    function dcache() {
		$this->__construct();
    }

    function get($key) {
        $this->shmop_key = ftok($this->pre.$key);//Linux/Unix Only
        $this->shmop_id = shmop_open($this->shmop_key, 'c', 0644, 0);
        if($this->shmop_id === false) return false;
		$data = shmop_read($this->shmop_id, 0, shmop_size($this->shmop_id));
		shmop_close($this->shmop_id);
		return function_exists('gzuncompress') ? gzuncompress($data) : $data;
    }

    function set($key, $val, $ttl = 600) {
        if(function_exists('gzcompress')) $val = gzcompress($val, 3);
        $this->shmop_key = ftok($this->pre.$key);
        $this->shmop_id = shmop_open($this->shmop_key, 'c', 0644, strlen($val));
        $result = shmop_write($this->shmop_id, $val, 0);
		shmop_close($this->shmop_id);
		return $result;
    }

    function rm($key) {
        $this->shmop_key = ftok($this->pre.$key);
        $this->shmop_id = shmop_open($this->shmop_key, 'c', 0644, 0);
        $result = shmop_delete($this->shmop_id);
		shmop_close($this->shmop_id);
		return $result;
    }

    function clear() {
        return true;
    }

	function expire() {
		return true;
	}
}
?>