<?php
/**
 *
 * 内容收藏
 *
 * @version        $Id: stow.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");

$aid = ( isset($aid) && is_numeric($aid) ) ? $aid : 0;
$type=empty($type)? "" : $type;

if($aid==0)
{
    ShowMsg('文档id不能为空!','javascript:window.close();');
    exit();
}

require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();

if($ml->M_ID==0)
{
    ShowMsg('只有会员才允许收藏操作！','javascript:window.close();');
    exit();
}


//读取文档信息
$arcRow = GetOneArchive($aid);
if($arcRow['aid']=='')
{
    ShowMsg("无法收藏未知文档!","javascript:window.close();");
    exit();
}
extract($arcRow, EXTR_SKIP);

$addtime = time();
if($type=='')
{
    $row = $dsql->GetOne("SELECT * FROM `#@__member_stow` WHERE aid='$aid' AND mid='{$ml->M_ID}' AND type='' ");
    if(!is_array($row))
    {
        $dsql->ExecuteNoneQuery("INSERT INTO `#@__member_stow`(mid,aid,title,addtime) VALUES ('".$ml->M_ID."','$aid','".addslashes($arctitle)."','$addtime'); ");
    }
} else {
    $row = $dsql->GetOne("SELECT * FROM `#@__member_stow` WHERE type='$type' AND (aid='$aid' AND mid='{$ml->M_ID}')");
    if(!is_array($row))
    {
        $dsql->ExecuteNoneQuery(" INSERT INTO `#@__member_stow`(mid,aid,title,addtime,type) VALUES ('".$ml->M_ID."','$aid','$title','$addtime','$type'); ");
    }
}

//更新用户统计
$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__member_stow` WHERE `mid`='{$ml->M_ID}' ");
$dsql->ExecuteNoneQuery("UPDATE #@__member_tj SET `stow`='$row[nums]' WHERE `mid`='".$ml->M_ID."'");

ShowMsg('成功收藏一篇文档！','javascript:window.close();');