<?php 
/**
 * 商铺商品
 * 
 * @version        $Id:shops_products.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
include_once DEDEINC.'/datalistcp.class.php';
$menutype = 'mydede';
$menutype_son = 'op';
if(!isset($dopost)) $dopost = '';

if($dopost=='')
{
    $do = isset($do) ? trim($do) : '';
    $oid = isset($oid) ? preg_replace("#[^-0-9A-Z]#i", "", $oid) : '';
    $addsql = '';
    if(!empty($oid))
    {
        if($do=='ok')
        {
            $dsql->ExecuteNoneQuery("UPDATE #@__shops_orders SET `state`='4' WHERE oid='$oid'");
            ShowMsg("已确认订单！",'shops_products.php?oid='.$oid);
            exit();
        }
        
        $row = $dsql->GetOne("SELECT * FROM #@__shops_userinfo WHERE userid='".$cfg_ml->M_ID."' AND oid='$oid'");
        if(!isset($row['oid']))
        {
            ShowMsg("订单不存在！",-1);
            exit();
        }
        $row['des'] = stripslashes($row['des']);
        $rs = $dsql->GetOne("SELECT * FROM #@__shops_orders WHERE userid='".$cfg_ml->M_ID."' AND oid='$oid'");
        $row['state'] = $rs['state'];
        $row['stime'] = $rs['stime'];
        $row['cartcount'] = $rs['cartcount'];
        $row['price'] = $rs['price'];
        $row['uprice'] = $rs['price'] / $rs['cartcount'];
        $row['dprice'] = $rs['dprice'];
        $row['priceCount'] = $rs['priceCount'];
        $rs = $dsql->GetOne("SELECT `dname` FROM #@__shops_delivery WHERE pid='$rs[pid]' LIMIT 0,1");
        $row['dname'] = $rs['dname'];
        unset($rs);
        $addsql = " AND oid='".$oid."'";
    }
    
    $sql = "SELECT * FROM #@__shops_products WHERE userid='".$cfg_ml->M_ID."' $addsql ORDER BY aid ASC";
    $dl = new DataListCP();
    $dl->pageSize = 20;
    if(!empty($oid)) $dl->SetParameter('oid',$oid);
    //这两句的顺序不能更换
    $dl->SetTemplate(dirname(__FILE__)."/templets/shops_products.htm");      //载入模板
    $dl->SetSource($sql);            //设定查询SQL
    $dl->Display(); 
}
else if ($dopost=='del')
{
    $ids = explode(',',$ids);
    if(isset($ids) && is_array($ids))
    {
        foreach($ids as $id)
        {
            $id = preg_replace("/^[a-z][0-9]$/","",$id);
            $query = "DELETE FROM `#@__shops_products` WHERE oid='$id' AND userid='{$cfg_ml->M_ID}'";
            $query2 = "DELETE FROM `#@__shops_orders` WHERE oid='$id' AND userid='{$cfg_ml->M_ID}'";
            $query3 = "DELETE FROM `#@__shops_userinfo` WHERE oid='$id' AND userid='{$cfg_ml->M_ID}'";
            $dsql->ExecuteNoneQuery($query);
            $dsql->ExecuteNoneQuery($query2);
            $dsql->ExecuteNoneQuery($query3);
        }
        ShowMsg("成功删除指定的交易记录!","shops_products.php");
      exit();
    }
}

/**
 *  获取状态
 *
 * @param     string  $sta  状态ID
 * @param     string  $oid  订单ID
 * @return    string
 */
function GetSta($sta,$oid)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT paytype FROM #@__shops_orders WHERE oid='$oid'");
    $payname = $dsql->GetOne("SELECT name,fee FROM #@__payment WHERE id='{$row['paytype']}'");
    if($sta==0)
    {
        return $payname['name']." 手续费:".$payname['fee']."元";
    }
    elseif($sta==1)
    {
        return '已付款,等发货';
    }
    elseif($sta==2)
    {
        return '<a href="shops_products.php?do=ok&oid='.$oid.'">确认</a>';
    }
    else
    {
        return '已完成';
    }
}

/**
 *  购物车时间
 *
 * @param     string  $oid  订单ID
 * @return    string
 */
function carTime($oid)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT stime FROM #@__shops_orders WHERE oid='$oid'");
    return Mydate('Y-m-d h:i:s',$row['stime']);
}