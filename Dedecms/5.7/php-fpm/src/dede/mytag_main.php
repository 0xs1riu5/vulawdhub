<?php
/**
 * 自定义模型管理
 *
 * @version        $Id: mychannel_main.php 1 15:26 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckPurview('temp_Other');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,'/');

$sql = "SELECT myt.aid,myt.tagname,tp.typename,myt.timeset,myt.endtime
        FROM `#@__mytag` myt LEFT JOIN `#@__arctype` tp ON tp.id=myt.typeid ORDER BY myt.aid DESC ";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN.'/templets/mytag_main.htm');
$dlist->SetSource($sql);
$dlist->display();

function TestType($tname)
{
    return $tname=='' ? '所有栏目' : $tname;
}

function TimeSetValue($ts)
{
    return $ts==0 ? '不限时间' : '限时标记';
}