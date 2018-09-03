<?php
/**
 * 会员模型管理
 *
 * @version        $Id: member_model_add.php 1 11:17 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".", trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
if(empty($action))
{
    $row = $dsql->GetOne("SELECT id FROM #@__member_model ORDER BY id DESC LIMIT 0,1 ");
    if(is_array($row)) $newid = $row['id']+1;
    else $newid = 1;
    include(DEDEADMIN."/templets/member_model_add.htm");
} else {
    if(preg_match("#[^0-9-]#", $id) || empty($id))
    {
        ShowMsg("<font color=red>'会员模型ID'</font>必须为数字！","-1");
        exit();
    }
    if($table=="")
    {
        ShowMsg("表名不能为空！","-1");
        exit();
    }
    $state = isset($state) && is_numeric($state) ? $state : 0;
    $name = htmlspecialchars($name);
    $row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$id' OR `table` LIKE '$table' OR name LIKE '$name' ");
    if(is_array($row))
    {
        ShowMsg("可能会员模型的‘ID’、‘名称’在数据库中已存在，不能重复使用！","-1");
        exit();
    }
    $query = "SHOW TABLES FROM {$dsql->dbName} ";
    $dsql->SetQuery($query);
    $dsql->Execute();
    while($row = $dsql->GetArray())
    {
        if(empty($row[0])) $row[0] = '';
        if($table == $row[0])
        {
            ShowMsg('指定的表在数据库中重复', '-1');
            exit();
        }
    }
    $sql = "CREATE TABLE IF NOT EXISTS  `$table`(
    `mid` int(10) unsigned NOT NULL auto_increment,
    ";
    if($mysql_version < 4.1){
        $sql .= " PRIMARY KEY  (`mid`)\r\n) TYPE=MyISAM; ";
    }else{
        $sql .= " PRIMARY KEY  (`mid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
    }
    if($dsql->ExecNoneQuery($sql)){
        $query = "INSERT INTO #@__member_model (`id`, `name`, `table`, `description`, `issystem`, `state`) VALUES ('$id', '$name', '$table', '$description', 0, '$state')";
        $dsql->ExecNoneQuery($query);
        //更新会员模型缓存
        UpDateMemberModCache();
        ShowMsg('会员模型创建成功，请自行添加字段', 'member_model_main.php');
    }else{
        ShowMsg('会员模型创建失败', '-1');
    }
}