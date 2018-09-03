<?php
/**
 *
 * 购物车过程
 *
 * @version        $Id: carbuyaction.php 1 20:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once (dirname(__FILE__) . "/../include/common.inc.php");
define('_PLUS_TPL_', DEDEROOT.'/templets/plus');
require_once DEDEINC.'/dedetemplate.class.php';
require_once DEDEINC.'/shopcar.class.php';
require_once DEDEINC.'/memberlogin.class.php';

if($cfg_mb_open == 'N')
{
    ShowMsg("系统关闭了会员功能，因此你无法访问此页面！","javascript:;");
    exit();
}

$cfg_ml = new MemberLogin();

if(!isset($dopost) || empty($dopost)){
    $payment = 'none';
    $cart    = new MemberShops();
    
    //获得购物车内商品,返回数组
    $Items = $cart->getItems();
    if(empty($Items))
    {
        ShowMsg("您的购物车中没有商品！","-1");
        exit();
    }
    
    $OrdersId = $cart->OrdersId;        //本次记录的订单号
    $CartCount     = $cart->cartCount();    //商品总数
    $priceCount    = $cart->priceCount();//该订单总价格
    
    /*
    function PostOrdersForm();                //填写订单信息
    */
    
    if(!isset($do) || empty($do))
    {
        $shops_deliveryarr = array();
        $dsql->SetQuery("SELECT pid,dname,price,des FROM #@__shops_delivery ORDER BY orders ASC");
        $dsql->Execute();
        while($row = $dsql->GetArray())
        {
            $shops_deliveryarr[] = $row;
        }

        //获取支付接口列表
        $shops_paymentarr = array();
        $dsql->SetQuery("SELECT * FROM #@__payment WHERE enabled='1' ORDER BY rank ASC");
        $dsql->Execute();
        $i = 0 ;
        while($row = $dsql->GetArray())
        {
            $row['disabled'] = ($row['id'] == 5) && ($cfg_ml->M_Money < $priceCount) ? ' disabled="disabled"' : '';
            $shops_paymentarr[] = $row;
            $i++;
        }
        unset($row);
        
        $dtp = new DedeTemplate();
        
        $carts = array(
            'orders_id' => $cart->OrdersId,
            'cart_count' => $cart->cartCount(),
            'price_count' => $cart->priceCount()
        );
        $dtp->Assign('carts',$carts);
        $dtp->LoadTemplate(_PLUS_TPL_.'/carbuyaction.htm');
        $dtp->Display();
        exit();
    } else if ($do == 'clickout')
    {
        $svali = GetCkVdValue();
        if((strtolower($vdcode) != $svali || $svali == "") && $payment == 'none')
        {
            ShowMsg("验证码错误！","-1");
            exit();
        }
        if(empty($address))
        {
            ShowMsg("请填写收货地址！","-1");
            exit();
        }
        if(empty($postname))
        {
            ShowMsg("请填写收货人姓名！","-1");
            exit();
        }
        $paytype    = isset($paytype) && is_numeric($paytype) ? $paytype : 0;
        $pid        = isset($pid) && is_numeric($pid) ? $pid : 0;
        if($paytype < 1)
        {
            ShowMsg("请选择支付方式！","-1");
            exit();
        }
        if($pid < 1)
        {
            ShowMsg("请选择配送方式！","-1");
            exit();
        }
        $address     = cn_substrR(trim($address),200);
        $des             = cn_substrR($des,100);
        $postname = cn_substrR(trim($postname),15);
        $tel            = preg_replace("#[^-0-9,\/\| ]#", "", $tel);
        $zip            = preg_replace("#[^0-9]#", "", $zip);
        $email        = cn_substrR($email,255);
        if(empty($tel))
        {
            ShowMsg("请填写正确的收货人联系电话！","-1");
            exit();
        }
        if($zip<1 || $zip>999999)
        {
            ShowMsg("请填写正确的收货人邮政编码！","-1");
            exit();
        }
    
        //确认用户登录信息
        if($cfg_ml->IsLogin())
        {
            $userid = $cfg_ml->M_ID;
        }
        else
        {
            $username = trim($username);
            $password = trim($password);
            
            if(empty($username) || $password)
            {
                ShowMsg("请选登录！","-1",0,2000);
                exit();
            }
            
            $rs = $cfg_ml->CheckUser($username,$password);
            if($rs==0)
            {
                ShowMsg("用户名不存在！","-1",0,2000);
                exit();
            }
            else if($rs==-1)
            {
                ShowMsg("密码错误！","-1",0,2000);
                exit();
            }
            $userid = $cfg_ml->M_ID;
        }
    
        //取得配送手续费
        $rs = $dsql->GetOne("SELECT `price` FROM #@__shops_delivery WHERE pid='$pid' LIMIT 0,1");
        $dprice = $rs['price'] > 0 ? $rs['price'] : 0;
        unset($rs);
        //
        //取得支付方式手续费
        $row = $dsql->GetOne("SELECT `fee` FROM #@__payment WHERE id='$paytype' LIMIT 0,1");
        $fprice = $row['fee'] > 0 ? $row['fee'] : 0;
        unset($row);
        //
        $ip = GetIP();
        $stime = time();
        //最后总计费用
        $lastpriceCount = sprintf("%01.2f", $priceCount+$dprice+$fprice);
    
        $rows = $dsql->GetOne("SELECT `oid` FROM #@__shops_orders WHERE oid='$OrdersId' LIMIT 0,1");
        if(empty($rows['oid']))
        {
            $sql = "INSERT INTO `#@__shops_orders` (`oid`,`userid`,`cartcount`,`price`,`state`,`ip`,`stime`,`pid`,`paytype`,`dprice`,`priceCount`)
            VALUES ('$OrdersId','$userid','$CartCount','$priceCount','0','$ip','$stime','$pid','$paytype','$dprice','$lastpriceCount');";
    
            //更新订单
            if($dsql->ExecuteNoneQuery($sql))
            {
                foreach($Items as $key=>$val)
                {
                    $val['price'] = str_replace(",","",$val['price']);
                    $dsql->ExecuteNoneQuery("INSERT INTO `#@__shops_products` (`aid`,`oid`,`userid`,`title`,`price`,`buynum`)
                    VALUES ('$val[id]','$OrdersId','$userid','$val[title]','$val[price]','$val[buynum]');");
                }
                $sql = "INSERT INTO `#@__shops_userinfo` (`userid`,`oid`,`consignee`,`address`,`zip`,`tel`,`email`,`des`)
                 VALUES ('$userid','$OrdersId','$postname','$address','$zip','$tel','$email','$des');
                ";
                $dsql->ExecuteNoneQuery($sql);
            }
            else
            {
                ShowMsg("更新订单时出现错误！".$dsql->GetError(),"-1");
                exit();
            }
        } else {
            $sql = "UPDATE `#@__shops_orders`
            SET `cartcount`='$CartCount',`price`='$priceCount',`ip`='$ip',`stime`='$stime',pid='$pid',paytype='$paytype',dprice='$dprice',priceCount='$lastpriceCount'
            WHERE oid='$OrdersId' AND userid='$userid' ;";
            if($dsql->ExecuteNoneQuery($sql))
            {
                $sql = "UPDATE `#@__shops_userinfo`
                SET `consignee`='$postname',`address`='$address',`zip`='$zip',`tel`='$tel',`email`='$email',`des`='$des'
                WHERE oid='$OrdersId';";
                $dsql->ExecuteNoneQuery($sql);
            }
            else
            {
                echo $dsql->GetError();
                exit;
            }
            unset($sql);
        }
        //最后结算价格 = 最后统计价格
        $priceCount = sprintf("%01.2f", $lastpriceCount);
        //更新用户商品统计    
        $countOrders = $dsql->GetOne("SELECT SUM(cartcount) AS nums FROM #@__shops_orders WHERE userid='".$cfg_ml->M_ID."'");    
        $dsql->ExecuteNoneQuery("UPDATE #@__member_tj SET `shop`='".$countOrders['nums']."' WHERE mid='".$cfg_ml->M_ID."'");
    
        $rs = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$paytype' ");
        
        require_once DEDEINC.'/payment/'.$rs['code'].'.php';
        $pay = new $rs['code'];
        if($rs['code']=="cod" || $rs['code']=="bank")
        {
           $order=$OrdersId;
        } else {
            $order=array( 'out_trade_no' => $cart->OrdersId,
                          'price' => $priceCount
            );
            require_once DEDEDATA.'/payment/'.$rs['code'].'.php';
        }
        $button=$pay->GetCode($order,$payment);
        $dtp = new DedeTemplate();
        $carts = array( 'orders_id' => $cart->OrdersId,
                                        'cart_count' => $cart->CartCount(),
                                        'price_count' => $priceCount
        );
        $row = $dsql->GetOne("SELECT dname,price FROM #@__shops_delivery WHERE pid='{$pid}'");
        $dtp->SetVar('pay_name',$row['dname']);
        $dtp->SetVar('price',$row['price']);
        $dtp->SetVar('pay_way',$rs['name']);
        $dtp->SetVar('description',$rs['description']);
        $dtp->SetVar('button',$button);
        $dtp->Assign('carts',$carts);
        $dtp->LoadTemplate(_PLUS_TPL_.'/shops_action_payment.htm');
        $dtp->Display();
        exit();
    }
} else if ($dopost == 'memclickout')
{
    $svali = GetCkVdValue();
    if(preg_match ("/S-P[0-9]+RN[0-9]/",$oid))
    {
        $oid=trim($oid);
    } else {
        ShowMsg("您的订单号不存在！","/member/shops_orders.php",0,2000);
        exit();
    }

    //确认用户登录信息
    if($cfg_ml->IsLogin())
    {
        $userid = $cfg_ml->M_ID;
    }
    else
    {
        $username = trim($username);
        $password = trim($password);
        
        if(empty($username) || $password)
        {
            ShowMsg("请选登录！","-1",0,2000);
            exit();
        }
        
        $rs = $cfg_ml->CheckUser($username,$password);
        if($rs==0)
        {
            ShowMsg("用户名不存在！","-1",0,2000);
            exit();
        }
        else if($rs==-1)
        {
            ShowMsg("密码错误！","-1",0,2000);
            exit();
        }
        $userid = $cfg_ml->M_ID;
    }
    
    $row=$dsql->GetOne("SELECT * FROM `#@__shops_orders` WHERE oid='$oid' ");
    if(is_array($row)){
        $OrdersId=$oid;
        $CartCount=$row['cartcount'];
        $priceCount=$row['priceCount'];
        $pid=$row['pid'];
        $rs = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='{$row['paytype']}' ");
    }
    
    require_once DEDEINC.'/payment/'.$rs['code'].'.php';
    $pay = new $rs['code'];
    $payment="";
    if($rs['code']=="cod" || $rs['code']=="bank") $order=$OrdersId;
    else{
        $order=array( 'out_trade_no' => $OrdersId,
                      'price' => $priceCount
        );
        require_once DEDEDATA.'/payment/'.$rs['code'].'.php';
    }
    $button=$pay->GetCode($order, $payment);
    $dtp = new DedeTemplate();
    $carts = array( 'orders_id' => $OrdersId,
                                    'cart_count' => $CartCount,
                                    'price_count' => $priceCount
    );
    $row = $dsql->GetOne("SELECT dname,price FROM #@__shops_delivery WHERE pid='{$pid}'");
    $dtp->SetVar('pay_name',$row['dname']);
    $dtp->SetVar('price',$row['price']);
    $dtp->SetVar('pay_way',$rs['name']);
    $dtp->SetVar('description',$rs['description']);
    $dtp->SetVar('button',$button);
    $dtp->Assign('carts',$carts);
    $dtp->LoadTemplate(_PLUS_TPL_.'/shops_action_payment.htm');
    $dtp->Display();
    exit();
} else if ($dopost == 'return')
{
    $write_list = array('alipay', 'bank', 'cod', 'yeepay');
    if (in_array($code, $write_list))
    {
        require_once DEDEINC.'/payment/'.$code.'.php';
        $pay = new $code;
        $msg=$pay->respond();
        ShowMsg($msg, "javascript:;", 0, 3000);
        exit();  
    } else {
        exit('Error:File Type Can\'t Recognized!');
    }
}