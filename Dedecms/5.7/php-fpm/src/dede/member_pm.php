<?php
/**
 * 会员短消息管理
 *
 * @version        $Id: member_pm.php 1 11:24 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Pm');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
require_once(DEDEINC .'/datalistcp.class.php');

if(!isset($folder)) $folder = '';
if(!isset($username)) $username = '';
if(!isset($keyword)) $keyword = '';

if(isset($dopost))
{
    $ID = preg_replace("#[^0-9]#", "", $ID);
    if($dopost=="del"&&!empty($ID))
    {
        $dsql->ExecuteNoneQuery("DELETE FROM #@__member_pms WHERE id='$ID'");
    }
}

$whereSql = '';
if(!empty($folder)) $whereSql = "WHERE folder='$folder'";
$postuser = "收件人";
if($folder=="inbox"||$folder=='') $postuser = "发件人";

if(!empty($keyword))
{
    $whereSql .= " AND (subject like '%".$keyword."%' OR message like '%".$keyword."%')";
}
if(!empty($username))
{
    $whereSql .= " AND floginid like '%".$username."%'";
}
$sql = "SELECT * FROM #@__member_pms $whereSql ORDER BY sendtime desc";
$dlist = new DataListCP();
$dlist->pagesize = 25;
$dlist->SetParameter("folder",$folder);
$dlist->SetParameter("username",$username);
$dlist->SetParameter("keyword",$keyword);
$dlist->SetTemplate(DEDEADMIN."/templets/member_pm.htm");
$dlist->SetSource($sql);
$dlist->Display();
$dlist->Close();

function GetFolders($me)
{
    if($me=="outbox") return '发件箱';
    else if($me=="inbox") return '收件箱';
}

function IsReader($me)
{
    $me = preg_replace("#[^0-1]#", "", $me);
    if($me) return "<font color='green'>√</font>";
    else return "<font color='red'>×</font>";
}