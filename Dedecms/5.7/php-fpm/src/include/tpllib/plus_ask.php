<?php
if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 动态模板ask标签
 *
 * @version        $Id: plus_ask.php 1 13:58 2010年7月5日Z tianya $
 * @package        DedeCMS.Tpllib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
function plus_ask(&$atts,&$refObj,&$fields)
{
    global $dsql,$_vars;

    $attlist = "titlelen=40,row=8,typeid=0,sort=";
    FillAtts($atts,$attlist);
    FillFields($atts,$fields,$refObj);
    extract($atts, EXTR_OVERWRITE);

    $wheresql = ' 1 ';
    if($sort=='') 
    {
        $orderby = 'ORDER BY id DESC';
    }
    else if($sort=='commend')
    {
        $wheresql .= ' And digest=1';
        $orderby = ' ORDER BY dateline DESC';
    }
    else if($sort=='ok')
    {
        $wheresql .= ' And status=1 ';
        $orderby = ' ORDER BY solvetime DESC';
    }
    else if($sort=='expiredtime')
    {
        $wheresql .= ' And status=0 ';
        $orderby = ' ORDER BY expiredtime ASC, dateline DESC';
    }
    else if($sort=='reward')
    {
        $wheresql .= ' And status=0 ';
        $orderby = ' ORDER BY reward DESC';
    }
    else
    {
        $wheresql .= ' And status=0 ';
        $orderby = ' ORDER BY disorder DESC, dateline DESC';
    }
    $query = "SELECT id, tid, tidname, tid2, tid2name, title FROM `#@__ask` WHERE $wheresql $orderby LIMIT $row";
    $dsql->SetQuery($query);
    $dsql->Execute('an');
    $rearr = array();
    while($row = $dsql->GetArray('an'))
    {
        if($row['tid2'] != 0)
            $row['typelink'] = $row['typedata'] = " <a href='browser.php?tid2={$row['tid2']}'>{$row['tid2name']}</a>\r\n";
        else
            $row['typelink'] = $row['typedata'] = " <a href='browser.php?tid={$row['tid']}'>{$row['tidname']}</a>\r\n";
        $row['title'] = cn_substr($row['title'],$titlelen);
        $rearr[] = $row;
    }
    return $rearr;
}