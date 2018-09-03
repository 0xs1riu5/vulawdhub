<?php
/**
 * 自定义模型字段添加
 *
 * @version        $Id: mychannel_field_add.php 1 15:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
require_once(DEDEADMIN."/inc/inc_admin_channel.php");
require_once(DEDEINC."/dedetag.class.php");

if(empty($action)) $action = '';
$mysql_version = $dsql->GetVersion();

/*----------------------
function Save()
---------------------*/
if($action=='save')
{
    //修改字段配置信息
    $dfvalue = trim($vdefault);
    $isnull = ($isnull==1 ? "true" : "false");
    $mxlen = $maxlength;
    
    if(preg_match("#^(select|radio|checkbox)$#i", $dtype))
    {
        if(!preg_match("#,#", $dfvalue))
        {
            ShowMsg("你设定了字段为 {$dtype} 类型，必须在默认值中指定元素列表，如：'a,b,c' ","-1");
            exit();
        }
    }
    
    if($dtype=='stepselect')
    {
        $arr = $dsql->GetOne("SELECT * FROM `#@__stepselect` WHERE egroup='$fieldname' ");
        if(!is_array($arr))
        {
            ShowMsg("你设定了字段为联动类型，但系统中没找到与你定义的字段名相同的联动组名!","-1");
            exit();
        }
    }

    //模型信息
    $row = $dsql->GetOne("SELECT fieldset,addtable,issystem FROM `#@__channeltype` WHERE id='$id'");
    $fieldset = $row['fieldset'];
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace("field", "<", ">");
    $dtp->LoadSource($fieldset);
    $trueTable = $row['addtable'];

    //检测被修改的字段类型
    $fieldinfos = GetFieldMake($dtype, $fieldname, $dfvalue, $mxlen);
    $ntabsql = $fieldinfos[0];
    $buideType = $fieldinfos[1];
    $rs = $dsql->ExecuteNoneQuery(" ALTER TABLE `$trueTable` ADD  $ntabsql ");
    if(!$rs)
    {
        $gerr = $dsql->GetError();
        ShowMsg("增加字段失败，错误提示为：".$gerr,"javascript:;");
        exit();
    }

    //检测旧配置信息，并替换为新配置
    $ok = FALSE;
    $fieldname = strtolower($fieldname);
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tagid=>$ctag)
        {
            if($fieldname == strtolower($ctag->GetName()))
            {
                $dtp->Assign($tagid, stripslashes($fieldstring), FALSE);
                $ok = true;
                break;
            }
        }
        $oksetting = $ok ? $dtp->GetResultNP() : $fieldset."\n".stripslashes($fieldstring);
    }
    else
    {
        $oksetting = $fieldset."\r\n".stripslashes($fieldstring);
    }
    
    $addlist = GetAddFieldList($dtp,$oksetting);
    $oksetting = addslashes($oksetting);
    $rs = $dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET fieldset='$oksetting',listfields='$addlist' WHERE id='$id' ");
    if(!$rs)
    {
        $grr = $dsql->GetError();
        ShowMsg("保存节点配置出错！".$grr, "javascript:;");
        exit();
    }
    
    ShowMsg("成功增加一个字段！", "mychannel_edit.php?id={$id}&dopost=edit&openfield=1");
    exit();
}

/*----------------------
function ShowPage()
---------------------*/
//检测模型相关信息，并初始化相关数据
$row = $dsql->GetOne("SELECT '#@__archives' AS maintable,addtable FROM `#@__channeltype` WHERE id='$id'");

$trueTable = $row['addtable'];
$tabsql = "CREATE TABLE IF NOT EXISTS  `$trueTable`( `aid` int(11) NOT NULL default '0',\r\n `typeid` int(11) NOT NULL default '0',\r\n ";

if($mysql_version < 4.1)
{
    $tabsql .= " PRIMARY KEY  (`aid`), KEY `".$trueTable."_index` (`typeid`)\r\n) TYPE=MyISAM; ";
}
else
{
    $tabsql .= " PRIMARY KEY  (`aid`), KEY `".$trueTable."_index` (`typeid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
}

$dsql->ExecuteNoneQuery($tabsql);

//检测附加表里含有的字段
$fields = array();

if(empty($row['maintable'])) $row['maintable'] = '#@__archives';

$rs = $dsql->SetQuery("SHOW fields FROM `{$row['maintable']}`");
$dsql->Execute('a');
while($nrow = $dsql->GetArray('a', MYSQL_ASSOC))
{
    $fields[strtolower($nrow['Field'])] = 1;
}

$dsql->Execute("a", "SHOW fields FROM `{$row['addtable']}`");
while($nrow = $dsql->GetArray('a', MYSQL_ASSOC))
{
    if(!isset($fields[strtolower($nrow['Field'])]))
    {
        $fields[strtolower($nrow['Field'])] = 1;
    }
}

$f = '';
foreach($fields as $k=>$v)
{
    $f .= ($f=='' ? $k : ' '.$k);
}

require_once(DEDEADMIN."/templets/mychannel_field_add.htm");
