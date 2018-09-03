<?php
/**
 * 附件编辑
 *
 * @version        $Id: media_edit.php 1 11:17 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");

//权限检查
CheckPurview('sys_Upload,sys_MyUpload');
if(empty($dopost)) $dopost = "";
$backurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "javascript:history.go(-1);";

/*---------------------------
function __del_file() //删除附件
-----------------------------*/
if($dopost=='del')
{
    CheckPurview('sys_DelUpload');
    if(empty($ids))
    {
        $ids="";
    }
    if($ids=="")
    {
        $myrow = $dsql->GetOne("SELECT url FROM #@__uploads WHERE aid='".$aid."'");
        $truefile = $cfg_basedir.$myrow['url'];
        $rs = 0;
        if(!file_exists($truefile)||$myrow['url']=="")
        {
            $rs = 1;
        } else {
            $rs = @unlink($truefile);
            //如果开启远程附件则需要同步删除文件
            if($cfg_remote_site=='Y')
            {
                if($ftp->connect($ftpconfig) && $remoteuploads == 1)
                {
                    $remotefile = str_replace(DEDEROOT, '', $truefile);
                    $ftp->delete_file($remotefile);
                }
            }
        }
        if($rs==1)
        {
            $msg = "成功删除一个附件！";
            $dsql->ExecuteNoneQuery("DELETE FROM #@__uploads WHERE aid='".$aid."'");
        }
        ShowMsg($msg,$backurl);
        exit();
    } else {
        $ids = explode(',', $ids);
        $idquery = "";
        foreach($ids as $aid)
        {
            if($idquery=="")
            {
                $idquery .= " WHERE aid='$aid' ";
            }
            else
            {
                $idquery .= " OR aid='$aid' ";
            }
        }
        $dsql->SetQuery("SELECT aid,url FROM #@__uploads $idquery ");
        $dsql->Execute();
        
        //如果开启远程附件则需要同步删除文件
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
        {
            $ftp->connect($ftpconfig);
        }
        while($myrow=$dsql->GetArray())
        {
            $truefile = $cfg_basedir.$myrow['url'];
            $rs = 0;
            if(!file_exists($truefile) || $myrow['url']=="")
            {
                $rs = 1;
            }
            else
            {
                $rs = @unlink($truefile);
                if($cfg_remote_site=='Y' && $remoteuploads == 1)
                {
                    $remotefile = str_replace(DEDEROOT, '', $truefile);
                    $ftp->delete_file($remotefile);
                }
            }
            if($rs==1)
            {
                $dsql->ExecuteNoneQuery("DELETE FROM #@__uploads WHERE aid='".$myrow['aid']."'");
            }
        }
        ShowMsg('成功删除选定的文件！',$backurl);
        exit();
    }
}
/*--------------------------------
function __save_edit() //保存更改
-----------------------------------*/
else if($dopost=='save')
{
    if($aid=="") exit();

    //检查是否有修改权限
    $myrow = $dsql->GetOne("SELECT * FROM #@__uploads WHERE aid='".$aid."'");
    if($myrow['mid']!=$cuserLogin->getUserID())
    {
        CheckPurview('sys_Upload');
    }

    //检测文件类型
    $addquery = "";
    if(is_uploaded_file($upfile))
    {
        if($mediatype==1)
        {
            $sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
            if(!in_array($upfile_type,$sparr))
            {
                ShowMsg("你上传的不是图片类型的文件！","javascript:history.go(-1);");
                exit();
            }
        }
        else if($mediatype==2)
        {
            $sparr = Array("application/x-shockwave-flash");
            if(!in_array($upfile_type,$sparr))
            {
                ShowMsg("你上传的不是Flash类型的文件！","javascript:history.go(-1);");
                exit();
            }
        }else if($mediatype==3)
        {
            if(!preg_match('#audio|media|video#i', $upfile_type))
            {
                ShowMsg("你上传的为不正确类型的影音文件！","javascript:history.go(-1);");
                exit();
            }
            if(!preg_match("#\.".$cfg_mediatype."#", $upfile_name))
            {
                ShowMsg("你上传的影音文件扩展名无法被识别，请更改系统配置的参数！","javascript:history.go(-1);");
                exit();
            }
        }else
        {
            if(!preg_match("#\.".$cfg_softtype."#", $upfile_name))
            {
                ShowMsg("你上传的附件扩展名无法被识别，请更改系统配置的参数！","javascript:history.go(-1);");
                exit();
            }
        }

        //保存文件
        $nowtime = time();
        $oldfile = $myrow['url'];
        $oldfiles = explode('/', $oldfile);
        $fullfilename = $cfg_basedir.$oldfile;
        $oldfile_path = preg_replace("#".$oldfiles[count($oldfiles)-1]."$#", "", $oldfile);
        if(!is_dir($cfg_basedir.$oldfile_path))
        {
            MkdirAll($cfg_basedir.$oldfile_path, 777);
            CloseFtp();
        }
        @move_uploaded_file($upfile, $fullfilename);
        if($mediatype==1)
        {
            require_once(DEDEINC."/image.func.php");
            if(in_array($upfile_type, $cfg_photo_typenames))
            {
                WaterImg($fullfilename, 'up');
            }
        }
        $filesize = $upfile_size;
        $imgw = 0;
        $imgh = 0;
        if($mediatype==1)
        {
            $info = "";
            $sizes[0] = 0; $sizes[1] = 0;
            $sizes = @getimagesize($fullfilename, $info);
            $imgw = $sizes[0];
            $imgh = $sizes[1];
        }
        if($imgw>0)
        {
            $addquery = ",width='$imgw',height='$imgh',filesize='$filesize' ";
        }
        else
        {
            $addquery = ",filesize='$filesize' ";
        }
    }
    else
    {
        $fileurl = $filename;
    }

    //写入数据库
    $query = " UPDATE #@__uploads SET title='$title',mediatype='$mediatype',playtime='$playtime'";
    $query .= "$addquery WHERE aid='$aid' ";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg('成功更改一则附件数据！','media_edit.php?aid='.$aid);
    exit();
}

//读取档案信息
$myrow = $dsql->GetOne("SELECT * FROM #@__uploads WHERE aid='".$aid."'");
if(!is_array($myrow))
{
    ShowMsg('错误，找不到此编号的档案！','javascript:;');
    exit();
}
include DedeInclude('templets/media_edit.htm');