<?php
/**
 * 开始采集指定节点操作
 *
 * @version        $Id: co_gather_start_action.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('co_PlayNote');
require_once(DEDEINC.'/dedecollection.class.php');
if($totalnum==0)
{
    ShowMsg('获取到的网址为零：可能是规则不对或没发现新内容！','javascript:;');
    exit();
}
if(!isset($oldstart)) $oldstart = $startdd;
if(empty($notckpic)) $notckpic = 0;

if($totalnum > $startdd+$pagesize) $limitSql = " LIMIT $startdd,$pagesize ";
else $limitSql = " LIMIT $startdd,".($totalnum - $startdd);

if($totalnum - $startdd < 1)
{
    if(empty($nid))
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_note` SET cotime='".time()."'; ");
    }
    else
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_note` SET cotime='".time()."' WHERE nid='$nid'; ");
    }
    ShowMsg('完成当前下载任务！','javascript:;');
    exit();
}

$co = new DedeCollection();
if(!empty($nid)) $co->LoadNote($nid);

//没指定采集ID时下载所有内容
if(!empty($nid))
{
    $dsql->SetQuery("SELECT aid,nid,url,isdown,litpic FROM `#@__co_htmls` WHERE nid=$nid $limitSql ");
}
else
{
    $dsql->SetQuery("SELECT aid,nid,url,isdown,litpic FROM `#@__co_htmls` $limitSql ");
}
$dsql->Execute(99);
$tjnum = $startdd;
while($row = $dsql->GetObject(99))
{
    if($row->isdown==0)
    {
        if(empty($nid)) $co->LoadNote($row->nid);
        $co->DownUrl($row->aid,$row->url,$row->litpic);
    }
    $tjnum++;
    if($sptime>0) sleep($sptime);
}
if($totalnum-$oldstart!=0)
{
    $tjlen = ceil( (($tjnum-$oldstart)/($totalnum-$oldstart)) * 100 );
    $dvlen = $tjlen * 2;
    $tjsta = "<div style='width:200;height:15;border:1px solid #898989;text-align:left'><div style='width:$dvlen;height:15;background-color:#829D83'></div></div>";
    $tjsta .= "<br/>完成当前任务的：$tjlen %，继续执行任务...";
}
if($tjnum < $totalnum)
{
    ShowMsg($tjsta, "co_gather_start_action.php?notckpic=$notckpic&sptime=$sptime&nid=$nid&oldstart=$oldstart&totalnum=$totalnum&startdd=".($startdd+$pagesize)."&pagesize=$pagesize","",0);
    exit();
}
else
{
    if(empty($nid))
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_note` SET cotime='".time()."'; ");
    }
    else
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_note` SET cotime='".time()."' WHERE nid='$nid'; ");
    }
    ShowMsg("完成当前下载任务！","javascript:;");
    exit();
}