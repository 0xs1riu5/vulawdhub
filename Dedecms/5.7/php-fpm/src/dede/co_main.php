<?php
/**
 * 采集规则管理
 *
 * @version        $Id: co_main.php 1 17:13 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL", $dedeNowurl, time()+3600, "/");
$sql  = "SELECT co.nid,co.channelid,co.notename,co.sourcelang,co.uptime,co.cotime,co.pnum,ch.typename";
$sql .= " FROM `#@__co_note` co LEFT JOIN `#@__channeltype` ch ON ch.id=co.channelid ORDER BY co.nid DESC";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/co_main.htm");
$dlist->SetSource($sql);
$dlist->display();

function GetDatePage($mktime)
{
    return $mktime=='0' ? '从未采集过' : MyDate('Y-m-d',$mktime);
}

function TjUrlNum($nid)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__co_htmls` WHERE nid='$nid' ");
    return $row['dd'];
}