<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
define('DB_ASSOC', MYSQL_ASSOC);
class db_mysql {
	var $connid;
	var $pre;
	var $querynum = 0;
	var $ttl;
	var $cursor = 0;
	var $halt = 0;
	var $linked = 1;
	var $result = array();

	function connect($dbhost, $dbuser, $dbpass, $dbname, $dbttl, $dbcharset, $pconnect = 0) {
		$this->ttl = $dbttl;
		$func = $pconnect == 1 ? 'mysql_pconnect' : 'mysql_connect';
		if(!$this->connid = $func($dbhost, $dbuser, $dbpass)) {
			$this->linked = 0;
			$retry = 5;
			while($retry-- > 0) {
				if($this->connid = $func($dbhost, $dbuser, $dbpass)) {
					$this->linked = 1;
					break;
				}
			}
			if($this->linked == 0) {
				global $DT_BOT;
				if($DT_BOT) dhttp(503);
				if($this->halt) {
					exit(include template('mysql', 'message'));
				} else {
					$this->halt('Can not connect to MySQL server');
				}
			}
		}
		$version = $this->version();
		/* NOET: IN_ADMIN COMMENT MESSY */
		if($version > '4.1' && $dbcharset) mysql_query(IN_ADMIN ? "SET NAMES '".$dbcharset."'" : "SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $this->connid);
		if($version > '5.0') mysql_query("SET sql_mode=''", $this->connid);
		if($dbname && !mysql_select_db($dbname, $this->connid)) $this->halt('Cannot use database '.$dbname);
		return $this->connid;
	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->connid);
	}

	function query($sql, $type = '', $ttl = 0) {
		$select = strtoupper(substr($sql, 0, 7)) == 'SELECT ' ? 1 : 0;
		if($this->ttl > 0 && $type == 'CACHE' && $select) {
			$this->cursor = 0;
			$this->result = array();
			return $this->_query($sql, $ttl ? $ttl : $this->ttl);
		}
		$func = $type == 'UNBUFFERED' ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->connid))) $this->halt('MySQL Query Error', $sql);
		$this->querynum++;
		return $query;
	}

	function get_one($sql, $type = '', $ttl = 0) {
		$sql = str_replace(array('select ', ' limit '), array('SELECT ', ' LIMIT '), $sql);
		if(strpos($sql, 'SELECT ') !== false && strpos($sql, ' LIMIT ') === false) $sql .= ' LIMIT 0,1';
		$query = $this->query($sql, $type, $ttl);
		$r = $this->fetch_array($query);
		$this->free_result($query);
		return $r;
	}
	
	function count($table, $condition = '', $ttl = 0) {
		$sql = 'SELECT COUNT(*) AS amount FROM '.$table;
		if($condition) $sql .= ' WHERE '.$condition;
		$r = $this->get_one($sql, $ttl ? 'CACHE' : '', $ttl);
		return $r ? $r['amount'] : 0;
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return is_array($query) ? $this->_fetch_array($query) : mysql_fetch_array($query, $result_type);
	}

	function affected_rows() {
		return mysql_affected_rows($this->connid);
	}

	function num_rows($query) {
		return mysql_num_rows($query);
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function result($query, $row) {
		return @mysql_result($query, $row);
	}

	function free_result($query) {
		if(is_resource($query) && get_resource_type($query) === 'mysql result') {
			return @mysql_free_result($query);
		}
	}

	function insert_id() {
		return mysql_insert_id($this->connid);
	}

	function fetch_row($query) {
		return mysql_fetch_row($query);
	}

	function version() {
		return mysql_get_server_info($this->connid);
	}

	function close() {
		return mysql_close($this->connid);
	}

	function error() {
		return @mysql_error($this->connid);
	}

	function errno() {
		return intval($this->error());
	}

	function halt($message = '', $sql = '')	{
		if($message && DT_DEBUG) log_write("\t\t<query>".$sql."</query>\n\t\t<errno>".$this->errno()."</errno>\n\t\t<error>".$this->error()."</error>\n\t\t<errmsg>".$message."</errmsg>\n", 'sql');
		if($this->halt) message('MySQL Query:'.str_replace($this->pre, '[pre]', $sql).' <br/> MySQL Error:'.str_replace($this->pre, '[pre]', $this->error()).' MySQL Errno:'.$this->errno().' <br/>Message:'.$message);
	}

	function _query($sql, $ttl) {
		global $dc;
		$cid = md5($sql);
		$this->result = $dc->get($cid);
		if(!is_array($this->result)) {
			$tmp = array(); 
			$result = $this->query($sql, '', '');
			while($r = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$tmp[] = $r; 
			}
			$this->result = $tmp;
			$this->free_result($result);
			$dc->set($cid, $tmp, $ttl);
		}
		return $this->result;
	}

	function _fetch_array($query = array()) {
		if($query) $this->result = $query; 
		if(isset($this->result[$this->cursor])) {
			return $this->result[$this->cursor++];
		} else {
			$this->cursor = 0;
			return array();
		}
	}
}
?>