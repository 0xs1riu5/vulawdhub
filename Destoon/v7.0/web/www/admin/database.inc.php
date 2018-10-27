<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/include/sql.func.php';
$menus = array (
    array('数据备份', '?file='.$file),
    array('数据恢复', '?file='.$file.'&action=import'),
    array('字符替换', '?file='.$file.'&action=replace'),
    array('执行SQL', '?file='.$file.'&action=execute'),
    array('显示进程', '?file='.$file.'&action=process'),
    array('数据互转', '?file='.$file.'&action=move'),
    array('数据导入', '?file=data'),
);
$this_forward = '?file='.$file;
$D = DT_ROOT.'/file/backup/';
isset($dir) or $dir = '';
isset($table) or $table = '';
if($table) $table = strip_sql($table, 0);
switch($action) {
	case 'repair':
		$DT['close'] or msg('为了数据安全，此操作必须在网站设置里关闭网站');
		$table or msg('Table为空');
		$db->query("REPAIR TABLE `$table`");
		dmsg('修复成功', $this_forward);
	break;
	case 'optimize':
		$DT['close'] or msg('为了数据安全，此操作必须在网站设置里关闭网站');
		$table or msg('Table为空');
		$db->query("OPTIMIZE TABLE `$table`");
		dmsg('优化成功', $this_forward);
	break;
	case 'drop':
		if(!$tables) msg();
		if(is_array($tables)) {
			foreach($tables as $table) {
				$table = strip_sql($table, 0);
				if(strpos($table, $DT_PRE) === false) $db->query("DROP TABLE `$table`");
			}
		}
		dmsg('删除成功', $this_forward);
	break;
	case 'execute':
		if(!isset($CFG['executesql']) || !$CFG['executesql']) msg('系统禁止了执行SQL，请FTP修改根目录config.inc.php<br/>$CFG[\'executesql\'] = \'0\'; 修改为 $CFG[\'executesql\'] = \'1\';');
		if($submit) {
			if(trim($sql) == '') {
				msg('SQL语句为空');
			} else {
				$sql = stripslashes($sql);
				$sql = strip_sql($sql, 0);
				sql_execute($sql);
				dmsg('执行成功', '?file='.$file.'&action=execute');
			}
		} else {
			include tpl('database_execute');
		}
	break;
	case 'process':
		$lists = array();
		$result = $db->query("SHOW FULL PROCESSLIST");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
		include tpl('database_process');
	break;
	case 'kill':
		$db->halt = 0;
		if($itemid) {
			if(is_array($itemid)) {
				foreach($itemid as $id) {
					$db->query("KILL $id");
				}
			} else {
				$db->query("KILL $itemid");
			}
		}
		dmsg('结束成功', '?file='.$file.'&action=process');
	break;
	case 'comments':
		$db->halt = 0;
		$C = include(DT_ROOT.'/file/setting/table-comment.php');
		foreach($C as $k=>$v) {
			$sql = "ALTER TABLE `{$DT_PRE}{$k}` COMMENT='{$v}'";
			$db->query($sql);
		}
		foreach($MODULE as $k=>$v) {
			if(in_array($v['module'], array('article', 'brand', 'buy', 'down', 'info', 'photo', 'sell', 'video'))) {
				$sql = "ALTER TABLE `".$DT_PRE.$v['module']."_".$v['moduleid']."` COMMENT='".$v['name']."'";
				$db->query($sql);
				$sql = "ALTER TABLE `".$DT_PRE.$v['module']."_data_".$v['moduleid']."` COMMENT='".$v['name']."内容'";
				$db->query($sql);
			}
		}
		dmsg('重建成功', '?file='.$file);
	break;
	case 'comment':
		$table or msg('Table为空');
		if($submit) {
			$name = trim($name);
			$db->query("ALTER TABLE `{$table}` COMMENT='{$name}'");
			dmsg('修改成功', '?file='.$file.'&action='.$action.'&table='.$table.'&note='.urlencode($name));
		} else {
			include tpl('database_comment');
		}
	break;
	case 'export':
		if(!$table) msg();
		//$memory_limit = trim(@ini_get('memory_limit'));
		$sizelimit = 1024*1024;//Max 1G
		file_down('', $table.'.sql', sql_dumptable($table));
	break;
	case 'download':
		$file_ext = file_ext($filename);
		if($file_ext != 'sql') msg('只能下载SQL文件');
		file_down($dir ? $D.$dir.'/'.$filename : $D.$filename);
	break;
	case 'delete':
		if(!is_array($filenames)) {
			$tmp = $filenames;
			$filenames = array();
			$filenames[0] = $tmp;
		}
		foreach($filenames as $filename) {
			if(file_ext($filename) == 'sql') {
				file_del($dir ? $D.$dir.'/'.$filename : $D.$filename);
			} else if(is_dir($D.$filename)) {
				dir_delete($D.$filename);
			}
		}
		dmsg('删除成功', $forward);
	break;
	case 'move':
		if($submit) {
			($fmid > 0 && $tmid > 0 && $fmid != $tmid) or msg('来源模块或目标模块设置错误');
			$catid or msg('请选择新分类');
			$condition = str_replace('and', 'AND', trim($condition));
			$condition = strpos($condition, 'AND') === false ? "itemid IN ($condition)" : substr($condition, 3);
			$condition = stripslashes($condition);
			$condition or msg('请填写转移条件');
			$i = 0;
			$fs = array();
			$ftb = get_table($fmid);
			$ftb_data = get_table($fmid, 1);
			$ttb = get_table($tmid);
			$ttb_data = get_table($tmid, 1);
			$result = $db->query("SHOW COLUMNS FROM `$ttb`");
			while($r = $db->fetch_array($result)) {
				$fs[] = $r['Field'];
			}
			$result = $db->query("SELECT * FROM {$ftb} WHERE $condition");
			while($r = $db->fetch_array($result)) {
				$fid = $r['itemid'];
				unset($r['itemid']);
				$r['catid'] = $catid;
				$r = daddslashes($r);
				if(is_file(DT_CACHE.'/'.$fmid.'.part')) $ftb_data = split_table($fmid, $fid);
				$t = $db->get_one("SELECT content FROM {$ftb_data} WHERE itemid=$fid");
				$content = daddslashes($t['content']);			
				$sqlk = $sqlv = '';
				foreach($r as $k=>$v) {
					if($fs && !in_array($k, $fs)) continue;
					$sqlk .= ','.$k; $sqlv .= ",'$v'";
				}
				$sqlk = substr($sqlk, 1);
				$sqlv = substr($sqlv, 1);
				$db->query("INSERT INTO {$ttb} ($sqlk) VALUES ($sqlv)");
				$tid = $db->insert_id();
				if(is_file(DT_CACHE.'/'.$tmid.'.part')) $ttb_data = split_table($tmid, $tid);
				$db->query("INSERT INTO {$ttb_data} (itemid,content)  VALUES ('$tid','$content')");
				$linkurl = str_replace($fid, $tid, $r['linkurl']);
				$db->query("UPDATE {$ttb} SET linkurl='$linkurl' WHERE itemid=$tid");
				if($delete) {
					$db->query("DELETE FROM {$ftb} WHERE itemid=$fid");
					$db->query("DELETE FROM {$ftb_data} WHERE itemid=$fid");
					$html = DT_ROOT.'/'.$MODULE[$fmid]['moduledir'].'/'.$r['linkurl'];
					if(is_file($html)) @unlink($html);
				}
				$i++;
			}
			msg('成功转移 '.$i.' 条数据', '?file='.$file.'&action='.$action, 2);
		} else {
			include tpl('database_move');
		}
	break;
	case 'replace':
		if($submit) {
			if(!$table || !$fields) msg('请选择字段');
			if($type == 1) {
				if(!$from) msg('请填写查找内容');
				$from = stripslashes($from);
				$to = stripslashes($to);
			} else {
				if(!$add) msg('请填写追加内容');
				$add = stripslashes($add);
			}
			if($conditon) $conditon = stripslashes($conditon);
			$key = '';
			$result = $db->query("SHOW COLUMNS FROM `$table`");
			while($r = $db->fetch_array($result)) {
				if($r['Key'] == 'PRI') {
					$key = $r['Field'];
					break;
				}
			}
			$key or msg('表'.$table.'无主键，无法完成操作');
			$key != $fields or msg('无法完成主键操作');
			$result = $db->query("SELECT `$fields`,`$key` FROM `$table` WHERE 1 $condition");
			while($r = $db->fetch_array($result)) {
				$value = '';
				if($type == 1) {
					$value = str_replace($from, $to, $r[$fields]);
				} else if($type == 2) {
					$value = $add.$r[$fields];
				} else if($type == 3) {
					$value = $r[$fields].$add;
				} else {
					msg();
				}
				$value = addslashes($value);
				$db->query("UPDATE `$table` SET $fields='".$value."' WHERE `$key`='".$r[$key]."'");
			}
			dmsg('操作成功', '?file='.$file.'&action='.$action);
		} else {
			$table_select = '';
			$query = $db->query("SHOW TABLES FROM `".$CFG['db_name']."`");
			while($r = $db->fetch_row($query)) {
				$table = $r[0];
				if(preg_match("/^".$DT_PRE."/i", $table)) {
					$table_select .= '<option value="'.$table.'">'.$table.'</option>';         
				}
			}
			$sql_select = '';
			$sqlfiles = glob($D.'*');
			if(is_array($sqlfiles)) {				
				$sqlfiles = array_reverse($sqlfiles);
				foreach($sqlfiles as $id=>$sqlfile)	{
					$tmp = basename($sqlfile);
					if(is_dir($sqlfile)) $sql_select .= '<option value="'.$tmp.'">'.$tmp.'</option>'; 
				}
			}
			include tpl('database_replace');
		}
	break;
	case 'file_replace':
		if(!$file_pre) msg('请选择或者填写备份文件前缀');
		if(!$file_from) msg('请请填写查找内容');
		isset($tid) or $tid = count(glob($D.$file_pre.'/*.sql'));
		$fileid = isset($fileid) ? $fileid : 1;
		$filename = $file_pre.'/'.$fileid.'.sql';
		$dfile = $D.$filename;
		$file_from = urldecode($file_from);
		$file_to = urldecode($file_to);
		if(is_file($dfile)) {
			$sql = file_get($dfile);
			$sql = str_replace($file_from, $file_to, $sql);
			file_put($dfile, $sql);
			$fid = $fileid;
			msg('分卷 <strong>#'.$fileid++.'</strong> 替换成功 程序将自动继续...'.progress(0, $fid, $tid), '?file='.$file.'&action='.$action.'&file_pre='.$file_pre.'&fileid='.$fileid.'&tid='.$tid.'&file_from='.urlencode($file_from).'&file_to='.urlencode($file_to));
		} else {
			msg('文件内容替换成功', '?file='.$file.'&action=replace');
		}
	break;
	case 'open':
		if(!$dir) msg('请选择备份系列');
		if(!is_dir($D.$dir)) msg('备份系列不存在');
		$sql = $sqls = array();
		$sqlfiles = glob($D.$dir.'/*.sql');
		if(!$sqlfiles) msg('备份系列文件不存在');
		$tid = count($sqlfiles);
		foreach($sqlfiles as $id=>$sqlfile)	{
			$tmp = basename($sqlfile);
			$sql['filename'] = $tmp;
			$sql['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
			$sql['pre'] = $dir;
			$sql['number'] = str_replace('.sql', '', $tmp);
			$sql['mtime'] = timetodate(filemtime($sqlfile), 5);
			$sql['btime'] = substr(str_replace('.', ':', $dir), 0, -3);
			$sqls[$sql['number']] = $sql;
		}
		include tpl('database_open');
	break;
	case 'fields':
		(isset($table) && $table) or exit;
		$fields_select = '';
		$result = $db->query("SHOW COLUMNS FROM `$table`");
		while($r = $db->fetch_array($result)) {
			$fields_select .= '<option value="'.$r['Field'].'">'.$r['Field'].'</option>';
		}
		echo '<select name="fields" id="fd"><option value="">选择字段</option>'.$fields_select.'</select>';
		exit;
	break;
	case 'import':
		if(isset($import)) {
			if(isset($filename) && $filename && file_ext($filename) == 'sql') {
				$dfile = $D.$filename;
				if(!is_file($dfile)) msg('文件不存在，请检查');
				$sql = file_get($dfile);
				sql_execute($sql);
				msg($filename.' 导入成功', '?file='.$file.'&action=import');
			} else {
				$fileid = isset($fileid) ? $fileid : 1;
				$tid = isset($tid) ? intval($tid) : 0;
				$filename = is_dir($D.$filepre) ? $filepre.'/'.$fileid : $filepre.$fileid;
				$filename = $D.$filename.'.sql';
				if(is_file($filename)) {
					$sql = file_get($filename);
					if(substr($sql, 0, 11) == '# DESTOON V') {
						$v = substr($sql, 11, 3);
						if(DT_VERSION != $v) msg('由于数据结构存在差异，备份数据不可以跨版本导入<br/>备份版本：V'.$v.'<br/>当前系统：V'.DT_VERSION);
					}
					sql_execute($sql);
					$prog = $tid ? progress(1, $fileid, $tid) : '';
					msg('分卷 <strong>#'.$fileid++.'</strong> 导入成功 程序将自动继续...'.$prog, '?file='.$file.'&action='.$action.'&filepre='.$filepre.'&fileid='.$fileid.'&tid='.$tid.'&import=1');
				} else {
					msg('数据库恢复成功', '?file='.$file.'&action=import');
				}
			}
		} else {
			$dbak = $dbaks = $dsql = $dsqls = $sql = $sqls = array();
			$sqlfiles = glob($D.'*');
			if(is_array($sqlfiles)) {
				$class = 1;
				foreach($sqlfiles as $id=>$sqlfile)	{
					$tmp = basename($sqlfile);
					if(is_dir($sqlfile)) {
						$dbak['filename'] = $tmp;
						$size = $number = 0;
						$ss = glob($D.$tmp.'/*.sql');
						foreach($ss as $s) {
							$size += filesize($s);
							$number++;
						}
						$dbak['filesize'] = round($size/(1024*1024), 2);
						$dbak['pre'] = $tmp;
						$dbak['number'] = $number;
						$dbak['mtime'] = str_replace('.', ':', substr($tmp,	0, 19));
						$dbak['btime'] = substr($dbak['mtime'], 0, -3);
						$dbaks[] = $dbak;
					} else {
						if(preg_match("/([a-z0-9_]+_[0-9]{8}_[0-9a-z]{8}_)([0-9]+)\.sql/i", $tmp, $num)) {
							$dsql['filename'] = $tmp;
							$dsql['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
							$dsql['pre'] = $num[1];
							$dsql['number'] = $num[2];
							$dsql['mtime'] = timetodate(filemtime($sqlfile), 5);	if(preg_match("/[a-z0-9_]+_([0-9]{4})([0-9]{2})([0-9]{2})_([0-9]{2})([0-9]{2})([0-9a-z]{4})_/i", $num[1], $tm)) {
								$dsql['btime'] = $tm[1].'-'.$tm[2].'-'.$tm[3].' '.$tm[4].':'.$tm[5];
							} else {
								$dsql['btime'] = $dsql['mtime'];
							}
							if($dsql['number'] == 1) $class = $class  ? 0 : 1;
							$dsql['class'] = $class;
							$dsqls[] = $dsql;
						} else {
							if(file_ext($tmp) != 'sql') continue;
							$sql['filename'] = $tmp;
							$sql['filesize'] = round(filesize($sqlfile)/(1024*1024),2);
							$sql['mtime'] = timetodate(filemtime($sqlfile), 5);
							$sqls[] = $sql;
						}
					}
				}
			}
		}
		if($dbaks) $dbaks = array_reverse($dbaks);
		include tpl('database_import');
	break;
	default:
		if(isset($backup)) {
			$fileid = isset($fileid) ? intval($fileid) : 1;
			$sizelimit = $sizelimit ? intval($sizelimit) : 2048;
			if($fileid == 1 && $tables) {
				if(!isset($tables) || !is_array($tables)) msg('请选择需要备份的表');
				$random = timetodate($DT_TIME, 'Y-m-d H.i.s').' '.strtolower(random(10));
				$tsize = 0;
				foreach($tables as $k=>$v) {
					$v = strip_sql($v, 0);
					$tables[$k] = $v;
					$tsize += $sizes[$v];
				}
				$tid = ceil($tsize*1024/$sizelimit);
				cache_write($_username.'_backup.php', $tables);
			} else {
				if(!$tables = cache_read($_username.'_backup.php')) msg('请选择需要备份的表');
			}
			$dumpcharset = $sqlcharset ? $sqlcharset : $CFG['db_charset'];
			$setnames = ($sqlcharset && $db->version() > '4.1' && (!$sqlcompat || $sqlcompat == 'MYSQL41')) ? "SET NAMES '$dumpcharset';\n\n" : '';
			if($db->version() > '4.1') {
				if($sqlcharset) $db->query("SET NAMES '".$sqlcharset."';\n\n");
				if($sqlcompat == 'MYSQL40')	{
					$db->query("SET SQL_MODE='MYSQL40'");
				} else if($sqlcompat == 'MYSQL41') {
					$db->query("SET SQL_MODE=''");
				}
			}
			$sqldump = '';
			$tableid = isset($tableid) ? $tableid - 1 : 0;
			$startfrom = isset($startfrom) ? intval($startfrom) : 0;
			$tablenumber = count($tables);
			for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++) {
				$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			if(trim($sqldump)) {
				$sqldump = "# DESTOON V".DT_VERSION." R".DT_RELEASE." https://www.destoon.com\n# ".timetodate($DT_TIME, 6)."\n# --------------------------------------------------------\n\n\n".$sqldump;
				$tableid = $i;
				$filename = $random.'/'.$fileid.'.sql';
				file_put($D.$filename, $sqldump);
				$fid = $fileid;
				msg('分卷 <strong>#'.$fileid++.'</strong> 备份成功.. 程序将自动继续...'.progress(0, $fid, $tid), '?file='.$file.'&sizelimit='.$sizelimit.'&sqlcompat='.$sqlcompat.'&sqlcharset='.$sqlcharset.'&tableid='.$tableid.'&fileid='.$fileid.'&fileid='.$fileid.'&tid='.$tid.'&startfrom='.$startrow.'&random='.$random.'&backup=1');
			} else {
			   cache_delete($_username.'_backup.php');
			   $db->query("DELETE FROM {$DT_PRE}setting WHERE item='destoon' AND item_key='backtime'");
			   $db->query("INSERT INTO {$DT_PRE}setting (item,item_key,item_value) VALUES('destoon','backtime','$DT_TIME')");
			   msg('数据库备份成功', '?file='.$file.'&action=import');
			}
		} else {
			$dtables = $tables = $C = $T = $S = array();
			$i = $j = $dtotalsize = $totalsize = 0;
			$result = $db->query("SHOW TABLES FROM `".$CFG['db_name']."`");
			while($r = $db->fetch_row($result)) {
				if(!$r[0]) continue;
				$T[$r[0]] = $r[0];
			}
			uksort($T, 'strnatcasecmp');
			$result = $db->query("SHOW TABLE STATUS FROM `".$CFG['db_name']."`");
			while($r = $db->fetch_array($result)) {
				$S[$r['Name']] = $r;
			}
			foreach($T as $t) {
				$r = $S[$t];
				if(preg_match('/^'.$DT_PRE.'/', $t)) {
					$dtables[$i]['name'] = $r['Name'];
					$dtables[$i]['rows'] = $r['Rows'];
					$dtables[$i]['size'] = round($r['Data_length']/1024/1024, 2);
					$dtables[$i]['index'] = round($r['Index_length']/1024/1024, 2);
					$dtables[$i]['tsize'] = $dtables[$i]['size']+$dtables[$i]['index'];
					$dtables[$i]['auto'] = $r['Auto_increment'];
					$dtables[$i]['updatetime'] = $r['Update_time'];
					$dtables[$i]['note'] = $r['Comment'];
					$dtables[$i]['chip'] = $r['Data_free'];
					$dtotalsize += $r['Data_length']+$r['Index_length'];
					$C[str_replace($DT_PRE, '', $r['Name'])] = $r['Comment'];
					$i++;
				} else {
					$tables[$j]['name'] = $r['Name'];
					$tables[$j]['rows'] = $r['Rows'];
					$tables[$j]['size'] = round($r['Data_length']/1024/1024, 2);
					$tables[$j]['index'] = round($r['Index_length']/1024/1024, 2);
					$tables[$j]['tsize'] = $tables[$j]['size']+$tables[$j]['index'];
					$tables[$j]['auto'] = $r['Auto_increment'];
					$tables[$j]['updatetime'] = $r['Update_time'];
					$tables[$j]['note'] = $r['Comment'];
					$tables[$j]['chip'] = $r['Data_free'];
					$totalsize += $r['Data_length']+$r['Index_length'];
					$j++;
				}
			}
			//cache_write('table-comment.php', $C);
			$dtotalsize = round($dtotalsize/1024/1024, 2);
			$totalsize = round($totalsize/1024/1024, 2);
			include tpl('database');
		}
	break;
}
?>