<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：mysql.class.php
 * $author：lucks
 */
if(!defined('IN_BLUE'))
{
	die('Access Denied!');
}

class mysql {

	var $linkid=null;

    function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'gbk', $connect = 1) {
    	$this -> mysql($dbhost, $dbuser, $dbpw, $dbname, $dbcharset, $connect);
    }

    function mysql($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = 'gbk', $connect=1){
    	$func = empty($connect) ? 'mysql_pconnect' : 'mysql_connect';
    	if(!$this->linkid = @$func($dbhost, $dbuser, $dbpw, true)){
    		$this->dbshow('Can not connect to Mysql!');
    	} else {
    		if($this->dbversion() > '4.1'){
    			mysql_query( "SET NAMES gbk");
    			if($this->dbversion() > '5.0.1'){
    				mysql_query("SET sql_mode = ''",$this->linkid);
    			}
    		}
    	}
    	if($dbname){
    		if(mysql_select_db($dbname, $this->linkid)===false){
    			$this->dbshow("Can't select MySQL database($dbname)!");
    		}
    	}
    }

    function select_db($dbname){
    	return mysql_select_db($dbname, $this->linkid);
    }

    function query($sql){
    	if(!$query=@mysql_query($sql, $this->linkid)){
    		$this->dbshow("Query error:$sql");
    	}else{
    		return $query;
    	}
    }

    function getall($sql, $type=MYSQL_ASSOC){
    	$query = $this->query($sql);
    	while($row = mysql_fetch_array($query,$type)){
    		$rows[] = $row;
    	}
    	return $rows;
    }

    function getone($sql, $type=MYSQL_ASSOC){
    	$query = $this->query($sql,$this->linkid);
    	$row = mysql_fetch_array($query, $type);
    	return $row;
    }
    
    function getfirst($sql, $type=MYSQL_NUM) {
    	$query = $this->query($sql, $this->linkid);
    	$row = mysql_fetch_array($query, $type);
    	return $row[0];
    }

	function fetch_array($result,$type = MYSQL_ASSOC){
		return mysql_fetch_array($result);
	}

    function affected_rows(){
    	return mysql_affected_rows($this->linkid);
    }

    function num_rows(){
    	return mysql_num_rows($this->linkid);
    }

    function num_fields($result){
    	return mysql_num_fields($result);
    }

    function insert_id(){
    	return mysql_insert_id($this->linkid);
    }

    function free_result(){
    	return mysql_free_result($this->linkid);
    }

	function escape_string($string)
    {
        if (PHP_VERSION >= '4.3')
        {
            return mysql_real_escape_string($string, $this->linkid);
        }
        else
        {
            return mysql_escape_string($string, $this->linkid);
        }
    }

    function error(){
    	return mysql_error($this->linkid);
    }

    function errno(){
    	return mysql_errno($this->linkid);
    }

    function close(){
    	return mysql_close($this->linkid);
    }

    function dbversion(){
    	return mysql_get_server_info($this->linkid);
    }

    function dbshow($msg){
    	if($msg){
    		echo "Error：".$msg."<br><br>";
    	}else{
    		echo "Errno：".$this->errno()."<br>Error：".$this->error();
    	}
    	exit;
    }

}
?>