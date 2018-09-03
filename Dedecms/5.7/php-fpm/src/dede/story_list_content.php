<?php
/**
 * @version        $Id: story_list_content.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/config.php");
require_once DEDEINC. '/datalistcp.class.php';
setcookie("ENV_GOBACK_URL", $dedeNowurl, time()+3600,"/");
CheckPurview('story_books');
if(!isset($action)) $action = '';

if(!isset($booktype)) $booktype = '-1';

if(!isset($keyword)) $keyword = "";

if(!isset($orderby)) $orderby = 0;

if(!isset($bookid)) $bookid = 0;

if(!isset($chapid)) $chapid = 0;

$addquery = "";
$orderby = " ORDER BY ct.id DESC ";
if($booktype!='-1') $addquery .= " And ct.booktype='$booktype' ";

if($keyword!="") $addquery .= " And (ct.bookname like '%$keyword%' Or ct.title like '%$keyword%') ";

if($bookid!=0) $addquery .= " And ct.bookid='$bookid' ";

if($chapid!=0) $addquery .= " And ct.chapterid='$chapid' ";

$query = "
   SELECT ct.id,ct.title,ct.bookid,ct.chapterid,ct.sortid,ct.bookname,ct.addtime,ct.booktype,c.chaptername,c.chapnum FROM #@__story_content  ct
   LEFT JOIN #@__story_chapter c ON c.id = ct.chapterid WHERE ct.id>0 $addquery $orderby
";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("keyword", $keyword);
$dlist->SetParameter("booktype", $booktype);
$dlist->SetParameter("bookit", $bookid);
$dlist->SetParameter("chapid", $chapid);
$dlist->SetTemplate(DEDEADMIN. '/templets/story_list_content.htm');
$dlist->SetSource($query);
$dlist->Display();
//ClearAllLink();
