<?php
/**
 * 采集操作
 *
 * @version        $Id: co_do.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/oxwindow.class.php");
if(!isset($nid)) $nid=0;
$ENV_GOBACK_URL = empty($_COOKIE["ENV_GOBACK_URL"]) ? "co_url.php" : $_COOKIE["ENV_GOBACK_URL"];

//删除节点
//删除节点将删除所有旧的网址索引
/*
function co_delete()
*/
if($dopost=="delete")
{
    CheckPurview('co_Del');
    $nid = intval($nid);
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_htmls` WHERE nid='$nid'");
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_note` WHERE nid='$nid'");
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_urls` WHERE nid='$nid'");
    ShowMsg("成功删除一个节点!","co_main.php");
    exit();
}

//清空采集内容
//清空采集内容时仍会保留旧的网址索引，在监控模式下始终采集新的内容
/*
function url_clear()
*/
else if($dopost=="clear")
{
    CheckPurview('co_Del');
    if(!isset($ids)) $ids='';
    if(empty($ids))
    {
        if(!empty($nid))
        {
            $nid = intval($nid);
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_htmls` WHERE nid='$nid'");
        }
        ShowMsg("成功清空一个节点采集的内容!","co_main.php");
        exit();
    }
    else
    {
        if(!empty($clshash))
        {
            $dsql->SetQuery("SELECT nid,url FROM `#@__co_htmls` WHERE aid IN($ids) ");
            $dsql->Execute();
            while($arr = $dsql->GetArray())
            {
                $nhash = md5($arr['url']);
                $nid = $row['nid'];
                $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_urls ` WHERE nid='$nid' AND hash='$nhash' ");
            }
        }
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_htmls` WHERE aid IN($ids) ");
        ShowMsg("成功删除指定的网址内容!",$ENV_GOBACK_URL);
        exit();
    }
}
else if($dopost=="clearct")
{
    CheckPurview('co_Del');
    if(!empty($ids))
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_htmls` SET isdown=0,result='' WHERE aid IN($ids) ");
    }
    ShowMsg("成功清除所有内容!",$ENV_GOBACK_URL);
    exit();
}
/*
function url_clearall()
*/
else if($dopost=="clearall")
{
    CheckPurview('co_Del');
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__co_htmls` ");
    ShowMsg("成功清空所有采集的临时内容!","co_main.php");
    exit();
}
//内容替换
/*
function co_replace() { }
*/
else if($dopost=="replace")
{
    //if()
    //$nid $aid $regtype $fdstring $rpstring
    $rpstring = trim($rpstring);
    if($regtype=='string')
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_htmls` SET `result`=REPLACE(`result`,'$fdstring','$rpstring') WHERE nid='$nid' ");
    }
    else
    {
        //返回一条测试结果，并要求用户确认操作
        if(empty($rpok))
        {
            $fdstring = stripslashes($fdstring);
            $rpstring = stripslashes($rpstring);
            $hiddenrpvalue = "<textarea name='fdstring' style='display:none'>{$fdstring}</textarea>\r\n<textarea name='rpstring' style='display:none'>{$rpstring}</textarea>\r\n";
            $fdstring = str_replace("\\/","#ASZZ#",$fdstring);
            $fdstring = str_replace('/',"\\/",$fdstring);
            $fdstring = str_replace('#ASZZ#',"\\/",$fdstring);
            $result = $rs = stripslashes($rs);
            if($fdstring!='')
            {
                $result = trim(preg_replace("/$fdstring/isU",$rpstring,$rs));
            }
            $wintitle = "采集管理-内容替换";
            $wecome_info = "<a href='co_main.php'>采集管理</a>::内容替换";
            $win = new OxWindow();
            $win->Init("co_do.php","js/blank.js","POST");
            $win->AddHidden('dopost',$dopost);
            $win->AddHidden('nid',$nid);
            $win->AddHidden('regtype','regex');
            $win->AddHidden('aid',$aid);
            $win->AddHidden('rpok','ok');
            $win->AddTitle("内容替换操作确认：如果下面结果正确，点击确认，系统将替换当前节点所有内容！{$hiddenrpvalue}");
            $win->AddItem("原来的内容：","<textarea name='rs' style='width:90%;height:250px'>{$rs}</textarea>\r\n");
            $win->AddItem("按规则替换后的内容：","<textarea name='okrs' style='width:90%;height:250px'>{$result}</textarea>\r\n");
            $winform = $win->GetWindow("ok");
            $win->Display();
            exit();
        }
        else
        {
            if($fdstring!='')
            {
                $dsql->SetQuery("SELECT `aid`,`result` FROM `#@__co_htmls` WHERE nid='$nid' ");
                $dsql->Execute();
                while($row = $dsql->GetArray())
                {
                    $fdstring = stripslashes($fdstring);
                    $rpstring = stripslashes($rpstring);
                    $fdstring = str_replace("\\/","#ASZZ#",$fdstring);
                    $fdstring = str_replace('/',"\\/",$fdstring);
                    $fdstring = str_replace('#ASZZ#',"\\/",$fdstring);
                    $result = trim(preg_replace("/$fdstring/isU",$rpstring,$row['result']));
                    $result = addslashes($result);
                    $dsql->ExecuteNoneQuery("UPDATE `#@__co_htmls` SET `result`='$result' WHERE aid='{$row['aid']}' ");
                }
            }
        }
    }
    ShowMsg("成功替换当前节点所有数据！","co_view.php?aid=$aid");
    exit();
}
//复制节点
/*
function co_copy()
*/
else if($dopost=="copy")
{
    CheckPurview('co_AddNote');
    if(empty($mynotename))
    {
        $wintitle = "采集管理-复制节点";
        $wecome_info = "<a href='co_main.php'>采集管理</a>::复制节点";
        $win = new OxWindow();
        $win->Init("co_do.php","js/blank.js","POST");
        $win->AddHidden("dopost",$dopost);
        $win->AddHidden("nid",$nid);
        $win->AddTitle("请输入新节点名称：");
        $win->AddItem("新节点名称：","<input type='text' name='mynotename' value='' size='30' />");
        $winform = $win->GetWindow("ok");
        $win->Display();
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__co_note` WHERE nid='$nid'");
    foreach($row as $k=>$v)
    {
        if(!isset($$k))
        {
            $$k = addslashes($v);
        }
    }
    $usemore = (empty($usemore) ? '0' : $usemore);
    $inQuery = " INSERT INTO `#@__co_note`(`channelid`,`notename`,`sourcelang`,`uptime`,`cotime`,`pnum`,`isok`,`listconfig`,`itemconfig`,`usemore`)
               VALUES ('$channelid','$mynotename','$sourcelang','".time()."','0','0','0','$listconfig','$itemconfig','$usemore'); ";
    $dsql->ExecuteNoneQuery($inQuery);
    ShowMsg("成功复制一个节点!",$ENV_GOBACK_URL);
    exit();
}
//测试Rss源是否正确
/*-----------------------
function co_testrss()
-------------------------*/
else if($dopost=="testrss")
{
    CheckPurview('co_AddNote');
    $msg = '';
    if($rssurl=='')
    {
        $msg = '你没有指定RSS地址！';
    }
    else
    {
        include(DEDEINC."/dedecollection.func.php");
        $arr = GetRssLinks($rssurl);
        $msg = "从 {$rssurl} 发现的网址：<br />";
        $i=1;
        if(is_array($arr))
        {
            foreach($arr as $ar)
            {
                $msg .= "<hr size='1' />\r\n";
                $msg .= "link: {$ar['link']}<br />title: {$ar['title']}<br />image: {$ar['image']}\r\n";
                $i++;
            }
        }
    }
    $wintitle = "采集管理-测试";
    $wecome_info = "<a href='co_main.php'>采集管理</a>::RSS地址测试";
    $win = new OxWindow();
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand");
    $win->Display();
    exit();
}
//测试批量网址是否正确
/*-----------------------
function co_testregx()
-------------------------*/
else if($dopost=="testregx")
{
    CheckPurview('co_AddNote');
    $msg = '';
    if($regxurl=='')
    {
        $msg = '你没有指定匹配的网址！';
    }
    else
    {
        include(DEDEINC."/dedecollection.func.php");
        $msg = "匹配的网址：<br />";
        $lists = GetUrlFromListRule($regxurl, '', $startid, $endid, $addv);
        foreach($lists as $surl)
        {
            $msg .= $surl[0]."<br />\r\n";
        }
    }
    $wintitle = "采集管理-测试匹配规则";
    $wecome_info = "<a href='co_main.php'>采集管理</a>::测试匹配列表网址规则";
    $win = new OxWindow();
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand");
    $win->Display();
    exit();
}

//采集未下载内容
/*--------------------
function co_all()
---------------------*/
else if($dopost=="coall")
{
    CheckPurview('co_PlayNote');
    $mrow = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__co_htmls` ");
    $totalnum = $mrow['dd'];
    if($totalnum==0)
    {
        ShowMsg("没发现可下载的内容！","-1");
        exit();
    }
    $wintitle = "采集管理-采集未下载内容";
    $wecome_info = "<a href='co_main.php'>采集管理</a>::采集未下载内容";
    $win = new OxWindow();
    $win->Init("co_gather_start_action.php","js/blank.js","GET");
    $win->AddHidden('startdd','0');
    $win->AddHidden('pagesize','5');
    $win->AddHidden('sptime','0');
    $win->AddHidden('nid','0');
    $win->AddHidden('totalnum',$totalnum);
    $win->AddMsgItem("本操作会检测并下载‘<a href='co_url.php'><u>临时内容</u></a>’中所有未下载的内容，是否继续？");
    $winform = $win->GetWindow("ok");
    $win->Display();
    exit();
}