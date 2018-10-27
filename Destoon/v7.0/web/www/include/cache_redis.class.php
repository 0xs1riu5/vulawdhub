<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dcache {
	var $pre;
	var $obj;

    function __construct() {
		$this->obj = new Redis;
		include DT_ROOT.'/file/config/redis.inc.php';
		$num = count($RedisServer);
		$key = $num == 1 ? 0 : abs(crc32(DT_IP))%$num;
		$this->obj->connect($RedisServer[$key]['host'], $RedisServer[$key]['port']);
    }

    function dcache() {
		$this->__construct();
    }

	function get($key) {
        $val = $this->obj->get($this->pre.$key);
		if(substr($val, 0, 2) == 'a:') {
			$arr = unserialize($val);
			if(is_array($arr)) return $arr;
		}
		return $val;
    }

    function set($key, $val, $ttl = 600) {
		if(is_array($val)) $val = serialize($val);
		return $ttl ? $this->obj->setex($this->pre.$key, $ttl, $val) : $this->obj->set($this->pre.$key, $val);
    }

    function rm($key) {
		return $this->obj->delete($this->pre.$key);
    }

    function clear() {
        return $this->obj->flushAll();
    }

	function expire() {
		return true;
	}
}
?>