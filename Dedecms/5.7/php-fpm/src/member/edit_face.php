<?php
/**
 * @version        $Id: edit_face.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if(!isset($dopost))
{
    $dopost = '';
}
if(!isset($backurl))
{
    $backurl = 'edit_face.php';
}
if($dopost=='save')
{
    $maxlength = $cfg_max_face * 1024;
    $userdir = $cfg_user_dir.'/'.$cfg_ml->M_ID;
    if(!preg_match("#^".$userdir."#", $oldface))
    {
        $oldface = '';
    }
    if(is_uploaded_file($face))
    {
        if(@filesize($_FILES['face']['tmp_name']) > $maxlength)
        {
            ShowMsg("你上传的头像文件超过了系统限制大小：{$cfg_max_face} K！", '-1');
            exit();
        }
        //删除旧图片（防止文件扩展名不同，如：原来的是gif，后来的是jpg）
        if(preg_match("#\.(jpg|gif|png)$#i", $oldface) && file_exists($cfg_basedir.$oldface))
        {
            @unlink($cfg_basedir.$oldface);
        }
        //上传新工图片
        $face = MemberUploads('face', $oldface, $cfg_ml->M_ID, 'image', 'myface', 180, 180);
    }
    else
    {
        $face = $oldface;
    }
    $query = "UPDATE `#@__member` SET `face` = '$face' WHERE mid='{$cfg_ml->M_ID}' ";
    $dsql->ExecuteNoneQuery($query);
    // 清除缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
    ShowMsg('成功更新头像信息！', $backurl);
    exit();
}
else if($dopost=='delold')
{
    if(empty($oldface))
    {
        ShowMsg("没有可删除的头像！", "-1");
        exit();
    }
    $userdir = $cfg_user_dir.'/'.$cfg_ml->M_ID;
    if(!preg_match("#^".$userdir."#", $oldface) || preg_match('#\.\.#', $oldface))
    {
        $oldface = '';
    }
    if(preg_match("#\.(jpg|gif|png)$#i", $oldface) && file_exists($cfg_basedir.$oldface))
    {
        @unlink($cfg_basedir.$oldface);
    }
    $query = "UPDATE `#@__member` SET `face` = '' WHERE mid='{$cfg_ml->M_ID}' ";
    $dsql->ExecuteNoneQuery($query);
    // 清除缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
    ShowMsg('成功删除原来的头像！', $backurl);
    exit();
}
$face = $cfg_ml->fields['face'];
include(DEDEMEMBER."/templets/edit_face.htm");
exit();
?>