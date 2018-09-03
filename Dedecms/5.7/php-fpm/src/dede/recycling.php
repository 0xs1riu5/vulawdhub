<?php
/**
 * 回收站
 *
 * @version        $Id: recycling.php 1 15:46 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckPurview('a_List,a_AccList,a_MyList');
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($cid))
{
    $cid = '0';
    $whereSql = '';
}
if($cid!=0)
{
    require_once(DEDEINC.'/channelunit.func.php');
    $whereSql = " AND arc.typeid IN (".GetSonIds($cid).")";
}
$query = "SELECT arc.*,tp.typename FROM `#@__archives` AS arc
LEFT JOIN #@__arctype AS tp ON arc.typeid = tp.id
WHERE arc.arcrank = '-2' $whereSql order by arc.id desc";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/recycling.htm");
$dlist->SetSource($query);
$dlist->display();