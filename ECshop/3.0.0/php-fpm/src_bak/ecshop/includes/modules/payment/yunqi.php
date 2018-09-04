<?php

/**
 * ECSHOP 云起收银(支付宝)
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: douqinghua $
 * $Id: yunqi.php 17217 2011-01-19 06:29:08Z douqinghua $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/yunqi.php';


if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'yunqi_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'YUNQI TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'https://charging.teegon.com/';

    /* 版本号 */
    $modules[$i]['version'] = '1.0';

    /* 配置信息 */
    $modules[$i]['config']  = array();

    return;
}


/**
 * 类
 */
class yunqi
{
    function __construct()
    {
        $this->yunqi();
    }

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function yunqi()
    {
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {        
        $this->edit_payment($payment);
        $name = get_goods_name_by_id($order['order_id']);
        $param['order_no'] = $order['order_sn']; //订单号
        $param['channel'] = $order['yunqi_paymethod']=='wxpay'?$order['yunqi_paymethod']:'alipay';
        $param['return_url'] = return_url(basename(__FILE__, '.php'));
        $param['amount'] = $order['order_amount'];
        $param['subject'] = (isset($order['process_type']) and $order['process_type']==0)?'余额充值':$name;
        $param['metadata'] = $order['yunqi_paymethod']=='wxpay'?'yunqiwx':'yunqi';
        $param['notify_url'] = return_url(basename(__FILE__, '.php'));
        $param['client_ip'] = $_SERVER["REMOTE_ADDR"];
        $param['client_id'] = $payment['yunqi_client_id'];
        $param['sign'] = $this->sign($param,$payment);

        $def_url  = '<div style="text-align:center"><form name="yunqi" accept-charset="UTF-8" style="text-align:center;" method="post" action="https://api.teegon.com/charge/pay" target="_blank">';
        $def_url .= "<input type='hidden' name='order_no' value='" . $param['order_no'] . "' />";
        $def_url .= "<input type='hidden' name='channel' value='" . $param['channel'] . "' />";
        $def_url .= "<input type='hidden' name='amount' value='" . $param['amount'] . "' />";
        $def_url .= "<input type='hidden' name='subject' value='" . $param['subject'] . "' />";
        $def_url .= "<input type='hidden' name='metadata' value='" . $param['metadata'] . "' />";
        $def_url .= "<input type='hidden' name='client_ip' value='" . $param['client_ip'] . "' />";
        $def_url .= "<input type='hidden' name='return_url' value='" . $param['return_url'] . "' />";
        $def_url .= "<input type='hidden' name='notify_url' value='" . $param['notify_url'] . "' />";
        $def_url .= "<input type='hidden' name='sign' value='" . $param['sign'] . "' />";
        $def_url .= "<input type='hidden' name='client_id' value='" . $param['client_id'] . "' />";
        $def_url .= "<input type='submit'  value='" . $GLOBALS['_LANG']['pay_button'] . "' />";
        $def_url .= "</form></div></br>";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        if (!empty($_POST))
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $payment  = get_payment($_GET['code']);
        $this->edit_payment($payment);
        $_GET['data'] = stripslashes($_GET['data']);
        unset($_GET['code']);
        $resign = $this->sign($_GET,$payment);

        //获取paid 判断是否为会员充值
        $is_recharge = strlen($_GET['order_no'])>8?'false':'true';
        $pay_id = get_order_id_by_sn($_GET['order_no'],$is_recharge);

        /* 检查支付的金额是否相符 */
        if (!check_money($pay_id, $_GET['amount']))
        {
            return false;
        }

        //修改订单状态
        if ($_GET['is_success'] == 'true')
        {
            /* 改变订单状态 */
            order_paid($pay_id, 2);
            if(!empty($_POST))
            {
                $tgarr = array(
                    array("source_account"=>"main","target_account"=>"main","amount"=> $_GET['amount']),
                );
                $tgreturn = json_encode($tgarr);
                $tgsign = md5($tgreturn.$payment['yunqi_client_secret']);
                header('Teegon-Rsp-Sign: '.$tgsign);
                echo $tgreturn;
                exit;
            }

            return true;
        }else{
            return false;
        }



    }

//yunqi 加密算法
    public function sign($para_temp,$payment){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->para_filter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->arg_sort($para_filter);
        //生成加密字符串
        $prestr = $this->create_string($para_sort);
        $prestr = $payment['yunqi_client_secret'] .$prestr . $payment['yunqi_client_secret'];
        return strtoupper(md5($prestr));
    }


    private function para_filter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    private function arg_sort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    private function create_string($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.$val;
        }


        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    private function edit_payment(&$payment){
        $payment['yunqi_client_id'] = get_certificate_info('appkey','yunqi_account');
        $payment['yunqi_client_secret'] = get_certificate_info('appsecret','yunqi_account');
    }
}
?>
