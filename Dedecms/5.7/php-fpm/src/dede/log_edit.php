<?php
/**
 * 编辑日志
 *
 * @version        $Id: log_edit.php 1 8:48 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Log');
if(empty($dopost))
{
    ShowMsg("你没指定任何参数！","javascript:;");
    exit();
}

//清空所有日志
if($dopost=="clear")
{
    $dsql->ExecuteNoneQuery("DELETE FROM #@__log");
    ShowMsg("成功清空所有日志！","log_list.php");
    exit();
}
else if($dopost=="del")
{
    $bkurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "log_list.php";
    $ids = explode('`',$ids);
    $dquery = "";
    foreach($ids as $id)
    {
        if($dquery=="")
        {
            $dquery .= " lid='$id' ";
        }
        else
        {
            $dquery .= " Or lid='$id' ";
        }
    }
    if($dquery!="") $dquery = " where ".$dquery;
    $dsql->ExecuteNoneQuery("DELETE FROM #@__log $dquery");
    ShowMsg("成功删除指定的日志！",$bkurl);
    exit();
}
else
{
    ShowMsg("无法识别你的请求！","javascript:;");
    exit();
}