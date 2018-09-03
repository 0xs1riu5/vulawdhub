<?php
/**
 * 编辑一个模板
 *
 * @version        $Id: templets_one_edit.php 1 23:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('temp_One');
if(empty($dopost)) $dopost = "";

$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
if($dopost=="saveedit")
{
    include_once(DEDEINC."/arc.sgpage.class.php");
    $uptime = time();
    $body = str_replace('&quot;', '\\"', $body);
    $filename = preg_replace("#^\/#", "", $nfilename);

    //如果更改了文件名，删除旧文件
    if($oldfilename!=$filename)
    {
        $oldfilename = $cfg_basedir.$cfg_cmspath."/".$oldfilename;
        if(is_file($oldfilename))
        {
            unlink($oldfilename);
        }
    }
    if($likeidsel!=$oldlikeid )
    {
        $likeid = $likeidsel;
    }
    $inQuery = "
     UPDATE `#@__sgpage` SET
     title='$title',
     keywords='$keywords',
     description='$description',
     likeid='$likeid',
     ismake='$ismake',
     filename='$filename',
     template='$template',
     uptime='$uptime',
     body='$body'
     WHERE aid='$aid'; ";
    if(!$dsql->ExecuteNoneQuery($inQuery))
    {
        ShowMsg("更新页面数据时失败，请检查长相是否有问题！","-1");
        exit();
    }
    $sg = new sgpage($aid);
    $sg->SaveToHtml();
    ShowMsg("成功修改一个页面！", "templets_one.php");
    exit();
}
else if($dopost=="delete")
{
    $row = $dsql->GetOne("SELECT filename FROM `#@__sgpage` WHERE aid='$aid'");
    $filename = preg_replace("#\/{1,}#", "/", $cfg_basedir.$cfg_cmspath."/".$row['filename']);
    $dsql->ExecuteNoneQuery(" DELETE FROM `#@__sgpage` WHERE aid='$aid' ");
    if(is_file($filename))
    {
        unlink($filename);
    }
    ShowMsg("成功删除一个页面！", "templets_one.php");
    exit();
}
else if($dopost=="make")
{
    include_once(DEDEINC."/arc.sgpage.class.php");
    $row = $dsql->GetOne("SELECT filename FROM `#@__sgpage` WHERE aid='$aid'");
    $fileurl = $cfg_cmsurl.'/'.preg_replace("#\/{1,}#", "/", $row['filename']);
    $sg = new sgpage($aid);
    $sg->SaveToHtml();
    ShowMsg("成功更新一个页面！", $fileurl);
    exit();
}
else if($dopost=="mkall")
{
    include_once(DEDEINC."/arc.sgpage.class.php");
    $dsql->Execute("ex","SELECT aid FROM `#@__sgpage` ");
    $i = 0;
    while($row = $dsql->GetArray("ex"))
    {
        $sg = new sgpage($row['aid']);
        $sg->SaveToHtml();
        $i++;
    }
    ShowMsg("成功更新 $i 个页面！", '-1');
    exit();
}
else if($dopost=="mksel")
{
    if(empty($ids))
    {
        $ids = '';
    }
    include_once(DEDEINC."/arc.sgpage.class.php");
    $i = 0;
    if($ids == 0)
    {
        ShowMsg('您没有选择需要更新的文档！','-1');
        exit();
    }
    else if(is_array($ids))
    {
        foreach($ids as $aid)
        {
            $sg = new sgpage($aid);
            $sg->SaveToHtml();
            $i++;
        }
        ShowMsg("成功更新 $i 个页面！",'-1');
        exit();
    }
}
$row = $dsql->GetOne("SELECT  * FROM `#@__sgpage` WHERE aid='$aid' ");
include(DEDEADMIN."/templets/templets_one_edit.htm");