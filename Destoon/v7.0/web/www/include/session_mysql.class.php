<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class dsession {
	var $table;

    function __construct() {
		$this->table = DT_PRE.'session';
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
		return true;
    }

    function close() {
		$this->gc();
    } 

    function read($sid) {
		$r = DB::get_one("SELECT data FROM {$this->table} WHERE sessionid='$sid'");
		return $r ? $r['data'] : '';
    } 

    function write($sid, $data) {
		$data = addslashes($data);
        DB::query("REPLACE INTO {$this->table} (sessionid,data,lastvisit) VALUES('$sid', '$data', '".DT_TIME."')");
    } 

    function destroy($sid) { 
		DB::query("DELETE FROM {$this->table} WHERE sessionid='$sid'");
    } 

	function gc() {
		$expiretime = DT_TIME - 1800;
		DB::query("DELETE FROM {$this->table} WHERE lastvisit<$expiretime");
    }
}
?>