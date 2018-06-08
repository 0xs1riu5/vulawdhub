<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
function file_down($file)
{
    global $lang_setdbNotExist;
	!file_exists($file) && okinfox('database.php?action=import',$lang_setdbNotExist);
	$filename = $filename ? $filename : basename($file);
	$filetype = fileext($filename);
	$filesize = filesize($file);
	header('Cache-control: max-age=31536000');
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
	header('Content-Encoding: none');
	header('Content-Length: '.$filesize);
	header('Content-Disposition: attachment; filename='.$filename);
	header('Content-Type: '.$filetype);
	readfile($file);
	exit;
}
function cache_write($file, $string, $type = 'array')
{
	if(is_array($string))
	{
		$type = strtolower($type);
		if($type == 'array')
		{
			$string = "<?php\n return ".var_export($string,TRUE).";\n?>";
		}
		elseif($type == 'constant')
		{
			$data='';
			foreach($string as $key => $value) $data .= "define('".strtoupper($key)."','".addslashes($value)."');\n";
			$string = "<?php\n".$data."\n?>";
		}
	}
	file_put_contents('../../databack/'.$file, $string);
}

function cache_read($file, $mode = 'i')
{
	$cachefile = '../../databack/'.$file;
	if(!file_exists($cachefile)) return array();
	return $mode == 'i' ? include $cachefile : file_get_contents($cachefile);
}

function cache_delete($file)
{
	return @unlink('../../databack/'.$file);
}

function sql_dumptable($table, $startfrom = 0, $currsize = 0)
{
	global $db, $sizelimit, $startrow;

	if(!isset($tabledump)) $tabledump = '';
	$offset = 100;
	if(!$startfrom)
	{
		$tabledump = "DROP TABLE IF EXISTS $table;\n";
		$createtable = $db->query("SHOW CREATE TABLE $table");
		$create = $db->fetch_row($createtable);
		$tabledump .= $create[1].";\n\n";
	}

	$tabledumped = 0;
	$numrows = $offset;
	while($currsize + strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset)
	{
		$tabledumped = 1;
		$rows = $db->query("SELECT * FROM $table LIMIT $startfrom, $offset");
		$numfields = $db->num_fields($rows);
		$numrows = $db->num_rows($rows);
		while ($row = $db->fetch_row($rows))
		{
			$comma = "";
			$tabledump .= "INSERT INTO $table VALUES(";
			for($i = 0; $i < $numfields; $i++)
			{
				$tabledump .= $comma."'".mysql_escape_string($row[$i])."'";
				$comma = ",";
			}
			$tabledump .= ");\n";
		}
		$startfrom += $offset;
	}
	$startrow = $startfrom;
	$tabledump .= "\n";
	return $tabledump;
}
function sql_execute($sql,$replace=0)
{
	global $db,$tablepre,$met_visit_day,$met_visit_detail;
    $split = sql_split($sql);
	$sqls  = $split['sql'];
	$info  = $split['info'];
	$infos=explode('#',$info);
	$localurl="http://";
	$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
	$localurl_a=explode("/",$localurl);
	$localurl_count=count($localurl_a);
	$localurl_admin=$localurl_a[$localurl_count-4];
	$localurl_admin=$localurl_admin."/system/";
	$localurl_real=explode($localurl_admin,$localurl);
	$localurl=$localurl_real[0];
	if($infos[3]&&$tablepre!=$infos[3])$sqlre1=1;
	if($infos[2]&&$localurl!=$infos[2])$sqlre2=1;
	if(is_array($sqls))
    {
		foreach($sqls as $sql)
		{
			if($replace){
				$sql=str_replace('met_',$tablepre,$sql);
				$sql=str_replace('metconfig_','met_',$sql);
			}
			if($sqlre1==1)$sql=preg_replace(array('/^INSERT INTO '.$infos[3].'/','/^DROP TABLE IF EXISTS '.$infos[3].'/','/^CREATE TABLE `'.$infos[3].'/'),array('INSERT INTO '.$tablepre,'DROP TABLE IF EXISTS '.$tablepre,'CREATE TABLE `'.$tablepre),$sql,1);
			if($sqlre2==1){
				if(!preg_match('/^INSERT INTO (('.$met_visit_day.')|('.$met_visit_detail.'.))/',$sql)){
					$sql=str_replace($infos[2],$localurl,$sql);
				}
			}
			if(trim($sql) != '') 
			{
				if(!$db->query($sql)){
					return false;
				}
			}
		}
		
	}
	else
	{
		if(!$db->query($sqls)){
			return false;
		}
	}
	return true;
}

function sql_split($sql){
	global $db_charset, $db;
	if($db->version() > '4.1' && $db_charset){
		$sql = preg_replace("/TYPE=(InnoDB|MyISAM)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=".$db_charset,$sql);
	}
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query){
		$ret['sql'][$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach($queries as $query){
			$str1 = substr($query, 0, 1);
			if($str1 != '#' && $str1 != '-') {
				$ret['sql'][$num] .= $query;
			}else{
				$ret['info'].= $query;
			}
		}
		$num++;
	}
	return($ret);
}
function fileext($filename){
	return trim(substr(strrchr($filename, '.'), 1));
}
function tableprearray($tablepre){
global $met_tablename;
	$mettables=explode('|',$met_tablename);
	$i=0;
	foreach($mettables as $key=>$val){
		$tables[$i]=$tablepre.$val;
		$i++;
	}
	return $tables;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>