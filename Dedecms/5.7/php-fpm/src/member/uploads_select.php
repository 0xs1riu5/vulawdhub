<?php
/**
 * 附件选择
 * 
 * @version        $Id: uploads_select.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$menutype = 'content';
if(empty($filename)) $filename = '';

$keyword = empty($keyword) ? '' : FilterSearch($keyword);
$addsql = " WHERE mid='".$cfg_ml->M_ID."' AND title LIKE '%$keyword%' ";
if(empty($mediatype)) $mediatype = 0;

$mediatype = intval($mediatype);
if($mediatype>0) $addsql .= " AND mediatype='$mediatype' ";

$sql = "SELECT * FROM `#@__uploads` $addsql ORDER BY aid DESC";
$dlist = new DataListCP();
$dlist->pageSize = 5;
$dlist->SetParameter("mediatype",$mediatype);
$dlist->SetParameter("keyword",$keyword);
$dlist->SetParameter("f",$f);
$dlist->SetTemplate(DEDEMEMBER."/templets/uploads_select.htm");
$dlist->SetSource($sql);
$dlist->Display();

/**
 *  附件类型
 *
 * @access    public
 * @param     int  $tid  类型ID
 * @param     string  $nurl
 * @return    string
 */
function MediaType($tid, $nurl)
{
    if($tid==1)
    {
        return "图片";
    }
    else if($tid==2)
    {
        return "FLASH";
    }
    else if($tid==3)
    {
        return "视频/音频";
    }
    else
    {
        return "附件/其它";
    }
}
function GetFileSize($fs)
{
    $fs = $fs/1024;
    return sprintf("%10.1f",$fs)." K";
}
function GetImageView($furl,$mtype)
{
    if($mtype==1)
    {
        return "<img src='$furl'  border='0' /><br />";
    }
}