<?php
/**
 * @version        $Id: story_edit_photo_action.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/config.php");
CheckPurview('story_Edit');
include_once(DEDEINC. "/image.func.php");
include_once(DEDEINC. "/oxwindow.class.php");
require_once(DEDEADMIN. "/inc/inc_archives_functions.php");

if( empty($chapterid)
|| (!empty($addchapter) && !empty($chapternew)) )
{
    if(empty($chapternew))
    {
         ShowMsg("由于你发布的内容没选择章节，系统拒绝发布！", "-1");
         exit();
    }
    $dsql = new DedeSql();
    $row = $dsql->GetOne("SELECT * FROM #@__story_chapter WHERE bookid='$bookid' ORDER BY chapnum DESC");
    if(is_array($row)) $nchapnum = $row['chapnum']+1;
    else $nchapnum = 1;
    $query = "INSERT INTO `#@__story_chapter`(`bookid`,`catid`,`chapnum`,`mid`,`chaptername`,`bookname`)
            VALUES ('$bookid', '$catid', '$nchapnum', '0', '$chapternew','$bookname');";
    $rs = $dsql->ExecuteNoneQuery($query);
    if($rs){
        $chapterid = $dsql->GetLastID();
    }
    else
  {
      ShowMsg("增加章节失败，请检查原因！","-1");
        exit();
  }
}else
{
    $dsql = new DedeSql();
}

//获得父栏目
$nrow = $dsql->GetOne("SELECT * FROM #@__story_catalog WHERE id='$catid' ");
$bcatid = $nrow['pid'];
$booktype = $nrow['booktype'];

if(empty($bcatid)) $bcatid = 0;
if(empty($booktype)) $booktype = 0;


$addtime = time();

//处理上传的缩略图
if(!isset($isremote)) $isremote = 0;
$bigpic = UploadOneImage('imgfile',$imgurl,$isremote);

$adminID = $cuserLogin->getUserID();


//----------------------------------
$inQuery = "
   UPDATE `#@__story_content` SET `title`='$title',`bookname`='$bookname',
   `chapterid`='$chapterid',`sortid`='$sortid',`bigpic`='$bigpic'
  WHERE id='$cid'
";

if(!$dsql->ExecuteNoneQuery($inQuery)){
    ShowMsg("把数据保存到数据库时出错，请检查！".str_repolace("'","`", $dsql->GetError().$inQuery), "-1");
    $dsql->Close();
    exit();
}

$arcID = $cid;

//生成HTML
//---------------------------------

//$artUrl = MakeArt($arcID,true);
if(empty($artcontentUrl))$artcontentUrl="";
if($artcontentUrl=="") $artcontentUrl = $cfg_mainsite.$cfg_cmspath."/book/show-photo.php?id=$arcID&bookid=$bookid&chapterid=$chapterid";

require_once(DEDEROOT. '/book/include/story.view.class.php');
$bv = new BookView($bookid, 'book');
$artUrl = $bv->MakeHtml();
$bv->Close();

//---------------------------------
//返回成功信息
//----------------------------------
$msg = "
　　请选择你的后续操作：
<a href='story_photo_edit.php?cid={$cid}'><u>继续修改</u></a>
&nbsp;&nbsp;
<a href='$artUrl' target='_blank'><u>预览漫画</u></a>
&nbsp;&nbsp;
<a href='$artcontentUrl' target='_blank'><u>预览内容</u></a>
&nbsp;&nbsp;
<a href='story_list_content.php?bookid={$bookid}'><u>管理所有内容</u></a>
&nbsp;&nbsp;
<a href='story_books.php'><u>管理所有图书</u></a>
";

$wintitle = "成功修改内容！";
$wecome_info = "连载管理::修改漫画内容";
$win = new OxWindow();
$win->AddTitle("成功发布漫画：");
$win->AddMsgItem($msg);
$winform = $win->GetWindow("hand", "&nbsp;", false);
$win->Display();
//ClearAllLink();
