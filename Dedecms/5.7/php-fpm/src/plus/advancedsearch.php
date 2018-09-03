<?php
/**
 *
 * 高级搜索
 *
 * @version        $Id: advancedsearch.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/datalistcp.class.php");
$timestamp = time();
@session_start();

//限制同时搜索数量
$timelock = '../data/time.lock';
if($cfg_allsearch_limit < 1)
{
    $cfg_allsearch_limit = 1;
}
if(file_exists($timelock))
{
    if($timestamp - filemtime($timelock) < $cfg_allsearch_limit)
    {
        showmsg('服务器忙，请稍后搜索','-1');
        exit();
    }
}
@touch($timelock,$timestamp);
$mid = isset($mid) && is_numeric($mid) ? $mid : 0;
$sqlhash = isset($sqlhash) && preg_match("/^[A-Za-z0-9]+$/", $sqlhash) ? $sqlhash : '';

if($mid == 0)
{
    showmsg('参数不正确，高级自定义搜索必须指定模型id', 'javascript');
    exit();
}

$query = "SELECT maintable, mainfields, addontable, addonfields, template FROM #@__advancedsearch WHERE mid='$mid'";
$searchinfo = $dsql->GetOne($query);
if(!is_array($searchinfo))
{
    showmsg('自定义搜索模型不存在','-1');
    exit();
}
$template = $searchinfo['template'] != '' ?  $searchinfo['template'] : 'advancedsearch.htm';
$sql = empty($_SESSION[$sqlhash])? '' : $_SESSION[$sqlhash];

if(empty($sql))
{
    //主表字段处理
    $q = stripslashes($q);
    $q = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", " ", trim($q));
    if( ($cfg_notallowstr!='' && preg_match("#".$cfg_notallowstr."#i", $q)) || ($cfg_replacestr!='' && preg_match("#".$cfg_replacestr."#i", $q)) )
    {
        echo "你的信息中存在非法内容，被系统禁止！<a href='javascript:history.go(-1)'>[返回]</a>"; exit();
    }
    $q = addslashes($q);
    $iscommend = isset($iscommend) && is_numeric($iscommend) ? $iscommend : 0;
    $typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
    $typeid = max($typeid, 0);
    $includesons = isset($includesons) ? 1 : 0;
    $writer = isset($writer) ? trim($writer) : '';
    $source = isset($source) ? trim($source) : '';
    $startdate = isset($startdate) ? trim($startdate) : '';
    $enddate = isset($enddate) ? trim($enddate) : '';
    if($startdate != '') $starttime = strtotime($startdate);
    else $starttime = 0;
        
    if($enddate != '') $endtime = strtotime($enddate);
    else $endtime = 0;
    $where = ' WHERE main.arcrank>-1 ';

    if($q != '') $where .= " AND main.title LIKE '%$q%' ";
    if($iscommend == 1) $where .= " AND FIND_IN_SET('c', main.flag)>0 ";
    if(!empty($typeid))
    {
        if($includesons == 1)
        {
            $tids =  TypeGetSunID($typeid, $dsql, '', $mid, TRUE);
            $where .= " AND main.typeid IN ($tids) ";
        }
        else
        {
            $where .= " AND main.typeid=$typeid ";
        }
    }
    else
    {
        $where .= " AND main.channel = $mid ";
    }
    if($writer != '')
    {
        $writer = stripslashes($writer);
        $writer = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", "", trim($writer));
        $writer = addslashes($writer);
        $where .= " AND main.writer='$writer' ";
    }
    if($source != '')
    {
        $source = stripslashes($source);
        $source = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", "", trim($source));
        $source = addslashes($source);
        $where .= " AND main.source='$source' ";
    }
    if($starttime > 0) $where .= " AND main.senddate>$starttime ";
    if($endtime > 0) $where .= " AND main.senddate<$endtime";
    $maintable = $searchinfo['maintable'];
    $addontable = $searchinfo['addontable'];
    $mainfields = $searchinfo['mainfields'];
    $addonfields = $searchinfo['addonfields'];
    $mainfieldsarr = explode(',', $mainfields);
    $addonfieldsarr = explode(',', $addonfields);
    array_pop($addonfieldsarr);//弹出

    $intarr = array('int','float');
    $textarr = array('textdata','textchar','text','htmltext','multitext');
    foreach($addonfieldsarr as $addonfield)
    {
        $addonfieldarr = explode(':', $addonfield);
        $var = $addonfieldarr[0];
        $type = $addonfieldarr[1];
        if(in_array($type, $intarr))
        {
            if(isset(${'start'.$var}) && trim(${'start'.$var})!='')
            {
                ${'start'.$var} = trim(${'start'.$var});
                ${'start'.$var} = intval(${'start'.$var});
                $where .= " AND addon.$var>${'start'.$var} ";
            }
            if(isset(${'end'.$var}) && trim(${'end'.$var})!='')
            {
                ${'end'.$var} = trim(${'end'.$var});
                ${'end'.$var} = intval(${'end'.$var});
                $where .= " AND addon.$var<${'end'.$var} ";
            }
        }
        elseif(in_array($type, $textarr))
        {
            if(isset(${$var}) && trim(${$var})!='')
            {
                ${$var} = stripslashes(${$var});
                ${$var} = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", "", trim(${$var}));
                ${$var} = addslashes(${$var});
                $where .= " AND addon.$var LIKE '%${$var}%'";
            }
        }
        elseif($type == 'select')
        {
            ${$var} = stripslashes(${$var});
            ${$var} = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", "", trim(${$var}));
            ${$var} = addslashes(${$var});
            if(${$var} != '')
            {
                $where .= " AND addon.$var LIKE '${$var}'";
            }
        }
        elseif($type == 'radio')
        {
            ${$var} = stripslashes(${$var});
            ${$var} = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", "", trim(${$var}));
            ${$var} = addslashes(${$var});
            if(${$var} != '')
            {
                $where .= " AND addon.$var LIKE '${$var}'";
            }
        }
        elseif($type == 'checkbox')
        {
            if(is_array(${$var}) && !empty(${$var}))
            {
                foreach(${$var} as $tmpvar)
                {
                    $tmpvar = trim($tmpvar);
                    if($tmpvar != '')
                    {
                        $tmpvar = stripslashes($tmpvar);
                        $tmpvar = preg_replace("#[\|\"\r\n\t%\*\?\(\)\$;,'%<>]#", "", trim($tmpvar));
                        $tmpvar = addslashes($tmpvar);
                        $where .= " AND CONCAT(',',addon.$var, ',') LIKE '%,$tmpvar,%' ";
                    }
                }
            }
        }
        elseif($type == 'datetime')
        {
            ${'start'.$var} = trim(${'start'.$var});
            if(${'start'.$var} != '')
            {
                ${'start'.$var} = strtotime(${'start'.$var});
            }
            else
            {
                ${'start'.$var} = 0;
            }
            ${'end'.$var} = trim(${'end'.$var});
            if(${'end'.$var} != '')
            {
                ${'end'.$var} = strtotime(${'end'.$var});
            }
            else
            {
                ${'end'.$var} = 0;
            }
        }
    }
    $orderby = ' order by main.senddate desc ';
    if($mid < -1)
    {
        $where = str_replace('main.', 'addon.', $where);
        $orderby = str_replace('main.', 'addon.', $orderby);
        $query = "SELECT addon.*, arctype.* FROM $addontable addon 
        LEFT JOIN #@__arctype arctype ON arctype.id = addon.typeid
        $where $orderby";
    } else {
        $query = "SELECT main.id AS aid,main.*,main.description AS description1, type.* 
    FROM $maintable main 
    LEFT JOIN #@__arctype type ON type.id = main.typeid 
    LEFT JOIN $addontable addon ON addon.aid = main.id 
    $where  $orderby";
    }
    $sql = $query;
}
else
{
    $sql = urldecode($sql);
    $query = $sql;
}

$sql = urlencode($sql);
//生成sql的唯一序列化字符串,并将sql语句记录到session中去
$sqlhash = md5($sql);
$_SESSION[$sqlhash] = $sql;

$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("hash", $sqlhash);
$dlist->SetParameter("mid", $mid);
if(file_exists(DEDEROOT."/templets/default/$template"))
{
    $templatefile = DEDEROOT."/templets/default/$template";
}
else
{
    $templatefile = DEDEROOT."/templets/default/advancedsearch.htm";
}
$dlist->SetTemplate($templatefile);
$dlist->SetSource($query);
require_once(DEDEINC."/channelunit.class.php");

//获得一个指定档案的链接
function GetArcUrl($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$artdir='',$money=0)
{
    return GetFileUrl($aid,$typeid,$timetag,$title,$ismake,$rank,$namerule,$artdir,$money);
}
$dlist->Display();