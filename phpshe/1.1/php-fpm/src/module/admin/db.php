<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0920 koyshe <koyshe@gmail.com>
 */
$menumark = 'db';
$back_path = "{$pe['path_root']}data/dbbackup/".date('Y-m-d@H-i-s')."/";
$table_list = $db->sql_selectall("show table status from `{$pe['db_name']}`");

$backup_list = pe_dirlist("{$pe['path_root']}data/dbbackup/*");
set_time_limit(0);
//导入数据
if ($act == 'import') {
	if ($_g_num == 'all') {
		if (is_file($sqlname = "{$pe['path_root']}data/dbbackup/{$_g_path}/db_all.sql")) {
			sql_import($sqlname) ? pe_success('数据导入完成！', 'admin.php?mod=db&act=backup') : pe_error('数据导入失败...', 'admin.php?mod=db&act=backup');
		}
	}
	else {
		if (is_file($sqlname = "{$pe['path_root']}data/dbbackup/{$_g_path}/db_v{$_g_num}.sql")) {
			$num = $_g_num + 1;
			sql_import($sqlname) ? pe_success('数据导入中请勿刷新！', "admin.php?mod=db&act=import&num={$num}&path={$_g_path}") : pe_error('数据导入失败...', 'admin.php?mod=db&act=backup');
		}
		else {
			pe_success('数据导入完成！', 'admin.php?mod=db&act=backup');
		}
	}
}
if (isset($_p_pebackup)) {//备份数据库
	$pe_cutsql = "/*#####################@ pe_cutsql @#####################*/\n";
	if (isset($_p_pebackup)) {//不分卷
		if ($_p_backup_cut && $_p_backup_where == 'down') pe_error('只有备份在服务器才可使用分卷功能...');
		if ($_p_backup_cut && !$_p_backup_cutsize) pe_error('使用分卷备份必须填写分卷文件大小...');
		if ($_p_backup_where == "server") {
			!is_dir($back_path) && mkdir($back_path, 0777, true);
			!is_writable($back_path) && pe_error("{$back_path} 目录没有写入权限...");
		}
		if (!$_p_backup_cut) {
			$sql_arr = array();
			foreach ($table_list as $v) {
				$sql_arr = array_merge($sql_arr, dosql($v['Name']));
			}
			$sql = implode($pe_cutsql, $sql_arr);
			if ($_p_backup_where == 'down') {
				down_file($sql, "db_all.sql");
			}
			elseif ($_p_backup_where == 'server') {
				if (file_put_contents("{$back_path}db_all.sql", $sql)) {
					pe_success("数据备份完成！");
				}
				else {
					pe_error("数据备份失败...");
				}
			}
		}
		else {
			$vnum = 1;
			$sql_arr = array();
			foreach ($table_list as $v) {
				$sql_arr = array_merge($sql_arr, dosql($v['Name']));
				$sql = implode($pe_cutsql, $sql_arr);
				if (strlen($sql) >= $_p_backup_cutsize * 1000) {
					file_put_contents("{$back_path}db_v{$vnum}.sql", $sql);
					$sql_arr = array();
					$vnum++;
				}
			}
			$sql && file_put_contents("{$back_path}db_v{$vnum}.sql", $sql);
			pe_success("数据分卷备份完成！");
		}
	}
}
elseif (isset($_p_peimport)) {//导入数据库
	if ($_p_import_server) {
		$db_list = pe_dirlist("{$pe['path_root']}data/dbbackup/{$_p_import_server}/*");
		($dbnum = count($db_list)) == 0 && pe_error("目录下没有有效的数据库文件");
		if ($dbnum > 1) {
			pe_goto("admin.php?mod=db&act=import&num=1&path={$_p_import_server}");
		}
		else {
			pe_goto("admin.php?mod=db&act=import&num=all&path={$_p_import_server}");
		}
	}
	else {
		pe_error("请选择需要导入的数据库目录...");
	}
}

function dosql($table)
{
	global $db;
	$info_create = $db->sql_select("show create table `{$table}`");
	$sql_arr[] = "DROP TABLE IF EXISTS `{$table}`;\n";
	$sql_arr[] = "{$info_create['Create Table']};\n";
	$data_num = $db->pe_num($table);
	for ($i = 0; $i < $data_num; $i = $i + 30) {
		$data_list = $db->pe_selectall($table, "limit {$i}, 30");
		$sql = "INSERT INTO `{$table}` VALUES";
		foreach ($data_list as $vv) {
			$sql .= "(";
			foreach ($vv as $vvv) {
				$sql .= "'".mysql_real_escape_string($vvv)."',";
			}
			$sql = trim($sql, ',')."),\n";
		}
		$sql = trim(trim($sql), ',').";\n";
		$sql_arr[] = $sql;
	}
	return $sql_arr;
}

function down_file($sql, $filename)
{
	ob_end_clean();
	header("Content-Encoding: none");
	header("Content-Type: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));	
	header("Content-Disposition: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ')."filename=".$filename);	
	header("Content-Length: ".strlen($sql));
	header("Pragma: no-cache");	
	header("Expires: 0");
	echo $sql;
	$e = ob_get_contents();
	ob_end_clean();
}

function sql_import($filename)
{
	global $db;
	$sql_arr = explode('/*#####################@ pe_cutsql @#####################*/', file_get_contents($filename));
	echo "<p style='color:red;text-align:center;margin-top:50px'>数据导入中...请勿刷新浏览器！<br/>当前执行路径：{$filename}</p>";
	foreach ($sql_arr as $v) {
		$result = $db->query(trim($v));
	}
	return result;
}

$seo = pe_seo($menutitle='数据安全', '', '', 'admin');
include(pe_tpl('db_list.html'));
?>