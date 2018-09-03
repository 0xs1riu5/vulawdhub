<?php
/**
 * 添加一个模板
 *
 * @version        $Id: templets_one_add.php 1 23:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('temp_One');
if(empty($dopost)) $dopost = "";

if($dopost=="save")
{
    require_once(DEDEINC."/arc.partview.class.php");
    $uptime = time();
    $body = str_replace('&quot;', '\\"', $body);
    $filename = preg_replace("#^\/#", "", $nfilename);
    if($likeid=='')
    {
        $likeid = $likeidsel;
    }
    $row = $dsql->GetOne("SELECT filename FROM `#@__sgpage` WHERE likeid='$likeid' AND filename LIKE '$filename' ");
    if(is_array($row))
    {
        ShowMsg("已经存在相同的文件名，请更改为其它文件名！","-1");
        exit();
    }
    $inQuery = "INSERT INTO `#@__sgpage`(title,keywords,description,template,likeid,ismake,filename,uptime,body)
     VALUES('$title','$keywords','$description','$template','$likeid','$ismake','$filename','$uptime','$body'); ";
    if(!$dsql->ExecuteNoneQuery($inQuery))
    {
        ShowMsg("增加页面失败，请检内容是否有问题！","-1");
        exit();
    }
    $id = $dsql->GetLastID();
    include_once(DEDEINC."/arc.sgpage.class.php");
    $sg = new sgpage($id);
    $sg->SaveToHtml();
    ShowMsg("成功增加一个页面！","templets_one.php");
    exit();
}
$row = $dsql->GetOne("SELECT MAX(aid) AS aid FROM `#@__sgpage`  ");
$nowid = is_array($row) ? $row['aid']+1 : '';
include_once(DEDEADMIN."/templets/templets_one_add.htm");