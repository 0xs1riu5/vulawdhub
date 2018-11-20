<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：database.php
 * $author：lucks
 */ 
define('IN_BLUE', true);
require dirname(__FILE__) . '/include/common.inc.php';
require dirname(__FILE__) . '/include/database.fun.php';
$act = !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'backup';

if($act == 'backup')
{
	$pre = str_replace('_', '\_', $pre);
 	$database_list = $db->getall("SHOW TABLES LIKE '$pre%'", MYSQL_NUM);
 	template_assign(
		array(
			'database_list', 
			'act', 
			'current_act'
		), 
		array(
			$database_list, 
			$act, 
			'数据库备份'
		)
	);
 	$smarty->display('database.htm');
}
elseif ($act == 'do_backup')
{
	if (!is_writable(BLUE_ROOT.DATA."backup/"))
	{
 		showmsg('备份文件存放目录data/backup不可写');
 	}
 	$limit_size = !empty($_POST['limit_size']) ? intval($_POST['limit_size']) : '2048';
	$mysql_type = !empty($_POST['mysql_type']) ? trim($_POST['mysql_type']) : '';
 	$file = date("Ymd", time()).'.sql';
 	$version = BLUE_VERSION;
	$db_version = $db->dbversion();
	$add_time = date("Y-m-d H:i:s");
	$sql .= "--BlueCMS VERSION:".$version."\r\n".
			"--Mysql VERSION:".$db_version."\r\n".
			"--Create time:".$add_time."\r\n";
 	$num = 1;
	if($_POST['tables'])
	{
		foreach($_POST['tables'] as $val)
		{
			$sql .= write_head($val);
			if ($mysql_type == 'mysql40' && $db_version > 4.0)
			{
				$sql = preg_replace('/ENGINE=MyISAM(.*)/','TYPE=MyISAM', $sql); 
			}
			elseif($mysql_type == 'mysql41' && $db_version < 4.1)
			{
				$sql = preg_replace('/TYPE=MyISAM/', 'EMGINE=MyISAM DEFAULT CHARSET='.BLUE_CHARSET, $sql);
			}

			$row = $db->getone("SELECT COUNT(*) AS num FROM ".$val);
			if($row['num'] > 0)
			{
				$sql .= write_data($val);
			}
			if (strlen($sql) >= $limit_size * 1000)
			{
				$file = date("Ymd", time()).'_'.$num.'.sql';
				if (!write_file(BLUE_ROOT.DATA.'backup/'.$file, $sql))
				{
					showmsg('备份数据库卷-'.$num.'失败');
				}
				else
				{
					$msg .= '生成备份文件 '.$file.' 成功<br/>';
				}
				$num++;
				$file = date("Ymd", time());
				$sql = '';
			}
 		}
	}
	else
	{
		showmsg('您没有选择备份的表');
	}
	
	if ($sql != '' && $num != 1)
	{
		$file .= '_'.$num.'.sql';
		$msg .= '生成备份文件 '.$file.' 成功<br/>';
	}
	else
	{
		$file .= '';
		$msg = '生成备份文件 '.$file.' 成功';
	}
 	if(write_file(BLUE_ROOT.DATA.'backup/'.$file, $sql))
	{
 		showmsg('备份完成<br/>'.$msg);
 	}
	else
	{
 		showmsg('备份数据库出错');
 	}
 }
elseif($act == 'restore')
{
	$data_backup_list = $file_info = array();
 	$dir = opendir(BLUE_ROOT.DATA.'/backup');
 	while($file = readdir($dir))
	{
 		if(strpos($file,'.sql')!==false && !in_array(substr($file, 0, 8).'.sql', $data_backup_list))
		{
 			$data_backup_list[] = substr($file, 0, 8).'.sql';
 		}
 	}
 	foreach($data_backup_list as $key => $file)
	{
		if (file_exists(BLUE_ROOT.DATA.'backup/'.$file))
		{
 			$head_arr = get_head(BLUE_ROOT.DATA.'backup/'.$file);
			$file_info[$key]['file_size'] = filesize(BLUE_ROOT.DATA.'backup/'.$file);
		}
		else
		{
			$head_arr = get_head(BLUE_ROOT.DATA.'backup/'.substr($file, 0, 8).'_1.sql');
			$i = 1;
			while (file_exists(BLUE_ROOT.DATA.'backup/'.substr($file, 0, 8).'_'.$i.'.sql'))
			{
				$file_info[$key]['file_size'] += filesize(BLUE_ROOT.DATA.'backup/'.substr($file, 0, 8).'_'.$i.'.sql');
				$i++;
			}
		}
 		$file_info[$key]['file_name'] = substr($file,0);
 		$file_info[$key]['bluecms_ver'] = $head_arr['bluecms_ver'];
 		$file_info[$key]['mysql_ver'] = $head_arr['mysql_ver'];
 		$file_info[$key]['add_time'] = $head_arr['add_time'];
 		
 	}
 	template_assign(
		array(
			'file_info', 
			'act', 
			'current_act'
		), 
		array(
			$file_info, 
			$act, 
			'数据库还原'
		)
	);
 	$smarty->display('database.htm');
}
elseif($act == 'import')
{
	$file_name = !empty($_GET['file_name']) ? trim($_GET['file_name']) : '';
	if (file_exists(BLUE_ROOT.DATA.'backup/'.$file_name))
	{
		$backup_file[] = $file_name;
	}
	elseif(file_exists(BLUE_ROOT.DATA.'backup/'.substr($file_name, 0, 8).'_1.sql'))
	{
		$file_name = substr($file_name, 0, 8);
		$i = 2;
		$backup_file[] = $file_name.'_1.sql';
		while (@file_exists(BLUE_ROOT.DATA.'backup/'.$file_name.'_'.$i.'.sql'))
		{
			$backup_file[] = $file_name.'_'.$i.'.sql';
			$i++;
		}
	}
	else
	{
		showmsg('请检查该备份文件是否存在');
	}
	
 	$file = BLUE_ROOT.DATA."backup/".$backup_file[0];
 	$file_info = get_head($file);

	if($file_info['bluecms_ver'] != BLUE_VERSION)
	{
		showmsg('BlueCMS当前程序与备份程序版本不一致');
	}
	foreach ($backup_file as $file)
	{
		$file = BLUE_ROOT.DATA.'backup/'.$file;
		$file = array_filter(file($file), 'remove_comment');
		$file = str_replace("\r", "\n", implode('', $file));
		$arr = explode(";\n", trim($file));
		$arr_count = count($arr);
		for($i = 0; $i < $arr_count; $i++)
		{
			$arr[$i] = trim($arr[$i]);
			if (!empty($arr[$i]))
			{
				if ((strpos($arr[$i], 'CREATE TABLE') !== false) && (strpos($arr[$i], 'DEFAULT CHARSET='.		str_replace('-', '', BLUE_CHARSET) )!== false))
				{
					$arr[$i] = str_replace('DEFAULT CHARSET='. str_replace('-', '', BLUE_CHARSET), '', $arr[$i]);
				}
				$db->query($arr[$i]);
			}
		}
	}
    showmsg('还原数据库成功');
}
elseif($act == 'del')
{
 	$file_name = !empty($_GET['file_name']) ? trim($_GET['file_name']) : '';
	$file = BLUE_ROOT.DATA."backup/".$file_name;
	if(!@unlink($file))
	{
		showmsg('删除备份文件失败');
	}
	else
	{
		showmsg('删除备份文件成功', 'database.php?act=restore');
	}
 }

?>