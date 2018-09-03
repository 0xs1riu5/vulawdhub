<?php
/**
 * 编辑附件
 * 
 * @version        $Id: uploads_edit.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
require_once(DEDEMEMBER.'/inc/inc_archives_functions.php');
$menutype = 'content';
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
if(empty($dopost)) $dopost = '';
if($dopost=='')
{
    $arow = $dsql->GetOne("SELECT * FROM `#@__uploads` WHERE aid='$aid ';");
    if(!is_array($arow))
    {
        ShowMsg('附件不存在', '-1');
        exit();
    }
    if($arow['mid'] != $cfg_ml->M_ID)
    {
        ShowMsg("你没有修改这个附件的权限！","-1");
        exit();
    }
    include(DEDEMEMBER."/templets/uploads_edit.htm");
    exit();
}
else if($dopost=='save')
{
    $title = HtmlReplace($title,2);
    if($mediatype==1) $utype = 'image';
    else if($mediatype==2)
    {
        $utype = 'flash';
    }
    else if($mediatype==3)
    {
        $utype = 'media';
    }
    else
    {
        $utype = 'addon';
    }
    $title = HtmlReplace($title, 2);
    $exname = preg_replace("#(.*)/#", "", $oldurl);
    $exname = preg_replace("#\.(.*)$#", "", $exname);
    $filename = MemberUploads('addonfile', $oldurl, $cfg_ml->M_ID, $utype,$exname, -1, -1, TRUE);
    SaveUploadInfo($title, $filename, $mediatype);
    ShowMsg("成功修改文件！", "uploads_edit.php?aid=$aid");
}