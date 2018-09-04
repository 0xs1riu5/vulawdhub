<?php
/**
 * ECSHOP OPEN API统一接口
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: sxc_shop $
 * $Id: goods.php 15921 2009-05-07 05:35:58Z sxc_shop $
 */
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
// require(ROOT_PATH . 'includes/lib_license.php');
require_once('includes/cls_certificate.php');
require_once('includes/cls_json.php');
define('RETURN_TYPE', empty($_POST['return_data']) ? 1 : ($_POST['return_data'] == 'json' ? 2 : 1));
define('GMTIME_UTC', gmtime()); // 获取 UTC 时间戳
/* 接收传递参数并初步检验 */
if (empty($_POST) || empty($_POST['ac']))
{
    api_err('0x003', 'no parameter');   //输出系统级错误:数据异常
}

if ( @constant( "DEBUG_API" ) ) {
    foreach ($_POST as $key=>$val) {
        $array_debug_info[] = $key."=".stripslashes($val);
    }
    $str_debug_info = implode("&", $array_debug_info);
    if(!is_dir(LOG_DIR)){
        mkdir(LOG_DIR,0777);
    }
    error_log(date("c")."\t".rawurldecode($str_debug_info)."\n".stripslashes(var_export($_POST,true))."\n\n",3,LOG_DIR."/debug_api_".date("Y-m-d",time()).".log");
    unset($str_debug_info,$array_debug_info);
}


/* 根据请求类型进入相应的接口处理程序 */
error_log("\r\n".$_POST['act'],3,__FILE__.".log");
switch ($_POST['act'])
{
    case 'search_goods_list': search_goods_list(); break;
    case 'search_goods_detail': search_goods_detail(); break;
    case 'search_deleted_goods_list': search_deleted_goods_list(); break;
    case 'search_products_list': search_products_list(); break;
    case 'search_site_info': search_site_info(); break;
    case 'get_certinfo': get_certinfo(); break;
    case 'fy.logistics.offline.send':fy_logistics_offline_send();break;//淘打发货接口

    case 'update_order_status':update_order_status();break;//更新订单状态
    case 'create_ome_delivery':ome_create_delivery();break;//创建发货、退货单
    case 'create_ome_payments':ome_create_payments();break;//创建支付单
    case 'create_ome_refunds':ome_create_reimburse();break;//创建退款单
    case 'set_ome_products_store':update_store();break;//更新库存
    case 'set_ome_ship_addr':update_consignee();break;//修改收货人信息
    case 'set_ome_message':add_buyer_msg();break;//添加买家家留言
    case 'set_ome_mark':update_memo();break;//添加备注
    case 'ome_update_order_item':ome_update_order_item();break;//修改订单商品及金额信息
    case 'ome:fetch_order_detail':get_orders_info();break;//获取订单信息
    case 'start_ome_payment':get_payment_conf();break;//获得当前店铺有效支付方式
    case 'create_return':create_return();break;//退货接口
    case 'get_return_status':get_return_status();break;//更新退货状态
    case 'update_consignor':update_consignor();break;//修改发货人信息
    case 'ome:fetch_order_list':search_order_lists();break;//获取订单列表

    case 'shopex_shop_login': shopex_shop_login(); break; //登录店铺
    case 'shopex_goods_cat_list': shopex_goods_cat_list(); break; //获取商品分类列
    case 'shopex_type_list': shopex_type_list(); break; //获取商品类型
    case 'shopex_brand_list': shopex_brand_list(); break; //获取品牌列表
    case 'shopex_goods_add': shopex_goods_add(); break; //添加商品
    case 'shopex_goods_search': shopex_goods_search(); break; //查找商品信息
    case 'ecmobile_send_sms':yunqi_send_sms();break;//发送短信接口
    case 'ecmobile_get_logistics_info';ecmobile_get_logistics_info($_CFG['lang']);break;
    case 'ecmobile_fire_event';ecmobile_fire_event();break;
    case 'get_auth_info';get_auth_info();break;

    default: api_err('0x008', 'no this type api');   //输出系统级错误:数据异常
}

//获取ecshop授权类型
function get_auth_info(){
    check_auth();   //检查基本权限
    $auth_sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = "authorize"';
    $auth = $GLOBALS['db']->getRow($auth_sql);
    $params = unserialize($auth['value']);
    if($auth){
        $data = array('authorize_code'=>$params['authorize_code'],'authorize_name'=>$params['authorize_name']);
        api_response('true','',$data,RETURN_TYPE);
    }else{
        api_response('fail','','',RETURN_TYPE);
    }
}

//ecmobile操作触发动作
function ecmobile_fire_event(){
    check_auth();   //检查基本权限
    $type = $_POST['type'];
    $args = $_POST['id'];
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    $matrix = new matrix;

    switch($type){
        case 'member_create':
            $flag = $matrix->createMember($args,'ecos.taocrm');
            break;
        case 'order_create':
            $flag = $matrix->createOrder($args,'');//多合一推送
            break;
        case 'order_update':
            $flag = $matrix->updateOrder($args,'');//多合一推送
            break;
        default:
            $flag = false;
            $msg = 'no this type api';
    }
    if($flag){
        api_response('true',$msg,'',RETURN_TYPE);
    }else{
        api_response('fail',$msg,'',RETURN_TYPE);
    }
}

//发送短信
function yunqi_send_sms(){
    check_auth();   //检查基本权限
    if(!$_POST['phone'] || !$_POST['content']){
        api_response('fail','ERR_PARAMS','',RETURN_TYPE);
    }

    include_once(ROOT_PATH.'includes/cls_sms.php');
    $sms = new sms();
    $is_succ = $sms->send($_POST['phone'],$_POST['content'])?'true':'fail';
    api_response($is_succ,'','',RETURN_TYPE);
}

//获取物流信息
function ecmobile_get_logistics_info($lang){
    check_auth();   //检查基本权限
    if(!$_POST['order_sn']){
        api_response('fail','ORDERID_NOT_EXSISTS','',RETURN_TYPE);
    }

    include_once(ROOT_PATH . 'includes/lib_order.php');
    $data = get_logistics_trace($_POST['order_sn'], 0, $lang);
    api_response('true','',$data,RETURN_TYPE);
}

// 更新订单
function ome_update_order_item(){
    require_once(ROOT_PATH . 'includes/lib_order.php');
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    $data = $_POST;

    $order_sn = $data['tid'];
    // 判断订单是否有效
    if(!verify_order_valid($order_sn,$order,'*',$msg)) api_err('0x003', '订单无效。'.$msg);
    $order_id = $order['order_id'];

    // 检测商品是否有效
    $data['orders']=getStructDataByType($data['orders'],$data['orders_type']);
    if (!verify_goods_valid($order_sn,$data['orders'],$msg)) api_err('0x003', '订单商品无效。'.$msg);

    $payed = $order['surplus']+$order['surplus']; //已付款
    $loginfo['msg']='修改订单金额';

    $order['goods_amount'] = $data['total_goods_fee']; //商品金额
    $order['shipping_fee'] = $data['shipping_fee']; //运费
    $order['insure_fee'] = $data['protect_fee']; //保价
    $order['pay_fee'] = $data['pay_cost']; //手续费
    $order['money_paid'] = $data['payed_fee']-$order['surplus']>=0?$data['payed_fee']-$order['surplus']:0; //已支付
    $order['order_amount'] = $data['total_trade_fee']-$data['payed_fee']; //还应支付金额
    if ($data['discount_fee']>0) {
        $order['discount'] = (-1)*abs($data['discount_fee'])+(isset($data['orders_discount_fee'])?$data['orders_discount_fee']:0);
    }else{
        $order['discount'] = abs($data['discount_fee'])+(isset($data['orders_discount_fee'])?$data['orders_discount_fee']:0);
    }
    $order['goods_discount_fee'] = abs($data['goods_discount_fee'])?abs($data['goods_discount_fee']):"0.00"; //商品折扣
    $order['tax'] = $data['invoice_fee']; //发票金额
    $order['inv_payee'] = $data['invoice_title']; //发票抬头

    $local_order_pay_status = $order['pay_status'];
    //订单 支付状态 -- 还有退款状态 不再处理
    if($order['surplus']+$data['payed_fee']==0){//未支付
        if ($local_order_pay_status == 0){//本地订单为未支付
            $order['pay_status'] =0;
        }
        $order['payed'] = $payed;
    }else if($order['order_amount'] == 0){//全额付款
        $order['pay_status']=($order['pay_status']==2 ? 2:1);    //如果2是支付中，否则已支付
    }else if ($refunds = $data['payed_fee']-$data['total_trade_fee'] >0){// 支付金额多余订单总金额
        $order['pay_status'] = 2;
        //多余的钱需要请求退款接口进行退还到预存款
    }else if($order['order_amount']>0){//部分支付(ecshop没有部分付款，视为未支付)
        $order['pay_status'] = 0;
    }

    //配送方式名称
    if ($data['shipping'] && $shipping = json_decode($data['shipping'],1)) {
        if(isset($shipping['shipping_name'])){
            $aRet = getDlTypeList();
            foreach ($aRet as $v){
                if($v['shipping_name']==$data['shipping_name']){
                    $order['shipping_id']=$v['shipping_id'];
                    $order['shipping_name']=$v['shipping_name'];
                    break 1;
                }
            }
        }
    }

    //修改时间
    $data['modified']&&($order['lastmodify']=strtotime($data['modified']));

    if($data['orders']){
        $loginfo['msg'].='修改订单商品信息';
        $fail_order_items = _omeUpdateOrderItem($order_sn,$data,$order);

        //订单配送状态  -- 只修改 已经发货，及部分发货
        if( in_array($order['ship_status'],array('0','1','3','4','5','6'))){
            $rs = $GLOBALS['db']->getAll("SELECT * FROM ".$GLOBALS['ecs']->table('order_goods')." WHERE order_id='".$order_id."'");
            $number_sum = 0;
            $number_num = 0;
            foreach($rs as $item){
                if( $item['send_number']!= '0' ) $number_sum +=$item['send_number'];
                $number_num+=$item['nums'];
            }
            // 重算 itemnum
            $order['send_number']=$number_num;
            if($number_num>0){
                if($number_sum==0){
                    $order['shipping_status'] = 0 ;
                }else if($number_num == $number_sum){
                    $order['shipping_status'] = 1 ;
                }else if($number_num > $number_sum){
                    $order['shipping_status'] = 4;
                }
            }
        }
    }

    // 积分
    $integral = integral_to_give($order);
    $order['give_integral'] = $integral['rank_points'];

    // 检测贺卡
    $card_info = $GLOBALS['db']->getRow("SELECT o.card_id,o.card_fee,c.card_name FROM ".$GLOBALS['ecs']->table('order_info')." as o LEFT JOIN ".$GLOBALS['ecs']->table('card')." as c on o.card_id=c.card_id WHERE o.order_id=".$order['order_id']);
    $order['card_id'] = $card_info['card_id'];
    $order['card_fee'] = $card_info['card_fee'];
    $order['card_name'] = $card_info['card_name'];

    if ($GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'),
        $order, 'UPDATE', "order_id = '$order_id'")) {
        // add log
        require_once(ROOT_PATH . 'includes/lib_common.php');
        $action_note = "管理员更新订单：".$order['order_sn'];
        order_action($order['order_sn'], $order['order_status'], $order['shipping_status'], $order['pay_status'], $action_note,'system');
        // 请求crm
        update_order_crm($order['order_sn']);
        data_back($fail_order_items,'',RETURN_TYPE);
    }else{
        api_err('0x003','更新订单金额及商品信息出错!');
    }

}


// 检测商品是否有效
function verify_goods_valid($order_sn,$data,&$msg){
    error_log(print_R($data,1)."\n",3,"/tmp/chen_1.log");
    $bns = array();
    $msg = '';
    foreach ($data['order'] as $key => $value) {
        foreach ($value['order_items']['order_item'] as $v) {
            // 剔除贺卡
            if (strpos($v['bn'], 'ECS_CARD_') === FALSE && $v['item_status'] == 'normal') {
                $bns[$v['bn']] = $v['bn'];
            }
        }
    }
    if (!$bns) {
        $msg = 'bn is empty.';
        return false;
    }
    $is_goods = $GLOBALS['db']->getAll("SELECT goods_sn,is_on_sale FROM ".$GLOBALS['ecs']->table('goods')." WHERE goods_sn in ('".implode("','", $bns)."') " );
    foreach ($is_goods as $goods) {
        if ($goods['goods_sn']) {
            if ($goods['is_on_sale'] == 0) {
                $msg .= "{$goods['goods_sn']} is disabled;";
            }else{
                unset($bns[$goods['goods_sn']]);
            }
        }
    }
    if ($bns) {
        $is_products = $GLOBALS['db']->getAll("SELECT g.goods_sn,g.is_on_sale,p.product_sn FROM ".$GLOBALS['ecs']->table('goods')." as g right join ".$GLOBALS['ecs']->table('products')." as p on g.goods_id=p.goods_id WHERE p.product_sn in ('".implode("','", $bns)."')" );
        foreach ($is_products as $goods) {
            if ($goods['product_sn']) {
                if ($goods['is_on_sale'] == 0) {
                    $msg .= "{$goods['product_sn']} is disabled;";
                }else{
                    unset($bns[$goods['product_sn']]);
                }
            }elseif ($goods['product_sn'] == 0 && $goods['goods_sn']) {
                if ($goods['is_on_sale'] == 0) {
                    $msg .= "{$goods['goods_sn']} is disabled;";
                }else{
                    unset($bns[$goods['goods_sn']]);
                }
            }
        }
    }

    if ($bns) {
        foreach ($bns as $bn) {
            $msg .= "{$bn} is not find;";
        }
    }
    if ($msg) return false;
    return true;
}


/**
 * 处理订单货品信息  增删改商品数,算积分,冻结库存 .
 * @param string $order_id
 * @param array $data 传进的参数
 * @param array $order 订单数据
 */
function _omeUpdateOrderItem($order_sn,$data,$order){

    //生成订单前检查库存
    // 商品信息   order_items   包含 的商品主要信息
    // "bn","price",'num'
    // 与原订单的 商品进行比较,
    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];
    $aProduct=array();
    $delbns = $nbns = array();
    $new_order_items=array();
    foreach($data['orders']['order'] as $d_order){
        foreach($d_order['order_items']['order_item'] as $d_order_items){
            if($d_order_items['item_status']=='cancel'){ //删除商品
                $delbns[$d_order_items['bn']]=$d_order_items['bn'];
                continue 1;
            }else{
                $nbns[$d_order_items['bn']]=$d_order_items['bn'];
            }
            $new_order_items[$d_order_items['bn']]['goods_name'] = $d_order_items['name'];
            $new_order_items[$d_order_items['bn']]['is_gift'] = $d_order_items['item_type'] == 'gift'?1:0;
            $new_order_items[$d_order_items['bn']]['goods_price'] = $d_order_items['price'];
            $new_order_items[$d_order_items['bn']]['goods_number'] = $d_order_items['num'];
            $new_order_items[$d_order_items['bn']]['send_number'] = $d_order_items['sendnum'];
            $new_order_items[$d_order_items['bn']]['discount_fee'] = $d_order_items['discount_fee'];
        }
    }

    // 已经存在的 Item
    $rs = $db->getAll("SELECT og.*,p.product_sn FROM ".$ecs->table('order_goods')." as og left JOIN ".$ecs->table('products')." as p ON og.product_id = p.product_id WHERE order_id='".$order['order_id']."'");
    foreach($rs as $item){
        if ($item['product_id'] == 0) {
            // product_id=0，说明是单规格货品
            if(empty($item['goods_sn'])) continue 1;

            if(in_array($item['goods_sn'],$nbns)){
                $skip=true;
                foreach($new_order_items[$item['goods_sn']] as $k=>$v){
                    if($item[$k]!=$v) $skip=false; //$item[$k]
                }
                if(!$skip){
                    $db->autoExecute($GLOBALS['ecs']->table('order_goods'),$new_order_items[$item['goods_sn']], 'UPDATE', "order_id = '".$order['order_id']."' AND goods_sn = '".$item['goods_sn']."'");
                }
                unset($new_order_items[$item['goods_sn']]);
            }else{
                $i=$db->query("Delete from ".$ecs->table('order_goods')." WHERE order_id='".$order['order_id']."' AND goods_sn = '".$item['goods_sn']."'");
            }
        }else{
            // 多规格商品
            if(empty($item['product_sn'])) continue 1;

            if(in_array($item['product_sn'],$nbns)){
                $skip=true;
                foreach($new_order_items[$item['product_sn']] as $k=>$v){
                    if($item[$k]!=$v) $skip=false;
                }
                if(!$skip){
                    $db->autoExecute($GLOBALS['ecs']->table('order_goods'),$new_order_items[$item['product_sn']], 'UPDATE', "order_id = '".$order['order_id']."' AND product_id = '".$item['product_id']."'");
                }
                unset($new_order_items[$item['product_sn']]);
            }else{
                $i=$db->query("Delete from ".$ecs->table('order_goods')." WHERE order_id='".$order['order_id']."' AND product_id = '".$item['product_id']."'");
            }
        }
    }
    $fail_order_items = '';
    // 新添加的货
    foreach($new_order_items as $key=>$item){
        $r=get_product($key,$order);
        if ($r['res']!='true') {
            $fail_order_items[] = $r['msg'];
            continue;
        }
        $r = $r['msg'];
        $goodId=$r['goods_id'];
        if(!$goodId) continue;
        $sql = "insert into ".$GLOBALS['ecs']->table('order_goods')." set order_id={$order['order_id']},goods_id={$r['goods_id']},goods_name='{$r['goods_name']}',goods_sn='{$r['goods_sn']}',product_id={$r['product_id']},goods_number={$item['goods_number']},market_price='{$r['market_price']}',goods_price='{$item['goods_price']}',discount_fee='{$item['discount_fee']}',goods_attr='{$r['goods_attr']}',send_number={$item['send_number']},is_real='{$r['is_real']}',is_gift='{$item['is_gift']}',goods_attr_id='{$r['goods_attr_id']}' ";
        $aRs = $db->query($sql);
    }
    return implode('|', $fail_order_items);
}


function getFieldById($id, $aFeild=array('*')){
    $sqlString = "SELECT ".implode(',', $aFeild)." FROM ".$GLOBALS['ecs']->table('products')." WHERE product_id = ".intval($id);
    return $GLOBALS['db']->getRow($sqlString);
}


function get_product($bn,$order){
    if (strpos($bn, 'ECS_CARD_') !== FALSE) {
        $card_id = str_replace('ECS_CARD_', '', trim($bn));
        if ($card_id) {
            include_once(ROOT_PATH."includes/lib_order.php");
            $card_fee = card_fee($card_id,$order['goods_amount']);
            $sql = "update ".$GLOBALS['ecs']->table('order_info')." set card_id = {$card_id},card_fee = '{$card_fee}' WHERE order_id = '{$order['order_id']}'";
            $GLOBALS['db']->query($sql);
        }
        // 贺卡直接返回空
        return array('res'=>'false');
    }
    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];
    if($tmp = $db->getRow("SELECT goods_id,goods_name,goods_sn,market_price,is_real,is_on_sale FROM ".$ecs->table('goods')." WHERE goods_sn='".$bn."'")){
        //商品
        unset($tmp['is_on_sale']);
        $tmp['product_id'] = "0";
        $tmp['goods_attr'] = $tmp['goods_attr_id'] = '';
        if($tmp['is_on_sale'] == '0'){
            return array('res'=>'false','msg'=>$bn.' is disabled');
        }else{
            return array('res'=>'true','msg'=>$tmp);
        }
    }else if($aData = $db->getRow("SELECT g.goods_id,g.goods_name,g.goods_sn,g.market_price,g.is_real,g.is_on_sale,p.product_id,p.goods_attr as goods_attr_id FROM ".$ecs->table('goods')." as g right join ".$ecs->table('products')." as p on g.goods_id = p.goods_id WHERE p.product_sn='".$bn."'")){
        //货品
        if($aData['is_on_sale'] == '0'){
            return array('res'=>'false','msg'=>$bn.' is disabled');
        }else{
            unset($aData['is_on_sale']);
            if ($aData['goods_attr_id']) $aData['goods_attr_id'] = str_replace('|', ",", $aData['goods_attr_id']);
            $aData['goods_attr'] = '';
            $attr_info = $db->getAll("SELECT ga.attr_value,a.attr_name FROM ".$ecs->table('goods_attr')." as ga LEFT JOIN ".$ecs->table('attribute')." as a on ga.attr_id=a.attr_id WHERE ga.goods_attr_id in ({$aData['goods_attr_id']})");
            if ($attr_info) {
                foreach ($attr_info as $attr) {
                    $aData['goods_attr'] = $attr['attr_name'].":".$attr['attr_value']."\n".$aData['goods_attr'];
                }
            }
            return array('msg'=>$aData,'res'=>'true');
        }
    }else{
        //找不到该bn
        return array('res'=>'false','msg'=>$bn.' is not find');
    }
}


function getStructDataByType($data,$type='json'){
    $return_data = json_decode($data,true);
    $return_data or $return_data = json_decode(stripcslashes($data),true);
    return  $return_data;
}


// 获取有效的配送方式
function getDlTypeList(){
    $sql = "SELECT shipping_id,shipping_name from ".$GLOBALS['ecs']->table('shipping')." WHERE enabled = 1";
    $res = $GLOBALS['db']->getAll($sql);
    return $res;
}


// 获取订单列表
function search_order_lists(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $data = $_POST;
    $order_status['status']=array('active','finish','pending','dead');
    $order_status['ship_status']=array(0,1,2,3,4);
    $order_status['pay_status']=array(0,1,2,3,4,5,6);
    $start_time = strtotime($data['start_time']);
    $end_time = strtotime($data['end_time']);
    $page_no = $data['page_no']?intval($data['page_no']):1;
    $page_size = $data['page_size']?intval($data['page_size']):40;
    $fields = $data['fields']?trim($data['fields']):'*';
    $all = $data['search_all'] ? true : false;
    $result=$where=array();
    //搜索条件
    if($start_time>=$end_time) api_err('','order_end_time_than_start_time');
    $where[]=' modified >= '.$start_time;
    $where[]=' modified < '.$end_time;
    if($data['status']){
        foreach(json_decode($data['status'],true) as $key=>$val){
            if(array_key_exists($key, $order_status) && in_array($val, $order_status[$key])){
                $where[] = $key." = '".$val."'";
            }
        }
        if(2 == count($where)) api_err('','order_status_struct');
    }
    //搜索结果
    $sql_count = 'select count(*) as total_results from '.$GLOBALS['ecs']->table('order_info').' where '.implode(' and ', $where).' ';
    $result = $GLOBALS['db']->getRow($sql_count);
    if(!$result['total_results']) data_back('true','',RETURN_TYPE);

    $sql = 'select order_sn from '.$GLOBALS['ecs']->table('order_info').' where '.implode(' and ', $where).' order by lastmodify limit '.($page_no-1)*$page_size.','.$page_size;
    $row = $GLOBALS['db']->getAll($sql);
    $result['trades']=array();

    //订单结构体
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    $matrix = new matrix;
    $msg = $matrix->getOrderStruct($order_id,$fields);
    foreach($row as $val){
        if($params = $matrix->getOrderStruct($val['order_sn'],$fields)){
            $result['trades'][]=$params;
        }
    }
    data_back($result,'',RETURN_TYPE);
}


// 拉取订单信息
function get_orders_info(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $data = $_POST;
    $result=array();
    $fields = $data['fields']?trim($data['fields']):'*';
    $order_id = trim($data['order_id']);
    $all = $data['search_all'] ? true : false;
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    $matrix = new matrix;
    $msg = $matrix->getOrderStruct($order_id,$fields);
    if ($msg) {
        $result['trade']=last_filter_params($msg,$fields);
        $result['trade'] and data_back($result,'',RETURN_TYPE);
    }
    data_back('true','',RETURN_TYPE);
}


//最后过滤返回参数
function last_filter_params($params,$fields='*'){
    if('*'!=$fields){
        $fields = explode(',',$fields);
        !in_array('order_source',$fields) and $fields[]='order_source';
        foreach($params as $key=>$val){
            if(!in_array($key,$fields)) unset($params[$key]);
        }
    }
    return $params;
}


// 修改收货人信息
function update_consignee(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $data = $_POST;
    if(empty($data['ship_tel']) && empty($data['ship_mobile'])){
        api_err('','手机或者座机必须有一个不为空！');
    }
    $order_sn=$data['order_id']?$data['order_id']:'-1';
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('order_info') .
        " WHERE order_sn = $order_sn";
    $orders = $GLOBALS['db']->getRow($sql);
    if (!$orders) {
        api_err('','订单号“'.$order_sn.'”不存在。');
    }
    // $state = str_replace('省', '', $data['ship_state']);
    // $city = str_replace('市', '', $data['ship_city']);
    $state = $data['ship_state'];
    $city = $data['ship_city'];
    $district = $data['ship_district'];

    $sql="SELECT region_id FROM ".$GLOBALS['ecs']->table('region')." WHERE region_type = '1' AND region_name = '".$state."'";
    if(!$region_id=$GLOBALS['db']->getRow($sql)){
        api_err('0x003','state error');
    }
    $aAddr['province'] = $region_id['region_id'];

    $sql="SELECT region_id FROM ".$GLOBALS['ecs']->table('region')." WHERE region_type = '2' AND region_name = '".$city."'";
    if(!$region_id=$GLOBALS['db']->getRow($sql)){
        api_err('0x003','city error');
    }
    $aAddr['city'] = $region_id['region_id'];

    $sql="SELECT region_id FROM ".$GLOBALS['ecs']->table('region')." WHERE region_type = '3' AND region_name = '".$district."'";
    if(!$region_id=$GLOBALS['db']->getRow($sql)){
        api_err('0x003','region error');
    }
    $aAddr['district'] = $region_id['region_id'];
    $aAddr['consignee'] = $data['ship_name'];
    // $aAddr['ship_area'] = 'mainland:'.implode('/',$region_area).':'.$region_id['region_id'];
    $aAddr['address'] = $data['ship_addr'];
    $aAddr['zipcode'] = $data['ship_zip'];
    $aAddr['tel'] = $data['ship_tel'];
    $aAddr['mobile'] = $data['ship_mobile'];
    $aAddr['email'] = $data['ship_email'];
    $aAddr['shipping_time'] = $data['ship_time'];
    $aAddr['lastmodify'] = gmtime();

    if ($GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'),
        $aAddr, 'UPDATE', "order_sn = '$order_sn'")){

        // add log
        unset($aAddr['lastmodify']);
        require_once(ROOT_PATH . 'includes/lib_common.php');
        $action_note = "管理员更新收货人地址：".$orders['order_sn'].":".implode(';', $aAddr);
        order_action($orders['order_sn'], $orders['order_status'], $orders['shipping_status'], $orders['pay_status'], $action_note,'system');
        // 请求crm
        update_order_crm($orders['order_sn']);
        data_back('true','',RETURN_TYPE);
    }else{
        api_err('0x003','update error');
    }
}


// 添加买家家留言
function add_buyer_msg(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $order_sn = $_POST['rel_order']?$_POST['rel_order']:'-1';
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('order_info') .
        " WHERE order_sn = $order_sn";
    $orders = $GLOBALS['db']->getRow($sql);
    if (!$orders) {
        api_err('','订单号“'.$order_sn.'”不存在。');
    }
    $order = array();
    $order['postscript'] = $orders['postscript']."<br>".$_POST['msg_from'].":".htmlspecialchars($_POST['message'])."--".$_POST['date']."；";
    require_once(ROOT_PATH . 'includes/lib_order.php');
    update_order($orders['order_id'], $order);
    // add log
    require_once(ROOT_PATH . 'includes/lib_common.php');
    $action_note = "管理员添加买家留言：".$orders['order_sn']."，留言：".htmlspecialchars($_POST['message']);
    order_action($orders['order_sn'], $orders['order_status'], $orders['shipping_status'], $orders['pay_status'], $action_note,'system');
    data_back(array('order_id'=>$order_sn),'',RETURN_TYPE);
}


// 添加备注
function update_memo(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $data = $_POST;
    $order_sn=$data['order_id']?$data['order_id']:'-1';
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('order_info') .
        " WHERE order_sn = $order_sn";
    $orders = $GLOBALS['db']->getRow($sql);
    if (!$orders) {
        api_err('','订单号“'.$order_sn.'”不存在。');
    }
    require_once(ROOT_PATH . 'includes/lib_order.php');
    $order = array();
    $order['to_buyer'] = $orders['to_buyer']."<br>".htmlspecialchars($data['mark_text'])."--".$data['date']."；";
    update_order($orders['order_id'],$order);
    // add log
    require_once(ROOT_PATH . 'includes/lib_common.php');
    $action_note = "管理员添加备注：".$orders['order_sn']."，备注：".htmlspecialchars($data['mark_text']);
    order_action($orders['order_sn'], $orders['order_status'], $orders['shipping_status'], $orders['pay_status'], $action_note,'system');
    data_back(array('order_id'=>$order_sn),'',RETURN_TYPE);
}


// 更新订单状态
function update_order_status(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $data = $_POST;
    $order_sn = $data['order_id'];
    $status = $data['status'];
    $type = $data['type'];
    $lastmodify = gmtime();
    $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('order_info')." WHERE order_sn = ".$order_sn;
    $_order = $GLOBALS['db']->getRow($sql);
    if (!$_order) {
        api_err('','订单号“'.$order_sn.'”不存在');
    }

    $sql = " update ".$GLOBALS['ecs']->table('order_info')." ";
    $loginfo = array();
    switch ($type){
        case 'pay_status' : //支付状态
            $msg = '订单支付状态改为';
            switch($status){
                case '0':
                    $loginfo['msg'] = $msg.'未支付';
                    $loginfo['behavior'] = '未支付';
                    break;
                case '1':
                    // $loginfo['msg'] = $msg.'已支付';
                    // $loginfo['behavior'] = '已支付';
                    $loginfo['msg'] = $msg.'已支付';
                    $loginfo['behavior'] = '已支付';
                    $status = '2';
                    break;
                // case '2':
                //     $loginfo['msg'] = $msg.'已付款至担保方';
                //     $loginfo['behavior'] = '已付款至担保方';
                //     break;
                // case '3':
                //     $loginfo['msg'] = $msg.'部分付款';
                //     $loginfo['behavior'] = '部分付款';
                //     break;
                // case '4':
                //     $loginfo['msg'] = $msg.'部分退款';
                //     $loginfo['behavior'] = '部分退款';
                //     break;
                // case '5':
                //     $loginfo['msg'] = $msg.'全额退款';
                //     $loginfo['behavior'] = '全额退款';
                //     break;
                case '6':
                    $loginfo['msg'] = $msg.'处理中';
                    $loginfo['behavior'] = '处理中';
                    $status = '1';
                    break;
                default:
                    api_err('0x015',"status pay_status 参数错误");
                    break;
            }
            $sql .= " set pay_status = '{$status}',lastmodify='".$lastmodify."' ";
            break;
        case 'ship_status' :  //发货状态
            $msg = '订单发货状态改为';
            switch($status){
                case '0':
                    $loginfo['msg'] = $msg.'未发货';
                    $loginfo['behavior'] = '未发货';
                    break;
                case '1':
                    $loginfo['msg'] = $msg.'已发货';
                    $loginfo['behavior'] = '已发货';
                    break;
                // case '2':
                //     $loginfo['msg'] = $msg.'部分发货';
                //     $loginfo['behavior'] = '部分发货';
                //     break;
                // case '3':
                //     $loginfo['msg'] = $msg.'部分退货';
                //     $loginfo['behavior'] = '部分退货';
                //     break;
                // case '4':
                //     $loginfo['msg'] = $msg.'已退货';
                //     $loginfo['behavior'] = '已退货';
                //     break;
                case '5':
                    $loginfo['msg'] = $msg.'已收货';
                    $loginfo['behavior'] = '已收货';
                    $status = '2';
                    break;
                case '6':
                    $loginfo['msg'] = $msg.'备货中';
                    $loginfo['behavior'] = '备货中';
                    $status = '3';
                    break;
                default:
                    api_err('0x015',"status ship_status参数错误 参数错误");
                    break;
            }
            $sql .= " set shipping_status = '{$status}' ,lastmodify='".$lastmodify."' ";
            break;
        case 'status' : //订单状态
            // order_status tinyint 订单状态。0，未确认；1，已确认；2，已取消；3，无效；4，退货；
            $msg = '订单状态改为';
            switch($status){
                case 'active':
                    $loginfo['msg'] = $msg.'活动';
                    $loginfo['behavior'] = '活动';
                    break;
                case 'finish':
                    $loginfo['msg'] = $msg.'完成';
                    $loginfo['behavior'] = '完成';
                    break;
                case 'pending':
                    $loginfo['msg'] = $msg.'暂停';
                    $loginfo['behavior'] = '暂停';
                    break;
                case 'dead':
                    if($row = $GLOBALS['db']->getOne("select order_id from ".$GLOBALS['ecs']->table('order_info')." where order_sn = {$order_sn} and (pay_status = 0 or order_amount=0)")){
                        $loginfo['msg'] = $msg.'死单';
                        $loginfo['behavior'] = '死单';
                        $status = 2;
                    }else{
                        include_once(ROOT_PATH . 'includes/cls_matrix.php');
                        $matrix = new matrix;
                        $matrix->updateOrder($order_sn);
                        api_err('0x003',"pay_status 不是未支付状态");
                    }
                    break;
                default:
                    api_err('0x015',"status  参数错误");
                    break;
            }
            $sql .= " set order_status = '{$status}' ,lastmodify='".$lastmodify."' ";
            break;
        default:
            api_err('0x015',"type  参数错误");
            break;
    }
    $sql .= " where order_sn = {$order_sn} ";
    if(!$GLOBALS['db']->query($sql, 'SILENT')){
        api_err('0x003',"sql error");
    }
    // add log
    require_once(ROOT_PATH . 'includes/lib_common.php');
    $action_note = "管理员修改订单状态";
    order_action($_order['order_sn'], $_order['order_status'], $_order['shipping_status'], $_order['pay_status'], $action_note,'system');
    // 请求crm
    update_order_crm($_order['order_sn']);
    data_back('true','',RETURN_TYPE);
}


/**
 *  获取商品列表接口函数
 */
function search_goods_list()
{
    check_auth();   //检查基本权限

    $version = '1.0';   //版本号

    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    if (is_numeric($_POST['last_modify_st_time']) && is_numeric($_POST['last_modify_en_time']))
    {
        $sql = 'SELECT COUNT(*) AS count' .
            ' FROM ' . $GLOBALS['ecs']->table('goods') .
            " WHERE is_delete = 0 AND is_on_sale = 1 AND (last_update > '" . $_POST['last_modify_st_time'] . "' OR last_update = 0)";
        $date_count = $GLOBALS['db']->getRow($sql);

        if (empty($date_count))
        {
            api_err('0x003', 'no data to back');    //无符合条件数据
        }

        $page = empty($_POST['pages']) ? 1 : $_POST['pages'];       //确定读取哪些记录
        $counts = empty($_POST['counts']) ? 100 : $_POST['counts'];

        $sql = 'SELECT goods_id, last_update AS last_modify' .
            ' FROM ' . $GLOBALS['ecs']->table('goods') .
            " WHERE is_delete = 0 AND is_on_sale = 1 AND (last_update > '" . $_POST['last_modify_st_time'] . "' OR last_update = 0)".
            " LIMIT ".($page - 1) * $counts . ', ' . $counts;
        $date_arr = $GLOBALS['db']->getAll($sql);

        if (!empty($_POST['columns']))
        {
            $column_arr = explode('|', $_POST['columns']);
            foreach ($date_arr as $k => $v)
            {
                foreach ($v as $key => $val)
                {
                    if (in_array($key, $column_arr))
                    {
                        $re_arr['data_info'][$k][$key] = $val;
                    }
                }
            }
        }
        else
        {
            $re_arr['data_info'] = $date_arr;
        }

        /* 处理更新时间等于0的数据 */
        $sql = 'UPDATE ' . $GLOBALS['ecs']->table('goods') .
            " SET last_update = 1 WHERE is_delete = 0 AND is_on_sale = 1 AND last_update = 0";
        $GLOBALS['db']->query($sql, 'SILENT');

        $re_arr['counts'] = $date_count['count'];
        data_back($re_arr, '', RETURN_TYPE);  //返回数据
    }
    else
    {
        api_err('0x003', 'required date invalid');   //请求数据异常
    }
}


/**
 *  商品详细信息接口函数
 */
function search_goods_detail()
{
    check_auth();   //检查基本权限

    $version = '1.0';   //版本号

    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    if (!empty($_POST['goods_id']) && is_numeric($_POST['goods_id']))
    {
        $sql = 'SELECT g.goods_id, g.last_update AS last_modify, g.cat_id, c.cat_name AS category_name, g.brand_id, b.brand_name, g.shop_price AS price, g.goods_sn AS bn, g.goods_name AS name, g.is_on_sale AS marketable, g.goods_weight AS weight, g.goods_number AS store , g.give_integral AS score, g.add_time AS uptime, g.original_img AS image_default, g.goods_desc AS intro' .
            ' FROM ' . $GLOBALS['ecs']->table('category') . ' AS c, ' . $GLOBALS['ecs']->table('goods') . ' AS g LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON g.brand_id = b.brand_id'.
            ' WHERE g.cat_id = c.cat_id AND g.goods_id = ' . $_POST['goods_id'];
        $goods_data = $GLOBALS['db']->getRow($sql);

        if (empty($goods_data))
        {
            api_err('0x003', 'no data to back');    //无符合条件数据
        }

        $goods_data['goods_link'] = 'http://' . $_SERVER['HTTP_HOST'] . '/goods.php?id=' . $goods_data['goods_id'];
        $goods_data['image_default'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $goods_data['image_default'];
        $goods_data['unit'] = '千克';
        $goods_data['brand_name'] = empty($goods_data['brand_name']) ? '' : $goods_data['brand_name'];

        $prop = create_goods_properties($_POST['goods_id']);
        $goods_data['props_name'] = $prop['props_name'];
        $goods_data['props'] = $prop['props'];

        if (!empty($_POST['columns']))
        {
            $column_arr = explode('|', $_POST['columns']);
            foreach ($goods_data as $key=>$val)
            {
                if (in_array($key, $column_arr))
                {
                    $re_arr['data_info'][$key] = $val;
                }
            }
        }
        else
        {
            $re_arr['data_info'] = $goods_data;
        }

        data_back($re_arr, '', RETURN_TYPE);  //返回数据
    }
    else
    {
        api_err('0x003', 'required date invalid');   //请求数据异常
    }
}


/**
 *  被删除商品列表接口函数
 */
function search_deleted_goods_list()
{
    api_err('0x007', '暂时不提供此服务功能');   //服务不可用
}

/**
 *  获取货品列表接口函数
 */
function search_products_list()
{
    check_auth();   //检查基本权限

    $version = '1.0';   //版本号

    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    if (!empty($_POST['goods_id']) && is_numeric($_POST['goods_id']) || !empty($_POST['bn']))
    {
        $sql = 'SELECT goods_id, last_update AS last_modify, shop_price AS price, goods_sn AS bn, goods_name AS name,  goods_weight         AS weight, goods_number AS store, add_time AS uptime' .
        ' FROM ' . $GLOBALS['ecs']->table('goods') .
        ' WHERE ' . empty($_POST['bn']) ? "goods_id = $_POST[goods_id]" : "goods_sn = $_POST[bn]";
        $goods_data = $GLOBALS['db']->getRow($sql);

        if (empty($goods_data))
        {
            api_err('0x003', 'no data to back');    //无符合条件数据
        }

        $goods_data['product_id'] = $_POST['goods_id'];
        $goods_data['cost'] = $goods_data['price'];

        $prop = create_goods_properties($_POST['goods_id']);
        $goods_data['props'] = $prop['props'];

        if (!empty($_POST['columns']))
        {
            $column_arr = explode('|', $_POST['columns']);
            foreach ($goods_data as $key=>$val)
            {
                if (in_array($key, $column_arr))
                {
                    $re_arr['data_info'][$key] = $val;
                }
            }
        }
        else
        {
            $re_arr['data_info'] = $goods_data;
        }

        data_back($re_arr, '', RETURN_TYPE);  //返回数据
    }
    else
    {
        api_err('0x003', 'required date invalid');   //请求数据异常
    }
}

/**
 *  获取站点信息接口函数
 */
function search_site_info()
{
    check_auth();   //检查基本权限

    $version = '1.0';   //版本号

    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    $sql = 'SELECT code, value'.
        ' FROM ' . $GLOBALS['ecs']->table('shop_config') .
        " WHERE code IN ('shop_name', 'service_phone')";

    $siteinfo['data_info'] = $GLOBALS['db']->getRow($sql);

    $siteinfo['data_info']['site_address'] = $_SERVER['SERVER_NAME'];

    data_back($siteinfo, '', RETURN_TYPE);  //返回数据
}

/**
 *  权限校验函数
 */
function check_auth()
{
    // return true;
    // $license = get_shop_license();  // 取出网店 license信息
    $GLOBALS['cert'] = new certificate();
    $license = $GLOBALS['cert']->get_shop_certificate();  // 取出网店 license信息

    if (empty($license['certificate_id']) || empty($license['token']) )
    {
        api_err('0x006', 'no certificate');   //没有证书数据，输出系统级错误:用户权限不够
    }

    if (!check_shopex_ac_new($_POST, $license['token']))
    {
        api_err('0x009');   //输出系统级错误:签名无效
    }

    // /* 对应用申请的session进行验证 */
    // $certi['certificate_id'] = $license['certificate_id']; // 网店证书ID
    // $certi['app_id'] = 'ecshop_b2c'; // 说明客户端来源
    // $certi['app_instance_id'] = 'webcollect'; // 应用服务ID
    // $certi['version'] = VERSION . '#' .  RELEASE; // 网店软件版本号
    // $certi['format'] = 'json'; // 官方返回数据格式
    // $certi['certi_app'] = 'sess.valid_session'; // 证书方法
    // $certi['certi_session'] = $_POST['app_session']; //应用服务器申请的session值
    // $certi['certi_ac'] = $cert->make_shopex_ac($certi, $license['token']); // 网店验证字符串

    // $request_arr = $cert->exchange_shop_license($certi, $license);
    // if ($request_arr['res'] != 'succ')
    // {
    //     api_err('0x001', 'session is invalid');   //输出系统级错误:身份验证失败
    // }
}


function check_shopex_ac_new($params,$token){
    $verfy=strtolower(trim($params['ac']));
    unset($params['ac']);

    ksort($params);
    $tmp_verfy='';
    foreach($params as $key=>$value){
        $params[$key]=stripslashes($value);
        $tmp_verfy.=$params[$key];
    }
    if($verfy && $verfy==strtolower(md5(trim($tmp_verfy.$token)))){
        return true;
    }else{
        return false;
    }
    // if($verfy && $verfy == strtoupper(md5(strtoupper(md5(assemble($params))).$token))){
    //     return true;
    // }
    // return false;
}


function assemble($params)
{
    if(!is_array($params)){
        return null;
    }

    ksort($params,SORT_STRING);
    $sign = '';
    foreach($params AS $key=>$val){
        $sign .= $key . (is_array($val) ? assemble($val) : $val);
    }
    return $sign;
}

/**
 *  验证POST签名
 *
 *  @param   string   $post_params   POST传递参数
 *  @param   string   $token         证书加密码
 *
 *  @return  boolean                 返回是否有效
 */
function check_shopex_ac($post_params,$token)
{
    ksort($post_params);
    $str = '';
    foreach($post_params as $key=>$value)
    {
        if ($key!='ac')
        {
            $str.=$value;
        }
    }
    if ($post_params['ac'] == md5($str.$token))
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 *  系统级错误处理
 *
 *  @param   string   $err_type   错误类型代号
 *  @param   string   $err_info   错误说明
 *
 */
function api_err($err_type, $err_info = '',$return_type=RETURN_TYPE)
{
    /* 系统级错误列表 */
    $err_arr = array();
    $err_arr['0x001'] = 'Verify fail';          //身份验证失败
    $err_arr['0x002'] = 'Time out';             //请求/执行超时
    $err_arr['0x003'] = 'Data fail';            //数据异常
    $err_arr['0x004'] = 'Db error';             //数据库执行失败
    $err_arr['0x005'] = 'Service error';        //服务器导常
    $err_arr['0x006'] = 'User permissions';     //用户权限不够
    $err_arr['0x007'] = 'Service unavailable';  //服务不可用
    $err_arr['0x008'] = 'Missing Method';       //方法不可用
    $err_arr['0x009'] = 'Missing signature';    //签名无效
    $err_arr['0x010'] = 'Missing api version';  //版本丢失
    $err_arr['0x011'] = 'Api verion error';     //API版本异常
    $err_arr['0x012'] = 'Api need update';      //API需要升级
    $err_arr['0x013'] = 'Shop Error';           //网痁服务异常
    $err_arr['0x014'] = 'Shop Space Error';     //网店空间不足
    $err_arr['0x015'] = 'Request parameters error'; //请求参数有误
    $err_arr['0x016'] = 'Parameter value error';    //参数值异常

    data_back($err_info == '' ? $err_arr[$err_type] : $err_info, $err_type, $return_type, 'fail');  //回复请求以错误信息
}

/**
 *  返回结果集
 *
 *  @param   mixed      $info       返回的有效数据集或是错误说明
 *  @param   string     $msg        为空或是错误类型代号
 *  @param   string     $result     请求成功或是失败的标识
 *  @param   int        $post       1为xml方式，2为json方式
 *
 */
function data_back($info, $msg = '', $post, $result = 'success')
{
    /* 分为xml和json两种方式 */
    $data_arr = array('result'=>$result, 'msg'=>$msg, 'info'=>$info ,'shopex_time'=>gmtime());
    $data_arr = to_utf8_iconv($data_arr);  //确保传递的编码为UTF-8

    if ($post == 1)
    {
        /* xml方式 */
        if (class_exists('DOMDocument'))
        {
            $doc=new DOMDocument('1.0','UTF-8');
            $doc->formatOutput=true;

            $shopex=$doc->createElement('shopex');
            $doc->appendChild($shopex);

            $result=$doc->createElement('result');
            $shopex->appendChild($result);
            $result->appendChild($doc->createCDATASection($data_arr['result']));

            $msg=$doc->createElement('msg');
            $shopex->appendChild($msg);
            $msg->appendChild($doc->createCDATASection($data_arr['msg']));

            $info=$doc->createElement('info');
            $shopex->appendChild($info);

            create_tree($doc, $info, $data_arr['info']);
            die($doc->saveXML());
        }

        die('<?xml version="1.0" encoding="UTF-8"?>' . array2xml($data_arr)) ;
    }
    else
    {
        /* json方式 */
        $json  = new JSON;
        // error_log(print_R(json_encode($data_arr),1)."\n",3,"/tmp/chen_".date('Y-m-d',time()).".log");
        die($json->encode($data_arr));    //把生成的返回字符串打印出来
    }
}

/**
 *  循环生成xml节点
 *
 *  @param  handle      $doc            xml实例句柄
 *  @param  handle      $top            当前父节点
 *  @param  array       $info_arr       需要解析的数组
 *  @param  boolean     $have_item      是否是数据数组，是则需要在每条数据上加item父节点
 *
 */
function create_tree($doc, $top, $info_arr, $have_item = false)
{
    if (is_array($info_arr))
    {
        foreach ($info_arr as $key => $val)
        {
            if (is_array($val))
            {
                if ($have_item == false)
                {
                    $data_info=$doc->createElement('data_info');
                    $top->appendChild($data_info);
                    create_tree($doc, $data_info, $val, true);
                }
                else
                {
                    $item=$doc->createElement('item');
                    $top->appendChild($item);
                    $key_code = $doc->createAttribute('key');
                    $item->appendChild($key_code);
                    $key_code->appendChild($doc->createTextNode($key));
                    create_tree($doc, $item, $val);
                }
            }
            else
            {
                $text_code=$doc->createElement($key);
                $top->appendChild($text_code);
                if (is_string($val))
                {
                    $text_code->appendChild($doc->createCDATASection($val));
                }
                else
                {
                    $text_code->appendChild($doc->createTextNode($val));
                }
            }
        }
    }
    else
    {
        $top->appendChild($doc->createCDATASection($info_arr));
    }
}

function array2xml($data,$root='shopex'){
    $xml='<'.$root.'>';
    _array2xml($data,$xml);
    $xml.='</'.$root.'>';
    return $xml;
}

function _array2xml(&$data,&$xml){
    if(is_array($data)){
        foreach($data as $k=>$v){
            if(is_numeric($k)){
                $xml.='<item key="' . $k . '">';
                $xml.=_array2xml($v,$xml);
                $xml.='</item>';
            }else{
                $xml.='<'.$k.'>';
                $xml.=_array2xml($v,$xml);
                $xml.='</'.$k.'>';
            }
        }
    }elseif(is_numeric($data)){
        $xml.=$data;
    }elseif(is_string($data)){
        $xml.='<![CDATA['.$data.']]>';
    }
}

function create_goods_properties($goods_id)
{
    /* 对属性进行重新排序和分组
    $sql = "SELECT attr_group ".
            "FROM " . $GLOBALS['ecs']->table('goods_type') . " AS gt, " . $GLOBALS['ecs']->table('goods') . " AS g ".
            "WHERE g.goods_id='$goods_id' AND gt.cat_id=g.goods_type";
    $grp = $GLOBALS['db']->getOne($sql);

    if (!empty($grp))
    {
        $groups = explode("\n", strtr($grp, "\r", ''));
    }
    */

    /* 获得商品的规格 */
    $sql = "SELECT a.attr_id, a.attr_name, a.attr_group, a.is_linked, a.attr_type, ".
        "g.goods_attr_id, g.attr_value, g.attr_price " .
        'FROM ' . $GLOBALS['ecs']->table('goods_attr') . ' AS g ' .
        'LEFT JOIN ' . $GLOBALS['ecs']->table('attribute') . ' AS a ON a.attr_id = g.attr_id ' .
        "WHERE g.goods_id = '$goods_id' " .
        'ORDER BY a.sort_order, g.attr_price, g.goods_attr_id';
    $res = $GLOBALS['db']->getAll($sql);

    $arr = array();
    $arr['props_name'] = array();     // props_name
    $arr['props'] = array();          // props

    foreach ($res AS $row)
    {
        if ($row['attr_type'] == 0)
        {
            //$group = (isset($groups[$row['attr_group']])) ? $groups[$row['attr_group']] : $GLOBALS['_LANG']['goods_attr'];

            //$arr['props_name'][$row['attr_group']]['name'] = $group;
            $arr['props_name'][] = array('name' => $row['attr_name'], 'value' => $row['attr_value']);

            $arr['props'][] = array('pid' => $row['attr_id'], 'vid' => $row['goods_attr_id']);
        }
    }

    return $arr;
}
/**
 * 接收淘打证书数据，联通矩阵申请绑定关系
 * @param type $data
 */
function get_certinfo(){
    $data = $_POST;
    check_auth();   //检查基本权限

    $version = '1.0';   //版本号

    if ($data['api_version'] != $version){      //网店的接口版本低{
        api_err('0x008', 'a low version api');
    }
    //验证必填参数
    if(!$data['node_id'] || !$data['token']){
        api_err('0x003', 'required date invalid');
    }
    include_once(ROOT_PATH."includes/cls_certificate.php");
    $cert = new certificate();
    //验证是否有eid的账号认证过
    $row = $GLOBALS['db']->getRow("select user_id from ".$GLOBALS['ecs']->table('admin_user')." where passport_uid = '".trim($data['eid'])."'");
    if($row){
        //获取证书
        $license = $cert->get_shop_certificate();
        $node_id = $license['node_id'];
        $cert_id = $license['certificate_id'];
        $token = $license['token'];

        if($node_id and $cert_id and $token){
            $is_bind = $cert->is_bind_sn('taodali','bind_type');
            $is_bind and api_err('0x003', 'taodali is bind');

            $is_bind_erp = $cert->is_bind_sn('ecos.ome','bind_type');
            $is_bind_erp and api_err('0x003','erp is bind');

            $ret['node_id'] = $node_id;
            $ret['cert_id'] = $cert_id;
            $ret['token'] = $token;
            //联通矩阵 申请绑定
            $params = array();
            $params['node_id'] = $node_id;
            $params['certi_id'] = $cert_id;
            $params['token'] = $token;
            $params['to_node'] = $data['node_id'];
            $params['to_token'] = $data['token'];
            $params['shop_name'] = '淘打';
            $res = $cert->applyNodeBind($params);
            if($res['res']=='succ'){
                //shop_config 标识 绑定淘打成功
                $sql = "insert into ".$GLOBALS['ecs']->table('shop_config')." set parent_id=2,code='bind_taodali',type='hidden',value='".serialize($data)."'";
                data_back($ret, '', RETURN_TYPE);  //返回数据
            }elseif($res['res']=='fail' && $res['info']!=""){
                api_err('0x003', 'bind fail');
            }else{
                api_err('0x003', $res['msg']);
            }
        }else{
            api_err('0x003', 'license fail');
        }

    }else{
        api_err('0x003', 'eid fail');
    }
}

function fy_logistics_offline_send(){
    $ecs = $GLOBALS['ecs'];
    $db = $GLOBALS['db'];
    $data = $_POST;
    check_auth();   //检查基本权限

    $version = '1.0';   //版本号
    if ($data['api_version'] != $version){      //网店的接口版本低{
        api_err('0x008', 'a low version api');
    }

    if(!$data['tid'] || !$data['logistics_no']){//验证必填参数
        api_err('0x003', 'required date invalid');
    }

    if(!verify_order_valid($data['tid'],$order,'*',$msg)){//验证订单有效性
        api_err('0x003', $msg);
    }
    //加入重复发货验证
    if($order['shipping_status'] == SS_SHIPPED){
        api_err('0x003', '订单已经发货，不需要重复发货');
    }

    $invoice_no = empty($data['logistics_no'])?"":trim($data['logistics_no']);//快递单号
    $order['shipping_name'] = $data['company_name'];
    if (!empty($invoice_no)){
        include_once(ROOT_PATH."includes/lib_order.php");
        include_once(ROOT_PATH."includes/lib_main.php");
        include_once(ROOT_PATH."includes/lib_time.php");
        include_once(ROOT_PATH."includes/lib_common.php");
        $order_id = intval($order['order_id']);
        $gmtime = gmtime();// 获取 UTC 时间戳
        /* 标记订单为已确认，配货中 */
        if ($order['order_status'] != OS_CONFIRMED)
        {
            $arr['order_status']    = OS_CONFIRMED;
            $arr['confirm_time']    = $gmtime;
        }
        $arr['shipping_status']     = SS_PREPARING;
        update_order($order_id, $arr);

        /* 记录log */
        order_action($order['order_sn'], OS_CONFIRMED, SS_PREPARING, $order['pay_status'], $action_note);

        /* 清除缓存 */
        /* 查询：取得用户名 */
        if ($order['user_id'] > 0){
            $user = user_info($order['user_id']);
            if (!empty($user)){
                $order['user_name'] = $user['user_name'];
            }
        }

        /* 取得区域名 */
        $sql = "SELECT concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), " .
            "'  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region " .
            "FROM " . $ecs->table('order_info') . " AS o " .
            "LEFT JOIN " . $ecs->table('region') . " AS c ON o.country = c.region_id " .
            "LEFT JOIN " . $ecs->table('region') . " AS p ON o.province = p.region_id " .
            "LEFT JOIN " . $ecs->table('region') . " AS t ON o.city = t.region_id " .
            "LEFT JOIN " . $ecs->table('region') . " AS d ON o.district = d.region_id " .
            "WHERE o.order_id = '" . $order['order_id'] . "'";
        $order['region'] = $delivery_order['region'] = $db->getOne($sql);

        /* 查询：其他处理 */
        $order['order_time']    = local_date($GLOBALS['_CFG']['time_format'], $order['add_time']);
        $order['invoice_no']    = $order['shipping_status'] == SS_UNSHIPPED || $order['shipping_status'] == SS_PREPARING ? $_LANG['ss'][SS_UNSHIPPED] : $order['invoice_no'];

        /* 查询：是否保价 */
        $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;
        /* 查询：是否存在实体商品 */
        $exist_real_goods = exist_real_goods($order_id);

        /* 查询：取得订单商品 */
        $_goods = get_order_goods(array('order_id' => $order_id, 'order_sn' =>$order['order_sn']));

        $attr = $_goods['attr'];
        $goods_list = $_goods['goods_list'];
        unset($_goods);

        /* 查询：商品已发货数量 此单可发货数量 */
        if ($goods_list){
            foreach ($goods_list as $key=>$goods_value){
                if (!$goods_value['goods_id']) continue;

                /* 超级礼包 */
                if (($goods_value['extension_code'] == 'package_buy') && (count($goods_value['package_goods_list']) > 0)){
                    $goods_list[$key]['package_goods_list'] = package_goods($goods_value['package_goods_list'], $goods_value['goods_number'], $goods_value['order_id'], $goods_value['extension_code'], $goods_value['goods_id']);

                    foreach ($goods_list[$key]['package_goods_list'] as $pg_key => $pg_value){
                        $goods_list[$key]['package_goods_list'][$pg_key]['readonly'] = '';
                        /* 使用库存 是否缺货 */
                        if ($pg_value['storage'] <= 0 && $GLOBALS['_CFG']['use_storage'] == '1' && $GLOBALS['_CFG']['stock_dec_time'] == SDT_SHIP){
                            $goods_list[$key]['package_goods_list'][$pg_key]['send'] = $_LANG['act_good_vacancy'];
                            $goods_list[$key]['package_goods_list'][$pg_key]['readonly'] = 'readonly="readonly"';
                        }elseif ($pg_value['send'] <= 0){/* 将已经全部发货的商品设置为只读 */
                            $goods_list[$key]['package_goods_list'][$pg_key]['send'] = $_LANG['act_good_delivery'];
                            $goods_list[$key]['package_goods_list'][$pg_key]['readonly'] = 'readonly="readonly"';
                        }
                    }
                }else{
                    $goods_list[$key]['sended'] = $goods_value['send_number'];
                    $goods_list[$key]['sended'] = $goods_value['goods_number'];
                    $goods_list[$key]['send'] = $goods_value['goods_number'] - $goods_value['send_number'];
                    $goods_list[$key]['readonly'] = '';
                    /* 是否缺货 */
                    if ($goods_value['storage'] <= 0 && $GLOBALS['_CFG']['use_storage'] == '1'  && $GLOBALS['_CFG']['stock_dec_time'] == SDT_SHIP)
                    {
                        $goods_list[$key]['send'] = $_LANG['act_good_vacancy'];
                        $goods_list[$key]['readonly'] = 'readonly="readonly"';
                    }
                    elseif ($goods_list[$key]['send'] <= 0)
                    {
                        $goods_list[$key]['send'] = $_LANG['act_good_delivery'];
                        $goods_list[$key]['readonly'] = 'readonly="readonly"';
                    }
                }
            }
        }

        $suppliers_id = 0;

        $delivery['order_sn'] = trim($order['order_sn']);
        $delivery['add_time'] = trim($order['order_time']);
        $delivery['user_id'] = intval(trim($order['user_id']));
        $delivery['how_oos'] = trim($order['how_oos']);
        // $delivery['shipping_id'] = trim($order['shipping_id']);
        $delivery['shipping_id'] = 0;
        $delivery['shipping_fee'] = trim($order['shipping_fee']);
        $delivery['consignee'] = trim($order['consignee']);
        $delivery['address'] = trim($order['address']);
        $delivery['country'] = intval(trim($order['country']));
        $delivery['province'] = intval(trim($order['province']));
        $delivery['cit'] = intval(trim($order['city']));
        $delivery['district'] = intval(trim($order['district']));
        $delivery['sign_building'] = trim($order['sign_building']);
        $delivery['email'] = trim($order['email']);
        $delivery['zipcode'] = trim($order['zipcode']);
        $delivery['tel'] = trim($order['tel']);
        $delivery['mobile'] = trim($order['mobile']);
        $delivery['best_time'] = trim($order['best_time']);
        $delivery['postscript'] = trim($order['postscript']);
        $delivery['how_oos'] = trim($order['how_oos']);
        $delivery['insure_fee'] = floatval(trim($order['insure_fee']));
        $delivery['shipping_fee'] = floatval(trim($order['shipping_fee']));
        $delivery['agency_id'] = intval(trim($order['agency_id']));
        $delivery['shipping_name'] = trim($order['shipping_name']);


        $virtual_goods = array();
        /* 生成发货单 */
        /* 获取发货单号和流水号 */
        $delivery['delivery_sn'] = get_delivery_sn();
        $delivery_sn = $delivery['delivery_sn'];

        /* 获取当前操作员 */
        $delivery['action_user'] = 'system';

        /* 获取发货单生成时间 */
        $delivery['update_time'] = $gmtime;
        $delivery_time = $delivery['update_time'];
        $sql ="select add_time from ". $ecs->table('order_info') ." WHERE order_sn = '" . $delivery['order_sn'] . "'";
        $delivery['add_time'] =  $db->getOne($sql);
        /* 获取发货单所属供应商 */
        $delivery['suppliers_id'] = $suppliers_id;

        /* 设置默认值 */
        $delivery['status'] = 2; // 正常
        $delivery['order_id'] = $order_id;

        /* 过滤字段项 */
        $filter_fileds = array(
            'order_sn', 'add_time', 'user_id', 'how_oos', 'shipping_id', 'shipping_fee',
            'consignee', 'address', 'country', 'province', 'city', 'district', 'sign_building',
            'email', 'zipcode', 'tel', 'mobile', 'best_time', 'postscript', 'insure_fee',
            'agency_id', 'delivery_sn', 'action_user', 'update_time',
            'suppliers_id', 'status', 'order_id', 'shipping_name'
        );
        $_delivery = array();
        foreach ($filter_fileds as $value){
            $_delivery[$value] = $delivery[$value];
        }
        /* 发货单入库 */
        $query = $db->autoExecute($ecs->table('delivery_order'), $_delivery, 'INSERT', '', 'SILENT');
        $delivery_id = $db->insert_id();
        if ($delivery_id){
            $delivery_goods = array();

            //发货单商品入库
            if (!empty($goods_list)){
                foreach ($goods_list as $value){
                    // 商品（实货）（虚货）
                    if (empty($value['extension_code']) || $value['extension_code'] == 'virtual_card'){
                        $delivery_goods = array(
                            'delivery_id' => $delivery_id,
                            'goods_id' => $value['goods_id'],
                            'product_id' => $value['product_id'],
                            'product_sn' => $value['product_sn'],
                            'goods_id' => $value['goods_id'],
                            'goods_name' => $value['goods_name'],
                            'brand_name' => $value['brand_name'],
                            'goods_sn' => $value['goods_sn'],
                            'send_number' => $value['goods_number'],
                            'parent_id' => 0,
                            'is_real' => $value['is_real'],
                            'goods_attr' => $value['goods_attr']
                        );
                        /* 如果是货品 */
                        if (!empty($value['product_id'])){
                            $delivery_goods['product_id'] = $value['product_id'];
                        }
                        $query = $db->autoExecute($ecs->table('delivery_goods'), $delivery_goods, 'INSERT', '', 'SILENT');
                        $sql = "UPDATE ".$ecs->table('order_goods'). " SET send_number = " . $value['goods_number'] . " WHERE order_id = '" . $value['order_id'] . "' AND goods_id = '" . $value['goods_id'] . "' ";
                        $db->query($sql, 'SILENT');
                    }elseif ($value['extension_code'] == 'package_buy'){// 商品（超值礼包）
                        foreach ($value['package_goods_list'] as $pg_key => $pg_value){
                            $delivery_pg_goods = array(
                                'delivery_id' => $delivery_id,
                                'goods_id' => $pg_value['goods_id'],
                                'product_id' => $pg_value['product_id'],
                                'product_sn' => $pg_value['product_sn'],
                                'goods_name' => $pg_value['goods_name'],
                                'brand_name' => '',
                                'goods_sn' => $pg_value['goods_sn'],
                                'send_number' => $value['goods_number'],
                                'parent_id' => $value['goods_id'], // 礼包ID
                                'extension_code' => $value['extension_code'], // 礼包
                                'is_real' => $pg_value['is_real']
                            );
                            $query = $db->autoExecute($ecs->table('delivery_goods'), $delivery_pg_goods, 'INSERT', '', 'SILENT');
                            $sql = "UPDATE ".$ecs->table('order_goods'). " SET send_number = " . $value['goods_number'] . " WHERE order_id = '" . $value['order_id'] . "' AND goods_id = '" . $pg_value['goods_id'] . "'";
                            $db->query($sql, 'SILENT');
                        }
                    }
                }
            }
        }else{
            /* 操作失败 */
            api_err('0x003', '创建发货单失败');
        }
        unset($filter_fileds, $delivery, $_delivery, $order_finish);

        /* 定单信息更新处理 */
        if (true){
            /* 标记订单为已确认 “发货中” */
            /* 更新发货时间 */
            $order_finish = get_order_finish($order_id);
            $shipping_status = SS_SHIPPED_ING;
            if ($order['order_status'] != OS_CONFIRMED && $order['order_status'] != OS_SPLITED && $order['order_status'] != OS_SPLITING_PART){
                $arr['order_status']    = OS_CONFIRMED;
                $arr['confirm_time']    = GMTIME_UTC;
            }
            $arr['order_status'] = $order_finish ? OS_SPLITED : OS_SPLITING_PART; // 全部分单、部分分单
            $arr['shipping_status']     = $shipping_status;
            update_order($order_id, $arr);
        }

        /* 记录log */
        order_action($order['order_sn'], $arr['order_status'], $shipping_status, $order['pay_status'], $action_note);

        /* 清除缓存 */
        clear_cache_files();

        /* 根据发货单id查询发货单信息 */
        if (!empty($delivery_id)){
            $delivery_order = delivery_order_info($delivery_id);
        }

        /* 检查此单发货商品库存缺货情况 */
        $virtual_goods = array();
        $delivery_stock_sql = "SELECT DG.goods_id, DG.is_real, DG.product_id, SUM(DG.send_number) AS sums, IF(DG.product_id > 0, P.product_number, G.goods_number) AS storage, G.goods_name, DG.send_number
        FROM " . $ecs->table('delivery_goods') . " AS DG, " . $ecs->table('goods') . " AS G, " . $ecs->table('products') . " AS P
        WHERE DG.goods_id = G.goods_id
        AND DG.delivery_id = '$delivery_id'
        AND DG.product_id = P.product_id
        GROUP BY DG.product_id ";

        $delivery_stock_result = $db->getAll($delivery_stock_sql);

        /* 如果商品存在规格就查询规格，如果不存在规格按商品库存查询 */
        if(!empty($delivery_stock_result)){
            foreach ($delivery_stock_result as $value){
                if (($value['sums'] > $value['storage'] || $value['storage'] <= 0) && (($GLOBALS['_CFG']['use_storage'] == '1'  && $GLOBALS['_CFG']['stock_dec_time'] == SDT_SHIP) || ($GLOBALS['_CFG']['use_storage'] == '0' && $value['is_real'] == 0))){
                    /* 操作失败 */
                    api_err('0x003', 'goods store error');
                    break;
                }

                /* 虚拟商品列表 virtual_card*/
                if ($value['is_real'] == 0){
                    $virtual_goods[] = array(
                        'goods_id' => $value['goods_id'],
                        'goods_name' => $value['goods_name'],
                        'num' => $value['send_number']
                    );
                }
            }
        }else{
            $delivery_stock_sql = "SELECT DG.goods_id, DG.is_real, SUM(DG.send_number) AS sums, G.goods_number, G.goods_name, DG.send_number
        FROM " . $ecs->table('delivery_goods') . " AS DG, " . $ecs->table('goods') . " AS G
        WHERE DG.goods_id = G.goods_id
        AND DG.delivery_id = '$delivery_id'
        GROUP BY DG.goods_id ";
            $delivery_stock_result = $db->getAll($delivery_stock_sql);
            foreach ($delivery_stock_result as $value){
                if (($value['sums'] > $value['goods_number'] || $value['goods_number'] <= 0) && (($GLOBALS['_CFG']['use_storage'] == '1'  && $GLOBALS['_CFG']['stock_dec_time'] == SDT_SHIP) || ($GLOBALS['_CFG']['use_storage'] == '0' && $value['is_real'] == 0))){
                    api_err('0x003', 'goods store error');
                    break;
                }

                /* 虚拟商品列表 virtual_card*/
                if ($value['is_real'] == 0){
                    $virtual_goods[] = array(
                        'goods_id' => $value['goods_id'],
                        'goods_name' => $value['goods_name'],
                        'num' => $value['send_number'],
                    );
                }
            }
        }

        /* 发货 */
        /* 处理虚拟卡 商品（虚货） */
        if (is_array($virtual_goods) && count($virtual_goods) > 0){
            foreach ($virtual_goods as $virtual_value){
                virtual_card_shipping($virtual_value,$order['order_sn'], $msg, 'split');
            }
        }

        /* 如果使用库存，且发货时减库存，则修改库存 */
        if ($GLOBALS['_CFG']['use_storage'] == '1' && $GLOBALS['_CFG']['stock_dec_time'] == SDT_SHIP){
            foreach ($delivery_stock_result as $value){
                /* 商品（实货）、超级礼包（实货） */
                if ($value['is_real'] != 0){
                    //（货品）
                    if (!empty($value['product_id'])){
                        $minus_stock_sql = "UPDATE " . $GLOBALS['ecs']->table('products') . "
                                        SET product_number = product_number - " . $value['sums'] . "
                                        WHERE product_id = " . $value['product_id'];
                        $db->query($minus_stock_sql, 'SILENT');
                    }
                    $minus_stock_sql = "UPDATE " . $GLOBALS['ecs']->table('goods') . "
                                    SET goods_number = goods_number - " . $value['sums'] . "
                                    WHERE goods_id = " . $value['goods_id'];
                    $db->query($minus_stock_sql, 'SILENT');
                }
            }
        }

        /* 修改发货单信息 */
        $_delivery['invoice_no'] = $invoice_no;
        $_delivery['status'] = 0; // 0，为已发货
        $query = $db->autoExecute($ecs->table('delivery_order'), $_delivery, 'UPDATE', "delivery_id = $delivery_id", 'SILENT');
        if (!$query){
            api_err('0x003', 'update delivery_id fail');
        }

        /* 标记订单为已确认 “已发货” */
        /* 更新发货时间 */
        $order_finish = get_all_delivery_finish($order_id);
        $shipping_status = ($order_finish == 1) ? SS_SHIPPED : SS_SHIPPED_PART;
        $arr['shipping_status']     = $shipping_status;
        $arr['shipping_time']       = $gmtime; // 发货时间
        $arr['invoice_no']          = trim($order['invoice_no'] . '<br>' . $invoice_no, '<br>');
        $arr['shipping_name'] = $data['company_name'];
        update_order($order_id, $arr);

        /* 发货单发货记录log */
        order_action($order['order_sn'], OS_CONFIRMED, $shipping_status, $order['pay_status'], $action_note, null, 1);
        /* 如果当前订单已经全部发货 */
        if ($order_finish){
            /* 如果订单用户不为空，计算积分，并发给用户；发红包 */
            if ($order['user_id'] > 0){
                /* 取得用户信息 */
                $user = user_info($order['user_id']);

                /* 计算并发放积分 */
                $integral = integral_to_give($order);

                log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($_LANG['order_gift_integral'], $order['order_sn']));

                /* 发放红包 */
                send_order_bonus($order_id);
            }
            /* 发送邮件 */
            $cfg = $GLOBALS['_CFG']['send_ship_email'];
            if ($cfg == '1'){
                $order['invoice_no'] = $invoice_no;
                $tpl = get_mail_template('deliver_notice');
                $GLOBALS['smarty']->assign('order', $order);
                $GLOBALS['smarty']->assign('send_time', local_date($GLOBALS['_CFG']['time_format']));
                $GLOBALS['smarty']->assign('shop_name', $GLOBALS['_CFG']['shop_name']);
                $GLOBALS['smarty']->assign('send_date', local_date($GLOBALS['_CFG']['date_format']));
                $GLOBALS['smarty']->assign('sent_date', local_date($GLOBALS['_CFG']['date_format']));
                $GLOBALS['smarty']->assign('confirm_url', $ecs->url() . 'receive.php?id=' . $order['order_id'] . '&con=' . rawurlencode($order['consignee']));
                $GLOBALS['smarty']->assign('send_msg_url',$ecs->url() . 'user.php?act=message_list&order_id=' . $order['order_id']);
                $content = $GLOBALS['smarty']->fetch('str:' . $tpl['template_content']);
                send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);
            }

            /* 如果需要，发短信 */
            if ($GLOBALS['_CFG']['sms_order_shipped'] == '1' && $order['mobile'] != ''){
                include_once('../includes/cls_sms.php');
                $sms = new sms();
                $sms->send($order['mobile'], sprintf($GLOBALS['_LANG']['order_shipped_sms'], $order['order_sn'],
                    local_date($GLOBALS['_LANG']['sms_time_format']), $GLOBALS['_CFG']['shop_name']), 0);
            }
        }

        /* 清除缓存 */
        clear_cache_files();
        $re_arr['tid'] = $data['tid'];
        // 请求crm
        update_order_crm($order['order_sn']);
        data_back($re_arr, '',RETURN_TYPE);  //返回数据
    }
}

/**
 * 验证订单是否有效
 *
 * @param int $order_sn
 * @param array $order
 * @param string $colums
 *
 * @return 验证订单是否存在
 */
function verify_order_valid($order_sn,&$order,$colums='*',&$msg){
    $sql = "select ".$colums." from ".$GLOBALS['ecs']->table('order_info')." where order_sn='".$order_sn."'";
    $_order = $GLOBALS['db']->getRow($sql);
    if(!$_order){
        $msg = '订单号“'.$order_sn.'”不存在';
        return false;
    }else{
        $sql = "select * from ".$GLOBALS['ecs']->table('order_goods')." where order_id=".$_order['order_id'];
        $_order_goods = $GLOBALS['db']->getAll($sql);
        if(!count($_order_goods)){
            $msg = '订单号“'.$order_sn.'”异常';
            return false;
        }
    }
    $order = $_order;
    return true;
}

/**
 * 取得订单商品
 * @param   array     $order  订单数组
 * @return array
 */
function get_order_goods($order){
    $goods_list = array();
    $goods_attr = array();
    $sql = "SELECT o.*, g.suppliers_id AS suppliers_id,IF(o.product_id > 0, p.product_number, g.goods_number) AS storage, o.goods_attr, IFNULL(b.brand_name, '') AS brand_name, p.product_sn " .
        "FROM " . $GLOBALS['ecs']->table('order_goods') . " AS o ".
        "LEFT JOIN " . $GLOBALS['ecs']->table('products') . " AS p ON o.product_id = p.product_id " .
        "LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " AS g ON o.goods_id = g.goods_id " .
        "LEFT JOIN " . $GLOBALS['ecs']->table('brand') . " AS b ON g.brand_id = b.brand_id " .
        "WHERE o.order_id = '$order[order_id]' ";
    $res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        // 虚拟商品支持
        if ($row['is_real'] == 0)
        {
            /* 取得语言项 */
            $filename = ROOT_PATH . 'plugins/' . $row['extension_code'] . '/languages/common_' . $GLOBALS['_CFG']['lang'] . '.php';
            if (file_exists($filename))
            {
                include_once($filename);
                if (!empty($GLOBALS['_LANG'][$row['extension_code'].'_link']))
                {
                    $row['goods_name'] = $row['goods_name'] . sprintf($GLOBALS['_LANG'][$row['extension_code'].'_link'], $row['goods_id'], $order['order_sn']);
                }
            }
        }

        $row['formated_subtotal']       = price_format($row['goods_price'] * $row['goods_number']);
        $row['formated_goods_price']    = price_format($row['goods_price']);

        $goods_attr[] = explode(' ', trim($row['goods_attr'])); //将商品属性拆分为一个数组

        if ($row['extension_code'] == 'package_buy')
        {
            $row['storage'] = '';
            $row['brand_name'] = '';
            $row['package_goods_list'] = get_package_goods_list($row['goods_id']);
        }

        //处理货品id
        $row['product_id'] = empty($row['product_id']) ? 0 : $row['product_id'];

        $goods_list[] = $row;
    }

    $attr = array();
    $arr  = array();
    foreach ($goods_attr AS $index => $array_val)
    {
        foreach ($array_val AS $value)
        {
            $arr = explode(':', $value);//以 : 号将属性拆开
            $attr[$index][] =  @array('name' => $arr[0], 'value' => $arr[1]);
        }
    }

    return array('goods_list' => $goods_list, 'attr' => $attr);
}

/**
 * 订单中的商品是否已经全部发货
 * @param   int     $order_id  订单 id
 * @return  int     1，全部发货；0，未全部发货
 */
function get_order_finish($order_id){
    $return_res = 0;

    if (empty($order_id)){
        return $return_res;
    }

    $sql = 'SELECT COUNT(rec_id)
            FROM ' . $GLOBALS['ecs']->table('order_goods') . '
            WHERE order_id = \'' . $order_id . '\'
            AND goods_number > send_number';

    $sum = $GLOBALS['db']->getOne($sql);
    if (empty($sum)){
        $return_res = 1;
    }
    return $return_res;
}
/**
 * 取得发货单信息
 * @param   int     $delivery_order   发货单id（如果delivery_order > 0 就按id查，否则按sn查）
 * @param   string  $delivery_sn      发货单号
 * @return  array   发货单信息（金额都有相应格式化的字段，前缀是formated_）
 */
function delivery_order_info($delivery_id, $delivery_sn = '')
{
    $return_order = array();
    if (empty($delivery_id) || !is_numeric($delivery_id))
    {
        return $return_order;
    }

    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('delivery_order');
    if ($delivery_id > 0)
    {
        $sql .= " WHERE delivery_id = '$delivery_id'";
    }
    else
    {
        $sql .= " WHERE delivery_sn = '$delivery_sn'";
    }

    $sql .= " LIMIT 0, 1";
    $delivery = $GLOBALS['db']->getRow($sql);
    if ($delivery)
    {
        /* 格式化金额字段 */
        $delivery['formated_insure_fee']     = price_format($delivery['insure_fee'], false);
        $delivery['formated_shipping_fee']   = price_format($delivery['shipping_fee'], false);

        /* 格式化时间字段 */
        $delivery['formated_add_time']       = local_date($GLOBALS['_CFG']['time_format'], $delivery['add_time']);
        $delivery['formated_update_time']    = local_date($GLOBALS['_CFG']['time_format'], $delivery['update_time']);

        $return_order = $delivery;
    }

    return $return_order;
}

/**
 * 判断订单的发货单是否全部发货
 * @param   int     $order_id  订单 id
 * @return  int     1，全部发货；0，未全部发货；-1，部分发货；-2，完全没发货；
 */
function get_all_delivery_finish($order_id){
    $return_res = 0;

    if (empty($order_id))
    {
        return $return_res;
    }

    /* 未全部分单 */
    if (!get_order_finish($order_id))
    {
        return $return_res;
    }
    /* 已全部分单 */
    else
    {
        // 是否全部发货
        $sql = "SELECT COUNT(delivery_id)
                FROM " . $GLOBALS['ecs']->table('delivery_order') . "
                WHERE order_id = '$order_id'
                AND status = 2 ";
        $sum = $GLOBALS['db']->getOne($sql);
        // 全部发货
        if (empty($sum))
        {
            $return_res = 1;
        }
        // 未全部发货
        else
        {
            /* 订单全部发货中时：当前发货单总数 */
            $sql = "SELECT COUNT(delivery_id)
            FROM " . $GLOBALS['ecs']->table('delivery_order') . "
            WHERE order_id = '$order_id'
            AND status <> 1 ";
            $_sum = $GLOBALS['db']->getOne($sql);
            if ($_sum == $sum)
            {
                $return_res = -2; // 完全没发货
            }
            else
            {
                $return_res = -1; // 部分发货
            }
        }
    }

    return $return_res;
}

/* 更新库存 */
function update_store(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }
    $data = $_POST;
    $result = json_decode($data['store_str'],true);
    $result or $result=json_decode(stripcslashes($data['store_str']),true);

    if(!$result){
        api_err('0x003', 'Sore data wrong ');
    }

    $result_data['msg'] = '';
    $result_data['error_response'] = array();
    $result_data['true_bn'] = array();
    foreach ($result as $val) {
        $memo = json_decode($val["memo"],true);
        if( checkStore($val['bn'],$memo['last_modified'],$val) ){
            if ($goods_id = $GLOBALS['db']->getOne("SELECT goods_id FROM ".$GLOBALS['ecs']->table('products')." WHERE product_sn = '".$val['bn']."'") ) {
                // 多规格商品
                $sql = "update " . $GLOBALS['ecs']->table('products') . "  set product_number={$val['store']} where product_sn = '".$val['bn']."' ";
                if ( $GLOBALS['db']->query($sql) ){
                    $sql = "update ".$GLOBALS['ecs']->table('goods') ." set goods_number = (SELECT sum(product_number) FROM ".$GLOBALS['ecs']->table('products')." WHERE goods_id = {$goods_id}) WHERE goods_id='{$goods_id}'";
                    if ( $GLOBALS['db']->query($sql) ){
                        // $val['last_modified'] = $memo['last_modified'];
                        $result_data['true_bn'][]=$val['bn'];
                    }
                }else{
                    $result_data['error_response'][]=$val['bn'];
                    $result_data['msg'] .= '['.$val['bn'].'] update error|';
                }
            }else{
                if ($goods_id = $GLOBALS['db']->getOne("SELECT goods_id FROM ".$GLOBALS['ecs']->table('goods')." WHERE goods_sn = '".$val['bn']."'") ) {
                    // 单规格商品
                    $sql_goods = "update ".$GLOBALS['ecs']->table('goods')." set goods_number = {$val['store']} WHERE goods_sn='".$val['bn']."'";
                    if($GLOBALS['db']->query($sql_goods)){
                        // print_r($sql_goods);//exit();
                        // $val['last_modified'] = $memo['last_modified'];
                        $result_data['true_bn'][]=$val['bn'];
                    }else{
                        $result_data['error_response'][]=$val['bn'];
                        $result_data['msg'] .= '['.$val['bn'].'] update error|';
                    }
                }else{
                    $result_data['error_response'][]=$val['bn'];
                    $result_data['msg'] .= '['.$val['bn'].'] update error|';
                }
            }
        }else{
            $result_data['error_response'][]=$val['bn'];
            $result_data['msg'] .= '['.$val['bn'].']kv not update|';
        }
    }

    data_back($result_data, '', RETURN_TYPE);
}

function checkStore($bn,$time,$val){
    $time = time();
    $sql = "select type_id,time FROM  " . $GLOBALS['ecs']->table('coincidence') . " WHERE type_id='".$bn."' and type='UPDATE_STORE' ";
    if($row = $GLOBALS['db']->getRow($sql)){
        if( $row['time'] < $time ){
            $sql = "update " . $GLOBALS['ecs']->table('coincidence') . " set time={$time} WHERE type_id='".$bn."' and type='UPDATE_STORE'  ";
            $res = $GLOBALS['db']->query($sql);
            return true;
        }
    }else{
        $sql = "insert into " . $GLOBALS['ecs']->table('coincidence') . " set type_id='".$bn."',type='UPDATE_STORE',time={$time} ";
        $res = $GLOBALS['db']->query($sql);
        return true;
    }
    return false;
}

/*
 *获得当前店铺有效支付方式
 */
function get_payment_conf(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    $return_data = array();
    $sql = "select pay_id as payment_id, pay_name as payment_name,IF(is_online=1, 'online', 'offline') as payout_type , pay_code as payment_bn  from  ". $GLOBALS['ecs']->table('payment') . "  where enabled = 1";
    $return_data = $GLOBALS['db']->getAll($sql);
    if($return_data){
        foreach ($return_data as $key => $value) {
            $return_data[$key]['payment_name'] = strip_tags($value['payment_name']);
            if($value['payment_bn'] == 'balance'){
                $return_data[$key]['payout_type'] = 'deposit';
            }
        }
        data_back($return_data, '', RETURN_TYPE);
    }
    data_back($return_data, '', RETURN_TYPE,'fail');
}


// 创建支付单
function ome_create_payments(){
    require_once(ROOT_PATH . 'includes/lib_order.php');
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    $data = $_POST;
    if(!$data['order_id']){
        api_err('0x003', 'order_id no exist');
    }
    $sql = "select order_id,order_sn,order_amount,money_paid,shipping_status,user_id from " . $GLOBALS['ecs']->table('order_info') . " where  order_sn = '".$data['order_id']."' and pay_status = ".PS_UNPAYED." ";
    $order = $GLOBALS['db']->getRow($sql);
    if(!$order){
        api_err('0x003', 'order_id no exist or Already Paid');
    }

    if((string)$data['money'] < $order['order_amount'] ){
        api_err('0x003', 'order insufficient fund ');
    }
    /* 余额是否超过了应付款金额，改为应付款金额 */
    if ($data['money'] > $order['order_amount']){
        $data['money'] = $order['order_amount'];
    }

    $sql = "select pay_id,pay_name from " . $GLOBALS['ecs']->table('payment') . " where pay_code = '".$data['payment']."'  ";
    $pay_info = $GLOBALS['db']->getRow($sql);

    if(!$pay_info){
        api_err('0x003', 'Payment No Exist');
    }

    //如果支付方式是余额支付
    if($data['payment'] == 'balance'){
        /* 取得用户信息 */
        $user = user_info($order['user_id']);
        /* 用户帐户余额是否足够 */
        if ($data['money'] > $user['user_money'] + $user['credit_line']){
            api_err('0x003', '余额不足。');
        }
    }


    $arr['pay_status']  = PS_PAYED;
    $arr['pay_time']    = gmtime();
    $arr['money_paid']  = $order['money_paid'] + $data['money'];
    $arr['order_amount']= 0;
    $arr['pay_id'] = $pay_info['pay_id'];
    $arr['pay_name'] = $pay_info['pay_name'];
    if( update_order($order['order_id'], $arr) ){
        $action_note = "管理员支付订单：".$order['order_sn']."，支付金额：".$data['money'];
        order_action($order['order_sn'], OS_CONFIRMED, $order['shipping_status'], PS_PAYED, $action_note,'system');
        if($data['payment'] == 'balance'){
            // 记录帐户变动
            log_account_change($order['user_id'], (-1) * $data['money'], 0, 0, 0, "支付订单 ".$order['order_sn']);
        }else{
            log_account_other_change($order['user_id'], $order['order_id'], $order['order_sn'], $data['money'], $data['payment'], $data['t_end']);
        }
        include_once(ROOT_PATH . 'includes/cls_matrix.php');
        $matrix = new matrix;
        $matrix->updateOrder($order['order_sn']);
        // 请求crm
        update_order_crm($order['order_sn']);
        data_back('succ', '', RETURN_TYPE);
    }
    api_err('0x003', 'toPay fail');

}


// 创建退款单
function ome_create_reimburse(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    $data = $_POST;
    if(!$data['order_id']){
        api_err('0x003', 'order_id no exist');
    }
    // $sql = "select * from " . $GLOBALS['ecs']->table('order_info') . " where  order_sn = '".$data['order_id']."' and order_status != ".OS_RETURNED." and pay_status = ".PS_PAYED." and shipping_status = ".SS_UNSHIPPED." ";
    $sql = "select *,money_paid+surplus as payed_all from " . $GLOBALS['ecs']->table('order_info') . " where  order_sn = '".$data['order_id']."' and money_paid+surplus>0";
    $order = $GLOBALS['db']->getRow($sql);
    if(!$order){
        api_err('0x003', 'order_id no exist or Already Reimburse');
    }

    require_once(ROOT_PATH . 'includes/lib_order.php');
    // 退款金额大于等于已支付金额
    if ($data['cur_money'] >= $order['payed_all']) {
        $action_note = "管理员退款订单：".$order['order_sn']."，全额退款金额：".$order['payed_all'];
        /* 标记订单为“退货”、“未付款”、“未发货” */
        $arr = array('order_status'     => OS_RETURNED,
            'pay_status'       => PS_UNPAYED,
            'shipping_status'  => SS_UNSHIPPED,
            'money_paid'       => 0,
            'invoice_no'       => '',
            'order_amount'     => 0,
            'surplus'          => 0);
        if(order_refund($order, 1, $action_note,$order['payed_all']) == false){
            api_err('0x003', 'reimburse fail');
        }
        update_order($order['order_id'], $arr);
        /* 记录log */
        order_action($order['order_sn'], OS_RETURNED, SS_UNSHIPPED, PS_UNPAYED, $action_note);

        /* 计算并退回红包 */
        if ($order['user_id'] > 0) return_order_bonus($order['order_id']);
        /* 如果使用库存，则增加库存（不论何时减库存都需要） */
        if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE ) change_order_goods_storage($order['order_id'], false, SDT_PLACE);

        /* 退货用户余额 */
        // if ($order['user_id'] > 0 && $order['surplus'] > 0)
        // {
        //     $surplus = $order['money_paid'] < 0 ? $order['surplus'] + $order['money_paid'] : $order['surplus'];
        //     log_account_change($order['user_id'], $surplus, 0, 0, 0, sprintf($GLOBALS['_LANG']['return_order_surplus'], $order['order_sn']));
        //     $GLOBALS['db']->query("UPDATE ". $GLOBALS['ecs']->table('order_info') . " SET `order_amount` = '0' WHERE `order_id` =". $order['order_id']);
        // }
        /* 退货积分 */
        if ($order['user_id'] > 0 && $order['integral'] > 0)
        {
            log_account_change($order['user_id'], 0, 0, 0, $order['integral'], sprintf($GLOBALS['_LANG']['return_order_integral'], $order['order_sn']));
        }

        /* 修改订单 */
        $arr = array(
            'bonus_id'  => 0,
            'bonus'     => 0,
            'integral'  => 0,
            'integral_money'    => 0,
            'surplus'   => 0
        );
        update_order($order['order_id'], $arr);
        $data['cur_money'] = $order['payed_all'];
    }else{
        // 部分退款时
        $action_note = "管理员退款订单：".$order['order_sn']."，部分退款金额：".$data['cur_money'];
        /* 标记订单为“已确认”、“未付款” */
        $arr = array('order_status'     => OS_CONFIRMED,
            // 'pay_status'       => PS_UNPAYED,
            // 'shipping_status'  => SS_UNSHIPPED,
            // 'money_paid'       => 0,
            // 'invoice_no'       => '',
            // 'order_amount'     => 0
        );
        if(order_refund($order, 1, $action_note,$data['cur_money']) == false){
            api_err('0x003', 'reimburse fail');
        }
        if ($order['money_paid']-$data['cur_money']>=0) {
            $arr['money_paid'] = $order['money_paid']-$data['cur_money'];
            // $arr['order_amount'] = $arr['order_amount'] - $data['cur_money'];
        }elseif ($order['money_paid']+$order['surplus']>=$data['cur_money']) {
            $arr['money_paid'] = 0;
            $arr['surplus'] = $order['surplus']+$order['money_paid']-$data['cur_money'];
            // $arr['order_amount'] = $arr['order_amount']-$data['cur_money'];
        }

        update_order($order['order_id'], $arr);
        /* 记录log */
        order_action($order['order_sn'], OS_RETURNED, SS_UNSHIPPED, PS_UNPAYED, $action_note,'system');
    }
    //更新订单到crm
    $is_succ = update_order_crm($order['order_sn']);
    // 退款请求crm
    $data['order_id'] = $order['order_sn'];
    // $data['cur_money'] = $order['total_fee'];
    send_refund_to_crm($data);

    // 退款通知到erp
    send_refund_to_matrix($data);
    data_back('succ', '', RETURN_TYPE);
}


// 退款通知到erp
function send_refund_to_matrix($data){
    $msg['refund_id'] = $data['refund_id'];
    $msg['tid'] = $data['order_id'];
    $msg['refund_fee'] = $data['cur_money'];
    $msg['pay_type'] = $data['pay_type']?$data['pay_type']:'deposit';
    $msg['status'] = 'SUCC';
    $msg['t_begin'] = date('Y-m-d H:i:s',time());
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    $matrix = new matrix;
    $is_succ = $matrix->send_refund_to_matrix($msg);
    $is_succ = $matrix->updateOrder($data['order_id']);
}

// 退款通知到crm
function send_refund_to_crm($data){
    $msg['refund_id'] = $data['refund_id'];
    $msg['tid'] = $data['order_id'];
    $msg['refund_fee'] = $data['cur_money'];
    $msg['pay_type'] = $data['pay_type']?$data['pay_type']:'deposit';
    $msg['status'] = 'SUCC';
    $msg['t_begin'] = date('Y-m-d H:i:s',time());
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    $matrix = new matrix;
    $bind_info = $matrix->get_bind_info(array('ecos.taocrm'));
    if($bind_info) {
        $matrix->send_refund_to_crm($msg);
    }
}


function ome_create_delivery(){
    check_auth();   //检查基本权限
    $version = '1.0';   //版本号
    if ($_POST['api_version'] != $version)      //网店的接口版本低
    {
        api_err('0x008', 'a low version api');
    }

    $data = $_POST;
    if(!$data['order_id']){
        api_err('0x003', 'order_id no exist');
    }
    $sql = "select * from " . $GLOBALS['ecs']->table('order_info') . " where  order_sn = '".$data['order_id']."' ";
    $order = $GLOBALS['db']->getRow($sql);
    if(!$order){
        api_err('0x003', 'order_id no exist');
    }

    if( $data['type'] == 'delivery' && $order['shipping_status'] == SS_SHIPPED ){
        api_err('0x003', '订单已经发货，不要重复发货');
    }elseif($data['type'] == 'return' && $order['order_status'] == OS_RETURNED){
        api_err('0x003', '订单已经退款，不要重复退款');
    }

    $match_logi = false;
    // 匹配快递名称
    if( $data['logi_name'] ){
        $sql = "select shipping_id from ".$GLOBALS['ecs']->table('shipping')." where shipping_name = '".$data['logi_name']."' ";
        if($shipping_id = $GLOBALS['db']->getOne($sql)){
            $match_logi = true;
            $shipping['shipping_id'] = $shipping_id;
            $shipping['shipping_name'] = $data['logi_name'];
        }
    }
    // 未匹配上快递名称 进行快递CODE匹配
    if( $data['logi_id'] and !$match_logi ){
        $sql = "select shipping_id from ".$GLOBALS['ecs']->table('shipping')." where shipping_code = '".strtolower($data['logi_id'])."' ";
        if($shipping_id = $GLOBALS['db']->getOne($sql)){
            $match_logi = true;
            $shipping['shipping_id'] = $shipping_id;
            $shipping['shipping_name'] = $data['logi_name'];
        }
    }

    // 都未匹配上，就用订单里的快递
    if($match_logi == false){
        $shipping['shipping_id'] = $order['shipping_id'];
        $shipping['shipping_name'] = $order['shipping_name'];
    }


    if( $data['type'] == 'delivery' ){// todo 处理发货

        $delivery = array();
        $delivery['invoice_no'] = $data['logi_no'];
        $delivery['shipping_id'] = $shipping['shipping_id'];
        $delivery['shipping_name'] = $shipping['shipping_name'];

        // 判断发货单是否存在
        $sql = "select delivery_id from ".$GLOBALS['ecs']->table('delivery_order')." where delivery_sn = ".$data['delivery_id'];
        if($GLOBALS['db']->getOne($sql)){
            api_err('0x003', '发货单已经存在不能重复添加');
        }

        $delivery['delivery_sn'] = $data['delivery_id'];
        $delivery['order_sn']    = $order['order_sn'];
        $delivery['order_id']    = $order['order_id'];
        $delivery['add_time']    = $order['add_time'];
        $delivery['user_id']     = $order['user_id'];
        $delivery['action_user'] = 'system';
        $delivery['consignee']   = $data['ship_name'];
        $delivery['address']     = $data['ship_addr'];
        $delivery['country']     = $order['country'];
        $delivery['province']    = $order['province'];
        $delivery['city']        = $order['city'];
        $delivery['district']    = $order['district'];
        $delivery['email']       = $order['ship_email'];
        $delivery['zipcode']     = $order['ship_zip'];
        $delivery['tel']         = $order['ship_tel'];
        $delivery['mobile']      = $order['ship_mobile'];
        $delivery['how_oos']     = $order['how_oos'];
        $delivery['insure_fee']  = $order['insure_fee'];
        $delivery['shipping_fee']= ($data['money']?$data['money']:'0');
        $delivery['status']      = 0; // 已发货
        $delivery['best_time']   = $order['best_time'];
        $delivery['update_time'] = GMTIME_UTC;
        $delivery['agency_id']   = $order['agency_id'];


        $delivery_item = $delivery_bns = array();
        $unship_num = 0;
        /* 发货单入库 */
        $query = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('delivery_order'), $delivery, 'INSERT', '', 'SILENT');
        $delivery_id = $GLOBALS['db']->insert_id();
        if ($delivery_id)
        {
            $sql = "select * from ".$GLOBALS['ecs']->table('order_goods')." where order_id = ".$order['order_id'];
            $order_goods = $GLOBALS['db']->getAll($sql);
            if($order_goods){

                if( $data['delivery_item'] ){
                    $delivery_item=json_decode($data['delivery_item'],true);
                    $delivery_item or $delivery_item=json_decode(stripcslashes($data['delivery_item']),true);
                }
                foreach($delivery_item as $v) $delivery_bns[$v['product_bn']] = $v['number'];
                $sql = "select g.*,(g.goods_number - g.send_number) as need_send_number,ifnull(p.`product_sn`,g.`goods_sn`) as product_sn from ".$GLOBALS['ecs']->table('order_goods')." as g left join ".$GLOBALS['ecs']->table('products')." as p on p.goods_id = g.goods_id  where g.order_id = ".$order['order_id'];
                $order_goods = $GLOBALS['db']->getAll($sql);

                foreach($order_goods as $value){
                    if($delivery_bns[$value['product_sn']] and $value['need_send_number'] > 0 ){
                        if( $delivery_bns[$value['product_sn']] > $value['need_send_number'] ){
                            $send_number = $value['need_send_number'];
                        }else{
                            $send_number = $delivery_bns[$value['product_sn']];
                            $unship_num += $send_number - $value['need_send_number'];
                        }
                    }else{
                        $unship_num += $delivery_bns[$value['product_sn']];
                        continue;
                    }
                    // 商品（实货）（虚货）
                    if (empty($value['extension_code']) || $value['extension_code'] == 'virtual_card')
                    {
                        $delivery_goods = array('delivery_id' => $delivery_id,
                            'goods_id' => $value['goods_id'],
                            'product_id' => $value['product_id'],
                            'product_sn' => $value['product_sn'],
                            'goods_id' => $value['goods_id'],
                            'goods_name' => addslashes($value['goods_name']),
                            'brand_name' => addslashes($value['brand_name']),
                            'goods_sn' => $value['goods_sn'],
                            'send_number' => $send_number,
                            'parent_id' => 0,
                            'is_real' => $value['is_real'],
                            'goods_attr' => addslashes($value['goods_attr'])
                        );

                        /* 如果是货品 */
                        if (!empty($value['product_id']))
                        {
                            $delivery_goods['product_id'] = $value['product_id'];
                        }

                        $query = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('delivery_goods'), $delivery_goods, 'INSERT', '', 'SILENT');
                    }
                    // 商品（超值礼包）
                    elseif ($value['extension_code'] == 'package_buy')
                    {
                        foreach ($value['package_goods_list'] as $pg_key => $pg_value)
                        {
                            $delivery_pg_goods = array('delivery_id' => $delivery_id,
                                'goods_id' => $pg_value['goods_id'],
                                'product_id' => $pg_value['product_id'],
                                'product_sn' => $pg_value['product_sn'],
                                'goods_name' => $pg_value['goods_name'],
                                'brand_name' => '',
                                'goods_sn' => $pg_value['goods_sn'],
                                'send_number' => $send_number,
                                'parent_id' => $value['goods_id'], // 礼包ID
                                'extension_code' => $value['extension_code'], // 礼包
                                'is_real' => $pg_value['is_real']
                            );
                            $query = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('delivery_goods'), $delivery_pg_goods, 'INSERT', '', 'SILENT');
                        }
                    }
                    //更新订单明细中的发货值
                    $sql = "update ".$GLOBALS['ecs']->table('order_goods')." set send_number = send_number + ".$send_number."  where order_id = ".$order['order_id']." and rec_id = ".$value['rec_id'];
                    $GLOBALS['db']->query($sql);
                }
            }

            require_once(ROOT_PATH . 'includes/lib_order.php');
            $arr = array();
            $arr['order_status']        = OS_SPLITED;
            $arr['shipping_status']     = $unship_num ? OS_SHIPPED_PART : SS_SHIPPED;
            $arr['shipping_time']       = GMTIME_UTC; // 发货时间
            $arr['invoice_no']          = trim($order['invoice_no'] . '<br>' . $data['logi_no'], '<br>');
            update_order($order['order_id'], $arr);

            /* 发货单发货记录log */
            $action_note = "系统管理员进行发货";
            order_action($order['order_sn'], OS_CONFIRMED, SS_SHIPPED, $order['pay_status'], $action_note, null, 1);

            // 全部发货
            if($unship_num == 0 ){
                /* 如果订单用户不为空，计算积分，并发给用户；发红包 */
                if ($order['user_id'] > 0)
                {
                    /* 计算并发放积分 */
                    $integral = integral_to_give($order);

                    log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($_LANG['order_gift_integral'], $order['order_sn']));

                    /* 发放红包 */
                    send_order_bonus($order['order_id']);
                }

                /* 如果使用库存，且发货时减库存*/
                if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_SHIP)
                {
                    change_order_goods_storage($order['order_id'], false, SDT_SHIP);
                }

                /* 发送邮件 */
                $cfg = $_CFG['send_ship_email'];
                if ($cfg == '1')
                {
                    $order['invoice_no'] = $delivery['invoice_no'];
                    $tpl = get_mail_template('deliver_notice');
                    $GLOBALS['smarty']->assign('order', $order);
                    $GLOBALS['smarty']->assign('send_time', local_date($_CFG['time_format']));
                    $GLOBALS['smarty']->assign('shop_name', $_CFG['shop_name']);
                    $GLOBALS['smarty']->assign('send_date', local_date($_CFG['date_format']));
                    $GLOBALS['smarty']->assign('sent_date', local_date($_CFG['date_format']));
                    $GLOBALS['smarty']->assign('confirm_url', $ecs->url() . 'receive.php?id=' . $order['order_id'] . '&con=' . rawurlencode($order['consignee']));
                    $GLOBALS['smarty']->assign('send_msg_url',$ecs->url() . 'user.php?act=message_list&order_id=' . $order['order_id']);
                    $content = $GLOBALS['smarty']->fetch('str:' . $tpl['template_content']);
                    if (!send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']))
                    {
                        $msg = $_LANG['send_mail_fail'];
                    }
                }

                /* 如果需要，发短信 */
                if ($GLOBALS['_CFG']['sms_order_shipped'] == '1' && $order['mobile'] != '')
                {
                    require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/order.php');
                    include_once(ROOT_PATH . 'includes/cls_sms.php');
                    $sms = new sms();
                    $sms->send($order['mobile'], sprintf($GLOBALS['_LANG']['order_shipped_sms'], $order['order_sn'],
                        local_date($GLOBALS['_LANG']['sms_time_format']), $GLOBALS['_CFG']['shop_name']), 0);
                }
            }
            // 请求crm
            update_order_crm($order['order_sn']);
            data_back('succ', '', RETURN_TYPE);

        }else{
            api_err('0x003', '发货失败');
        }

    }else{ // todo 处理退货
        $back_order = array();
        $back_order['invoice_no'] = $data['logi_no'];
        $back_order['shipping_id'] = $shipping['shipping_id'];
        $back_order['shipping_name'] = $shipping['shipping_name'];

        // 判断退货单是否存在
        $sql = "select back_id from ".$GLOBALS['ecs']->table('back_order')." where delivery_sn = ".$data['delivery_id'];
        if($GLOBALS['db']->getOne($sql)){
            api_err('0x003', '退货单已经存在不能重复添加');
        }
        require_once(ROOT_PATH . 'includes/lib_order.php');
        $order_id = $order['order_id'];

        /* 添加退货记录 */
        $back_order['delivery_sn'] = $data['delivery_id'];
        $back_order['order_sn']    = $order['order_sn'];
        $back_order['order_id']    = $order['order_id'];
        $back_order['add_time']    = $order['add_time'];
        $back_order['user_id']     = $order['user_id'];
        $back_order['action_user'] = 'system';
        $back_order['consignee']   = $data['ship_name'];
        $back_order['address']     = $data['ship_addr'];
        $back_order['country']     = $order['country'];
        $back_order['province']    = $order['province'];
        $back_order['city']        = $order['city'];
        $back_order['district']    = $order['district'];
        $back_order['email']       = $order['ship_email'];
        $back_order['zipcode']     = $order['ship_zip'];
        $back_order['tel']         = $order['ship_tel'];
        $back_order['mobile']      = $order['ship_mobile'];
        $back_order['how_oos']     = $order['how_oos'];
        $back_order['insure_fee']  = $order['insure_fee'];
        $back_order['shipping_fee']= ($data['money']?$data['money']:'0');
        $back_order['return_time']   = GMTIME_UTC;
        // $back_order['update_time'] = GMTIME_UTC;
        $back_order['update_time'] = $order['shipping_time'];
        $back_order['agency_id']   = $order['agency_id'];

        /* 退货单入库 */
        $query = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('back_order'), $back_order, 'INSERT', '', 'SILENT');
        $back_id = $GLOBALS['db']->insert_id();

        if(!$back_id){
            api_err('0x003', '退货单创建失败');
        }

        if( $data['struct'] ){
            $delivery_item=json_decode($data['struct'],true);
            $delivery_item or $delivery_item=json_decode(stripcslashes($data['struct']),true);
        }
        foreach($delivery_item as $v) $delivery_bns[$v['product_bn']] = $v['number'];

        $sql = "select g.* , ifnull(p.`product_sn`,g.`goods_sn`) as product_sn from ".$GLOBALS['ecs']->table('order_goods')." as g left join ".$GLOBALS['ecs']->table('products')." as p on p.goods_id = g.goods_id  where g.order_id = ".$order['order_id'] ." and g.send_number > 0 ";
        $order_goods = $GLOBALS['db']->getAll($sql);

        if ($order_goods)
        {
            foreach ($order_goods as $value)
            {
                /* 货品存在并且小于等于发货数 */
                if ( $delivery_bns[$value['product_sn']] and $delivery_bns[$value['product_sn']] <= $value['send_number'] ) {
                    // 商品（实货）（虚货）
                    if (empty($value['extension_code']) || $value['extension_code'] == 'virtual_card')
                    {
                        $back_goods   =   array('back_id' => $back_id,
                            'goods_id' => $value['goods_id'],
                            'product_id' => $value['product_id'],
                            'product_sn' => $value['product_sn'],
                            'goods_id' => $value['goods_id'],
                            'goods_name' => addslashes($value['goods_name']),
                            'brand_name' => addslashes($value['brand_name']),
                            'goods_sn' => $value['goods_sn'],
                            'send_number' => $delivery_bns[$value['product_sn']],
                            'parent_id' => 0,
                            'is_real' => $value['is_real'],
                            'goods_attr' => addslashes($value['goods_attr'])
                        );

                        $query = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('back_goods'), $back_goods, 'INSERT', '', 'SILENT');

                        /* 将订单的商品发货数量更新 */
                        $sql = "UPDATE " . $GLOBALS['ecs']->table('order_goods') . "
                                SET send_number = send_number - " . $back_goods['send_number'] . "
                                WHERE order_id = '$order_id' and goods_id = ".$value['goods_id']." and product_id = ".$value['product_id'];
                        $GLOBALS['db']->query($sql, 'SILENT');

                        /* 如果使用库存，则增加库存 */
                        if ($_CFG['use_storage'] == '1' )
                        {
                            change_goods_storage($back_goods['goods_id'], $back_goods['product_id'], $back_goods['send_number']);
                        }

                    }
                }
            }
        }

        $action_note = "系统管理员退款订单：".$order['order_sn'];
        /* todo 处理退款 */
        if ($order['pay_status'] != PS_UNPAYED)
        {
            if(order_refund($order, 1, $action_note,$data['money']) == false){
                api_err('0x003', '退款失败');
            }
        }

        $sql = "select sum(send_number) from ".$GLOBALS['ecs']->table('order_goods')." where order_id = ".$order['order_id'];
        $send_number = $GLOBALS['db']->getOne($sql);

        /* todo 部分退货 */
        if ( $send_number > 0 ){
            $part_arr = array();
            if($order['money_paid'] > 0 ){
                $part_arr['money_paid'] = $order['money_paid'] - $data['money'];
            }
            if($order['surplus'] > 0 ){
                $part_arr['surplus'] = $order['surplus'] - $data['money'];
            }
            $action_note = "系统管理员部分退款订单：".$order['order_sn'];
            update_order($order['order_id'], $part_arr);
            order_action($order['order_sn'], OS_RETURNED, SS_SHIPPED, $order['pay_status'], $action_note);
            /* 清除缓存 */
            clear_cache_files();
            data_back('succ', '', RETURN_TYPE);
        }

        /* 修改订单的发货单状态为退货 */
        $sql_delivery = "UPDATE " . $GLOBALS['ecs']->table('delivery_order') . "
                         SET status = 0
                         WHERE status IN (1, 2)
                         AND order_id = " . $order['order_id'];
        $GLOBALS['db']->query($sql_delivery, 'SILENT');

        /* 修改订单 */
        $arr = array(
            'order_status'     => OS_RETURNED,
            'pay_status'       => PS_UNPAYED,
            'shipping_status'  => SS_UNSHIPPED,
            'money_paid'       => 0,
            'invoice_no'       => '',
            'order_amount'     => $order['money_paid'],
            'bonus_id'  => 0,
            'bonus'     => 0,
            'integral'  => 0,
            'integral_money'    => 0,
            'surplus'   => 0
        );
        update_order($order['order_id'], $arr);

        /* 记录log */
        order_action($order['order_sn'], OS_RETURNED, SS_UNSHIPPED, PS_UNPAYED, $action_note);


        /* 如果使用库存，则增加库存（不论何时减库存都需要） */
        if ($_CFG['use_storage'] == '1')
        {
            if ($_CFG['stock_dec_time'] == SDT_SHIP)
            {
                change_order_goods_storage($order['order_id'], false, SDT_SHIP);
            }
            elseif ($_CFG['stock_dec_time'] == SDT_PLACE)
            {
                change_order_goods_storage($order['order_id'], false, SDT_PLACE);
            }
        }



        /* 如果订单用户不为空，计算积分，并退回 */
        if ($order['user_id'] > 0)
        {
            /* 取得用户信息 */
            $user = user_info($order['user_id']);

            $sql = "SELECT  goods_number, send_number FROM". $GLOBALS['ecs']->table('order_goods') . "
                WHERE order_id = '".$order['order_id']."'";

            $goods_num = $GLOBALS['db']->query($sql);
            $goods_num = $GLOBALS['db']->fetchRow($goods_num);

            if($goods_num['goods_number'] == $goods_num['send_number'])
            {
                /* 计算并退回积分 */
                $integral = integral_to_give($order);
                log_account_change($order['user_id'], 0, 0, (-1) * intval($integral['rank_points']), (-1) * intval($integral['custom_points']), sprintf($_LANG['return_order_gift_integral'], $order['order_sn']));
            }
            /* todo 计算并退回红包 */
            return_order_bonus($order_id);
        }

        /* 退货用户余额 */
        if ($order['user_id'] > 0 && $order['surplus'] > 0)
        {
            $surplus = $order['money_paid'] < 0 ? $order['surplus'] + $order['money_paid'] : $order['surplus'];
            log_account_change($order['user_id'], $surplus, 0, 0, 0, sprintf($GLOBALS['_LANG']['return_order_surplus'], $order['order_sn']));
            $GLOBALS['db']->query("UPDATE ". $GLOBALS['ecs']->table('order_info') . " SET `order_amount` = '0' WHERE `order_id` =". $order['order_id']);
        }

        /* 退货积分 */
        if ($order['user_id'] > 0 && $order['integral'] > 0)
        {
            log_account_change($order['user_id'], 0, 0, 0, $order['integral'], sprintf($GLOBALS['_LANG']['return_order_integral'], $order['order_sn']));
        }

        /* 清除缓存 */
        clear_cache_files();
        // 订单更新crm
        error_log("order_update",3,__FILE__.".log");
        update_order_crm($order['order_sn']);
        data_back('succ', '', RETURN_TYPE);
    }
}

//货店通接口 START
/**
 * 头文件
 */
function _header($content = 'text/html', $charset = 'utf-8')
{
    header('Content-type: ' . $content . ';charset=' . $charset);
    header("Cache-Control: no-cache,no-store , must-revalidate");
    $expires = gmdate("D, d M Y H:i:s", time() + 20);
    header("Expires: " . $expires . " GMT");
}

/**
 * api 返回数据
 */
function api_response($resCode, $errorCode = false, $data = null, $type = 1)
{
    $resposilbe = array(
        'true' => 'success',
        'fail' => 'fail',
        'wait' => 'wait'
    );

    $result['result'] = $resposilbe[$resCode];
    $result['msg'] = $errorCode ? $errorCode : '';
    $result['shopex_time'] = time();
    $result['info'] = $data;

    if ($type == 1) {
        //XML
        _header('text/xml');
        $result = array2xml($result, 'shopex');
    } else {
        //JSON
        _header('text/html');
        $result = json_encode($result);
    }
    echo $result;
    exit;
}

/**
 * 登录店铺
 */
function shopex_shop_login()
{
    check_auth(); //检查基本权限

    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];
    $version = '1.0'; //版本号

    $cert = new certificate();
    if ($db->getRow("SELECT user_id FROM " . $ecs->table('admin_user') . " WHERE passport_uid = '" . trim($_POST['passport_uid']) . "'")) {
        $license = $cert->get_shop_certificate();
        if ($license['passport_uid'] && $license['passport_uid'] == trim($_POST['passport_uid'])) {
            $re_arr = array();

            //获取站点信息
            $_CFG = load_config();
            $re_arr['site_name'] = $_CFG['shop_name']; //站点名称
            $re_arr['site_desc'] = $_CFG['shop_desc']; //站点简介
            $re_arr['site_address'] = $ecs->url();
            $re_arr['site_phone'] = ''; //站点电话
            $re_arr['site_zip_code'] = ''; //站点邮编
            $re_arr['score_set'] = 0; //积分设置
            $re_arr['shop_version'] = $version; //版本号
            $re_arr['site_type'] = 7; //店铺类型

            $re_arr['session'] = SESS_ID;
            $re_arr['goods_url'] = $ecs->url() . 'goods.php?id=0'; //站点网址

            api_response('true', '', $re_arr, RETURN_TYPE);
        }
    }
    api_response('fail', 'Verify fail', '', RETURN_TYPE);
}

/**
 * 获取商品分类列表
 */
function shopex_goods_cat_list()
{
    check_auth(); //检查基本权限

    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    $page_no = empty($_POST['page_no']) ? 1 : intval($_POST['page_no']);
    $page_size = empty($_POST['page_size']) ? 20 : intval($_POST['page_size']);

    /* 获取分类列表 */
    // $cat_list = cat_list(0, 0, false);
    $sql = "SELECT cat_id,cat_name,cat_desc,parent_id,sort_order FROM " . $ecs->table('category');
    if ($res = $db->getAll($sql)) {
        $cat_list = $re_arr = array();
        foreach ($res as $cat) $cat_list[$cat['cat_id']] = $cat;

        foreach ($cat_list as $cid => $cat) {
            // $path = '';
            $re_arr[$cid]['cat_name'] = $cat['cat_name']; //分类名称
            $re_arr[$cid]['order_by'] = $cat['sort_order']; //分类排序
            $re_arr[$cid]['desc'] = $cat['cat_desc']; //分类描述
            $re_arr[$cid]['disabled'] = 'false';
            $re_arr[$cid]['type_name'] = '';
            $re_arr[$cid]['last_modify'] = time(); //最后修改时间
            $re_arr[$cid]['cat_path'] = empty($cat['parent_id']) ? '' : $cat_list[$cat['parent_id']]['cat_name'];
            // get_cat_path($cat, $cat_list, $path);
            // krsort($path);
            // $re_arr[$cid]['cat_path'] = implode('->', $path);
        }
        $re_arr = array_slice($re_arr, ($page_size * ($page_no - 1)), $page_size);
        $re_arr['item_total'] = count($cat_list);

        api_response('true', '', $re_arr, RETURN_TYPE);
    }
    api_response('true', 'No Data', '', RETURN_TYPE);
}

function get_cat_path($cat, $cat_list, &$path)
{
    $path[] = $cat['cat_name'];
    if ($cat['parent_id'] != 0) {
        $pid = $cat['parent_id'];
        get_cat_path($cat_list[$pid], $cat_list, $path);
    }
}

/**
 * 获取品牌列表
 */
function shopex_brand_list()
{
    check_auth(); //检查基本权限

    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    $page_no = empty($_POST['page_no']) ? 1 : intval($_POST['page_no']);
    $page_size = empty($_POST['page_size']) ? 20 : intval($_POST['page_size']);

    $sql = "SELECT brand_name,site_url,brand_desc,sort_order FROM " . $ecs->table('brand');
    if ($res = $db->getAll($sql)) {
        $re_arr = array();
        foreach ($res as $k => $v) {
            $re_arr[$k]['brand_name'] = $v['brand_name']; //品牌名称
            $re_arr[$k]['brand_url'] = $v['site_url']; //品牌URL
            $re_arr[$k]['brand_desc'] = $v['brand_desc']; //品牌描述
            $re_arr[$k]['brand_logo'] = ''; //品牌logo
            $re_arr[$k]['brand_alias'] = ''; //别名
            $re_arr[$k]['disabled'] = 'false'; //是否屏蔽
            $re_arr[$k]['order_by'] = $v['sort_order']; //排序
            // $re_arr[$k]['brand_setting'] = ''; //品牌参数
            $re_arr[$k]['last_modify'] = 0; //最后修改时间
        }
        $re_arr['item_total'] = count($res);
        $re_arr = array_slice($re_arr, ($page_size * ($page_no - 1)), $page_size);

        api_response('true', '', $re_arr, RETURN_TYPE);
    }
    api_response('true', 'No Data', '', RETURN_TYPE);
}

/**
 * 获取商品类型
 */
function shopex_type_list()
{
    check_auth(); //检查基本权限

    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    $page_no = empty($_POST['page_no']) ? 1 : intval($_POST['page_no']);
    $page_size = empty($_POST['page_size']) ? 20 : intval($_POST['page_size']);

    $sql = "SELECT gt.cat_id AS type_id,gt.cat_name AS type_name,a.attr_id,a.attr_name,a.attr_values,a.sort_order " .
        "FROM " . $ecs->table('attribute') . " AS a LEFT JOIN " . $ecs->table('goods_type') . " AS gt ON a.cat_id = gt.cat_id " .
        "WHERE gt.cat_id IS NOT NULL";
    if ($res = $db->getAll($sql)) {
        $re_arr = $type_arr = $props_arr = array();
        foreach ($res as $v) {
            //属性
            $props_arr[$v['type_id']][$v['attr_id']]['prop_name'] = addslashes($v['attr_name']); //属性名称
            $props_arr[$v['type_id']][$v['attr_id']]['alias'] = $v['attr_name']; //属性名称别名
            $props_arr[$v['type_id']][$v['attr_id']]['memo'] = ''; //备注
            $props_arr[$v['type_id']][$v['attr_id']]['show_type'] = 2; //商品详细页的显示类型 2：选择项-渐进式筛选
            $props_arr[$v['type_id']][$v['attr_id']]['order_by'] = $v['sort_order']; //排序
            $props_arr[$v['type_id']][$v['attr_id']]['is_show'] = 'true'; //是否显示
            $props_arr[$v['type_id']][$v['attr_id']]['disabled'] = 'false'; //是否屏蔽
            $props_arr[$v['type_id']][$v['attr_id']]['prop_type'] = 1; //属性类型 1：扩展属性
            $props_arr[$v['type_id']][$v['attr_id']]['prop_value'] = str_replace("\n", ",", $v['attr_values']); //属性值列表 扩展属性：选择项可选值列表

            //类型
            $type_arr[$v['type_id']]['name'] = $v['type_name']; //类型名称
            $type_arr[$v['type_id']]['alias'] = $v['type_name']; //类型名称别名
            $type_arr[$v['type_id']]['is_default'] = 'false'; //是否是系统默认
            $type_arr[$v['type_id']]['is_physical'] = 'true'; //是否为实体商品
            $type_arr[$v['type_id']]['is_has_brand'] = 'false'; //是否为实体商品
            $type_arr[$v['type_id']]['is_has_prop'] = 'true'; //是否有扩展属性
            $type_arr[$v['type_id']]['is_has_params'] = 'true'; //是否有商品详细参数
            $type_arr[$v['type_id']]['is_must_minfo'] = 'false'; //是否有购物必填项
            $type_arr[$v['type_id']]['disabled'] = 'false'; //是否屏蔽
            $type_arr[$v['type_id']]['spec_names'] = 'false'; //类型相关联的规格项
            $type_arr[$v['type_id']]['spec_alias'] = 'false'; //类型相关联的规格项别名
            $type_arr[$v['type_id']]['props'] = $props_arr[$v['type_id']]; //类型扩展属性列表
            $type_arr[$v['type_id']]['params'] = 'false'; //类型详细参数列表
            $type_arr[$v['type_id']]['must_minfo'] = 'false'; //类型购物必填项列表
            $type_arr[$v['type_id']]['last_modify'] = 0; //最后修改时间
        }
        $re_arr = array_slice($type_arr, ($page_size * ($page_no - 1)), $page_size);
        $re_arr['item_total'] = count($type_arr);

        api_response('true', '', $re_arr, RETURN_TYPE);
    }
    api_response('true', 'No Data', '', RETURN_TYPE);
}


/**
 * 添加商品
 */
function shopex_goods_add()
{
    check_auth(); //检查基本权限

    $goods_data = json_decode($_POST['goodsinfo'], true);
    $goods_data or $goods_data = json_decode(stripcslashes($_POST['goodsinfo']), true);
    if (empty($goods_data)) api_response('fail', 'Data Error', '', RETURN_TYPE); //商品数据异常

    //数组key转换为小写
    lowerKey($goods_data);
    //检查商品数据
    checkGoodsArray($goods_data);
    //获取商品分类id
    getCatagory($goods_data, $cat_id);
    //获取商品类型id
    goodsType($goods_data, $type_id);
    //新增和更新商品
    goods($goods_data, $cat_id, $type_id, $goods);
    //新增和更新商品属性
    attribute($goods_data, $goods, $type_id);
    //新增和更新商品图片
    gallery($goods_data, $goods);

    clear_tpl_files();

    $re_arr = array();
    $re_arr['goods_id'] = $goods['goods_id'];
    $re_arr['goods_url'] = $GLOBALS['ecs']->url() . "/goods.php?id={$goods['goods_id']}";

    api_response('true', '', $re_arr, RETURN_TYPE);
}

/**
 * 将数组标签转换成小写
 */
function lowerKey(&$goods_data)
{
    if (is_array($goods_data)) {
        $goods_data = array_change_key_case($goods_data, CASE_LOWER);
        foreach ($goods_data as $key => $value) {
            if (is_array($value)) {
                lowerKey($goods_data[$key]);
            }
        }
    }
}

/**
 * 检查商品数据
 */
function checkGoodsArray(&$goods_data)
{
    $verify_arr = array(
        'title' => 'goods title can not be empty',
        'shopexcatagory' => 'catagory can not be empty',
        'shopexcategorypath' => 'catagory path can not be empty',
        'typename' => 'goods type can not be empty',
        'images' => 'image can not be empty',
        'defaultimage' => 'default image can not be empty'
    );

    foreach ($verify_arr as $key => $msg) {
        switch ($key) {
            case 'shopexcatagory':
            case 'shopexcategorypath':
            case 'typename':
                if (isset($goods_data['shopexobj'][$key]) == false || empty($goods_data['shopexobj'][$key])) {
                    api_response('fail', $msg, '', RETURN_TYPE);
                }
                break;
            default:
                if (isset($goods_data[$key]) == false || empty($goods_data[$key])) {
                    api_response('fail', $msg, '', RETURN_TYPE);
                }
                break;
        }
    }
}

/**
 * 获取商品分类id
 */
function getCatagory($goods_data, &$cat_id)
{
    $shopexcatagory = $goods_data['shopexobj']['shopexcatagory']; //分类名称
    $shopexcategorypath = $goods_data['shopexobj']['shopexcategorypath']; //分类路径

    $cat_list = cat_list(0, 0, false);
    $tmp_arr = array();
    foreach ($cat_list as $id => $cat) {
        if ($cat['cat_name'] == $shopexcatagory) {
            $tmp_arr[$id] = $cat;
        }
    }
    if (empty($tmp_arr)) api_response('fail', 'catagory is not found', '', RETURN_TYPE);

    if (count($tmp_arr) == 1) $cat_id = key($tmp_arr);

    foreach ($tmp_arr as $id => $cat) {
        get_cat_path($cat, $cat_list, $path);
        krsort($path);
        if (implode('->', $path) == $shopexcategorypath) {
            $cat_id = $id;
        }
    }
    if (isset($cat_id) == false) api_response('fail', 'catagory is not found', '', RETURN_TYPE);
}

/**
 * 获取商品类型id
 */
function goodsType($goods_data, &$type_id)
{
    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    //类型名称
    $type_name = $goods_data['shopexobj']['typename'];
    preg_match('/\[.*\](.*)\(.*\)/', $type_name, $matches);
    $type_name = empty($matches) ? $type_name : $matches[1];

    $sql = "SELECT cat_id FROM " . $ecs->table('goods_type') . " WHERE cat_name = '{$type_name}'";
    if (($type_id = $db->getOne($sql)) == false) {
        $sql = "INSERT INTO " . $ecs->table('goods_type') . " (`cat_name`,`enabled`) VALUE ('{$type_name}',1)";
        if ($db->query($sql) === false) {
            api_response('fail', 'add goods type failed', '', RETURN_TYPE);
        }
        $type_id = $GLOBALS['db']->insert_id();
    }
}

/**
 * 商品数据
 */
function goods($goods_data, $cat_id, $type_id, &$goods)
{
    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    //上下架
    $is_on_sale = $goods_data['shopexobj']['item_status'] == '仓库中' ? 0 : 1;

    //是否包邮
    //$free_postage = $goods_data['shopexobj']['ispostage'] == '是' ? 'true' : 'false';

    //库存
    $quantity = isset($goods_data['quantity']) ? intval($goods_data['quantity']) : 0;

    $goods_arr = array(
        'cat_id' => $cat_id, //商品分类
        'goods_sn' => trim($goods_data['bn']), //商品编码
        'goods_name' => trim($goods_data['title']), //商品名称
        'goods_number' => $quantity, //库存
        'market_price' => $goods_data['price'], //市场价
        'shop_price' => $goods_data['price'], //市场价
        'goods_desc' => addslashes($goods_data['desc']), //商品详情
        'is_on_sale' => $is_on_sale, //上下架
        'add_time' => time(), //创建时间
        'last_update' => time(), //最后更新时间
        'goods_type' => $type_id, //商品类型
    );

    //品牌
    $brand = $goods_data['shopexobj']['goodsbrand'];
    if ($brand) {
        $sql = "SELECT brand_id FROM " . $ecs->table('brand') . " WHERE brand_name = '{$brand}'";
        if ($brand_id = $db->getOne($sql)) {
            $goods_arr['brand_id'] = $brand_id;
        }
        if (empty($brand_id)) unset($goods_arr['brand_id']);
    }

    $sql = "SELECT * FROM " . $ecs->table('goods') . " WHERE goods_sn = '" . $goods_arr['goods_sn'] . "'";
    if ($res = $db->getOne($sql)) {
        unset($goods_arr['add_time']);
        $sets = '';
        foreach ($goods_arr as $k => $v) $sets .= "`{$k}`" . " = '" . $v . "',";
        $sets = trim($sets, ',');
        $sql = "UPDATE " . $ecs->table('goods') . " SET " . $sets . " WHERE goods_sn = '{$goods_arr['goods_sn']}'";
    } else {
        $sql = "INSERT INTO " . $ecs->table('goods') . " (`" . implode('`,`', array_keys($goods_arr)) . "`) " . "VALUES ('" . implode("','", array_values($goods_arr)) . "')";
    }

    if ($db->query($sql) === false) {
        api_response('fail', 'update goods failed', '', RETURN_TYPE);
    }

    //返回商品数据
    $goods = $db->getRow("SELECT * FROM " . $ecs->table('goods') . " WHERE goods_sn = '" . $goods_arr['goods_sn'] . "'");
}

/**
 * 商品属性
 */
function attribute($goods_data, $goods, $type_id)
{
    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    //整理扩展属性，过滤空值
    $attr_arr = $goods_data['baseprop'];
    $filter = array('颜色分类', '尺码'); //淘宝定义属性，过滤(特殊处理)
    foreach ($attr_arr as $k => $v) {
        if (trim($v) == '' || in_array($k, $filter)) unset($attr_arr[$k]);
        else $attr_arr[$k] = array($v);
    }

    //整理规格
    $sku_arr = array();
    if (isset($goods_data['skus']) && !empty($goods_data['skus'])) {
        foreach ($goods_data['skus'] as $sku) {
            foreach ($sku['specs'] as $attr_name => $v) {
                $attr_arr[$attr_name][] = $v['text'];
                $sku_arr[$sku['bn']][$attr_name]['name'] = $attr_name;
                $sku_arr[$sku['bn']][$attr_name]['value'] = $v['text'];
                $sku_arr[$sku['bn']][$attr_name]['bn'] = $sku['bn'];
                $sku_arr[$sku['bn']][$attr_name]['price'] = $sku['price'];
                $sku_arr[$sku['bn']][$attr_name]['quantities'] = $sku['quantities'];
                $attr_arr[$attr_name] = array_unique($attr_arr[$attr_name]);
            }
        }
    }

    $goods_attr_arr = $attr_arr; //用于处理商品属性

    //处理已存在的属性
    $sql = "SELECT * FROM " . $ecs->table('attribute') . " WHERE cat_id = {$type_id} AND attr_name IN ('" . implode("','", array_keys($attr_arr)) . "')";
    if ($res = $db->getAll($sql)) {
        //整理属性数据
        $res_arr = array();
        foreach ($res as $attr) {
            $tmp_arr = explode("\n", $attr['attr_values']);
            foreach ($tmp_arr as $k => $v) {
                $tmp_arr[$k] = trim($v, "\n\r");
            }
            $res_arr[$attr['attr_name']] = $tmp_arr;
        }

        //更新属性
        foreach ($attr_arr as $name => $value) {
            //检查已经存在属性
            if (isset($res_arr[$name]) == true) {
                //和数据库数据做对比
                if ($diff = array_diff($value, $res_arr[$name])) {
                    $attr_value = array_merge($res_arr[$name], $diff);
                    $sql = "UPDATE " . $ecs->table('attribute') . " SET `attr_values` = \"" . implode("\n", $attr_value) . "\" WHERE cat_id = {$type_id} AND attr_name = '{$name}'";
                    if ($db->query($sql) === false) api_response('fail', 'update attribute failed', '', RETURN_TYPE);
                }
                //去除已更新的属性
                unset($attr_arr[$name]);
            }
        }
    }

    //新增属性
    if ($attr_arr) {
        $sql = "INSERT INTO " . $ecs->table('attribute') . " (`cat_id`,`attr_name`,`attr_values`,`attr_input_type`,`attr_type`) VALUES ";
        foreach ($attr_arr as $name => $attr_value) {
            $attr_type = count($attr_value) > 1 ? 1 : 0;
            $sql .= "({$type_id},'{$name}',\"" . implode("\n", $attr_value) . "\",1,$attr_type),";
        }
        $sql = trim($sql, ",");

        if ($db->query($sql) === false) api_response('fail', 'add attribute failed', '', RETURN_TYPE);
    }

    $attribute = $db->getAll("SELECT * FROM " . $ecs->table('attribute') . " WHERE cat_id = {$type_id}");
    $attribute_arr = array();
    foreach ($attribute as $v) {
        if (!empty($v['attr_values'])) {
            $attribute_arr[$v['attr_name']][$v['attr_id']] = explode("\n", $v['attr_values']);
            $attribute_arr[$v['attr_name']]['type'] = $v['attr_type'];
        }
    }

    //处理已存在的商品属性
    if ($res = $db->getAll("SELECT * FROM " . $ecs->table('goods_attr') . " AS g LEFT JOIN " . $ecs->table('attribute') . " AS a ON g.attr_id = a.attr_id WHERE g.goods_id = {$goods['goods_id']}")) {
        //整理商品属
        $res_arr = array();
        foreach ($res as $v) {
            if (!empty($v['attr_values'])) {
                $res_arr[$v['attr_name']][$v['attr_id']] = explode("\n", $v['attr_values']);
                $res_arr[$v['attr_name']]['value'] = $v['attr_value'];
                $res_arr[$v['attr_name']]['type'] = $v['attr_type'];
                $res_arr[$v['attr_name']]['price'] = $v['attr_price'];
                $res_arr[$v['attr_name']]['goods_attr_id'] = $v['goods_attr_id'];
            }
        }

        //比对原有数据
        foreach ($res_arr as $name => $values) {
            $value = $values['value'];
            $type = $values['type'];
            $price = $values['price'];
            $goods_attr_id = $values['goods_attr_id'];
            unset($values['type'], $values['price'], $values['value'], $values['goods_attr_id']);
            if ($goods_attr_arr[$name]) {
                foreach ($goods_attr_arr[$name] as $k => $v) {
                    if (in_array($v, reset($values))) {
                        if ($type == 0 && $v != $value) {
                            $sql = "UPDATE " . $ecs->table('goods_attr') . " SET attr_value = '{$v}' WHERE goods_id = {$goods['goods_id']} AND attr_id = " . key($values) . " AND goods_attr_id = {$goods_attr_id}";
                            if ($db->query($sql) === false) api_response('fail', 'update goods attribute failed', '', RETURN_TYPE);
                        }
                        unset($goods_attr_arr[$name][$k]);
                    }
                }
                if (empty($goods_attr_arr[$name])) unset($goods_attr_arr[$name]);
            }
        }
    }

    //新增商品属性
    if ($goods_attr_arr) {
        $sql = "INSERT INTO " . $ecs->table('goods_attr') . " (`goods_id`,`attr_id`,`attr_value`,`attr_price`) VALUES ";
        foreach ($goods_attr_arr as $gk => $gv) {
            unset($attribute_arr[$gk]['type']);
            if ($attr_id = key($attribute_arr[$gk])) {
                foreach ($gv as $v) {
                    $sql .= "({$goods['goods_id']},{$attr_id},'{$v}',0),";
                }
            }
        }
        $sql = trim($sql, ",");
        if ($db->query($sql) === false) api_response('fail', 'add goods attribute failed', '', RETURN_TYPE);
    }

    $goods_attr = $db->getAll("SELECT * FROM " . $ecs->table('goods_attr') . " WHERE goods_id = {$goods['goods_id']}");

    //货品
    foreach ($sku_arr as $bn => $sku) {
        $sku_ids[] = $bn;
        foreach ($sku as $sk => $sv) {
            foreach ($goods_attr as $gk => $gv) {
                if ($sv['value'] == $gv['attr_value']) {
                    $sku_arr[$bn][$sk]['goods_attr_id'] = $gv['goods_attr_id'];
                }
            }
            $sku_keys[] = $sk;
        }
    }

    $sku_keys = array_unique($sku_keys);
    $db->query("UPDATE " . $ecs->table('attribute') . " SET attr_type = 1 WHERE cat_id = {$type_id} AND attr_name IN ('" . implode("','", $sku_keys) . "')");

    if ($res = $db->getAll("SELECT product_sn FROM " . $ecs->table('products') . " WHERE product_sn IN ('" . implode("','", $sku_ids) . "')")) {
        //根据货号过滤已经存在的货品，不做更新操作
        $res_arr = array();
        foreach ($res as $k) {
            $res_arr[] = $k['product_sn'];
        }

        //更新货品
        foreach ($sku_arr as $bn => $sku) {
            if (in_array($bn, array_values($res_arr))) {
                $goods_attr = '';
                foreach ($sku as $K => $v) {
                    $goods_attr .= $v['goods_attr_id'] . '|'; //规格
                    $quantities = $v['quantities']; //库存
                }
                $goods_attr = trim($goods_attr, '|');
                $sql = "UPDATE " . $ecs->table('products') . " SET goods_attr = '{$goods_attr}', product_number = {$quantities} WHERE product_sn = '{$bn}'";
                if ($db->query($sql) === false) api_response('fail', 'update product failed', '', RETURN_TYPE);
                unset($sku_arr[$bn]);
            }
        }
    }

    if ($sku_arr) {
        $sql = "INSERT INTO " . $ecs->table('products') . " (`goods_id`,`goods_attr`,`product_sn`,`product_number`) VALUES ";
        foreach ($sku_arr as $bn => $sku) {
            $goods_attr = '';
            foreach ($sku as $k => $v) {
                $goods_attr .= $v['goods_attr_id'] . '|'; //规格
                $quantities = $v['quantities']; //库存
            }
            $goods_attr = trim($goods_attr, '|');
            $sql .= "({$goods['goods_id']},'{$goods_attr}','{$bn}',$quantities),";
        }
        $sql = trim($sql, ",");
        if ($db->query($sql) === false) api_response('fail', 'add product failed', '', RETURN_TYPE);
    }
}

/**
 * 商品图片
 */
function gallery($goods_data, $goods)
{
    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    $images = $goods_data['images'];
    $defaultimage = $goods_data['defaultimage'];


    //删除不存在的图片
    $sql = "DELETE FROM " . $ecs->table('goods_gallery') . " WHERE `goods_id` = {$goods['goods_id']} AND `img_original` NOT IN ('" . implode("','", $images) . "')";
    $db->query($sql);

    //查找已有图片
    $sql = "SELECT img_original FROM " . $ecs->table('goods_gallery') . " WHERE `goods_id` = {$goods['goods_id']} AND `img_original` IN ('" . implode("','", $images) . "')";
    $tmp_img_arr = array();
    if ($res = $db->getAll($sql)) {
        foreach ($res as $v) {
            $tmp_img_arr[] = $v['img_original'];
        }
    }

    include_once(ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php');
    include_once(ROOT_PATH . '/includes/cls_image.php');
    //筛选出没有保存的图片
    if ($images = array_diff($images, $tmp_img_arr)) {
        $_CFG = load_config();
        $GLOBALS['image'] = new cls_image($_CFG['bgcolor']);
        $image_files = array('name' => array(''), 'type' => array(''), 'tmp_name' => array(''), 'error' => array('4'), 'size' => array('0'));
        $image_descs = array('');

        foreach ($images as $img) {
            handle_gallery_image($goods['goods_id'], $image_files, $image_descs, array($img));
            // 更新商品默认图
            // if ($img == $defaultimage) {
                // $GLOBALS['image'] = new cls_image($_CFG['bgcolor']);
                // $thumb_default_image = $GLOBALS['image']->make_thumb($defaultimage, $GLOBALS['_CFG']['image_width'], $GLOBALS['_CFG']['image_width']);
                // $sql = "UPDATE " . $ecs->table('goods') . " SET goods_thumb = '{$thumb_default_image}', goods_img = '{$thumb_default_image}', original_img = '{$img}' WHERE `goods_id` = {$goods['goods_id']}";
                // $db->query($sql);
            // }
        }
    }
    
    // 更新商品默认图
    if($defaultimage) {
        $_CFG = load_config();
        $GLOBALS['image'] = new cls_image($_CFG['bgcolor']);
        $thumb_default_image = $GLOBALS['image']->make_thumb($defaultimage, $GLOBALS['_CFG']['image_width'], $GLOBALS['_CFG']['image_width']);
        $sql = "UPDATE " . $ecs->table('goods') . " SET goods_thumb = '{$thumb_default_image}', goods_img = '{$thumb_default_image}', original_img = '{$img}' WHERE `goods_id` = {$goods['goods_id']}";
        $db->query($sql);
    }
}

/**
 * 查找商品信息
 */
function shopex_goods_search()
{
    check_auth(); //检查基本权限

    $db = $GLOBALS['db'];
    $ecs = $GLOBALS['ecs'];

    $page_no = empty($_POST['page_no']) ? 1 : intval($_POST['page_no']);
    $page_size = empty($_POST['page_size']) ? 20 : intval($_POST['page_size']);

    $re_arr = array();
    $re_arr['item_total'] = $db->getOne("SELECT count(*) FROM " . $ecs->table('goods'));

    $cat_list = cat_list(0, 0, false);
    $brand_list = get_brand_list();
    if ($res = $db->getAll("SELECT * FROM " . $ecs->table('goods_type'))) {
        foreach ($res as $v) {
            $type_list[$v['cat_id']] = $v['cat_name'];
        }
    }

    $sql = "SELECT * FROM " . $ecs->table('goods') . " LIMIT " . ($page_no - 1) * $page_size . ",{$page_size}";
    if ($res = $db->getAll($sql)) {
        foreach ($res as $k => $goods) {
            //分类
            $cat_name = $cat_list[$goods['cat_id']]['cat_name'];
            $path = '';
            get_cat_path($cat_list[$goods['cat_id']], $cat_list, $path);
            krsort($path);
            //品牌
            $brand_name = $brand_list[$goods['brand_id']];
            //默认图片
            $default_image_path = $goods['goods_img'] ? $ecs->url() . $goods['goods_img'] : '';
            $has_default_image = $goods['goods_img'] ? 'true' : 'false';

            $gid = $goods['goods_id'];
            $re_arr['goods'][$gid]['cat_name'] = $cat_name; //分类名称
            $re_arr['goods'][$gid]['cat_path'] = implode('->', $path); //分类路径
            $re_arr['goods'][$gid]['type_name'] = $type_list[$goods['goods_type']]; //商品类型名称
            $re_arr['goods'][$gid]['goods_type'] = 'normal'; //商品类型（normal：正常；bind：捆绑商品）
            $re_arr['goods'][$gid]['brand_name'] = $brand_name; //品牌名称
            $re_arr['goods'][$gid]['default_image_path'] = $default_image_path; //默认图片路径
            $re_arr['goods'][$gid]['has_default_image'] = $has_default_image; //是否有商品默认图
            $re_arr['goods'][$gid]['mktprice'] = $goods['market_price']; //市场价
            $re_arr['goods'][$gid]['cost'] = $goods['shop_price']; //商品成本
            $re_arr['goods'][$gid]['price'] = $goods['shop_price']; //商品销售价
            $re_arr['goods'][$gid]['bn'] = $goods['goods_sn']; //商品编码
            $re_arr['goods'][$gid]['bn_code'] = $goods['goods_sn']; //商品货号
            $re_arr['goods'][$gid]['name'] = $goods['goods_name']; //商品名称
            $re_arr['goods'][$gid]['goods_keywords'] = str_replace(" ", "|", $goods['keywords']); //商品关键词(多个关键词用半角竖线"|"分开)
            $re_arr['goods'][$gid]['weight'] = $goods['goods_weight']; //单件重量
            $re_arr['goods'][$gid]['unit'] = '千克'; //单位
            $re_arr['goods'][$gid]['store'] = $goods['goods_number']; //商品库存
            $re_arr['goods'][$gid]['is_postage'] = 'false'; //是否包邮
            $re_arr['goods'][$gid]['marketable'] = $goods['is_on_sale']; //是否上架
            $re_arr['goods'][$gid]['list_time'] = '0'; //上架时间
            $re_arr['goods'][$gid]['disabled'] = $goods['is_delete'] ? 'true' : 'false'; //是否屏蔽商品
            $re_arr['goods'][$gid]['order_by'] = $goods['sort_order']; //排序
            $re_arr['goods'][$gid]['brief'] = $goods['goods_brief']; //商品简介
            $goods['goods_desc'] = htmlspecialchars_decode(preg_replace("/src=[\'|\"][^http](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"]/i", "src=\"" . $ecs->url() . "$1\"", $goods['goods_desc']));
            $re_arr['goods'][$gid]['intro'] = $goods['goods_desc']; //商品详细信息

            //属性 & 货品
            $products_props = array();
            $prop_values = $products = $spec_values = '';
            $sql = "SELECT * FROM " . $ecs->table('goods_attr') . " AS g LEFT JOIN " . $ecs->table('attribute') . " AS a "
                . "ON g.attr_id = a.attr_id WHERE g.goods_id = {$goods['goods_id']}";
            if ($res = $db->getAll($sql)) {
                foreach ($res as $v) {
                    $prop_values[] = array('key' => $v['attr_name'], 'value' => $v['attr_value']);
                    $products_props[$v['goods_attr_id']] = array('name' => $v['attr_name'], 'value' => $v['attr_value']);
                }
            }
            $re_arr['goods'][$gid]['prop_values'] = $prop_values; //扩展属性值

            $sql = "SELECT * FROM " . $ecs->table('products') . " WHERE goods_id = {$goods['goods_id']}";
            if ($res = $db->getAll($sql)) {
                foreach ($res as $pk => $v) {
                    $goods_attrs = explode('|', $v['goods_attr']);
                    $products[$pk]['barcode'] = '';
                    $products[$pk]['bn_code'] = $v['product_sn'];
                    $products[$pk]['price'] = $goods['shop_price'];
                    $products[$pk]['cost'] = '0.000';
                    $products[$pk]['weight'] = $goods['goods_weight'];
                    $products[$pk]['store'] = $v['product_number'];
                    $products[$pk]['goods_space'] = '';
                    $products[$pk]['op_status'] = '1';
                    $products[$pk]['last_modify'] = '0';
                    $products[$pk]['is_unlimit'] = '0';
                    $products[$pk]['member_lps'] = '';
                    foreach ($goods_attrs as $gak => $gav) {
                        $spec_values[$gak]['spec_name'] = $products_props[$gav]['name'];
                        $spec_values[$gak]['spec_alias_name'] = $products_props[$gav]['name'];
                        $spec_values[$gak]['spec_value_name'] = $products_props[$gav]['value'];
                        $spec_values[$gak]['customer_spec_value_name'] = '';
                        $spec_values[$gak]['customer_spec_value_image'] = '';
                        $spec_values[$gak]['spec_value_image'] = '';
                    }
                    $products[$pk]['spec_values'] = $spec_values;
                }
            }
            $re_arr['goods'][$gid]['products'] = $products; //货品列表
            $re_arr['goods'][$gid]['params_values'] = ''; //详细参数值
            $re_arr['goods'][$gid]['is_unlimit'] = 'false'; //是否为无限库存，是：true 否 ：false

            //商品图片
            $goods_images = '';
            $sql = "SELECT * FROM " . $ecs->table('goods_gallery') . " WHERE goods_id = {$goods['goods_id']}";
            if ($res = $db->getALL($sql)) {
                foreach ($res as $k => $v) {
                    preg_match('/^http:\/\//', $v['img_url'], $matches);
                    if (empty($matches)) {
                        $goods_images[$k]['source'] = $ecs->url() . '/' . $v['img_url'];
                    } else {
                        $goods_images[$k]['source'] = $v['img_url'];
                    }
                    $goods_images[$k]['is_remote'] = 0;
                    $goods_images[$k]['orderby'] = 0;
                }
            }
            $re_arr['goods'][$gid]['goods_images'] = $goods_images; //Image列表
            $re_arr['goods'][$gid]['last_modify'] = $goods['last_update']; //最后更新时间
            $re_arr['goods'][$gid]['goods_id'] = $goods['goods_id']; //商品id
            $re_arr['goods'][$gid]['goods_url'] = $ecs->url() . "goods.php?id={$goods['goods_id']}"; //商品详前台URL
        }
        api_response('true', '', $re_arr, RETURN_TYPE);
    }
    api_response('true', 'No Data', '', RETURN_TYPE);
}

// 更新订单到crm
function update_order_crm($order_sn){
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    $matrix = new matrix();
    $bind_info = $matrix->get_bind_info(array('ecos.taocrm'));
    if($bind_info){
        return $matrix->updateOrder($order_sn,'ecos.taocrm');
    }
    return true;
}

?>