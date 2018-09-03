<?php
/**
 * 订单操作
 *
 * @version        $Id: shops_operations_cart.php 1 15:46 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckPurview('shops_Operations');

if(!isset($oid)) exit("<a href='javascript:window.close()'>无效操作!</a>");
$oid = preg_replace("#[^-0-9A-Z]#", "", $oid);
if(empty($oid)) exit("<a href='javascript:window.close()'>无效订单号!</a>");

$row = $dsql->GetOne("SELECT * FROM #@__shops_userinfo WHERE oid='$oid'");
$sql="SELECT o.*,p.title,p.price as uprice,d.dname FROM #@__shops_orders as o left join #@__shops_products as p on o.oid=p.oid left join #@__shops_delivery as d on d.pid=o.pid WHERE o.oid='$oid'";

$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("oid",$oid);
$dlist->SetTemplate(DEDEADMIN."/templets/shops_operations_cart.htm");
$dlist->SetSource($sql);
$dlist->Display();
$dlist->Close();

function GetSta($sta,$oid)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT paytype FROM #@__shops_orders WHERE oid='$oid'");
    $payname = $dsql->GetOne("SELECT name,fee FROM #@__payment WHERE id='{$row['paytype']}'");
    if($sta==0)
    {
        return $payname['name']." 手续费:".$payname['fee']."元";
    }
    else if ($sta==1)
    {
        return '<font color="red">已付款,等发货</font>';
    }
    else if ($sta==2)
    {
        return '<a href="shops_products.php?do=ok&oid='.$oid.'">确认</a>';
    }
    else
    {
        return '<font color="red">已完成</font>';
    }
}