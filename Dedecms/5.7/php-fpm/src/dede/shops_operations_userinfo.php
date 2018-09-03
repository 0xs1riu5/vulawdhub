<?php
/**
 * 用户信息
 *
 * @version        $Id: shops_operations_userinfo.php 1 16:09 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('shops_Operations');
if(!isset($oid)) exit("<a href='javascript:window.close()'>无效操作!</a>");
$oid     = preg_replace("#[^-0-9A-Z]#", "", $oid);
if(empty($oid)) exit("<a href='javascript:window.close()'>无效订单号!</a>");

$rows = $dsql->GetOne("SELECT * FROM #@__shops_userinfo WHERE oid='$oid' LIMIT 0,1");
if(!is_array($rows)){
    $dsql->Close();
    exit("<a href='javascript:window.close()'>该订单下没相关用户信息!</a>");
}

$row = $dsql->GetOne("SELECT pid,dprice FROM #@__shops_orders WHERE oid='$oid'");
if(is_array($row))
{
    $rs = $dsql->GetOne("SELECT dname FROM #@__shops_delivery WHERE pid='$row[pid]'");
    $rows['dname'] = $rs['dname'];
    $rows['dprice'] = $row['dprice'];
}
$rows['des'] = stripslashes($rows['des']);
include DEDEADMIN."/templets/shops_operations_userinfo.htm";
unset($rows);