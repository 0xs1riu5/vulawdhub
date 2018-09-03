<?php
/**
 * 文件图片查看
 *
 * @version        $Id: file_pic_view.php 1 8:48 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('pic_view');
if(empty($activepath)) $activepath=$cfg_medias_dir;

$activepath = preg_replace("#\/{1,}#", "/", $activepath);
$truePath = $cfg_basedir.$activepath;
$listSize=5;
include DedeInclude('templets/file_pic_view.htm');

/**
 *  获取上一级目录
 *
 * @access    public
 * @param     string  $nowPath  当前目录
 * @return    string
 */
function GetPrePath($nowPath)
{
    if($nowPath=="" || $nowPath=="/")
    {
        echo("当前为根目录\n");
    }
    else
    {
        $dirs = split("/", $nowPath);
        $nowPath = "";
        for($i=1;$i < count($dirs)-1; $i++)
        {
            $nowPath .= "/".$dirs[$i];
        }
        echo("<a href=\"pic_view.php?activepath=".$nowPath."\">转到上级目录</a>\n");
    }
}

/**
 *  列出图片
 *
 * @access    public
 * @param     string  $truePath  真实地址
 * @param     string  $nowPath  当前地址
 * @return    string
 */
function ListPic($truePath, $nowPath)
{
    global $listSize;
    $col=0;
    $rowdd=0;
    $rowdd++;
    $imgfile="";
    $truePath = preg_replace("#\/$#", "", preg_replace("#\\\\{1,}#", "/", trim($truePath)));
    $nowPath = preg_replace("#\/$#", "", preg_replace("#\/{1,}#", "/", trim($nowPath)));
    $dh = dir($truePath);
    echo("<tr align='center'>\n");
    while($filename=$dh->read())
    {
        if(!preg_match("#\.$#", $filename))
        {
            $fullName = $truePath."/".$filename;
            $fileUrl =  $nowPath."/".$filename;
            if(is_dir($fullName))
            {
                if($col % $listSize == 0 && $col != 0)
                {
                    echo("</tr>\n<tr align='center'>\n");
                    for($i=$rowdd-$listSize; $i<$rowdd; $i++)
                    {
                        echo("<td>".$filelist[$i]."</td>\n");
                    }
                    echo("</tr>\n<tr align='center'>\n");
                }
                $line = "
                <td>
                <table width='106' height='106' border='0' cellpadding='0' cellspacing='1' bgcolor='#CCCCCC'>
                <tr><td align='center' bgcolor='#FFFFFF'>
                <a href='pic_view.php?activepath=".$fileUrl."'>
                <img src='images/pic_dir.gif' width='44' height='42' border='0'>
                </a></td></tr></table></td>";
                $filelist[$rowdd] = $filename;
                $col++;
                $rowdd++;
                echo $line;
            }
            else if(IsImg($filename))
            {
                if($col%$listSize==0&&$col!=0)
                {
                    echo("</tr>\n<tr align='center'>\n");
                    for($i=$rowdd-$listSize;$i<$rowdd;$i++)
                    {
                        echo("<td>".$filelist[$i]."</td>\n");
                    }
                    echo("</tr>\n<tr align='center'>\n");
                }
                $line = "
                <td>
                <table width='106' height='106' border='0' cellpadding='0' cellspacing='1' bgcolor='#CCCCCC'>
                <tr>
                <td align='center' bgcolor='#FFFFFF'>
                ".GetImgFile($truePath,$nowPath,$filename)."
                </td>
                </tr></table></td>";
                $filelist[$rowdd] = $filename;
                $col++;
                $rowdd++;
                echo $line;
            }
        }
    }
    echo("</tr>\n");
    if(!empty($filelist))
    {
        echo("<tr align='center'>\n");
        $t = ($rowdd-1)%$listSize;
        if($t==0)
        {
            $t=$listSize;
        }
        for($i=$rowdd-$t;$i<$rowdd;$i++)
        {
            echo("<td>".$filelist[$i]."</td>\n");
        }
        echo("</tr>\n");
    }
}

/**
 *  获取图片文件
 *
 * @access    public
 * @param     string  $truePath  真实地址
 * @param     string  $nowPath  当前地址
 * @param     string  $fileName  文件名称
 * @return    string
 */
function GetImgFile($truePath, $nowPath, $fileName)
{
    $toW=102;
    $toH=102;
    $srcFile = $truePath."/".$fileName;
    $info = "";
    $data = GetImageSize($srcFile, $info);
    $srcW=$data[0];
    $srcH=$data[1];
    if($toW>=$srcW&&$toH>=$srcH)
    {
        $ftoW=$srcW;
        $ftoH=$srcH;
    }
    else
    {
        $toWH=$toW / $toH;
        $srcWH=$srcW / $srcH;
        if($toWH<=$srcWH)
        {
            $ftoW=$toW;
            $ftoH=$ftoW*($srcH/$srcW);
        }
        else
        {
            $ftoH=$toH;
            $ftoW=$ftoH*($srcW/$srcH);
        }
    }
    return("<a href='".$nowPath."/".$fileName."' target='_blank'><img src='".$nowPath."/".$fileName."' width='".$ftoW."' height='".$ftoH."' border='0'></a>");
}

function IsImg($fileName)
{
    if(preg_match("#\.(jpg|gif|png)$#", $fileName)) return 1;
    else return 0;
}