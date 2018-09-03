<?php
/**
 * 会员日志编辑
 *
 * @version        $Id: member_feed_edit.php 1 14:08 2010年7月19日Z tianya $
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
if(empty($dellog)) $dellog = 0;

//清空所选日志
if($dopost == "clearcheck")
{
    $nowtime = time();
    $starttime = $nowtime - (24*3600);
    $endtime =$nowtime -($dellog*24*3600);
    $dsql->ExecuteNoneQuery("DELETE FROM #@__member_feed WHERE dtime BETWEEN $endtime AND $starttime ");
    ShowMsg("成功清空过去".$dellog."天记录！","member_feed_main.php");
    exit();
}
//清空所有日志
else if($dopost == "clear")
{
    $dsql->ExecuteNoneQuery("TRUNCATE TABLE #@__member_feed");
    ShowMsg("成功清空所有记录！","memberlog_list.php");
    exit();
}
//删除选定日志
else if($dopost == "del")
{
    $bkurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "member_feed_main.php";
    $ids = explode('`', $ids);
    $dquery = "";
    foreach($ids as $id)
    {
        if($dquery=="") $dquery .= " fid='$id' ";
        else $dquery .= " OR fid='$id' ";
    }
    if($dquery!="") $dquery = " WHERE ".$dquery;
    $dsql->ExecuteNoneQuery("DELETE FROM #@__member_feed $dquery");
    ShowMsg("成功删除指定的记录！",$bkurl);
    exit();
}
//审核选定日志
else if($dopost=="check")
{
    $bkurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "member_feed_main.php";
    $ids = explode('`', $ids);
    $dquery = "";
    foreach($ids as $id)
    {
        if($dquery=="") $dquery .= " fid='$id' ";
        else $dquery .= " OR fid='$id' ";
    }
    if($dquery!="") $dquery = " WHERE ".$dquery;
    $dsql->ExecuteNoneQuery("UPDATE #@__member_feed SET ischeck=1 $dquery");
    ShowMsg("成功审核指定的记录！",$bkurl);
    exit();
} else {
    ShowMsg("无法识别你的请求！","javascript:;");
    exit();
}