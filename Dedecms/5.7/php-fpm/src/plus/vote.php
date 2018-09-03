<?php
/**
 *
 * 投票
 *
 * @version        $Id: vote.php 1 20:54 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/../include/common.inc.php");
require(DEDEINC."/dedevote.class.php");
require(DEDEINC."/memberlogin.class.php");
require(DEDEINC."/userlogin.class.php");

$member = new MemberLogin;
$memberID = $member->M_LoginID;
$time = time();
$content = $memberID.'|'.$time;
$file = DEDEDATA.'/cache/vote_'.$aid.'_'.$member->M_ID.'.inc';//存放会员投票记录的缓存文件

$loginurl = $cfg_basehost."/member";
$ENV_GOBACK_URL = empty($_SERVER['HTTP_REFERER']) ? '':$_SERVER['HTTP_REFERER'];

if(empty($dopost)) $dopost = '';

$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
if($aid==0) die(" Request Error! ");

if($aid==0)
{
    ShowMsg("没指定投票项目的ID！","-1");
    exit();
}
$vo = new DedeVote($aid);
$rsmsg = '';


$row = $dsql->GetOne("SELECT * FROM #@__vote WHERE aid='$aid'");
//判断是否允许游客进行投票
if($row['range'] == 1)
{
    if(!$member->IsLogin())
    {
        ShowMsg('请先登录再进行投票',$loginurl);
        exit();
    }
}

if($dopost=='send')
{
    
    if(!empty($voteitem))
    {
        $rsmsg = "<br />&nbsp;你方才的投票状态：".$vo->SaveVote($voteitem)."<br />";
    }
    else
    {
        $rsmsg = "<br />&nbsp;你刚才没选择任何投票项目！<br />";
    }
    
    if($row['isenable'] == 1)
    {
        ShowMsg('此投票项未启用,暂时不能进行投票',$ENV_GOBACK_URL);
        exit();
    }
}

$voname = $vo->VoteInfos['votename'];
$totalcount = $vo->VoteInfos['totalcount'];
$starttime = GetDateMk($vo->VoteInfos['starttime']);
$endtime = GetDateMk($vo->VoteInfos['endtime']);
$votelist = $vo->GetVoteResult("98%",30,"30%");





//判断是否允许被查看
$admin = new userLogin;
if($dopost == 'view')
{
    if($row['view'] == 1 && empty($admin->userName))
    {
        ShowMsg('此投票项不允许查看结果',$ENV_GOBACK_URL);
        exit();
    }
}
//显示模板(简单PHP文件)
include(DEDETEMPLATE.'/plus/vote.htm');