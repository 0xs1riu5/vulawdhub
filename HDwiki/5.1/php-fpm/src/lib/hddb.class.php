<?php

class hddb {

	var $mlink;

	function hddb($dbhost, $dbuser, $dbpw, $dbname = '',$dbcharset='utf8', $pconnect=0){
		if($pconnect){
			if(!$this->mlink = @mysql_pconnect($dbhost, $dbuser, $dbpw)){
				$this->halt('Can not connect to MySQL');
			}
		} else {
			if(!$this->mlink = @mysql_connect($dbhost, $dbuser, $dbpw)){
				$this->halt('Can not connect to MySQL');
			}
		}
		if($this->version()>'4.1'){
			if('utf-8'==strtolower($dbcharset)){
				$dbcharset='utf8';
			}
			if($dbcharset){
				mysql_query("SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary", $this->mlink);
			}
			if($this->version() > '5.0.1'){
				mysql_query("SET sql_mode=''", $this->mlink);
			}
		}
		if($dbname){
			mysql_select_db($dbname, $this->mlink);
		}
	}

	function select_db($dbname){
		return mysql_select_db($dbname, $this->mlink);
	}
	
	function get_array($sql){
		$list = array();
		$query=$this->query($sql);
		while($row=$this->fetch_array($query)){
			$list[]=$row;
		}
		return $list;
	}
	
	function fetch_array($query, $result_type = MYSQL_ASSOC){
		return (is_resource($query))? mysql_fetch_array($query, $result_type) :false;
	}

	function result_first($sql){
		$query = $this->query($sql);
		return $this->result($query, 0);
	}

	function fetch_first($sql){
		$query = $this->query($sql);
		return $this->fetch_array($query);
	}
	
	function fetch_by_field($table,$field,$value,$select_fields='*'){
		$query=$this->query("SELECT $select_fields FROM ".DB_TABLEPRE."$table WHERE $field='$value'");
		return $this->fetch_array($query);
	}
	
	function update_field($table,$field,$value,$where){
		return $this->query("UPDATE ".DB_TABLEPRE."$table SET $field='$value' WHERE $where");
	}
	
	function fetch_total($table,$where='1'){
		return $this->result_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."$table WHERE $where");
	}
	
	function query($sql, $type = 'SILENT'){
		global $mquerynum;
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->mlink)) && $type != 'SILENT'){
			$this->halt("MySQL Query Error",'TRUE',$sql);
		}
		$mquerynum++;
		return $query;
	}

	function affected_rows(){
		return mysql_affected_rows($this->mlink);
	}

	function error(){
		return (($this->mlink) ? mysql_error($this->mlink) : mysql_error());
	}

	function errno(){
		return intval(($this->mlink) ? mysql_errno($this->mlink) : mysql_errno());
	}

	function result($query, $row){
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query){
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query){
		return mysql_num_fields($query);
	}

	function free_result($query){
		return mysql_free_result($query);
	}

	function insert_id(){
		return ($id = mysql_insert_id($this->mlink)) >= 0 ? $id : $this->result($this->query('SELECT last_insert_id()'), 0);
	}

	function fetch_row($query){
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query){
		return mysql_fetch_field($query);
	}

	function version(){
		return mysql_get_server_info($this->mlink);
	}

	function close(){
		return mysql_close($this->mlink);
	}

	function halt($msg, $debug=true, $sql=''){
		@ini_set("date.timezone","Asia/Shanghai");
		if($debug){
			$output .="<html>\n<head>\n";
			$output .="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".WIKI_CHARSET."\">\n";
			$output .="<title>$msg</title>\n";
			$output .="</head>\n<body><table>";
			$output .="<b>HDwiki Error Info</b><table><tr><td width='100px'><b>Message</b></td><td>$msg</td></tr>\n";
			$output .="<tr><td><b>Time</b></td><td>".date("Y-m-d H:i:s")."<br /></td></tr>\n";
			$output .="<tr><td><b>Script</b></td><td> ".$_SERVER['PHP_SELF']."<br /></td></tr>\n\n";
			$output .="<tr><td><b>SQL</b></td><td> ".htmlspecialchars($sql)."<br />\n</td></tr><tr><td><b>Error</b></td><td>  ".$this->error()."</td></tr><br />\n";
			$output .="<tr><td><b>Errno.</b></td><td>  ".$this->errno()."</td></tr></table>";
			$output .='<p style="font-family: Verdana, Tahoma; font-size: 12px; background: #FFFFFF;"><a href="http://kaiyuan.hudong.com/faq.php?type=mysql&dberrno='.$this->errno().'&dberror='.rawurlencode($this->error()).'" target="_blank">&#x5230; http://kaiyuan.hudong.com &#x641c;&#x7d22;&#x6b64;&#x9519;&#x8bef;&#x7684;&#x89e3;&#x51b3;&#x65b9;&#x6848;</a></p>';
			$output .="\n</body></html>";
			echo $output;
			exit();
		}
		$this->errorlog($msg,$sql);
	}
	
	function errorlog($msg,$sql){
		$error="<?php exit;?>"."\t".time()."\t".util::getip()."\tMysql\t".$_SERVER['PHP_SELF']."\t".$this->errno()."\t".$this->error()."\t$sql\n";
		file::forcemkdir(HDWIKI_ROOT."/data/logs");
		@$fp = fopen(HDWIKI_ROOT."/data/logs/".date('Ym')."_errorlog.php","a");
		@flock($fp, 2);
		@fwrite($fp,$error);
		@fclose($fp);
	}
	
	function cond_handle($conditions) {
		if(empty($conditions)) return '1';
		if(is_string($conditions)) return $conditions;
		if(is_array($conditions)) {
			$return = '1';
			foreach($conditions as $key => $val) {
				$return .= " AND `$key`='".addslashes(stripcslashes($val))."'";
			}
			return $return;
		}
	}
	
}

?>