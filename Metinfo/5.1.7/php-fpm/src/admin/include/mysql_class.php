<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
class dbmysql {
	var $querynum = 0;
	var $link;
	function  dbconn($con_db_host,$con_db_id,$con_db_pass, $con_db_name = '',$db_charset='utf8',$pconnect = 0) {
		if($pconnect) {
			if(!$this->link = @mysql_pconnect($con_db_host,$con_db_id,$con_db_pass)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = @mysql_connect($con_db_host,$con_db_id,$con_db_pass, 1)) {
				$this->halt('Can not connect to MySQL server');
			}
		}
		if($this->version() > '4.1') {
			if($db_charset!='latin1') {
				@mysql_query("SET character_set_connection=$db_charset, character_set_results=$db_charset, character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') {
				@mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($con_db_name) {
			@mysql_select_db($con_db_name, $this->link);
		}

	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query,$result_type);
	}
	
	function update($table, $bind=array(),$where = '')
	{
	    $set = array();
	    foreach ($bind as $col => $val) {
	        $set[] = "$col = '$val'";
	        unset($set[$col]);
	    }
	    $sql = "UPDATE "
             . $table
             . ' SET ' . implode(',', $set)
             . (($where) ? " WHERE $where" : '');
        $this->query($sql);
	}
	
	
	function insert($table, $bind=array())
	{
	    $set = array();
	    foreach ($bind as $col => $val) {
	        $set[] = "`$col`";
	        $vals[] = "'$val'";
	    }
	   $sql = "INSERT INTO "
             . $table
             . ' (' . implode(', ', $set).') '
             . 'VALUES (' . implode(', ', $vals).')';
        $this->query($sql);
        return $this->insert_id();
	}
	
	/**
	* @param string sql
	* @return array
	*/
	function get_one($sql, $type = '')
	{
		$query = $this->query($sql, $type);
		$rs = $this->fetch_array($query);
		$this->free_result($query);
		return $rs ;
	}
	
	function get_all($sql, $type = '')
	{
		$query = $this->query($sql, $type);
		while($list = $this->fetch_array($query)){
			$rs[]=$list;
		}
		$this->free_result($query);
		return $rs ;
	}


	function query($sql, $type = '') {
	   $func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->close();
				global $config_db;
				$db_settings = parse_ini_file("$config_db");
	            @extract($db_settings);
				$this->dbconn($con_db_host,$con_db_id,$con_db_pass, $con_db_name = '',$pconnect);
				$this->query($sql, 'RETRY'.$type);
			} 
		}
		$this->querynum++;
		return $query;
	}
	
	function counter($table_name,$where_str="", $field_name="*")
	{
	    $where_str = trim($where_str);
	    if(strtolower(substr($where_str,0,5))!='where' && $where_str) $where_str = "WHERE ".$where_str;
	    $query = " SELECT COUNT($field_name) FROM $table_name $where_str ";
	    $result = $this->query($query);
	    $fetch_row = mysql_fetch_row($result);
	    return $fetch_row[0];
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}
	function list_fields($con_db_name,$table) {
		$fields=mysql_list_fields($con_db_name,$table,$this->link);
	    $columns=$this->num_fields($fields);
	    for ($i = 0; $i < $columns; $i++) {
	        $tables[]=mysql_field_name($fields, $i);
	    }
	    return $tables;
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '',$sql) {
	     $sqlerror = mysql_error();
		 $sqlerrno = mysql_errno();
		 $sqlerror = str_replace($dbhost,'dbhost',$sqlerror);
		 header('HTTP/1.1 500 Internal Server Error');
		 echo"<html><head><title>MetInfo</title><style type='text/css'>P,BODY{FONT-FAMILY:tahoma,arial,sans-serif;FONT-SIZE:10px;}A { TEXT-DECORATION: none;}a:hover{ text-decoration: underline;}TD { BORDER-RIGHT: 1px; BORDER-TOP: 0px; FONT-SIZE: 16pt; COLOR: #000000;}</style><body>\n\n";
		echo"<table style='TABLE-LAYOUT:fixed;WORD-WRAP: break-word'><tr><td>";
		echo"<br><br><b>The URL Is</b>:<br>http://$_SERVER[HTTP_HOST]$REQUEST_URI";
		echo"<br><br><b>MySQL Server Error</b>:<br>$sqlerror  ( $sqlerrno )";
		echo"<br><br><b>You Can Get Help In</b>:<br><a target=_blank href=http://www.MetInfo.cn/><b>http://www.MetInfo.cn</b></a>";
		echo"</td></tr></table>";
		exit;
	}
}
$met_mysql=$tablepre.'otherinfo';
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>