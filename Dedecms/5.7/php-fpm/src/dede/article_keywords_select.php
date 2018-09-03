<?php
/**
 * 文档关键词选择
 *
 * @version        $Id: article_keywords_select.php 1 8:26 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(empty($keywords)) $keywords = "";

$sql = "SELECT * FROM #@__keywords ORDER BY rank DESC";
$dlist = new DataListCP();
$dlist->SetTemplate(DEDEADMIN."/templets/article_keywords_select.htm");
$dlist->pageSize = 300;
$dlist->SetParameter("f",$f);
$dlist->SetSource($sql);
$dlist->Display();

function GetSta($sta)
{
    if($sta==1) return "正常";
    else return "<font color='red'>禁用</font>";
}

function GetMan($sta)
{
    if($sta==1) return "<u>禁用</u>";
    else return "<u>启用</u>";
}