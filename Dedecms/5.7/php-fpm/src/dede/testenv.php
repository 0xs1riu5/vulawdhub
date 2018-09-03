<?php
/**
 * 系统目录权限检测与修正
 *
 * @version        $Id: testenv.php 1 23:44 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
if(empty($action)) $action = '';

$needDir = "$cfg_medias_dir|
$cfg_image_dir|
$ddcfg_image_dir|
$cfg_user_dir|
$cfg_soft_dir|
$cfg_other_medias|
$cfg_medias_dir/flink|
$cfg_cmspath/data|
$cfg_cmspath/data/$cfg_backup_dir|
$cfg_cmspath/data/textdata|
$cfg_cmspath/data/sessions|
$cfg_cmspath/data/tplcache|
$cfg_cmspath/data/admin|
$cfg_cmspath/data/enums|
$cfg_cmspath/data/mark|
$cfg_cmspath/data/module|
$cfg_cmspath/data/rss|
$cfg_special|
$cfg_cmspath$cfg_arcdir";
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
<title>系统目录权限检测与修正</title>
<link href='css/base.css' rel='stylesheet' type='text/css'>
</head>
<body background='images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#cfcfcf">
<tr>
    <td height="28" background="images/tbg.gif" style="padding-left:10px;">
    	<b>系统目录权限检测与修正</b>
    </td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" valign="top" style="padding:5px;">
<?php
echo '<meta http-equiv="Content-Type" content="text/html; charset='.$cfg_soft_lang.'">';
if(($isSafeMode || $cfg_ftp_mkdir=='Y') && $cfg_ftp_host=='')
{
    echo "由于你的站点的PHP配置存在限制，程序只能通过FTP形式进行目录操作，因此你必须在后台指定FTP相关的变量！<br>";
    echo "<a href='sys_info.php'>&lt;&lt;修改系统参数&gt;&gt;</a>";
    exit();
}
if($action=='')
{
    echo "本程序将检测下列目录是否存在，或者是否具有写入的权限，并尝试创建或更改：<br>";
    echo "（如果您的主机使用的是windows系统，您无需进行此操作）<br>";
    echo "'/include' 目录和 '当前目录/templets' 文件夹请你在FTP中手工更改权限为可写入(0777)<br>";
    echo "<pre>".str_replace('|','',$needDir)."</pre>";
    echo "</td></tr>\r\n<tr><td bgcolor='#F9FCEF' height='32px' style='padding-left:20px'>\r\n<a href='testenv.php?action=ok' class='np coolbg'>&lt;&lt;开始检测&gt;&gt;</a> &nbsp; <a href='index_body.php' class='np coolbg'>&lt;&lt;返回主页&gt;&gt;</a>";
}
else
{
    $needDirs = explode('|', $needDir);
    $needDir = '';
    foreach($needDirs as $needDir)
    {
        $needDir = trim($needDir);
        $needDir = str_replace("\\","/",$needDir);
        $needDir = preg_replace("#\/{1,}#", "/", $needDir);
        if(CreateDir($needDir))
        {
            echo "成功更改或创建：{$needDir} <br>";
        }
        else
        {
            echo "更改或创建目录：{$needDir} <font color='red'>失败！</font> <br>";
        }
    }
    echo "<br>如果发现更改或创建错误的项目，请<a href='testenv.php?action=ok&play=".time()."'><u>重试</u></a>或手动登陆到FTP更改相关目录的权限为777或666<br>";
    echo "</td></tr>\r\n<tr><td bgcolor='#F9FCEF' height='32px' style='padding-left:20px'>\r\n<a href='index_body.php' class='np coolbg'>&lt;&lt;返回主页&gt;&gt;</a>";
    CloseFtp();
}
?>
</td>
</tr>
</table>
</body>
</html>