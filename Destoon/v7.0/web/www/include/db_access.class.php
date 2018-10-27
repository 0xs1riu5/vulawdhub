<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class db_access {
	var $querynum = 0;
	var $conn;
	var $insertid = 0;
	var $cursor = 0;

	function connect($dbhost, $dbuser = '', $dbpw = '', $dbname = '', $pconnect = 0) {
		$this->conn = new com('adodb.connection') or exit('Cannot start ADO');
		$this->conn->open("DRIVER={Microsoft Access Driver (*.mdb)};dbq=$dbhost;uid=$dbuser;pwd=$dbpw");
		if($this->conn->state == 0) {
			$this->conn->open("Provider=Microsoft.Jet.OLEDB.4.0; Data Source=$dbhost");
			if($this->conn->state == 0)	exit('Can not connect to Access Database !');
		}
		define('NUM', 1);
		define('ASSOC', 2);
		define('BOTH', 3);
	}

	function query($sql, $type = '', $expires = 3600, $dbname = '') {
		$this->querynum++;
		$sql = trim($sql);
		if(preg_match("/^(select.*)limit ([0-9]+)(,([0-9]+))?$/i", $sql, $matches)) {
			$sql = $matches[1];
			$offset = $matches[2];
			$pagesize = $matches[4];
			$query = $this->conn->Execute($sql);
			return $this->limit($query, $offset, $pagesize);
		} else {
			return $this->conn->Execute($sql);
		}
	}

	function get_one($query) {
		$this->querynum++;
	    $rs = $this->conn->Execute($query);
		$r = $this->fetch_array($rs);
		$this->free_result($rs);
		return $r;
	}

	function fetch_array($rs, $result_type = 3) {
		if(is_array($rs)) {
			return $this->cursor < count($rs) ? $rs[$this->cursor++] : FALSE;
		} else {
			if($rs->EOF) return FALSE;
			$array = array();
			for($i = 0; $i < $this->num_fields($rs); $i++) {
				$fielddata = $rs->Fields[$i]->Value;
			    if($result_type == NUM || $result_type == BOTH) $array[$i] = $fielddata;
			    if($result_type == ASSOC || $result_type == BOTH) $array[$rs->Fields[$i]->Name] = $fielddata;
			}
			$rs->MoveNext();
			return $array;
		}
	}

	function affected_rows($rs) {
		return count($rs);
	}

	function num_rows($rs) {
	    return is_array($rs) ? count($rs) : $rs->recordcount;
	}

	function num_fields($rs) {
	    return $rs->Fields->Count;
	}

	function fetch_assoc($rs) {
	    return $this->fetch_array($rs, ASSOC);
	}

	function fetch_row($rs) {
	    return $this->fetch_array($rs, NUM);
	}

	function free_result($rs) {
	    if(is_resource($rs)) $rs->close();
	}

	function error() {
	    return $this->conn->Errors[$this->conn->Errors->Count-1]->Number;
	}

	function errormsg() {
	    return $this->conn->Errors[$this->conn->Errors->Count-1]->Description;
	}

	function close() {
	    $this->conn->close();
	}

	function limit($rs, $offset, $pagesize = 0) {
		if($pagesize > 0) {
			$rs->Move($offset);
		} else {
			$pagesize = $offset;
		}
		$info = array();
		for($i = 0; $i < $pagesize; $i++) {
			$r = $this->fetch_array($rs);
			if(!$r) break;
			$info[] = $r;
		}
		$this->free_result($rs);
		$this->cursor = 0;
		return $info;
	}
}
?>