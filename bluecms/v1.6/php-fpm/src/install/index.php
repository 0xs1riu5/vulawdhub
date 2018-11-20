<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：index.php
 * $author：lucks
 */
 define('IN_BLUE', true);
 require_once(dirname(__FILE__) . '/include/common.inc.php');

 if(file_exists(BLUE_ROOT.'data/install.lock')){
 	install_showmsg('您已经安装过本系统，如果想重新安装，请删除data目录下install.lock文件', '../index.php');
 }
 $act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'step1';

 if($act == 'step1'){
 	$install_smarty->display('step1.htm');
 }
 elseif($act == 'step2'){
 	$system_info = array();
    $system_info['version'] = BLUE_VERSION;
    $system_info['os'] = PHP_OS;
    $system_info['ip'] = $_SERVER['SERVER_ADDR'];
    $system_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
    $system_info['php_ver'] = PHP_VERSION;
    $system_info['max_filesize'] = ini_get('upload_max_filesize');

 	$dir_check = check_dirs($need_check_dirs);
 	install_template_assign(array('dir_check', 'system_info'), array($dir_check, $system_info));
 	$install_smarty->display('step2.htm');
 }
 elseif($act == 'step3'){
 	$cookie_hash = chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('a'),ord('z'))).chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('A'),ord('Z'))).chr(mt_rand(ord('a'),ord('z'))).mt_rand(1,9999).chr(mt_rand(ord('A'),ord('Z')));
 	install_template_assign(array('cookie_hash'),array($cookie_hash));
 	$install_smarty->display('step3.htm');
 }
 elseif($act == 'step4'){
 	$dbhost = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : '';
 	$dbname = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
 	$dbuser = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
 	$dbpass = isset($_POST['dbpass']) ? trim($_POST['dbpass']) : '';
 	$pre  = isset($_POST['pre']) ? trim($_POST['pre']) : 'blue_';
 	$admin_name = isset($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
    $admin_pwd = isset($_POST['admin_pwd']) ? trim($_POST['admin_pwd']) : '';
    $admin_pwd1 = isset($_POST['admin_pwd1']) ? trim($_POST['admin_pwd1']) : '';
    $admin_email = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : '';
    $cookie_hash = isset($_POST['cookie_hash']) ? trim($_POST['cookie_hash']) : '';

    if($dbhost == '' || $dbname == ''|| $dbuser == ''|| $admin_name == ''|| $admin_pwd == '' || $admin_pwd1 == '' || $admin_email == ''){
    	install_showmsg('您填写的信息不完整，请核对');
    }
    if($admin_pwd != $admin_pwd1){
    	install_showmsg('您两次输入的密码不一致');
    }

	if(!$db = @mysql_connect($dbhost, $dbuser, $dbpass)){
		install_showmsg('连接数据库错误，请核对信息是否正确');
	}
	mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbname."`;",$db);
	$mysql_version = mysql_get_server_info($db);
	if(!mysql_select_db($dbname)){
		install_showmsg('选择数据库错误，请检查是否拥有权限或存在此数据库');
	}
	mysql_query("SET NAMES '".BLUE_CHARSET."',character_set_client=binary,sql_mode='';",$db);

	$content = '<?'."php\n";
    $content .= "\$dbhost   = \"$dbhost\";\n\n";
    $content .= "\$dbname   = \"$dbname\";\n\n";
    $content .= "\$dbuser   = \"$dbuser\";\n\n";
    $content .= "\$dbpass   = \"$dbpass\";\n\n";
    $content .= "\$pre    = \"$pre\";\n\n";
	$content .= "\$cookiedomain = '';\n\n";
	$content .= "\$cookiepath = '/';\n\n";
    $content .= "define('BLUE_CHARSET','".BLUE_CHARSET."');\n\n";
    $content .= "define('BLUE_VERSION','".BLUE_VERSION."');\n\n";
    $content .= '?>';

    $fp = @fopen(BLUE_ROOT . 'data/config.php', 'wb+');
    if (!$fp){
        install_showmsg('打开配置文件失败');
    }
    if (!@fwrite($fp, trim($content))){
    	install_showmsg('写入配置文件失败');
    }
    @fclose($fp);

	$fp = @fopen(BLUE_ROOT . 'data/update_log.txt', 'wb+');
	if(!$fp){
		install_showmsg('打开更新日志文件失败');
	}
	if(!@fwrite($fp, BLUE_UPDATE_NO)){
		install_showmsg('写入更新文件失败');
	}
	@fclose($fp);

    if(!$fp = @fopen(dirname(__FILE__).'/sql-structure.sql','rb')){
		install_showmsg('打开文件sql-structure.sql出错，请检查文件是否存在');
	}
    $query = '';
    while(!feof($fp))
    {
		$line = rtrim(fgets($fp,1024));
		if(preg_match('/;$/',$line))
		{
			$query .= $line."\n";
			$query = str_replace('blue_',$pre,$query);
			if ( $mysql_version > 4.1 )
			{
				mysql_query(str_replace("TYPE=MyISAM", "ENGINE=MyISAM  DEFAULT CHARSET=gbk",  $query), $db);
			}
			else
			{
				mysql_query($query, $db);
			}
			$query='';
		 }
		 else if(!ereg('/^(//|--)/',$line))
		 {
		 	$query .= $line;
		 }
	}
	@fclose($fp);

	$query = '';
	if(!$fp = @fopen(dirname(__FILE__).'/sql-data.sql','rb')){
		install_showmsg('打开文件sql-data.sql出错，请检查文件是否存在');
	}
	while(!feof($fp)){
		 $line = rtrim(fgets($fp,1024));
		 if(ereg(";$",$line)){
		 	$query .= $line;
			$query = str_replace('blue_',$pre,$query);
			mysql_query($query,$db);
			$query='';
		 }
		 else if(!ereg("^(//|--)",$line)){
			$query .= $line;
		 }
	}
	fclose($fp);

	$site_url = "http://".$_SERVER['SERVER_NAME'].substr(dirname($php_self), 0, -8);
	mysql_query("UPDATE `".$pre."config` SET value = '".$cookie_hash."' WHERE name = 'cookie_hash'", $db);
	mysql_query("UPDATE `".$pre."config` SET value = '".$site_url."' WHERE name = 'site_url'", $db);
	mysql_query("INSERT INTO `".$pre."admin` (admin_id, admin_name, email, pwd, purview, add_time, last_login_time, last_login_ip) VALUES (1, '$admin_name', '$admin_email', md5('$admin_pwd'), 'all', '$timestamp', '$timestamp', '')", $db);
	mysql_query("INSERT INTO ".$pre."user (user_id, user_name, pwd, email, birthday, sex, money, face_pic, mobile_phone, home_phone, office_phone, qq, msn, address, reg_time, last_login_time, last_login_ip) VALUES (1, '$admin_name', md5('$admin_pwd'), '$admin_email', '0000-00-00', 0, '0.00', '', '', '', '', '', '', '', '$timestamp', '$timestamp', '')", $db);

	header('Location:index.php?act=step5');
 }
elseif($act == 'step5')
{
	define('IN_BLUE', TRUE);
	include dirname(__FILE__) . '/../include/common.inc.php';
	include BLUE_ROOT . 'admin/include/common.fun.php';
	update_data_cache();
	update_pay_cache();
	if(is_writable(BLUE_ROOT.'data/'))
	{
		$fp = @fopen(BLUE_ROOT.'data/install.lock', 'wb+');
		fwrite($fp, 'OK');
		fclose($fp);
	}
 	$install_smarty->display('step5.htm');
}

?>
