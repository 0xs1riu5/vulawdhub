<?php
/**
 * 增加自定义表单
 *
 * @version        $Id: diy_add.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".",trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
if(empty($action))
{
    $row = $dsql->GetOne("SELECT diyid FROM #@__diyforms ORDER BY diyid DESC LIMIT 0,1 ");
    if(is_array($row)) $newdiyid = $row['diyid']+1;
    else $newdiyid = 1;
    include(DEDEADMIN."/templets/diy_add.htm");
}
else
{
    if(preg_match("#[^0-9-]#", $diyid) || empty($diyid))
    {
        ShowMsg("<font color=red>'自定义表单diyid'</font>必须为数字！","-1");
        exit();
    }
    if($table=="")
    {
        ShowMsg("表名不能为空！", "-1");
        exit();
    }
    $public = isset($public) && is_numeric($public) ? $public : 0;
    $name = htmlspecialchars($name);
    $row = $dsql->GetOne("SELECT * FROM #@__diyforms WHERE diyid='$diyid' OR `table` LIKE '$table' OR name LIKE '$name' ");
    if(is_array($row))
    {
        ShowMsg("可能自定义表单的‘diyid’、‘名称’在数据库中已存在，不能重复使用！","-1");
        exit();
    }
    $query = "SHOW TABLES FROM {$dsql->dbName} ";
    $dsql->SetQuery($query);
    $dsql->Execute();
    while($row = $dsql->getarray())
    {
        if(empty($row[0])) $row[0] = '';
        if($table == $row[0])
        {
            showmsg('指定的表在数据库中重复', '-1');
            exit();
        }
    }
    $sql = "CREATE TABLE IF NOT EXISTS  `$table`(
    `id` int(10) unsigned NOT NULL auto_increment,
    `ifcheck` tinyint(1) NOT NULL default '0',
    ";
    if($mysql_version < 4.1)
    {
        $sql .= " PRIMARY KEY  (`id`)\r\n) TYPE=MyISAM; ";
    }
    else
    {
        $sql .= " PRIMARY KEY  (`id`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
    }
    if($dsql->ExecuteNoneQuery($sql))
    {
        $query = "INSERT INTO #@__diyforms (`diyid`, `name`, `table`, `info`, `listtemplate`, `viewtemplate`, `posttemplate`, `public` ) VALUES ('$diyid', '$name', '$table', '', '$listtemplate', '$viewtemplate', '$posttemplate', '$public')";
        $dsql->ExecuteNoneQuery($query);
        showmsg('自定义表单创建成功，请自行添加字段', 'diy_main.php');
    }
    else
    {
        showmsg('自定义表单创建失败', '-1');
    }
}