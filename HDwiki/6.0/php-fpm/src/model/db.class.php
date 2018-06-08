<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class dbmodel {

	var $db;
	var $base;

	function dbmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function showtables(){
		$tablelist=array();
		$query=$this->db->query("SHOW TABLES LIKE '".DB_TABLEPRE."%'");
		while($table=$this->db->fetch_array($query)){
			@$tablelist[]=$table["Tables_in_".DB_NAME." (".DB_TABLEPRE."%)"];
		}
		return $tablelist;
	}
	
	function get_sqlfile_list($filedir) {
		$filelist=array();
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename&& ('.zip'==substr($filename,-4) || '.sql'==substr($filename,-4))){
				$file['filename']=$filename;
				$file['filepath']="/data/db_backup/".$file['filename'];
				$filesize=(filesize($filedir.$filename)/1024);
				$size=explode(".",$filesize);
				$file['filesize']=$size[0]." KB";
				$file['filectime']=$this->base->date(filectime($filedir.$filename));
				$filelist[]=$file;
			}
		}
		closedir($handle);
		return $filelist;
	}
	
	function show_table_status($table_name=''){
		if(empty($table_name)) $table = DB_TABLEPRE;
		$statuslist=array();
		$query=$this->db->query("SHOW TABLE STATUS LIKE '".$table_name."%'");
		while($status=$this->db->fetch_array($query)){
			$statuslist[]=$status;
		}
		return $statuslist;
	}
	
	function check_table($table_name){
		$query = $this->db->query("CHECK TABLE ".$table_name);
		return $this->db->fetch_array($query);
	}

	function show_tables_like(){
		$tablelist=array();
		$query = $this->db->query("SHOW TABLES LIKE '".DB_TABLEPRE."%'");
		while ($table = $this->db->fetch_row($query)){
			$tablelist[] = $table[0];
		}
		return $tablelist;
	}
	
	function optimize_table($table){
		if($this->db->query("OPTIMIZE TABLE ".$table)){
			return 1;
		}else{
			return 0;
		}
	}

	function repair_table($table){
		if($this->db->query("REPAIR TABLE ".$table)){
			return 1;
		}else{
			return 0;
		}		
	}
	
	function splitsql(&$sql) {
		$sqllist = array();
		$num = 0;
		$sql = str_replace("\r", "\n", $sql);
		$sql = explode(";\n", trim($sql));
		foreach($sql as $query) {
			$queries = explode("\n", trim($query));
			foreach($queries as $query) {
				$sqllist[$num] .= $query[0] == "#" ? NULL : $query;
			}
			$num++;
		}
		unset($sql);
		return($sqllist);
	}
	
	
	function syntablestruct($sql, $version, $dbcharset) {
		if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
			return $sql;
		}
		$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;
		if($sqlversion === $version) {
			return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
		}
		if($version) {
			return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);
		} else {
			return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
		}
	}
	
	
	
	function write_to_sql(&$sqldump,$dumpfile,$volume){
		$type = isset($type)?$type:null;
		$sqldump =	"# <?php exit();?>\n".
					"# HDWiki Multi-Volume Data Dump Vol.$volume\n".
					"# Version: hdwiki ".HDWIKI_VERSION."\n".
					"# Time: ".date("Y-m-d",$this->base->time)."\n".
					"# Type: $type\n".
					"# Table Prefix: ".DB_TABLEPRE."\n".
					"# HDWiki Home: http://kaiyuan.hudong.com\n".$sqldump;
		$dumpfilename = sprintf($dumpfile, $volume);
		$byte=file::writetofile($dumpfilename,$sqldump);
		if($byte>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function write_to_zip($backupfilename,$dumpfile,$volume){
		require_once HDWIKI_ROOT.'/lib/zip.class.php';
		$zip = new zip();
		if(!$zip->chk_zip){
			$this->base->message($this->view->lang['chkziperror'],'');
		}
		$zipfilename = $backupfilename.'.zip';
		$unlinks = '';
		for($i = 1; $i <= $volume; $i++) {
			$filename = sprintf($dumpfile, $i);
			$fp = fopen($filename, "r");
			$content = @fread($fp, filesize($filename));
			fclose($fp);
			$zip->add_File($content, basename($filename));
			$unlinks .= "@unlink('$filename');";
		}
		$fp = fopen($zipfilename, 'w');
		if(@fwrite($fp, $zip->get_file()) !== FALSE) {
			eval($unlinks);
		}
		unset($sqldump, $zip, $content);
	}
	
	function sqldumptable($table,$complete, $sizelimit, $startfrom = 0, $currsize = 0) {
		$sqlcompat="";
		$db=$this->base->db;
		$offset = 300;
		$tabledump = '';
		$tablefields = array();
		$sqlcharset=DB_CHARSET;
		$dumpcharset=WIKI_CHARSET;
		$usehex = false;
		if($table==DB_TABLEPRE."session"){
			$result['tabledump']="\n";
			$result['startfrom']=$startfrom;
			$result['complete']=$complete;
			return $result;
		}
		$query = $db->query("SHOW FULL COLUMNS FROM $table", 'SILENT');
		if(!$query && $db->errno() == 1146) {
			return;
		} elseif(!$query) {
			$usehex = FALSE;
		} else {
			while($fieldrow = $db->fetch_array($query)) {
				$tablefields[] = $fieldrow;
			}
		}
		if(!$startfrom) {
			$createtable = $db->query("SHOW CREATE TABLE $table", 'SILENT');
			if(!$db->error()) {
				$tabledump = "DROP TABLE IF EXISTS $table;\n";
			} else {
				return '';
			}
			$create = $db->fetch_row($createtable);

			if(strpos($table, '.') !== FALSE) {
				$tablename = substr($table, strpos($table, '.') + 1);
				$create[1] = str_replace("CREATE TABLE $tablename", 'CREATE TABLE '.$table, $create[1]);
			}
			$tabledump .= $create[1];

			if($sqlcompat == 'MYSQL41' && $db->version() < '4.1') {
				$tabledump = preg_replace("/TYPE\=(.+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
			}
			if($db->version() > '4.1' && $sqlcharset) {
				$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=.+/", "DEFAULT CHARSET=".$sqlcharset, $tabledump);
			}

			$tablestatus = $db->fetch_first("SHOW TABLE STATUS LIKE '$table'");
			$tabledump .= ($tablestatus['Auto_increment'] ? " AUTO_INCREMENT=$tablestatus[Auto_increment]" : '').";\n\n";
			if($sqlcompat == 'MYSQL40' && $db->version() >= '4.1' && $db->version() < '5.1') {
				if($tablestatus['Auto_increment'] <> '') {
					$temppos = strpos($tabledump, ',');
					$tabledump = substr($tabledump, 0, $temppos).' auto_increment'.substr($tabledump, $temppos);
				}
				if($tablestatus['Engine'] == 'MEMORY') {
					$tabledump = str_replace('TYPE=MEMORY', 'TYPE=HEAP', $tabledump);
				}
			}
		}
		$tabledumped = 0;
		$numrows = $offset;
		$firstfield = $tablefields[0];
		while($currsize + strlen($tabledump) + 500 < $sizelimit * 1000 && $numrows == $offset) {
			$selectsql = "SELECT * FROM $table LIMIT $startfrom, $offset";
			$tabledumped = 1;
			$rows = $db->query($selectsql);
			$numfields = $db->num_fields($rows);
			$numrows = $db->num_rows($rows);
			while($row = $db->fetch_row($rows)) {
				$comma = $t = '';
				for($i = 0; $i < $numfields; $i++) {
					$t .= $comma.($usehex && !empty($row[$i]) && (strexists($tablefields[$i]['Type'], 'char') || strexists($tablefields[$i]['Type'], 'text')) ? '0x'.bin2hex($row[$i]) : '\''.mysql_escape_string($row[$i]).'\'');
					$comma = ',';
				}
				if(strlen($t) + $currsize + strlen($tabledump) + 500 < $sizelimit * 1000) {
					$startfrom++;
					$tabledump .= "INSERT INTO $table VALUES ($t);\n";
				} else {
					$complete = FALSE;
					break 2;
				}
			}
		}
		
		$result['tabledump']=$tabledump."\n";
		$result['startfrom']=$startfrom;
		$result['complete']=$complete;
		return $result;
	}
	
	function databasesize(){
		$dbsize = 0;
		$query=$this->db->query("SHOW TABLE STATUS FROM `".DB_NAME."`");
		while($table=$this->db->fetch_array($query)){
			$dbsize += $table['Data_length'] + $table['Index_length'];
		}
		return $dbsize;
	}
	
	function editionconvert($type,$number){
		if($type=='txt'){
			$changenum=100;
			$maxeid=$this->db->result_first("SELECT MAX(eid) FROM ".DB_TABLEPRE."edition WHERE 1");
			if($maxeid < ($number-$changenum)){
				return true;
			}
			$query=$this->db->query("SELECT eid,content FROM ".DB_TABLEPRE."edition WHERE eid >($number-$changenum) AND eid <= $number");
			while($edition=$this->db->fetch_array($query)){
				if($edition['content']){
					$path='data/edition/'.ceil($edition['eid']/$changenum)*$changenum;
					file::forcemkdir($path);
					file::writetofile($path."/".$edition['eid'].".txt",$edition['content']);
				}
			}
			$this->db->query("UPDATE ".DB_TABLEPRE."edition SET content=''  WHERE eid >($number-$changenum) AND eid <= $number");
		}else{
			$dirlist=array();
			if ($handle = opendir('data/edition')) {
				while (false !== ($dir = readdir($handle))) {
					$dirlist[]=$dir;
				}
				closedir($handle);
			}
			$maxeid=@max($dirlist);
			if($number>$maxeid){
				return true;
			}
			$path="data/edition/$number";
			$files=file::get_file_by_ext($path,array('txt'));
			foreach((array)$files as $file){
				$eid=substr($file,0,-4);
				$filename=$path."/".$file;
				$content=string::haddslashes(file::readfromfile($filename), 1);
				if($content){
					$this->db->query("UPDATE ".DB_TABLEPRE."edition SET content='$content' WHERE eid=$eid");
					@unlink($filename);
				}
			}
		}
		return false;
	}
}
?>