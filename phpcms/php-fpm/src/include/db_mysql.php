<?php
/*****************************************
 * CMS Version 0.1<br />
 * Copyright (C) 2009 , Inc.;<br />
 * All Rights Reserved.<br />
 * http://www.test.com<br />
 ****************************************/

/**
 * MYSQL 公用类库
 * @author cms
 * @version 1.0
 */
class db_mysql {
	/**
	 * $debug=true		打开数据库调试模式<br />
	 * $debug=false		关闭数据库调试模式<br />
	 *
	 * @var boolean
	 */
	public $debug = false;
	private $version = "";
	private $link_id = NULL;
	
	/**
	 * 构造函数
	 *
	 */
	function __construct() {
		$this->debug = false;
	}
	
	/**
	 * 连接数据库
	 *
	 * param  string  $dbhost		数据库主机名<br />
	 * param  string  $dbuser		数据库用户名<br />
	 * param  string  $dbpw			数据库密码<br />
	 * param  string  $dbname		数据库名称<br />
	 * param  string  $dbcharset	数据库字符集<br />
	 * param  string  $pconnect		持久链接,1为开启,0为关闭
	 * return bool
	 **/
	function connect($dbhost, $dbuser, $dbpwd, $dbname = '', $dbcharset = 'utf8', $pconnect = 0) {
		if ($pconnect) {
			if (! $this->link_id = mysql_pconnect ( $dbhost, $dbuser, $dbpwd )) {
				$this->ErrorMsg ();
			}
		} else {
			if (! $this->link_id = mysql_connect ( $dbhost, $dbuser, $dbpwd, 1 )) {
				$this->ErrorMsg ();
			}
		}
		$this->version = mysql_get_server_info ( $this->link_id );
		if ($this->getVersion () > '4.1') {
			if ($dbcharset) {
				mysql_query ( "SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link_id );
			}
			
			if ($this->getVersion () > '5.0.1') {
				mysql_query ( "SET sql_mode=''", $this->link_id );
			}
		}
		if (mysql_select_db ( $dbname, $this->link_id ) === false) {
			$this->ErrorMsg ();
		}
	}
	/**
	 * 发送一条 MySQL 查询
	 *
	 * @param string $sql
	 * @return bool
	 */
	function query($sql) {
		if ($this->debug) echo "<pre><hr>\n" . $sql . "\n<hr></pre>";//如果设置成调试模式，将打印SQL语句
		if (! ($query = mysql_query ( $sql, $this->link_id ))) {
			$this->ErrorMsg ();
			return false;
		} else {
			return $query;
		}
	}
	/**
	 * 插入数据
	 *
	 * @param string $table			表名<br />
	 * @param array $field_values	数据数组<br />
	 * @return id					最后插入ID
	 */
	function insert($table, $field_values) {
		$field_names = $this->getCol ( 'DESC ' . $table );
		$fields = array ();
		$values = array ();
		foreach ( $field_names as $value ) {
			if (array_key_exists ( $value, $field_values ) == true) {
				$fields [] = $value;
				$values [] = "'" . $field_values [$value] . "'";
			}
		}
		if (! empty ( $fields )) {
			$sql = 'INSERT INTO ' . $table . ' (' . implode ( ', ', $fields ) . ') VALUES (' . implode ( ', ', $values ) . ')';
		}
		if ($sql) {
			$this->query ( $sql );
			return $this->getInsertId ();
		} else {
			return false;
		}
	}
	/**
	 * 获取刚插入数据的ID
	 */
	function getInsertId() {
		return mysql_insert_id ( $this->link_id );
	}
	/**
	 * 更新数据
	 *
	 * @param string $table			要更新的表<br />
	 * @param array $field_values	要更新的数据，使用而为数据例:array('列表1'=>'数值1','列表2'=>'数值2')
	 * @param string $where 		更新条件
	 * @return bool	
	 */	
	function update($table, $field_values, $where = '') {
		$field_names = $this->getCol ( 'DESC ' . $table );
		$sets = array ();
		foreach ( $field_names as $value ) {
			if (array_key_exists ( $value, $field_values ) == true) {
				$sets [] = $value . " = '" . $field_values [$value] . "'";
			}
		}
		if (! empty ( $sets )) {
			$sql = 'UPDATE ' . $table . ' SET ' . implode ( ', ', $sets ) . ' WHERE ' . $where;
		}
		if ($sql) {
			return $this->query ( $sql );
		} else {
			return false;
		}
	}
	
	/**
	 * 删除数据
	 *
	 * @param string $table	要删除的表<br />
	 * @param string $where	删除条件，默认删除整个表
	 * @return bool
	 */	
	function delete($table,$where=''){
		if(empty($where)){
			$sql = 'DELETE FROM '.$table;
		}else{
			$sql = 'DELETE FROM '.$table.' WHERE '.$where;
		}
		if($this->query ( $sql )){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 获取数据列表
	 *
	 * @param string $sql	查询语句
	 * @return array		二维数组
	 */
	function getList($sql) {
		$res = $this->query ( $sql );
		if ($res !== false) {
			$arr = array ();
			$row = mysql_fetch_assoc ( $res );
			while ($row) {
				$arr [] = $row;
				$row = mysql_fetch_assoc ( $res );
			}
			return $arr;
		} else {
			return false;
		}
	}
	/**
	 * 获取数据列表
	 *
	 * @param string $sql	查询语句<br />
	 * @param int $numrows	返回个数<br />
	 * @param int $offset	指定偏移量
	 * @return array		二维数组
	 */
	function selectLimit($sql, $numrows=-1, $offset=-1) {
		if($offset==-1){
			$sql .= ' LIMIT ' . $numrows;
		}else{
			$sql .= ' LIMIT ' . $offset . ', ' . $numrows;
		}
		return $this->getList( $sql );
	}
	/**
	 * 获取一条记录
	 *
	 * @param string $sql	查询语句
	 * @return array		一维数组
	 */	
	function getOneRow($sql) {
		$res = $this->query ( $sql );
		if ($res !== false) {
			return mysql_fetch_assoc ( $res );
		} else {
			return false;
		}
	}
	/**
	 * 返回查询记录数
	 *
	 * @return int
	 */
	function getRowsNum($sql) {
		$query = $this->query ( $sql );
		return mysql_num_rows ( $query );
	}
	/**
	 * 返回查询的结果的第一个数据
	 *
	 * @return string
	 */	
	function getOneField($sql){
		$val = mysql_fetch_array($this->query ( $sql ));
		return $val[0];
	}
	/**
	 * 获取列
	 *
	 * @param string $sql
	 * @return array
	 */
	function getCol($sql) {
		$res = mysql_query ( $sql );
		if ($res !== false) {
			$arr = array ();
			$row = mysql_fetch_row ($res);
			while ($row) {
				$arr [] = $row [0];
				$row = mysql_fetch_row ($res);
			}
			return $arr;
		} else {
			return false;
		}
	}
	
	/**
	 * 关闭数据库连接（通常不需要，非持久连接会在脚本执行完毕后自动关闭）
	 */
	function close() {
		return mysql_close ( $this->link_id );
	}
	/*获取数据库版本信息*/
	function getVersion() {
		return $this->version;
	}
	/*数据库报错处理*/
	function ErrorMsg($message = '') {
		if ($message) {
			echo $message;
		} else {
			echo @mysql_error ();
		}
		exit ();
	}
}
?>