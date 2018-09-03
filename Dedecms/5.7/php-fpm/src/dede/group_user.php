<?php
/**
 * 圈子用户管理
 *
 * @version        $Id: group_user.php 1 15:34 2011-1-21 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC.'/datalistcp.class.php');
CheckPurview('group_Edit');

$gid = isset($gid) && is_numeric($gid) ? $gid : 0;
$id = isset($id) && is_numeric($id) ? $id : 0;
$action = isset($action) ? trim($action) : '';

$username = isset($username) ? trim($username) : '';
$username = stripslashes($username);
$username = preg_replace("#[\"\r\n\t\*\?\(\)\$%']#", " ", trim($username));
$username = addslashes($username);

if($gid < 1)
{
    ShowMsg("含有非法操作!.","-1");
    exit();
}

$row = $db->GetOne("SELECT ismaster,uid FROM #@__groups WHERE groupid='{$gid}'");
$ismaster     = $row['ismaster'];
$ismasterid        = $row['uid'];

if($action=="del")
{
    if($ismasterid == $id)
    {
        ShowMsg("圈主不能脱离群关系!","-1");
        exit();
    }
    $row = $db->GetOne("SELECT username FROM #@__group_user WHERE uid='$id' AND gid='$gid'");
    if(is_array($row))
    {
        $username = $row['username'];
        $master = explode(",",$ismaster);
        if(in_array($username,$master))
        {
            //如果会员存管理员字段将移出
            $k = array_search($username,$master);
            unset($master[$k]);
        }
        $master = array_filter($master, "filter");
        $ismaster = implode(",",$master);
        $db->ExecuteNoneQuery("UPDATE #@__groups SET ismaster='{$ismaster}' WHERE groupid='{$gid}'");
    }
    if($id > 0)
    {
        $db->ExecuteNoneQuery("DELETE FROM #@__group_user WHERE uid='$id' AND gid='$gid'");
    }
    ShowMsg("已将该会员移出本群!.","-1");
    exit();
}
else if($action=="admin")
{
    if($ismasterid == $id)
    {
        ShowMsg("圈主应同时有管理权!","-1");
        exit();
    }
    $row = $db->GetOne("SELECT username FROM #@__group_user WHERE uid='$id' AND gid='$gid'");
    if(is_array($row))
    {
        $username = $row['username'];
        $master = explode(",",$ismaster);
        if(in_array($username,$master))
        {
            //如果会员存管理员字段将移出
            $k = array_search($username,$master);
            unset($master[$k]);
            $msg = "已将 {$username},设为普通会员!";
        }
        else
        {
            //否则加入到管理员数组
            array_push($master,$username);
            $msg = "已将 {$username},设为管理员!";
        }
        $master = array_filter($master, "filter");
        $ismaster = implode(",",$master);
        $db->ExecuteNoneQuery("UPDATE #@__groups SET ismaster='{$ismaster}' WHERE groupid='{$gid}'");
    }
    ShowMsg("{$msg}","-1");
    exit();
}
else if($action=="add")
{
    $uname = cn_substr($uname,15);
    if(empty($uname))
    {
        ShowMsg("请填写用户名!.","-1");
        exit();
    }
    $rs = $db->GetOne("SELECT COUNT(*) AS c FROM #@__group_user WHERE username like '$uname' AND gid='$gid'");
    if($rs['c'] > 0)
    {
        ShowMsg("用户已加入该圈子!.","-1");
        exit();
    }
    $row = $db->GetOne("SELECT userid,mid FROM #@__member WHERE userid like '$uname'");
    if(!is_array($row))
    {
        ShowMsg("站内不存在该用户!.","-1");
        exit();
    }
    else
    {
        $row['id'] = $row['mid'];
        $db->ExecuteNoneQuery("INSERT INTO #@__group_user(uid,username,gid,jointime) VALUES('".$row['id']."','".$row['userid']."','$gid','".time()."');");
        //如果设成管理员
        if($setmaster)
        {
            $master = explode(",",$ismaster);
            array_push($master,$uname);
            $master = array_filter($master, "filter");
            $ismaster = implode(",",$master);
            $db->ExecuteNoneQuery("UPDATE #@__groups SET ismaster='{$ismaster}' WHERE groupid='{$gid}'");
        }
    }
    ShowMsg("成功添加用户:{$uname}","-1");
    exit();
}

//列表加载模板
$wheresql = "WHERE gid='{$gid}'";
if(!empty($username))
{
    $wheresql .= " AND username like '%".$username."%'";
}
$sql = "SELECT * FROM #@__group_user $wheresql ORDER BY jointime DESC";


$dl = new DataListCP();
$dl->pageSize = 20;
$dl->SetParameter("username",$username);
$dl->SetParameter("id",$id);
$dl->SetParameter("gid",$gid);

//这两句的顺序不能更换
$dl->SetTemplate(DEDEADMIN."/templets/group_user.htm");      //载入模板
$dl->SetSource($sql);            //设定查询SQL
$dl->Display();                  //显示


function filter($var)
{
    return $var == '' ? false : true;
}

function GetMaster($user)
{
    global $ismaster;
    $master = explode(",",$ismaster);
    if(in_array($user,$master))
    {
        return "<img src='img/adminuserico.gif'> 管理员";
    }
    else
    {
        return "普通会员";
    }
}

?>