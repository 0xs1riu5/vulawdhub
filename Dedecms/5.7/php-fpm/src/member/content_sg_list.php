<?php
/**
 * 单表模型内容列表
 * 
 * @version        $Id: content_sg_list.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEMEMBER."/inc/inc_list_functions.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$cid = isset($cid) && is_numeric($cid) ? $cid : 0;
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 0;
$mtypesid = isset($mtypesid) && is_numeric($mtypesid) ? $mtypesid : 0;
if(!isset($keyword)) $keyword = '';
if(!isset($arcrank)) $arcrank = '';

$positionname = '';
$menutype = 'content';
$mid = $cfg_ml->M_ID;
$tl = new TypeLink($cid);
$cInfos = $tl->dsql->GetOne("SELECT arcsta,issend,issystem,usertype,typename,addtable FROM `#@__channeltype`  WHERE id='$channelid'; ");
if(!is_array($cInfos))
{
    ShowMsg('模型不存在', '-1');
    exit();
}
$arcsta = $cInfos['arcsta'];

//禁止访问无权限的模型
if($cInfos['usertype'] !='' && $cInfos['usertype']!=$cfg_ml->M_MbType)
{
    ShowMsg('你无权限访问该部分', '-1');
    exit();
}

if($cid==0)
{
    $positionname = $cInfos['typename']." &gt;&gt; ";
}
else
{
    $positionname = str_replace($cfg_list_symbol," &gt;&gt; ",$tl->GetPositionName())." &gt;&gt; ";
}
$whereSql = " WHERE arc.channel = '$channelid' AND arc.mid='$mid' ";
if($keyword!='')
{
    $keyword = cn_substr(trim(preg_replace("#[><\|\"\r\n\t%\*\.\?\(\)\$ ;,'%-]#", "", stripslashes($keyword))),30);
    $keyword = addslashes($keyword);
    $whereSql .= " AND (arc.title like '%$keyword%') ";
}
if($cid!=0)
{
    $whereSql .= " AND arc.typeid in (".GetSonIds($cid).")";
}

$query = "SELECT arc.aid,arc.aid as id,arc.typeid,arc.senddate,arc.channel,arc.click,arc.title,arc.mid,tp.typename
        FROM `{$cInfos['addtable']}` arc
        LEFT JOIN `#@__arctype` tp ON tp.id=arc.typeid
        $whereSql
        ORDER BY arc.aid desc ";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("dopost","listArchives");
$dlist->SetParameter("keyword",$keyword);
$dlist->SetParameter("cid",$cid);
$dlist->SetParameter("channelid",$channelid);
$dlist->SetTemplate(DEDEMEMBER."/templets/content_sg_list.htm");
$dlist->SetSource($query);
$dlist->Display();