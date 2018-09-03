<?php
/**
 * 圈子帖子管理
 *
 * @version        $Id: group_threads.php 1 15:34 2011-1-21 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('group_Main');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

$id = isset($id) && is_numeric($id) ? $id : 0;
$action = isset($action) ? trim($action) : '';

$keyword = isset($keyword) ? trim($keyword) : '';
$keyword = stripslashes($keyword);
$keyword = preg_replace("#[\"\r\n\t\*\?\(\)\$%']#"," ",trim($keyword));
$keyword = addslashes($keyword);

$username = isset($username) ? trim($username) : '';
$username = stripslashes($username);
$username = preg_replace("#[\"\r\n\t\*\?\(\)\$%']#"," ",trim($username));
$username = addslashes($username);

if(!empty($ids))
{
    foreach($ids as $val)
    {
        $db->ExecuteNoneQuery("DELETE FROM #@__group_threads WHERE tid='{$val}'");
        $db->ExecuteNoneQuery("DELETE FROM #@__group_posts WHERE tid='$val'");
    }
}

if($action != '' && $id<1)
{
    ShowMsg("未定义的操作！","-1");
    exit();
}

//Function 主题操作
if($action=="del")
{
    if($id > 0)
    {
        $db->ExecuteNoneQuery("DELETE FROM #@__group_threads WHERE tid='$id'");
        $db->ExecuteNoneQuery("DELETE FROM #@__group_posts WHERE tid='$id'");
    }
}
else if($action=="digest")
{
    //精华
    $rs = $db->GetOne("SELECT digest FROM #@__group_threads WHERE tid='".$id."'");
    if(is_array($rs))
    {
        if(!$rs['digest'])
        {
            $digestval = 1;
        }
        else
        {
            $digestval = 0;
        }
        $db->ExecuteNoneQuery("UPDATE  #@__group_threads SET digest='$digestval' WHERE tid='$id'");
    }
}
else if($action=="close")
{
    //关闭
    $rs = $db->GetOne("SELECT closed FROM #@__group_threads WHERE tid='".$id."'");
    if(is_array($rs))
    {
        if(!$rs['closed'])
        {
            $closeval = 1;
        }
        else
        {
            $closeval = 0;
        }
        $db->ExecuteNoneQuery("UPDATE  #@__group_threads SET closed='$closeval' WHERE tid='$id'");
    }
}
else if($action=="top")
{
    //置顶
    $rs = $db->GetOne("SELECT displayorder FROM #@__group_threads WHERE tid='".$id."'");
    if(is_array($rs))
    {
        if(!$rs['displayorder'])
        {
            $displayval = 1;
        }
        else
        {
            $displayval = 0;
        }
        $db->ExecuteNoneQuery("UPDATE  #@__group_threads SET displayorder='$displayval' WHERE tid='$id'");
    }
}
if(!isset($orders)) $orders = '';


$wheresql = "WHERE tid>0";
$sqlorders = "ORDER BY displayorder DESC,";
if (isset($gid) && !empty($gid))
{
    $wheresql .= " AND gid=$gid";
}

if($orders=="digest")
{
    $wheresql .= " AND digest=1";
}
if($orders=="close")
{
    $wheresql .= " AND closed=1";
}
if(!empty($keyword))
{
    $wheresql .= " AND subject like '%".$keyword."%'";
}
if(!empty($username))
{
    $wheresql .= " AND (author like '%".$username."%' OR lastposter like '%".$username."%')";
}
if($orders=="rep")
{
    $sqlorders = "ORDER BY replies DESC,";
}
$sqlorders .= " dateline DESC";

$sql = "SELECT * FROM #@__group_threads $wheresql $sqlorders";

$dl = new DataListCP();
$dl->pageSize = 20;
$dl->SetParameter("username", $username);
$dl->SetParameter("orders", $orders);
$dl->SetParameter("keyword", $keyword);

//这两句的顺序不能更换
$dl->SetTemplate(DEDEADMIN."/templets/group_threads.htm");      //载入模板
$dl->SetSource($sql);            //设定查询SQL
$dl->Display();                  //显示

function GetGroupname($id)
{
    global $db;
    $rs = $db->GetOne("SELECT groupname FROM #@__groups WHERE groupid='".$id."'");
    if(is_array($rs)) return $rs['groupname'];
    else return_nulls;
}

?>