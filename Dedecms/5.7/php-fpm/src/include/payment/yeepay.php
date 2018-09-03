<?php
if(!defined('DEDEINC')) exit('Request Error!');
/**
 *易宝接口类
 */
class yeepay
{
    var $dsql;
    var $mid;
  
    # 业务类型
    # 支付请求，固定值"Buy" 
    var $p0_Cmd = 'Buy';
  
    # 送货地址
    # 为"1": 需要用户将送货地址留在易宝支付系统;为"0": 不需要，默认为 "0".
    var $p9_SAF = "0";
    
    # 网关地址不能随便更改
    var $reqURL_onLine = "https://www.yeepay.com/app-merchant-proxy/node";
    //$reqURL_onLine = "http://tech.yeepay.com:8080/robot/debug.action";
    
    var $return_url='/plus/carbuyaction.php?dopost=return'; //返回处理地址
        
    /**
    * 构造函数
    *
    * @access  public
    * @param
    *
    * @return void
    */
    function yeepay()
    {
        global $dsql;
        $this->dsql = $dsql;
    }

    function __construct()
    {
        $this->yeepay();
    }
    
    /**
     *  设定接口会送地址
     *
     *  例如: $this->SetReturnUrl($cfg_basehost."/tuangou/control/index.php?ac=pay&orderid=".$p2_Order)
     *
     * @param     string  $returnurl  会送地址
     * @return    void
     */
    function SetReturnUrl($returnurl='')
    {
        if (!empty($returnurl))
        {
            $this->return_url = $returnurl;
        }
    }

    /**
    * 生成支付代码
    * @param   array   $order      订单信息
    * @param   array   $payment    支付方式信息
    */
    function GetCode($order, $payment)
    {
        global $cfg_basehost,$cfg_cmspath;
        //对于二级目录的处理
        if(!empty($cfg_cmspath)) $cfg_basehost = $cfg_basehost.'/'.$cfg_cmspath;

        #    商家设置用户购买商品的支付信息.
        ##易宝支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码
        
        #    商户订单号,选填.
        ##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
        $p2_Order                    = trim($order['out_trade_no']);
        
        #    支付金额,必填.
        ##单位:元，精确到分.
        $p3_Amt                        = $order['price'];
        
        #    交易币种,固定值"CNY".
        $p4_Cur                        = "CNY";
        
        #    商品名称
        ##用于支付时显示在易宝支付网关左侧的订单产品信息.
        $p5_Pid                        = trim($order['out_trade_no']);
        
        #    商品种类
        $p6_Pcat                    = 'cart';
        
        #    商品描述
        $p7_Pdesc                    = '';
        
        #    商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
        //$p8_Url                        = $cfg_basehost."/plus/carbuyaction.php?dopost=return&code=".$payment['code'];  
        $p8_Url                        =   $cfg_basehost.$this->return_url.'&code='.$payment['code'];
        
        #    商户扩展信息
        ##商户可以任意填写1K 的字符串,支付成功时将原样返回.                                                
        $pa_MP                        = 'member';
        
        #    应答机制
        ##为"1": 需要应答机制;为"0": 不需要应答机制.
        $pr_NeedResponse    = 1;
        
        #    银行编码
            ##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.            
        $pd_FrpId                    = '';
        #调用签名函数生成签名串
        $hmac = $this->getReqHmacString($payment['yp_account'],$payment['yp_key'],$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
        
        $button = '<form target="_blank" method="post" action="'.$this->reqURL_onLine.'">
                            <input type="hidden" value="'.$this->p0_Cmd.'" name="p0_Cmd">
                            <input type="hidden" value="'.$payment['yp_account'].'" name="p1_MerId">
                            <input type="hidden" value="'.$p2_Order.'" name="p2_Order">
                            <input type="hidden" value="'.$p3_Amt.'" name="p3_Amt">
                            <input type="hidden" value="'.$p4_Cur.'" name="p4_Cur">
                            <input type="hidden" value="'.$p5_Pid.'" name="p5_Pid">
                            <input type="hidden" value="'.$p6_Pcat.'" name="p6_Pcat">
                            <input type="hidden" value="'.$p7_Pdesc.'" name="p7_Pdesc">
                            <input type="hidden" value="'.$p8_Url.'" name="p8_Url">
                            <input type="hidden" value="'.$this->p9_SAF.'" name="p9_SAF">
                            <input type="hidden" value="'.$pa_MP.'" name="pa_MP">
                            <input type="hidden" value="'.$pd_FrpId.'" name="pd_FrpId">
                            <input type="hidden" value="'.$pr_NeedResponse.'" name="pr_NeedResponse"    >
                            <input type="hidden" value="'.$hmac.'" name="hmac">
                            <input type="submit" value="立即使用YeePay易宝支付"></form>';

    /* 清空购物车 */
        require_once DEDEINC.'/shopcar.class.php';
        $cart     = new MemberShops();
        $cart->clearItem();
        $cart->MakeOrders();
        return $button;
    }

    /**
    * 响应操作
    */
    function respond()
    {

        /* 引入配置文件 */
        require_once DEDEDATA.'/payment/'.$_REQUEST['code'].'.php';
        
        $p1_MerId = trim($payment['yp_account']);
        $merchantKey = trim($payment['yp_key']);
        
        #  解析返回参数.
        $return = $this->getCallBackValue($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac);
        
        #  判断返回签名是否正确（True/False）
        $bRet = $this->CheckHmac($p1_MerId,$merchantKey,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

        #  校验码正确.
        if($bRet)
        {
            if($r1_Code=="1")
            {
                /*判断订单类型*/
                if(preg_match ("/S-P[0-9]+RN[0-9]/",$r6_Order)) 
                {
                    //获取用户mid
                    $row = $this->dsql->GetOne("SELECT * FROM #@__shops_orders WHERE oid = '{$r6_Order}'");
                    $this->mid = $row['userid'];
                    $ordertype="goods";
                } else if (preg_match ("/M[0-9]+T[0-9]+RN[0-9]/",$r6_Order)){
                    $row = $this->dsql->GetOne("SELECT * FROM #@__member_operation WHERE buyid = '{$r6_Order}'");
                    //获取订单信息，检查订单的有效性
                    if(!is_array($row)||$row['sta']==2) return $msg = "您的订单已经处理，请不要重复提交!";
                    $ordertype = "member";
                    $product =    $row['product'];
                    $pname= $row['pname'];
                    $pid=$row['pid'];
                    $this->mid = $row['mid'];
                } else {    
                    return $msg = "支付失败，您的订单号有问题!";
                }


                #    需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
                #    并且需要对返回的处理进行事务控制，进行记录的排它性处理，防止对同一条交易重复发货的情况发生.                
                if($r9_BType == "1" || $r9_BType == "3"){
                    if($ordertype == "goods"){ 
                        if($this->success_db($r6_Order))  return $msg = "支付成功!<br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                        else  return $msg = "支付失败!<br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                    } else if ($ordertype=="member") {
                        $oldinf = $this->success_mem($r6_Order,$pname,$product,$pid);
                        return $msg = "<font color='red'>".$oldinf."</font><br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                    }
                } else if ( $r9_BType == "2" ){
                    #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
                    echo "success";
                    if($ordertype=="goods"){ 
                        if($this->success_db($r6_Order))  return $msg = "支付成功!<br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                        else  return $msg = "支付失败!<br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                    } else if ($ordertype=="member") {
                        if($this->success_mem($r6_Order,$pname,$product,$pid))  return $msg = "支付成功!<br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                        else  return $msg = "支付失败!<br> <a href='/'>返回主页</a> <a href='/member'>会员中心</a>";
                    }
                }
            }
        } else {
            $this->log_result ("verify_failed");
            return $msg = "交易信息被篡!<br> <a href='/'>返回主页</a> ";
        }
    }


    #签名函数生成签名串
    function getReqHmacString($p1_MerId,$merchantKey,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse)
    {
        #进行签名处理，一定按照文档中标明的签名顺序进行
        $sbOld = "";
        #加入业务类型
        $sbOld = $sbOld.$this->p0_Cmd;
        #加入商户编号
        $sbOld = $sbOld.$p1_MerId;
        #加入商户订单号
        $sbOld = $sbOld.$p2_Order;     
        #加入支付金额
        $sbOld = $sbOld.$p3_Amt;
        #加入交易币种
        $sbOld = $sbOld.$p4_Cur;
        #加入商品名称
        $sbOld = $sbOld.$p5_Pid;
        #加入商品分类
        $sbOld = $sbOld.$p6_Pcat;
        #加入商品描述
        $sbOld = $sbOld.$p7_Pdesc;
        #加入商户接收支付成功数据的地址
        $sbOld = $sbOld.$p8_Url;
        #加入送货地址标识
        $sbOld = $sbOld.$this->p9_SAF;
        #加入商户扩展信息
        $sbOld = $sbOld.$pa_MP;
        #加入银行编码
        $sbOld = $sbOld.$pd_FrpId;
        #加入是否需要应答机制
        $sbOld = $sbOld.$pr_NeedResponse;
        
        return $this->HmacMd5($sbOld,$merchantKey);
    } 

    #    取得返回串中的所有参数
    function getCallBackValue(&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r7_Uid,&$r8_MP,&$r9_BType,&$hmac)
    {  
        $r0_Cmd       = $_REQUEST['r0_Cmd'];
        $r1_Code      = $_REQUEST['r1_Code'];
        $r2_TrxId     = $_REQUEST['r2_TrxId'];
        $r3_Amt       = $_REQUEST['r3_Amt'];
        $r4_Cur       = $_REQUEST['r4_Cur'];
        $r5_Pid       = $_REQUEST['r5_Pid'];
        $r6_Order     = $_REQUEST['r6_Order'];
        $r7_Uid       = $_REQUEST['r7_Uid'];
        $r8_MP        = $_REQUEST['r8_MP'];
        $r9_BType     = $_REQUEST['r9_BType']; 
        $hmac         = $_REQUEST['hmac'];
        return NULL;
    }

    function CheckHmac($p1_MerId,$merchantKey,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
    {
        if($hmac == $this->getCallbackHmacString($p1_MerId,$merchantKey,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType))
            return TRUE;
        else
            return FALSE;
    }

    function getCallbackHmacString($p1_MerId,$merchantKey,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
    {
        #取得加密前的字符串
        $sbOld = "";
        #加入商家ID
        $sbOld = $sbOld.$p1_MerId;
        #加入消息类型
        $sbOld = $sbOld.$r0_Cmd;
        #加入业务返回码
        $sbOld = $sbOld.$r1_Code;
        #加入交易ID
        $sbOld = $sbOld.$r2_TrxId;
        #加入交易金额
        $sbOld = $sbOld.$r3_Amt;
        #加入货币单位
        $sbOld = $sbOld.$r4_Cur;
        #加入产品Id
        $sbOld = $sbOld.$r5_Pid;
        #加入订单ID
        $sbOld = $sbOld.$r6_Order;
        #加入用户ID
        $sbOld = $sbOld.$r7_Uid;
        #加入商家扩展信息
        $sbOld = $sbOld.$r8_MP;
        #加入交易结果返回类型
        $sbOld = $sbOld.$r9_BType;
        
        return $this->HmacMd5($sbOld,$merchantKey,'gbk');

    }

    function HmacMd5($data,$key,$lang='utf-8')
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // Eliminates the need to install mhash to compute a HMAC
        // Hacked by Lance Rushing(NOTE: Hacked means written)
        
        //需要配置环境支持iconv，否则中文参数不能正常处理
        if($GLOBALS['cfg_soft_lang'] != 'utf-8' || $lang!='utf-8')
        {
            $key = gb2utf8($key);
            $data = gb2utf8($data);
        }
        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*",md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;
        
        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }

    /*处理物品交易*/
    function success_db($order_sn)
    {
        //获取订单信息，检查订单的有效性
        $row = $this->dsql->GetOne("SELECT state FROM #@__shops_orders WHERE oid='$order_sn' ");
        if($row['state'] > 0)
        {
            return TRUE;
        }    
        /* 改变订单状态_支付成功 */
        $sql = "UPDATE `#@__shops_orders` SET `state`='1' WHERE `oid`='$order_sn' AND `userid`='".$this->mid."'";
        if($this->dsql->ExecuteNoneQuery($sql))
        {
            $this->log_result("verify_success,订单号:".$order_sn); //将验证结果存入文件
            return TRUE;
        } else {
            $this->log_result ("verify_failed,订单号:".$order_sn);//将验证结果存入文件
            return FALSE;
        }
    }

    /*处理点卡，会员升级*/
    function success_mem($order_sn,$pname,$product,$pid)
    {
        //更新交易状态为已付款
        $sql = "UPDATE `#@__member_operation` SET `sta`='1' WHERE `buyid`='$order_sn' AND `mid`='".$this->mid."'";
        $this->dsql->ExecuteNoneQuery($sql);

        /* 改变点卡订单状态_支付成功 */
        if($product=="card")
        {
            $row = $this->dsql->GetOne("SELECT cardid FROM #@__moneycard_record WHERE ctid='$pid' AND isexp='0' ");;
            //如果找不到某种类型的卡，直接为用户增加金币
            if(!is_array($row))
            {
                $nrow = $this->dsql->GetOne("SELECT num FROM #@__moneycard_type WHERE pname = '{$pname}'");
                $dnum = $nrow['num'];
                $sql1 = "UPDATE `#@__member` SET `money`=money+'{$nrow['num']}' WHERE `mid`='".$this->mid."'";
                $oldinf ="已经充值了".$nrow['num']."金币到您的帐号！";
            } else {
                $cardid = $row['cardid'];
                $sql1=" UPDATE #@__moneycard_record SET uid='".$this->mid."',isexp='1',utime='".time()."' WHERE cardid='$cardid' ";
                $oldinf='您的充值密码是：<font color="green">'.$cardid.'</font>';
            }
            //更新交易状态为已关闭
            $sql2=" UPDATE #@__member_operation SET sta=2,oldinfo='$oldinf' WHERE buyid='$order_sn'";
            if($this->dsql->ExecuteNoneQuery($sql1) && $this->dsql->ExecuteNoneQuery($sql2))
            {
                $this->log_result("verify_success,订单号:".$order_sn); //将验证结果存入文件
                return $oldinf;
            } else {
                $this->log_result ("verify_failed,订单号:".$order_sn);//将验证结果存入文件
                return "支付失败！";
            }
        /* 改变会员订单状态_支付成功 */
        } else if ( $product=="member" ){
            $row = $this->dsql->GetOne("SELECT rank,exptime FROM #@__member_type WHERE aid='$pid' ");
            $rank = $row['rank'];
            $exptime = $row['exptime'];
            /*计算原来升级剩余的天数*/
            $rs = $this->dsql->GetOne("SELECT uptime,exptime FROM #@__member WHERE mid='".$this->mid."'");
            if($rs['uptime']!=0 && $rs['exptime']!=0 ) 
            {
                $nowtime = time();
                $mhasDay = $rs['exptime'] - ceil(($nowtime - $rs['uptime'])/3600/24) + 1;
                $mhasDay=($mhasDay>0)? $mhasDay : 0;
            }
            //获取会员默认级别的金币和积分数
            $memrank = $this->dsql->GetOne("SELECT money,scores FROM #@__arcrank WHERE rank='$rank'");
            //更新会员信息
            $sql1 =  " UPDATE #@__member SET rank='$rank',money=money+'{$memrank['money']}',
                       scores=scores+'{$memrank['scores']}',exptime='$exptime'+'$mhasDay',uptime='".time()."' 
                       WHERE mid='".$this->mid."'";
            //更新交易状态为已关闭
            $sql2=" UPDATE #@__member_operation SET sta='2',oldinfo='会员升级成功!' WHERE buyid='$order_sn' ";
            if($this->dsql->ExecuteNoneQuery($sql1) && $this->dsql->ExecuteNoneQuery($sql2))
            {
                $this->log_result("verify_success,订单号:".$order_sn); //将验证结果存入文件
                return "会员升级成功！";
            } else {
                $this->log_result ("verify_failed,订单号:".$order_sn);//将验证结果存入文件
                return "会员升级失败！";
            }
        }    
    }

    function  log_result($word) {
        global $cfg_cmspath;
        $fp = fopen(dirname(__FILE__)."/../../data/payment/log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,$word.",执行日期:".strftime("%Y-%m-%d %H:%I:%S",time())."\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}//End API