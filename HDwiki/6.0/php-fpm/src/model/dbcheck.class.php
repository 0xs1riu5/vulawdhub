<?php !defined('IN_HDWIKI') && exit('Access Denied');
define('HDWIKI_CODE', WIKI_CHARSET.HDWIKI_VERSION.HDWIKI_RELEASE);
class dbcheckmodel {
	var $base;
	var $db;
	var $dir;
	var $hdwiki_root;
	function dbcheckmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->hdwiki_root = $this->formatpath(HDWIKI_ROOT);
		$this->dir = $this->hdwiki_root.'data/db_file/';
		$this->url_cache='http://kaiyuan.hudong.com/verify/';
		$this->phpexit = '<?php exit;?>';
	}

	//把路径格式化为"/"形式的
	function formatpath($path){
		$path = str_replace('\\', '/', $path);
		if(substr($path, -1) != '/'){
			$path = $path.'/';
		}
		return $path;
	}

	/**
	 * 数据库检测
	 * @return <type>
	 */
	function dbcheck(){
		$tablelist = $_ENV['db']->showtables();
		$result = array('total'=>0, 'isok'=>0, 'error'=>0, 'repair'=>0, 'ignore'=>0, 'chip'=>0);
		foreach($tablelist as $table){
			$result['total']++;
			$status = $_ENV['db']->show_table_status($table);
			$status = $status[0];

			if ($status['Engine'] == 'MEMORY'){
				$result['ignore']++;
				continue;
			}

			$res = $_ENV['db']->check_table($table);
			if ($res['Msg_text'] == 'OK'){
				$result['isok']++;
			}else{
				$result['error']++;
				$res = $_ENV['db']->repair_table($table);
	
				$i=0;
				while(!$res && $i < 6){
					$i++;
					$res = $_ENV['db']->repair_table($table);
				}
				if ($res){
					$result['repair']++;
				}
			}

			if ($status['Data_free']>0){
				$result['chip'] += $status['Data_free'];
				$_ENV['db']->optimize_table($table);
			}
		}
		return 'OK:'.$result['total'].'|'.$result['isok'].'|'.$result['error'].'|'.$result['repair'].'|'.$result['ignore'].'|'.$result['chip'];
		
	}

	/**
	 * 数据库完整性
	 * @return <type>
	 */
	function dbcompare(){
		$dbstruct = $this->get('Dbstruct'.HDWIKI_CODE);
		if ($dbstruct){
			$compare_struct = $this->compare_struct($dbstruct);
			$this->cache_set('Dbcompare'.HDWIKI_CODE, $compare_struct);
			$html = 'OK:';
			if(!empty($compare_struct['remove'])){
				$html .= implode(',', $compare_struct['remove']);
			}
			$html .= '|';
			if(!empty($compare_struct['modify'])){
				foreach($compare_struct['modify'] as $name => $table){
					$html .= '<span style="font-weight:bold">'.$name."表:</span><br>";
					if ($table['remove']){
						$html .= '丢失的字段：'.implode(',', $table['remove']).'；<br>';
					}

					if ($table['modify']){
						$html .= '被修改的字段：'.implode(',', $table['modify']).'；<br>';
					}
				}
			}
			return  $html;
		}else{
			return  '没有 Dbstruct'.HDWIKI_CODE.'.php 校对文件。';
		}

	}


	function dbrepair_struct(){
		$out_info = '';
		$dbstruct = $this->get('Dbstruct'.HDWIKI_CODE);
		$compare_result = $this->get('Dbcompare'.HDWIKI_CODE);

		$sql_list = $this->get_repair_sql($dbstruct, $compare_result);

		if (empty($sql_list)){
			$out_info =  '没有可修改的表。';
		}elseif ($this->repair_struct($sql_list)){
			$out_info = 'OK';
		}else{
			$out_info = '修复过程出现错误，请先检测并尝试优化数据库，再检测数据库结构完整性。';
		}
		return $out_info;
	}
	

	function get_columns($table){
		$columns = array();
		$query = $this->db->query("SHOW COLUMNS from ".$table);
		if (!$query){return false;}
		while($field = $this->db->fetch_array($query)){
			$columns[$field['Field']] = array(
				'Type'=>$field['Type'],
				'Null'=>$field['Null'],
				'Key'=>$field['Key'],
				'Default'=>$field['Default'],
				'Extra'=>$field['Extra'],
			);
		}
		return $columns;
	}

	function cache_set($name, $data, $method = ''){
		$file = $this->dir.$name.'.php';

        $data = base64_encode(serialize($data));
		$data = $this->phpexit.$data;

		file::forcemkdir($this->dir);
		$bytes = file::writetofile($file, $data);
		return $bytes;
	}
	
	function get($name, $expires = 0){
		$file = $this->dir.$name.'.php';

		if(!file_exists($file)){
			$data=util::hfopen($this->url_cache. rawurlencode($name) .'.php');
			if($data){
				file::forcemkdir($this->dir);
				$flag = file::writetofile($this->dir.$name.'.php', $data);
			}
			else return '';
		}

		if(file_exists($file)){
			$data = file::readfromfile($file);
			$data = str_replace($this->phpexit, '', $data);
			return unserialize(base64_decode($data));
		} else {
			return '';
		}

	}

	function compare_struct($struct){
		$result = array('remove'=>array(), 'modify'=>array());
		$my_struct = $this->get_db_struct();
		foreach($struct as $table => $fields){
			if (array_key_exists($table, $my_struct)){
				foreach($fields as $field => $value){
					if (array_key_exists($field, $my_struct[$table])){
						//字段存在，判断是否一致
						if ($value['Type'] != $my_struct[$table][$field]['Type']
							|| $value['Default'] != $my_struct[$table][$field]['Default']
							|| $value['Null'] != $my_struct[$table][$field]['Null']
							|| $value['Key'] != $my_struct[$table][$field]['Key']
							|| $value['Extra'] != $my_struct[$table][$field]['Extra']
						){
							if (isset($result['modify'][$table])){//后续添加，向第一次添加的数组中填充
								$result['modify'][$table]['modify'][] = $field;
							}else{//首次添加，添加一个数组
								$result['modify'][$table] = array('modify'=>array($field));
							}
						}
					}else{
						//字段不存在
						if (isset($result['modify'][$table])){
							$result['modify'][$table]['remove'][] = $field;
						}else{
							$result['modify'][$table] = array('remove'=>array($field));
						}
					}
				}
			}else{
				$result['remove'][] = $table;
			}
		}
		return $result;
	}

	function get_db_struct(){
		//$tablelist = $this->get_tables();
		$tablelist = $_ENV['db']->showtables();
		$tablestruct = array();
		foreach($tablelist as $table){
			$tablestruct[str_replace(DB_TABLEPRE,'',$table)] = $this->get_columns($table);
		}
		return $tablestruct;
	}

	/**
	 * 修复表
	 * @param <type> $struct
	 * @param <type> $compare_result
	 * @return <type>
	 */
	function get_repair_sql($struct, $compare_result){
		$sqls = array();
		$result = $compare_result;
		if (isset($result['remove'])){
			foreach($result['remove'] as $table){
				$fields = $struct[$table];
				$sql='CREATE TABLE '.DB_TABLEPRE.$table.' (';
				foreach($fields as $fieldname => $field){
					$sql .= "`$fieldname` ".$field['Type'];
					if($field['Null'] == 'YES') {
						$sql .= ' NULL';
						if (is_null($field['Default']) || $field['Default'] == 'NULL') {
							$sql .= " DEFAULT NULL";
						}
					}else{
						$sql .= ' NOT NULL';
					}

					if (!is_null($field['Default']) && $field['Default'] !== 'NULL') {
						$sql .= " DEFAULT '".$field['Default']."'";
					}

					if($field['Extra'] == 'auto_increment') {
						$sql .= ' auto_increment,';
					} else {
						$sql .= ',';
					}

					if($field['Key'] == 'PRI') {
						$sql .= " PRIMARY KEY  (`$fieldname`),";
					}else if($field['Key'] == 'MUL') {
						$sql .= " KEY `$fieldname`(`$fieldname`),";
					}else if($field['Key'] == 'UNI') {
						$sql .= " UNIQUE KEY `$fieldname`(`$fieldname`),";
					}
				}//foreach
				$sql .= ') TYPE=MyISAM  default charset='.DB_CHARSET;
				$sqls[] = str_replace(',)',')', $sql);
			}//foreach
		}

		if (isset($result['modify'])){
			foreach($result['modify'] as $tablename => $table){
				$fields = $struct[$tablename];
				if (isset($table['remove'])){
					foreach($table['remove'] as $fieldname){
						$field = $fields[$fieldname];
						$sql = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD `$fieldname` ".$field['Type'];
						if($field['Null'] == 'YES') {
							$sql .= ' NULL';
							if (is_null($field['Default']) || $field['Default'] == 'NULL') {
								$sql .= " DEFAULT NULL";
							}
						}else {
							$sql .= ' NOT NULL';
						}
						if (!is_null($field['Default']) && $field['Default'] !== 'NULL') {
							$sql .= " DEFAULT '".$field['Default']."'";
						}
						$sqls[] = $sql;

						switch($field['Key']){
							case 'PRI': $sqls[] = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD PRIMARY KEY(`$fieldname`)";break;
							case 'MUL': $sqls[] = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD INDEX(`$fieldname`)";break;
							case 'UNI': $sqls[] = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD UNIQUE(`$fieldname`)";break;
						}
					}
				}

				if (isset($table['modify'])){
					foreach($table['modify'] as $fieldname){
						$field = $fields[$fieldname];
						$sql = "ALTER TABLE ".DB_TABLEPRE."$tablename CHANGE `$fieldname` `$fieldname` ".$field['Type'];
						if($field['Null'] == 'YES') {
							$sql .= ' NULL';
							if (is_null($field['Default']) || $field['Default'] == 'NULL') {
								$sql .= " DEFAULT NULL";
							}
						}else {
							$sql .= ' NOT NULL';
						}
						if (!is_null($field['Default']) && $field['Default'] !== 'NULL') {
							$sql .= " DEFAULT '".$field['Default']."'";
						}
						$sqls[] = $sql;

						if (!empty($field['Key'])){
							$_field = $this->get_field($tablename, $fieldname);
							if (empty($_field['Key'])){
								switch($field['Key']){
									case 'PRI': $sqls[] = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD PRIMARY KEY(`$fieldname`)";break;
									case 'MUL': $sqls[] = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD INDEX(`$fieldname`)";break;
									case 'UNI': $sqls[] = "ALTER TABLE ".DB_TABLEPRE."$tablename ADD UNIQUE(`$fieldname`)";break;
								}
							}
						}
					}
				}
			}//foreach
		}
		return $sqls;
	}

	function repair_struct($sql_list){
		if (!is_array($sql_list)){return false;}
		$bool = true;
		foreach($sql_list as $sql){
			if (false === $this->db->query($sql)){
				$bool = false;
			}
		}
		return $bool;
	}
}