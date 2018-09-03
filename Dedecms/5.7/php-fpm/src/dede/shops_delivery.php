<?php
/**
 * 配送方式设置
 *
 * @version        $Id: shops_delivery.php 1 15:46 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('shops_Delivery');
require_once DEDEINC.'/datalistcp.class.php';

if(!isset($do)) $do ='';
if($do=='add')
{
    if( empty($dname) || (strlen($dname) > 100) )
    {
        ShowMsg("请填写配送方式名称!","-1");
        exit();
    }
    $price     = preg_replace("#[^.0-9]#", "", $price);
    if($price < 0.01)
    {
        $price = '0.00';
    }
    $des = cn_substrR($des,255);
    $InQuery = "INSERT INTO #@__shops_delivery(`dname`,`price`,`des`) VALUES ('$dname','$price','$des');";
    $result = $dsql->ExecuteNoneQuery($InQuery);
    if($result)
    {
        ShowMsg("成功添加一个配送方式!","shops_delivery.php");
    }
    else
    {
        ShowMsg("添加配送方式时发生SQL错误!","-1");
    }
    exit();
} else if ($do == 'del')
{
    $id = intval($id);
    $dsql->ExecuteNoneQuery("DELETE FROM #@__shops_delivery WHERE pid='$id'");
    ShowMsg("已删除当前配送方式!","shops_delivery.php");
    exit();
} else if ($do == 'edit')
{
    foreach($pid as $id)
    {
        $id = intval($id);
        $row = $dsql->GetOne("SELECT pid,dname,price,des FROM #@__shops_delivery WHERE pid='$id' LIMIT 0,1");
        if(!is_array($row))
        {
            continue;
        }
        $dname = ${"m_dname".$id};
        $price = ${"m_price".$id};
        $des = ${"m_des".$id};
        if( empty($dname) || (strlen($dname) > 100) )
        {
            $dname = addslashes($row['dname']);
        }
        $price = preg_replace("#[^.0-9]#", "", $price);
        if(empty($price))
        {
            $price = $row['price'];
        }
        if(empty($des))
        {
            $des = addslashes($row['des']);
        }
        else
        {
            $des = cn_substrR($des,255);
        }
        $dsql->ExecuteNoneQuery("UPDATE #@__shops_delivery SET dname='$dname',price='$price',des='$des' WHERE pid='$id'");
    }
    ShowMsg("成功修改配送方式!","shops_delivery.php");
    exit();
}
$deliveryarr = array();
$dsql->SetQuery("SELECT pid,dname,price,des FROM #@__shops_delivery ORDER BY orders ASC");
$dsql->Execute();
while($row = $dsql->GetArray())
{
    $deliveryarr[] = $row;
}
$dlist = new DataListCP();
$dlist->pageSize = 25;              //设定每页显示记录数（默认25条）

//这两句的顺序不能更换
$dlist->SetTemplate(DEDEADMIN."/templets/shops_delivery.htm");      //载入模板
$dlist->SetSource("SELECT `pid`,`dname`,`price`,`des` FROM #@__shops_delivery ORDER BY `orders` ASC");            //设定查询SQL
$dlist->Display();                  //显示