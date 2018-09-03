<?php
/**
 * 会员模型字段编辑
 *
 * @version        $Id: member_model_field_edit.php 1 11:24 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");

//增加权限检查

require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEADMIN."/inc/inc_admin_channel.php");
if(empty($action)) $action = '';

//获取模型信息
$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".", trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
$row = $dsql->GetOne("SELECT `table`,`info` FROM #@__member_model WHERE id='$id'");
$fieldset = $row['info'];
$trueTable = $row['table'];
$dtp = new DedeTagParse();
$dtp->SetNameSpace("field", "<",">");
$dtp->LoadSource($fieldset);
foreach($dtp->CTags as $ctag)
{
    if(strtolower($ctag->GetName())==strtolower($fname)) break;
}

//字段类型信息
$ds = file(DEDEADMIN."/inc/fieldtype.txt");
foreach($ds as $d)
{
    $dds = explode(',',trim($d));
    $fieldtypes[$dds[0]] = $dds[1];
}

//保存更改
/*--------------------
function _SAVE()
----------------------*/
if($action=='save')
{
    if(!isset($fieldtypes[$dtype]))
    {
        ShowMsg("你修改的是系统专用类型的数据，禁止操作！","-1");
        exit();
    }

    //检测数据库是否存在附加表，不存在则新建一个
    $tabsql = "CREATE TABLE IF NOT EXISTS  `$trueTable`(
    `mid` int(10) unsigned NOT NULL auto_increment,
    ";
    if($mysql_version < 4.1)
    {
        $tabsql .= " PRIMARY KEY  (`mid`)\r\n) TYPE=MyISAM; ";
    }
    else
    {
        $tabsql .= " PRIMARY KEY  (`mid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
    }
    
    //检测表里含有的字段
    $fields = array();
    $rs = $dsql->SetQuery("SHOW fields FROM `$trueTable`");
    $dsql->Execute('a');
    while($nrow = $dsql->GetArray('a',MYSQL_ASSOC))
    {
        $fields[ strtolower($nrow['Field']) ] = $nrow['Type'];
    }

    //修改字段配置信息
    $dfvalue = $vdefault;
    $isnull = "true";
    $mxlen = $maxlength;
    $fieldname = strtolower($fname);

    //检测被修改的字段类型，并更新数据表
    $fieldinfos = GetFieldMake($dtype, $fieldname, $dfvalue, $mxlen);
    $ntabsql = $fieldinfos[0];
    $buideType = $fieldinfos[1];
    $tabsql  = '';

    //检测旧数据类型，并替换为新配置
    foreach($dtp->CTags as $tagid=>$ctag)
    {
        if(trim($fieldname)==trim(strtolower($ctag->GetName())))
        {

            if(isset($fields[$fieldname]) && $fields[$fieldname]!=$buideType)
            {
                $tabsql = "ALTER TABLE `$trueTable` CHANGE `$fieldname` ".$ntabsql;
                $dsql->ExecuteNoneQuery($tabsql);
            }
            else if(!isset($fields[$fieldname]))
            {
                $tabsql = "ALTER TABLE `$trueTable` ADD ".$ntabsql;
                $dsql->ExecuteNoneQuery($tabsql);
            }
            else
            {
                $tabsql = '';
            }
            $dtp->Assign($tagid,stripslashes($fieldstring),false);
            break;
        }
    }
    $oksetting = $dtp->GetResultNP();

    $oksetting = addslashes($oksetting);
    $dsql->ExecuteNoneQuery("UPDATE #@__member_model SET info='$oksetting' WHERE id='$id' ");
    ShowMsg("成功更改一个字段的配置！","member_model_edit.php?id={$id}");
    exit();
}
/*----------------
禁用字段
function __Disabled()
-----------------*/
else if($action=="disabled")
{
    foreach($dtp->CTags as $tagid=>$ctag)
    {
        if(strtolower($ctag->GetName())==strtolower($fname))
        {
            $statenum = ($ctag->GetAtt('state')==1)? 0 : 1;
            $fieldstring = "<field:{$ctag->GetName()} itemname=\"{$ctag->GetAtt('itemname')}\" autofield=\"{$ctag->GetAtt('autofield')}\" type=\"{$ctag->GetAtt('type')}\" isnull=\"{$ctag->GetAtt('isnull')}\" default=\"{$ctag->GetAtt('default')}\"  maxlength=\"{$ctag->GetAtt('maxlength')}\" issearch=\"{$ctag->GetAtt('issearch')}\" isshow=\"{$ctag->GetAtt('isshow')}\" state=\"{$statenum}\">\r\n";
            $fieldstring .= "</field:{$ctag->GetName()}>";
            $dtp->Assign($tagid,stripslashes($fieldstring),false);
        }
    }
    $oksetting = addslashes($dtp->GetResultNP());
    //echo $oksetting;exit();
    $dsql->ExecuteNoneQuery("UPDATE #@__member_model SET info='$oksetting' WHERE id='$id' ");
    ShowMsg("成功禁用一个字段！","member_model_edit.php?id={$id}");
    exit();
}
/*------------------
删除字段
function _DELETE()
-------------------*/
else if($action=="delete")
{
    //检测旧数据类型，并替换为新配置
    foreach($dtp->CTags as $tagid=>$ctag)
    {
        if(strtolower($ctag->GetName())==strtolower($fname))
        {
            $dtp->Assign($tagid,"#@Delete@#");
        }
    }
    $oksetting = addslashes($dtp->GetResultNP());
    $dsql->ExecuteNoneQuery("Update #@__member_model set info='$oksetting' where id='$id' ");
    $dsql->ExecuteNoneQuery("ALTER TABLE `$trueTable` DROP `$fname` ");
    ShowMsg("成功删除一个字段！","member_model_edit.php?id={$id}");
    exit();
}
require_once(DEDEADMIN."/templets/member_model_field_edit.htm");