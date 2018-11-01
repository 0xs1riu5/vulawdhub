<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2010-1001 koyshe <koyshe@gmail.com>
 */
class db { 
	var $pconnect = FALSE;
	var $dbconn;
	var $page;
	var $table_index;
	var $sql;
	public function __construct($db_host, $db_user, $db_pw, $db_name, $db_coding)
	{
		$this->connect($db_host, $db_user, $db_pw, $db_name, $db_coding);
	}
	public function connect($db_host, $db_user, $db_pw, $db_name, $db_coding)
	{
		if ($this->pconnect) {
			$this->dbconn = @mysql_pconnect($db_host, $db_user, $db_pw);
		}
		else {
			$this->dbconn = @mysql_connect($db_host, $db_user, $db_pw);
		}
		if (!$this->dbconn) pe_bug('数据库连接失败...数据库ip，用户名，密码对吗？', __LINE__); 
		if (!mysql_select_db($db_name, $this->dbconn)) pe_bug('数据库选择失败...数据库名对吗？', __LINE__);
		$this->query("SET NAMES {$db_coding}");
		$this->query("SET sql_mode = ''");
	}
  	public function query($sql)
  	{
  		$this->sql[] = $sql;
		return mysql_query($sql, $this->dbconn);
  	}
	public function fetch_assoc($result = null)
	{
  		return mysql_fetch_assoc($result);
  	}
  	public function fetch_row($result = null)
  	{
  		return mysql_fetch_row($result);
  	}
	public function num_rows($result = null)
  	{
  		return mysql_num_rows($result);
  	}
	public function insert_id()
	{
		return mysql_insert_id();
	}
	public function index($table_index)
	{
		$this->table_index = $table_index;
		return $this;
	}
	/* ====================== 原始mysql处理函数 ====================== */
	public function sql_insert($sql)
	{
		$this->query($sql);
		if ($insert_id = mysql_insert_id()) {
			return $insert_id;
		}
		else {
			$result = mysql_affected_rows();
			return $result > 0 ? $result : 0;
		}
	}
	public function sql_delete($sql)
	{
		$this->query($sql);
		$result = mysql_affected_rows();
		return $result > 0 ? $result : 0;
	}
	public function sql_update($sql)
	{
		if ($this->query($sql) == true) {
			$result = mysql_affected_rows();
			return $result == 0 ? 1 : $result;
		}
		return 0;		
	}
	public function sql_selectall($sql, $limit_page = array())
	{
		//每页数量显示+分页 or 每页数量显示+不分页
		if (count($limit_page)==2) {
			$allnum = $this->sql_num(preg_replace('/select [\s\S]+?(?!from) from/', 'select count(1) from', $sql, 1));
			$this->page = new page($allnum, $limit_page[1], $limit_page[0]);
			$sqllimit = $this->page->limit;
		}
		elseif (count($limit_page)==1) {
			$sqllimit = " limit {$limit_page[0]}";
		}

		$result = $this->query($sql.$sqllimit);
		$rows = array();
		//自定义索引
		if ($this->table_index) {
			$table_index = explode('|', $this->table_index);
			$table_index_num = count($table_index);
			unset($this->table_index);
		}
		else {
			$table_index_num = 0;
		}
		while ($row = $this->fetch_assoc($result)) {
			if ($table_index_num == 0) {
				$rows[] = $row;
			}
			elseif ($table_index_num == 1) {
				$rows[$row[$table_index[0]]] = $row;
			}
			elseif ($table_index_num == 2) {
				$rows[$row[$table_index[0]]][$row[$table_index[1]]] = $row;
			}
		}
		return $rows;
	}
	public function sql_select($sql)
	{
		$row = array();
		return $row = $this->fetch_assoc($this->query($sql));
	}
	//可以用于判断符合sql条件的总行数(但sql必须遵循 "select count(1) from table where条件")合适，也可以用户判断某行是否存在
	public function sql_num($sql)
	{
		$rows = $this->fetch_row($this->query($sql));
		return intval($rows[0]);
	}
	/* ====================== 快速mysql处理函数 ====================== */
	public function pe_selectall($table, $where = '', $field = '*', $limit_page = array())
	{
		//处理条件语句
		$sqlwhere = $this->_dowhere($where);
		return $this->sql_selectall("select {$field} from `".dbpre."{$table}` {$sqlwhere}", $limit_page);
	}
	public function pe_select($table, $where = '', $field = '*')
	{
		//处理条件语句
		$sqlwhere = $this->_dowhere($where);
		return $this->sql_select("select {$field} from `".dbpre."{$table}` {$sqlwhere} limit 1");
	}
	public function pe_insert($table, $set)
	{
		//处理设置语句
		$sqlset = $this->_doset($set);
		return $this->sql_insert("insert into `".dbpre."{$table}` {$sqlset}");
	}
	public function pe_update($table, $where, $set)
	{
		//处理设置语句
		$sqlset = $this->_doset($set);
		//处理条件语句
		$sqlwhere = $this->_dowhere($where);
		return $this->sql_update("update `".dbpre."{$table}` {$sqlset} {$sqlwhere}");	
	}
	public function pe_delete($table, $where = '')
	{
		//处理条件语句
		$sqlwhere = $this->_dowhere($where);
		return $this->sql_delete("delete from `".dbpre."{$table}` {$sqlwhere}");
	}
	public function pe_num($table, $where = '')
	{
		//处理条件语句
		$sqlwhere = $this->_dowhere($where);
		return $this->sql_num("select count(1) from `".dbpre."{$table}` {$sqlwhere}");
	}
	public function sql()
	{
		$i = 1;
		foreach ((array)$this->sql as $k => $v) {
			if ($k <=1) {
				continue;
			}
			else {
				echo  "<p>[".($i++)."] => {$v}</p>";
			}
		}
	}
	/* ====================== 仅供内部调用 ====================== */
	//处理条件语句
	protected function _dowhere($where)
	{
		if (is_array($where)) {
			foreach ($where as $k => $v) {
				if (is_array($v)) {
					$where_arr[] = "`{$k}` in('".implode("','", $v)."')";			
				}
				else {
					in_array($k, array('order by', 'group by')) ? ($sqlby = " {$k} {$v}") : ($where_arr[] = "`{$k}` = '{$v}'");
				}
			}
			$sqlwhere = is_array($where_arr) ? 'where '.implode($where_arr, ' and ').$sqlby : $sqlby;
		}
		else {
			$where && $sqlwhere = (stripos(trim($where), 'order by') === 0 or stripos(trim($where), 'group by') === 0) ? "{$where}" : "where 1 {$where}";
		}
		return $sqlwhere;
	}
	//处理设置语句
	protected function _doset($set)
	{
		if (is_array($set)) {
			foreach ($set as $k => $v) {
				$set_arr[] = "`{$k}` = '{$v}'";
			}
			$sqlset = 'set '.implode($set_arr, ' , ');
		}
		else {
			$sqlset = "set {$set}";
		}
		return $sqlset;
	}
}
?>