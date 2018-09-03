<?php
/**
 * @version        $Id: story_add_action.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */


require_once(dirname(__FILE__). "/config.php");
CheckPurview('story_New');
require_once(DEDEINC. "/image.func.php");
require_once(DEDEINC. "/oxwindow.class.php");
require_once(dirname(__FILE__). "/inc/inc_archives_functions.php");
if(!isset($iscommend))
{
    $iscommend = 0;
}
if($catid==0)
{
    ShowMsg("请指定图书所属栏目！", "-1");
    exit();
}

//获得父栏目
$nrow = $dsql->GetOne("SELECT * FROM #@__story_catalog WHERE id='$catid' ");
$bcatid = $nrow['pid'];
$booktype = $nrow['booktype'];
$pubdate = GetMkTime($pubdate);
$bookname = cn_substr($bookname,50);
if($keywords!="") $keywords = trim(cn_substr($keywords,60));

if(empty($author))$author=$cuserLogin->getUserName();

//处理上传的缩略图
if($litpic != ""){
    $litpic = GetDDImage('litpic',$litpicname,0);
}
$adminID = $cuserLogin->getUserID();

//自动摘要
if($description=="" && $cfg_auot_description>0)
{
    $description = stripslashes(cn_substr(html2text($body),$cfg_auot_description));
    $description = addslashes($description);
}

$inQuery = "
INSERT INTO `#@__story_books`(`catid`,`bcatid`,`booktype`,`iscommend`,`click`,`freenum`,`bookname`,`author`,`mid`,`litpic`,`pubdate`,`lastpost`,`postnum`,`lastfeedback`,`feedbacknum`,`weekcc`,`monthcc`,`weekup`,`monthup`,`description`,`body`,`keywords`,`userip`,`senddate` ,`arcrank`,`goodpost`,`badpost`,`notpost`) VALUES ('$catid','$bcatid','$booktype', '$iscommend', '$click', '$freenum', '$bookname', '$author', '0', '$litpic', '$pubdate', '$pubdate', '0', '0', '0', '0', '0', '0', '0', '$description' , '$body' , '$keywords', '','".time()."','$arcrank','0','0','0')";
if(!$dsql->ExecuteNoneQuery($inQuery))
{
    ShowMsg("把数据保存到数据库时出错，请检查！","-1");
    exit();
}
$arcID = $dsql->GetLastID();

//生成HTML
require_once(DEDEROOT. '/book/include/story.view.class.php');
$bv = new BookView($arcID, 'book');
$artUrl = $bv->MakeHtml();
$bv->Close();

//返回成功信息
$msg = "
　　请选择你的后续操作：
<a href='./story_add.php?catid=$catid'><u>继续发布图书</u></a>
&nbsp;&nbsp;
<a href='$artUrl' target='_blank'><u>查看图书</u></a>
&nbsp;&nbsp;
<a href='./story_add_content.php?bookid={$arcID}'><u>增加图书内容</u></a>
&nbsp;&nbsp;
<a href='./story_books.php'><u>管理图书</u></a>
";
$wintitle = "成功发布图书！";
$wecome_info = "连载管理::发布图书";
$win = new OxWindow();
$win->AddTitle("成功发布一本图书：");
$win->AddMsgItem($msg);
$winform = $win->GetWindow("hand",  "&nbsp;", false);
$win->Display();
