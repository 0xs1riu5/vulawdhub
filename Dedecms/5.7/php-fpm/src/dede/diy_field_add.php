<?php
/**
 * 增加自定义表单字段
 *
 * @version        $Id: diy_field_add.php 1 18:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");

//增加权限检查
require_once(DEDEADMIN.'/inc/inc_admin_channel.php');
if(empty($action)) $action = '';

$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".",trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
/*----------------------
function Save()
---------------------*/
if($action=='save')
{
    //模型信息
    $fieldname = strtolower($fieldname);
    $row = $dsql->GetOne("SELECT `table`,`info` FROM #@__diyforms WHERE diyid='$diyid'");
    $fieldset = $row['info'];
    require_once(DEDEINC."/dedetag.class.php");
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace("field","<",">");
    $dtp->LoadSource($fieldset);
    $trueTable = $row['table'];

    //修改字段配置信息
    $dfvalue = trim($vdefault);
    $isnull = ($isnull==1 ? "true" : "false");
    $mxlen = $maxlength;

    //检测被修改的字段类型
    $fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
    $ntabsql = $fieldinfos[0];
    $buideType = $fieldinfos[1];

    $rs = $dsql->ExecuteNoneQuery(" ALTER TABLE `$trueTable` ADD  $ntabsql ");

    if(!$rs)
    {
        $gerr = $dsql->GetError();
        ShowMsg("增加字段失败，错误提示为：".$gerr,"javascript:;");
        exit();
    }
    $ok = FALSE;

    //检测旧配置信息，并替换为新配置
    if(is_array($dtp->CTags))
    {
        //遍历旧配置
        foreach($dtp->CTags as $tagid=>$ctag)
        {
            if($fieldname == strtolower($ctag->GetName()))
            {
                $dtp->Assign($tagid,stripslashes($fieldstring), FALSE);
                $ok = TRUE;
                break;
            }
        }
        $oksetting = $ok ? $dtp->GetResultNP() : $fieldset."\n".stripslashes($fieldstring);
    }
    else
    {
        //原来的配置为空
        $oksetting = $fieldset."\n".stripslashes($fieldstring);
    }
    $addlist = GetAddFieldList($dtp,$oksetting);
    $oksetting = addslashes($oksetting);
    $rs = $dsql->ExecuteNoneQuery("Update #@__diyforms set `info`='$oksetting' where diyid='$diyid' ");
    if(!$rs)
    {
        $grr = $dsql->GetError();
        ShowMsg("保存节点配置出错！".$grr,"javascript:;");
        exit();
    }
    ShowMsg("成功增加一个字段！","diy_edit.php?diyid=$diyid");
    exit();
}

//检测模型相关信息，并初始化相关数据
/*----------------------
function ShowPage()
---------------------*/
$row = $dsql->GetOne("SELECT `table` FROM #@__diyforms WHERE diyid='$diyid'");
$trueTable = $row['table'];
$tabsql = "CREATE TABLE IF NOT EXISTS  `$trueTable`(
`id` int(10) unsigned NOT NULL auto_increment,
`ifcheck` tinyint(1) NOT NULL default '0',
";
if($mysql_version < 4.1)
{
    $tabsql .= " PRIMARY KEY  (`id`)\r\n) TYPE=MyISAM; ";
}
else
{
    $tabsql .= " PRIMARY KEY  (`id`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
}
$dsql->ExecuteNoneQuery($tabsql);

//检测附加表里含有的字段
$fields = array();
$rs = $dsql->SetQuery("show fields from `$trueTable`");
$dsql->Execute('a');
while($nrow = $dsql->GetArray('a',MYSQL_ASSOC))
{
    $fields[strtolower($nrow['Field'])] = 1;
}
$f = '';
foreach($fields as $k=>$v)
{
    $f .= ($f=='' ? $k : ' '.$k);
}
require_once(DEDEADMIN."/templets/diy_field_add.htm");