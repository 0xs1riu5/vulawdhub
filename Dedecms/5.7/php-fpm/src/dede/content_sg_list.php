<?php
/**
 * 单表模型列表
 *
 * @version        $Id: content_sg_list.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
$cid = isset($cid) ? intval($cid) : 0;
$channelid = isset($channelid) ? intval($channelid) : 0;
$mid = isset($mid) ? intval($mid) : 0;
if(!isset($keyword)) $keyword = '';
if(!isset($arcrank)) $arcrank = '';

if(empty($cid) && empty($channelid))
{
    ShowMsg("该页面必须指定栏目ID或内容模型ID才能浏览！","javascript:;");
    exit();
}

//检查权限许可，总权限
CheckPurview('a_List,a_AccList,a_MyList');

//栏目浏览许可
if(TestPurview('a_List'))
{

}
else if(TestPurview('a_AccList'))
{
    if($cid==0)
    {
        $ucid = $cid = $cuserLogin->getUserChannel();
    }
    else
    {
        CheckCatalog($cid,"你无权浏览非指定栏目的内容！");
    }
}

$adminid = $cuserLogin->getUserID();
$maintable = '#@__archives';
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEADMIN."/inc/inc_list_functions.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$tl = new TypeLink($cid);
$listtable = trim($tl->TypeInfos['addtable']);
if( !empty($channelid) && !empty($ucid) && $tl->TypeInfos['channeltype'] != $channelid)
{
  ShowMsg('你没权限访问此页！','javascript:;');
  exit();
}

if($cid==0)
{
    $row = $tl->dsql->GetOne("SELECT typename,addtable FROM `#@__channeltype` WHERE id='$channelid'");
    $positionname = $row['typename']." &gt; ";
    $listtable = $row['addtable'];
}
else
{
    $positionname = str_replace($cfg_list_symbol, " &gt; ", $tl->GetPositionName())." &gt; ";
}

$optionarr = $tl->GetOptionArray($cid, $admin_catalogs, $channelid);
$whereSql = $channelid==0 ? " WHERE arc.channel < -1 " : " WHERE arc.channel = '$channelid' ";

if(!empty($mid)) $whereSql .= " AND arc.mid = '$mid' ";
if($keyword!='') $whereSql .= " AND (arc.title like '%$keyword%') ";
if($cid!=0) $whereSql .= " AND arc.typeid in (".GetSonIds($cid).")";

if($arcrank!='')
{
    $whereSql .= " AND arc.arcrank = '$arcrank' ";
    $CheckUserSend = "<input type='button' class='coolbg np' onClick=\"location='content_sg_list.php?cid={$cid}&channelid={$channelid}&dopost=listArchives';\" value='所有文档' />";
}
else
{
    $CheckUserSend = "<input type='button' class='coolbg np' onClick=\"location='content_sg_list.php?cid={$cid}&channelid={$channelid}&dopost=listArchives&arcrank=-1';\" value='稿件审核' />";
}

$query = "SELECT arc.aid,arc.aid as id,arc.typeid,arc.arcrank,arc.flag,arc.senddate,arc.channel,arc.title,arc.mid,arc.click,tp.typename,ch.typename as channelname
FROM `$listtable` arc
LEFT JOIN `#@__arctype` tp ON tp.id=arc.typeid
LEFT JOIN `#@__channeltype` ch ON ch.id=arc.channel
$whereSql
ORDER BY arc.aid DESC";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("dopost", "listArchives");
$dlist->SetParameter("keyword", $keyword);
$dlist->SetParameter("cid", $cid);
$dlist->SetParameter("channelid", $channelid);
$dlist->SetTemplate(DEDEADMIN."/templets/content_sg_list.htm");
$dlist->SetSource($query);
$dlist->Display();
$dlist->Close();