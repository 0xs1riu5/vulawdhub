<?php
/**
 * 搜索关键词管理
 *
 * @version        $Id: search_keywords_main.php 1 15:46 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(empty($pagesize)) $pagesize = 30;
if(empty($pageno)) $pageno = 1;
if(empty($dopost)) $dopost = '';
if(empty($orderby)) $orderby = 'aid';

//重载列表
if($dopost=='getlist')
{
    AjaxHead();
    GetKeywordList($dsql,$pageno,$pagesize,$orderby);
    exit();
}
//更新字段
else if($dopost=='update')
{
    $aid = preg_replace("#[^0-9]#", "", $aid);
    $count = preg_replace("#[^0-9]#", "", $count);
    $keyword = trim($keyword);
    $spwords = trim($spwords);
    $dsql->ExecuteNoneQuery("UPDATE `#@__search_keywords` SET keyword='$keyword',spwords='$spwords',count='$count' WHERE aid='$aid';");
    AjaxHead();
    GetKeywordList($dsql, $pageno, $pagesize, $orderby);
    exit();
}
//删除字段
else if($dopost=='del')
{
    $aid = preg_replace("#[^0-9]#", "", $aid);
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__search_keywords` WHERE aid='$aid';");
    AjaxHead();
    GetKeywordList($dsql, $pageno, $pagesize, $orderby);
    exit();
}
//批量删除字段
else if($dopost=='delall')
{
    foreach($aids as $aid)
    {
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__search_keywords` WHERE aid='$aid';");
    }
    ShowMsg("删除成功！",$ENV_GOBACK_URL);
    exit();
}
//第一次进入这个页面
if($dopost=='')
{
    $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__search_keywords` ");
    $totalRow = $row['dd'];
    include(DEDEADMIN."/templets/search_keywords_main.htm");
}

//获得特定的关键字列表
function GetKeywordList($dsql,$pageno,$pagesize,$orderby='aid')
{
    global $cfg_phpurl;
    $start = ($pageno-1) * $pagesize;
    $printhead ="<form name='form3' action=\"search_keywords_main.php\" method=\"post\">
    <input name=\"dopost\" type=\"hidden\" value=\"\">
    <table width='98%' border='0' cellpadding='1' cellspacing='1' bgcolor='#cfcfcf' style='margin-bottom:3px' align='center'>
    <tr align='center' bgcolor='#FBFCE2' height='24'>
      <td width='5%'>选择</td>
      <td width='6%' height='23'><a href='#' onclick=\"ReloadPage('aid')\"><u>ID</u></a></td>
      <td width='20%'>关键字</td>
      <td width='35%'>分词结果</td>
      <td width='6%'><a href='#' onclick=\"ReloadPage('count')\"><u>频率</u></a></td>
      <td width='6%'><a href='#' onclick=\"ReloadPage('result')\"><u>结果</u></a></td>
      <td width='15%'><a href='#' onclick=\"ReloadPage('lasttime')\"><u>最后搜索时间</u></a></td>
      <td>管理</td>
    </tr>\r\n
    ";
    echo $printhead;
    if($orderby=='result') $orderby = $orderby." ASC";
    else $orderby = $orderby." DESC";
    $dsql->SetQuery("SELECT * FROM #@__search_keywords ORDER BY $orderby LIMIT $start,$pagesize ");
    $dsql->Execute();
    while($row = $dsql->GetArray())
    {
        $line = "
      <tr align='center' bgcolor='#FFFFFF' onMouseMove=\"javascript:this.bgColor='#FCFDEE';\" onMouseOut=\"javascript:this.bgColor='#FFFFFF';\">
      <td height='24'><input name=\"aids[]\" type=\"checkbox\" class=\"np\" value=\"{$row['aid']}\" /></td>
      <td height='24'>{$row['aid']}</td>
      <td style='padding:5px;'><input name='keyword' type='text' id='keyword{$row['aid']}' value='{$row['keyword']}' style='width:93%;'></td>
      <td style='padding:5px;'><input name='spwords' type='text' id='spwords{$row['aid']}' value='{$row['spwords']}' style='width:95%;'></td>
      <td style='padding:5px;'><input name='count' type='text' id='count{$row['aid']}' value='{$row['count']}' size='5'></td>
      <td><a href='{$cfg_phpurl}/search.php?kwtype=0&keyword=".urlencode($row['keyword'])."&searchtype=titlekeyword' target='_blank'><u>{$row['result']}</u></a></td>
      <td>".MyDate("Y-m-d H:i:s",$row['lasttime'])."</td>
      <td>
      <a href='#' onclick='UpdateNote({$row['aid']})'>更新</a> |
      <a href='#' onclick='DelNote({$row['aid']})'>删除</a>
      </td>
    </tr>
    ";
        echo $line;
    }
    echo "  <tr align='left' bgcolor='#ffffff' height='30'>
            <td colspan='8' style='padding-left:10px;'>
            <a href='javascript:selAll()' class='coolbg np'>反选</a>
            <a href='javascript:noselAll()' class='coolbg np'>取消</a>
            <a href='javascript:delall()' class='coolbg np'>删除</a>
           </td>
           </tr>\r\n";
    echo "</table></form>\r\n";
}