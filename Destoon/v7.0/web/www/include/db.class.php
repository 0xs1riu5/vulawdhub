<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
class DB {
	public static $db;
	
	public static function query($sql, $type = '', $ttl = 0) {
		return self::$db->query($sql, $type, $ttl);
	}

	public static function get_one($sql, $type = '', $ttl = 0) {
		return self::$db->get_one($sql, $type, $ttl);
	}

	public static function count($table, $condition = '', $ttl = 0) {
		return self::$db->count($table, $condition, $ttl);
	}

	public static function fetch_array($query, $result_type = DB_ASSOC) {
		return self::$db->fetch_array($query, $result_type);
	}

	public static function insert_id() {
		return self::$db->insert_id();
	}

	public static function version() {
		return self::$db->version();
	}
}
DB::$db = $db;
?>