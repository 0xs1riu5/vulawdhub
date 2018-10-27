<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dcache {
	var $pre;

    function __construct() {
		//
    }

    function dcache() {
		$this->__construct();
    }

    function get($key) {
        return apc_fetch($this->pre.$key);
    }

    function set($key, $val, $ttl = 600) {
        return apc_store($this->pre.$key, $val, $ttl);
    }

    function rm($key) {
        return apc_delete($this->pre.$key);
    }

    function clear() {
        return apc_clear_cache();
    }

	function expire() {
		return true;
	}
}
?>