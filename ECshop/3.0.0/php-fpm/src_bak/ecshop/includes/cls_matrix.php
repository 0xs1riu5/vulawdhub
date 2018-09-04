<?php

/**
 * ECSHOP 联通矩阵 相关函数类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_article.php 16336 2009-06-24 07:09:13Z liubo $
 */
if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

class matrix
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct(){
        include_once(ROOT_PATH . 'includes/cls_transport.php');
        $this->transport = new transport;
        $this->shopex_app = array('taodali','ecos.ome','ecos.taocrm');
        $this->db = $GLOBALS['db'];
        $this->ecs = $GLOBALS['ecs'];
    }

    /**
     * 功能：查询绑定详情
     *
     * @param   array     $node_type    绑定类型
     * @return  array
     */
    function get_bind_info($node_type)
    {
        if (!is_array($node_type)) {
            $node_type = array($node_type);
        }
        $sql = "SELECT *
                FROM " . $this->ecs->table('shop_bind') . "
                WHERE node_type in ('".implode("','", $node_type)."') AND status='bind'";
        $bind_info = $this->db->getRow($sql);
        $bind_info = is_array($bind_info) ? $bind_info : array();
        return $bind_info;
    }

    /**
     * 功能：保存矩阵绑定关系
     *
     * @param   array     $data    绑定信息
     * @return  string
     */
    function save_shop_bind($data)
    {
        $sql = "INSERT INTO ".$this->ecs->table('shop_bind')." (name, node_id, node_type, status, app_url) ".
            "VALUES ('".$data['name']."','".$data['node_id']."','".$data['node_type']."','".$data['status']."','".$data['app_url']."')";
        error_log("保存绑定关系sql",3,__FILE__.".log");
        error_log(var_export($sql,1),3,__FILE__.".log");
        $this->db->query($sql);
        /* 转入权限分配列表 */
        $new_id = $this->db->Insert_ID();
        /* 将矩阵的绑定节点状态写入config */
        $this->bind_config($data['node_type'],'true');
        return $new_id;
    }

    /**
     * 功能：删除矩阵绑定关系
     *
     * @param   array     $node_type    绑定类型
     */
    function delete_shop_bind($node_type){
        error_log(date("c")."\t".__LINE__."\n\n",3,LOG_DIR."/api.log");
        if(!$node_type) return false;
        $sql = "delete from ".$this->ecs->table('shop_bind')." where node_type = '".$node_type."'";
        error_log(date("c")."delete_shop_bind:\t".__LINE__.print_r($sql,1)."\n\n",3,__FILE__.".log");
        $this->db->query($sql);
        /* 将矩阵的绑定节点状态写入configg */
        $this->bind_config($node_type,'false');
        return true;
    }

    /**
     * 功能：将矩阵的绑定节点状态写入config
     *
     * @param   array     $code    绑定类型
     * @param   strint    $status    true:绑定  false:解绑
     */
    function bind_config($code,$status='true'){
        if(!$code || $code=='') return false;
        $sql = "SELECT * FROM ".$this->ecs->table('shop_config')." WHERE code = 'bind_list'";
        $bind_row = $this->db->getRow($sql);
        $bind_row and $list = json_decode($bind_row['value'],1);
        if($code == 'ecos.taocrm'){
            $sql = "SELECT * FROM ".$this->ecs->table('shop_config')." WHERE code = 'bind_crm_order_time'";
            $bind_crm_time = $this->db->getRow($sql);
            error_log("crm绑定状态",3,__FILE__.".log");
            error_log(var_export($bind_row,1),3,__FILE__.".log");
            if(!$bind_crm_time){
                if($status=='true'){
                    $time = time();
                    //历史订单推送时间
                    $sql_time = "insert into ".$this->ecs->table('shop_config')." set parent_id=2,code='bind_crm_order_time',type='hidden',value=".$time.",sort_order=1";
                    $this->db->query($sql_time);
                    //历史会员推送时间
                    $sql_time = "insert into ".$this->ecs->table('shop_config')." set parent_id=2,code='bind_crm_member_time',type='hidden',value=".$time.",sort_order=1";
                    $this->db->query($sql_time);
                }
            }
        }
        if($list){
            if($status=='true'){
                $list[] = $code;
            }else{
                foreach($list as $k => $value){
                    if($code == $value) unset($list[$k]);
                }
            }
            $sql = "UPDATE ".$this->ecs->table('shop_config')." SET value='".json_encode($list)."' WHERE code='bind_list'";
            if(empty($list)) $sql = "delete from ".$this->ecs->table('shop_config')." where code='bind_list'";
            $this->db->query($sql);
        }else{
            if($status=='true'){
                $list = array($code);
                $sql = "insert into ".$this->ecs->table('shop_config')." set parent_id=2,code='bind_list',type='hidden',value='".json_encode($list)."',sort_order=1";
                $this->db->query($sql);
            }
        }
    }

    /**
     * 功能：获取支付方式
     *
     * @param   int     $pay_id    支付方式ID
     */
    function get_payment($pay_id){
        $sql = "SELECT pay_id, pay_name,pay_code FROM ".$this->ecs->table('payment').
            " WHERE enabled = 1 AND pay_id = ".$pay_id;
        return $this->db->getRow($sql);
    }


    function getItemNum($order_id){
        $sql = "SELECT SUM(goods_number) as itemnum from ".$this->ecs->table('order_goods')." where order_id=".$order_id;
        return $this->db->getRow($sql);
    }


    function http_request_matrix($paramss,$bind_type='ecos.ome'){
        //sync同步
        foreach($this->shopex_app as $k){
            switch($k){
                case 'taodali':
                    $commit_setting[$k]['commit_url'] = MATRIX_COMMIT_URL_SYNC;
                    $commit_setting[$k]['real_time'] = 'true';
                    $commit_setting[$k]['callback_type'] = $paramss['callback_type'];
                    break;
                case 'ecos.ome':
                    $commit_setting[$k]['commit_url'] = MATRIX_COMMIT_URL_SYNC;
                    $commit_setting[$k]['real_time'] = 'true';
                    $commit_setting[$k]['callback_type'] = $paramss['callback_type'];
                    break;
                case 'ecos.taocrm':
                    $commit_setting[$k]['commit_url'] = MATRIX_COMMIT_URL_SYNC;
                    $commit_setting[$k]['real_time'] = 'true';
                    $commit_setting[$k]['callback_type'] = $paramss['callback_type'];
                    break;
            }
        }
        include_once(ROOT_PATH."includes/cls_certificate.php");
        $cert = new certificate();
        //系统级参数
        $paramss['app_id'] = VERIFY_APP_ID;
        $certificate = $cert->get_shop_certificate();
        $paramss['certi_id'] = $certificate['certificate_id'];
        $paramss['from_node_id'] = $certificate['node_id'];
        $paramss['date'] = time();
        // $paramss['timestamp'] = microtime(true);
        $paramss['timestamp'] = date('Y-m-d H:i:s',time());
        $paramss['refresh_time'] = date('Y-m-d H:i:s',time());
        $paramss['format'] = "json";
        $paramss['v'] = "1.0";
        $paramss['from_api_v'] = '1.0';
        $http_type = $paramss['type'];
        unset($paramss['type']);
        $bind_type=='retry' and $bind_type = $this->shopex_app;
        $shop = $this->get_bind_info($bind_type);
        $paramss['node_type'] = $shop['node_type'];
        $paramss['to_node_id'] = $shop['node_id'];
        $paramss['_id'] = "rel_".$paramss['from_node_id']."_".$paramss['method']."_".$paramss['to_node_id'];
        $paramss['task'] = $this->create_task_id();
        $paramss['real_time'] = $commit_setting[$shop['node_type']]['real_time'];
        $paramss['callback_type'] = $commit_setting[$shop['node_type']]['callback_type'];
        $paramss['callback_url'] = $this->ecs->url();
        unset($paramss['sign']);
        $paramss['sign']  = $this->get_matrix_sign($paramss,$certificate['token']);
        if ( @constant( "DEBUG_API" ) ) {
            foreach ($paramss as $key=>$val) {
                $array_debug_info[] = $key."=".stripslashes($val);
            }
            $str_debug_info = implode("&", $array_debug_info);
            if(!is_dir(LOG_DIR)){
                mkdir(LOG_DIR,0777);
            }
            error_log(date("c")."\t".rawurldecode($str_debug_info)."\n".stripslashes(var_export($paramss,true))."\n\n",3,LOG_DIR."/api_".date("Y-m-d",time()).".log");
            unset($str_debug_info,$array_debug_info);
        }
        error_log(date("c")."\t".__LINE__.print_r($commit_setting,1)."\n\n",3,LOG_DIR."/api.log");
        error_log(date("c")."\t".__LINE__.print_r($shop,1)."\n\n",3,LOG_DIR."/api.log");
        $i=0;
        do{
            $i++;
            $response = $this->transport->request($commit_setting[$shop['node_type']]['commit_url'], $paramss);
        }while(strlen(trim($response['body']))==0&&$i<0);

        if ( @constant( "DEBUG_API" ) ) {
            error_log(date("c")."\t"."\n".stripslashes(var_export($response,true))."\n\n",3,LOG_DIR."/api_".date("Y-m-d",time()).".log");
        }
        $callback = json_decode($response['body'],true);
        $status = $callback['rsp']=='succ'?'true':'false';

        $this->set_callback($callback,$http_type,$commit_setting[$shop['node_type']]['callback_type'],$paramss['callback_type_id'],$paramss['method'],$paramss,$status);
        if($status == 'true'){
            return true;
        }else{
            return false;
        }
    }

    //crm获取历史订单
    function push_history_order(){
        $sql = "SELECT * FROM ".$this->ecs->table('shop_config')." WHERE code = 'bind_crm_order_time'";
        $bind_crm_time = $this->db->getRow($sql);
        $bind_crm_time = $bind_crm_time['value'];
        $sql = "select order_sn,add_time from ".$this->ecs->table('order_info')." where add_time < ".$bind_crm_time." order by add_time desc limit 5 ";
        $rows = $this->db->getAll($sql);
        if(!$rows)return;
        foreach($rows as $row){
            if(!$this->createOrder($row['order_sn'],'ecos.taocrm'))return;
            //重置推送时间
            $sql_time = "update ".$this->ecs->table('shop_config')." set value=".$row['add_time']." where code='bind_crm_order_time'";
            $this->db->query($sql_time);
        }
    }

    //crm获取历史会员
    function push_history_member(){
        $sql = "SELECT * FROM ".$this->ecs->table('shop_config')." WHERE code = 'bind_crm_member_time'";
        $bind_crm_time = $this->db->getRow($sql);
        $bind_crm_time = $bind_crm_time['value'];
        $sql = "select user_id,reg_time from ".$this->ecs->table('users')." where reg_time < ".$bind_crm_time." order by reg_time desc limit 5 ";
        $rows = $this->db->getAll($sql);
        foreach($rows as $row){
            if(!$this->createMember($row['user_id'],'ecos.taocrm'))return;
            error_log('id:'.$row['user_id'],3,__FILE__.".log");
            //重置推送时间
            $sql_time = "update ".$this->ecs->table('shop_config')." set value=".$row['reg_time']." where code='bind_crm_member_time'";
            $this->db->query($sql_time);
        }
    }

    //创建订单
    function createOrder($order_sn,$type=''){
        include_once(ROOT_PATH.'includes/cls_certificate.php');
        $cert = new certificate();
        //订单总体信息
        $paramss = $this->getOrderStruct($order_sn);
        if(!$paramss['orders']) return null;

        $paramss['method'] = 'store.trade.add';
        $paramss['callback_type'] = 'CREATEORDER';
        $paramss['callback_type_id'] = $paramss['tid'];
        $paramss['type'] = 'request';
        $paramss['from_type'] = VERIFY_APP_ID;

        //crm历史订单创建推送
        if($type == 'ecos.taocrm'&&$cert->is_bind_sn('ecos.taocrm','bind_type')){
            $is_succ = $this->http_request_matrix($paramss,$type);
            if($is_succ){
                $sql = "select * from ".$this->ecs->table('shop_config')." where code='bind_crm_order_push'";
                $push =  $this->db->getRow($sql);
                if(!$push){
                    $sql_push = "insert into ".$this->ecs->table('shop_config')." set parent_id=2,code='bind_crm_order_push',type='hidden',value=1,sort_order=1";
                }else{
                    $sql_push = "update ".$this->ecs->table('shop_config')." set value=value+1 where code='bind_crm_order_push'";
                }
                $this->db->query($sql_push);
                return true;
            }else{
                return false;
            }
        }
        //综合订单创建推送
        foreach($this->shopex_app as $k){
            if($cert->is_bind_sn($k,'bind_type')){
                $is_succ = $this->http_request_matrix($paramss,$k);
                if($is_succ){
                    if($k == 'ecos.taocrm'){
                        $sql = "select * from ".$this->ecs->table('shop_config')." where code='bind_crm_order_push'";
                        $push =  $this->db->getRow($sql);
                        if(!$push){
                            $sql_push = "insert into ".$this->ecs->table('shop_config')." set parent_id=2,code='bind_crm_order_push',type='hidden',value=1,sort_order=1";
                        }else{
                            $sql_push = "update ".$this->ecs->table('shop_config')." set value=value+1 where code='bind_crm_order_push'";
                        }
                        $this->db->query($sql_push);
                    }
                }else{
                    return false;
                }
            }
        }
        return true;
    }

    //创建会员
    function createMember($ueser_id,$type=''){
        include_once(ROOT_PATH.'includes/cls_certificate.php');
        $cert = new certificate();
        if($cert->is_bind_sn('ecos.taocrm','bind_type')){
            //会员总体信息
            $sql = "select * from ".$this->ecs->table('users')." where user_id=".$ueser_id;
            $rows = $this->db->getRow($sql);
            if(!$rows) return null;
            $paramss['method'] = 'store.user.add';
            $paramss['callback_type'] = 'CREATEMEMBER';
            $paramss['callback_type_id'] = $rows['user_id'];
            $paramss['type'] = 'request';
            $paramss['from_type'] = VERIFY_APP_ID;
            $paramss['userid'] = $rows['user_id'];
            $paramss['uid'] = $rows['user_id'];
            $paramss['user_name'] = $rows['user_name'];
            $paramss['sex'] = $rows['sex'];
            $paramss['created'] = date('Y-m-d H:i:s',$rows['reg_time']);
            $paramss['last_visit'] = date('Y-m-d H:i:s',$rows['last_login']);
            $paramss['birthday'] = $rows['birthday'];
            $paramss['email'] = $rows['email'];
            $paramss['mobile'] = $rows['mobile_phone'];
            $paramss['age'] = $rows['age'];
            $is_succ = $this->http_request_matrix($paramss,$type);
            if($is_succ){
                $sql = "select * from ".$this->ecs->table('shop_config')." where code='bind_crm_member_push'";
                $push =  $this->db->getRow($sql);
                if(!$push){
                    $sql_push = "insert into ".$this->ecs->table('shop_config')." set parent_id=2,code='bind_crm_member_push',type='hidden',value=1,sort_order=1";
                }else{
                    $sql_push = "update ".$this->ecs->table('shop_config')." set value=value+1 where code='bind_crm_member_push'";
                }
                $this->db->query($sql_push);
            }
            return $is_succ;
        }else{
            return true;
        }


    }


    //更新订单
    function updateOrder($order_sn,$type=''){
        include_once(ROOT_PATH.'includes/cls_certificate.php');
        $cert = new certificate();

        //订单总体信息
        $paramss = $this->getOrderStruct($order_sn);
        $paramss['method'] = 'store.trade.update';
        $paramss['callback_type'] = 'UPDATEORDER';
        $paramss['callback_type_id'] = $paramss['tid'];
        $paramss['type'] = 'request';
        $paramss['from_type'] = VERIFY_APP_ID;

        if($type){
            if($cert->is_bind_sn($type,'bind_type')) {
                $is_succ = $this->http_request_matrix($paramss, $type);
                if (!$is_succ) return false;
            }
        }else{
            foreach($this->shopex_app as $shopex_app){
                if($cert->is_bind_sn($shopex_app,'bind_type')){
                    $is_succ = $this->http_request_matrix($paramss,$shopex_app);
                    if(!$is_succ)return false;
                }
            }
        }
        return true;
    }

    //获取货品列表
    function getProductList($items,$p_real_price){
        if( !empty($items) ){
            $sql = "select p.product_id,p.goods_id as iid,p.goods_id,p.goods_attr,attr.attr_price,attr.attr_value,g.shop_price as price,g.goods_name as name from ".$this->ecs->table('products')." as p LEFT join ".$this->ecs->table('goods_attr')." as attr on p.goods_attr=attr.goods_attr_id and p.goods_id=attr.goods_id LEFT join ".$this->ecs->table('goods')." as g on p.goods_id=g.goods_id where p.product_id in (".join(',',$items[1]).")";
            $rows = $this->db->getAll($sql);
            $datas = array();
            $goods_ids = array();
            foreach($rows as $k => $val){
                if($val['attr_value']){
                    $val['name'] .= "(".$val['attr_value'].")";
                    $val['sku_properties'] = $val['attr_value'];
                }
                $val['num']=$items[0][$val['product_id']]['goods_number'];

                $val['total_item_fee']  = $this->format_number($items[0][$val['product_id']]['goods_price'] * $val['num']);
                $val['sendnum'] = 0;
                $val['item_type'] = 'product';
                $val['sale_price'] = $this->format_number($p_real_price[$val['product_id']] * $val['num']);
                $val['discount_fee'] = $this->format_number(($val['price']+$val['attr_price'])*$val['num']-$val['sale_price']); //商品差价
                $this->_total_products_price += ($val['price']+$val['attr_price'])*$val['num'];  //未打折之前总的商品价格
                $this->_total_discount_fee +=$val['discount_fee'];  //总商品差价
                $datas[$val['iid']][$val['product_id']] = $val;
                $goods_ids[$val['iid']] = $val['iid'];
                $goods_total[$val['iid']][$val['product_id']]['product_id'] = $val['product_id'];
                $goods_total[$val['iid']][$val['product_id']]['num'] = $val['num'];
                $goods_total[$val['iid']][$val['product_id']]['total'] = $val['total_item_fee'];
                //unset($datas[$val['iid']][$k]['product_id']);
            }
            if( !empty($goods_total) ){
                foreach( $goods_total as $key=>$val ){
                    foreach($val as $v){
                        $goods[$key]['items_num'] += $v['num'];
                        $goods[$key]['total_order_fee'] += $v['total'];
                    }
                }
            }
        }
        return array($datas,$goods,$goods_ids);//列表数据，商品个数和总价，商品id列表
    }

    //获取商品列表
    function getGoodsList($goods_ids){
        if( !empty($goods_ids) ){
            $sql = "select goods_id as iid,goods_name as title,goods_sn as bn from ".$this->ecs->table('goods')." where  goods_id in (".join(',',$goods_ids).")";
            $rows = $this->db->getAll($sql);
        }
        return $rows;
    }

    //获取订单信息
    function get_order_info($order_sn,$params=''){
        $params = $params?$params:'*';
        $sql = "select ".$params." from ".$this->ecs->table('order_info')." where order_sn='".$order_sn."'";
        $row = $this->db->getRow($sql);
        return $row;
    }

    //获取订单货品信息order_goods
    function get_order_items($order_id){
        $sql = "select distinct product_id,goods_name,goods_sn,goods_price,market_price,goods_number,goods_attr,rec_id from ".$this->ecs->table('order_goods')." where order_id='".$order_id."' and is_gift =0";
        if($rs = $this->db->getAll($sql)){
            foreach($rs as $k=>$v){
                $product_ids[$v['product_id']] = $v['product_id'];
                $v['goods_attr'] = str_replace("\n", "", $v['goods_attr']);
                $items[$v['product_id']] = $v;
                $items_id[] = $v['rec_id'];
                $p_items_id[$v['product_id']] = $v['rec_id'];
            }
        }
        $diff_items_id = array_diff($items_id, array_values($p_items_id));
        return array($items,$product_ids,$diff_items_id);
    }

    //根据order_id获取order_goods货品信息
    function getGoodsInfoByOid($order_id){
        $sql = "select product_id,goods_price from ".$this->ecs->table('order_goods')." where order_id='".$order_id."' and is_gift = 0";
        $row = $this->db->getAll($sql);
        foreach($row as $k=>$v){
            $p_real_price[$v['product_id']] = $v['goods_price'];
        }
        return $p_real_price;
    }

    //插入callback记录 写返回日志
    function set_callback($msg,$http_type,$type,$tpye_id,$method,$data=array(),$status=""){
        $time = time();
        $status or $status = $msg['msg_id'] ? "running":"false";
        $data = addslashes(serialize(array('params'=>$data,'result'=>$msg)));
        if( $this->checkCallbackExit($type,$tpye_id) ){
            $data and $data = ",data='$data'";
            $sql = "update ".$this->ecs->table('callback_status')." set msg_id='".$msg['msg_id']."',status='".$status."',date_time='".$time."',times=times+1 {$data} WHERE method='".$method."' AND type_id='".$tpye_id."' ";
        }else{
            $sql = "insert into ".$this->ecs->table('callback_status')." set msg_id='".$msg['msg_id']."',type='".$type."',http_type='".$http_type."',status='".$status."',type_id='".$tpye_id."',method='".$method."',date_time='".$time."',data='".$data."',times=1";
        }
        error_log('sql:'.$sql,3,__FILE__.".log");
        $this->db->query($sql);
        //接口失败，修改订单的返回状态 order_info 的 callback_status
        if($status=='false' || $status=='true'){
            $order_sql = "update ".$this->ecs->table('order_info')." set callback_status='".$status."' where order_sn='".$tpye_id."'";
            $this->db->query($order_sql);
        }

    }

    /**
     * 根据type_id 获取 矩阵返回日志
     * @param $type_id 日志id
     * @return  array
     */
    function get_callback($type_id){
        $sql = "select * from ".$GLOBALS['ecs']->table('callback_status')." where type_id='".$type_id."' and status='false'";
        $row = $GLOBALS['db']->getRow($sql,1);
        if(!$row) return false;
        $row['data'] = unserialize($row['data']);
        return $row;
    }

    //验证重试次数
    function checkCallbackCount($type,$type_id){
        $sql = "select times from ".$this->ecs->table('callback_status')." where type='".$type."' and type_id='".$type_id."'";
        $row = $this->db->getRow($sql);
        return $row['times'];
    }

    //验证重试次数
    function checkCallbackExit($type,$type_id){
        $sql = "select * from ".$this->ecs->table('callback_status')." where type='".$type."' and type_id='".$type_id."'";
        if($row = $this->db->getRow($sql)){
            error_log(var_export($row,1),3,__FILE__.".log");
            return true;
        }
        return false;
    }

    //更新callback记录
    function update_callback($msg_id,$data="",$status='true'){
        $status = $status=='true'?$status:"false";
        $data and $sqlstr = ",data=CONCAT(data,'\n".addslashes(serialize($data))."')";
        $set_times = $status=='true'?'times=0,':'times=times+1,';//成功后置0
        if( is_array($data) && $data['type'] && $data['type_id'] ){
            $sql = "update ".$this->ecs->table('callback_status')." set {$set_times}status='{$status}' {$sqlstr} where type='".$data['type']."' AND type_id='".$data['type_id']."' and status!='true' ";
        }else{
            $sql = "update ".$this->ecs->table('callback_status')." set {$set_times}status='{$status}' {$sqlstr} where msg_id='".$msg_id."'  and status!='true'  ";
        }
        error_log(date("c")."\t sql :".$sql."\n",3,__FILE__.".log");
        if( $this->db->query($sql) === false ){
            // error_log(date("c")."\t error sql :".$sql."\n",3,__FILE__.".log");
        }
        //修改订单的返回状态 order_info 的 callback_status
        $order_sql = "update ".$this->ecs->table('order_info')." set callback_status='".$status."' where order_sn='".$data['type_id']."'";
        $this->db->query($order_sql);
    }


    function get_card_info($order_sn,$card){
        $gift_value = array();
        $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('card')." WHERE card_name = '{$card['card_name']}'";
        $res = $GLOBALS['db']->getRow($sql);
        if ($res) {
            $gift_value = array(
                'iid' => '0',
                'title' => $res['card_name'],
                'bn' => 'ECS_CARD',
                // 'orders_bn' => 'ECS_CARD',
                'orders_bn' => 'ECS_CARD_'.$res['card_id'], //erp要求传跟order_items里的一样
                'items_num' => '1',
                'total_order_fee' => $card['card_fee'],
                'oid' => $order_sn,
                'status' => 'TRADE_ACTIVE',
                'type' => 'goods',
                'order_items' => array(
                    'item'=> array(
                        0=>array(
                            'iid' => '0',
                            'bn' => 'ECS_CARD_'.$res['card_id'],
                            'price' => $card['card_fee']>0?$res['card_fee']:'0.00',
                            'name' => $res['card_name'],
                            'weight' => '0.00',
                            'num' => '1',
                            'total_item_fee' => $card['card_fee'],
                            'sku_properties' => $res['card_name'],
                            'sendnum' => '0',
                            'item_type' => 'product',
                            'sale_price' => $card['card_fee']>0?$res['card_fee']:'0.00',
                            'discount_fee' => '0.00',
                            'score' => '',
                            'item_status' => 'normal',
                        )
                    )
                )

            );
        }
        return $gift_value;
    }


    function get_order_goods($res,$has_product){
        $return_value = array();
        foreach ($res as $key => $value) {
            // if (isset($value['p_goods_attr']) && strstr($value['p_goods_attr'],"|")) {
            //     $sql = "SELECT sum(attr_price) as attr_price FROM ".$GLOBALS['ecs']->table('goods_attr')." WHERE goods_id = '".$value['goods_id']."' AND goods_attr_id in (".str_replace("|", ",", $value['p_goods_attr']).")";
            //     $attr_price = $GLOBALS['db']->getOne($sql);
            //     $attr_price && $value['shop_price'] = $value['shop_price']+$attr_price;
            // }

            // if ($return_value[$value['goods_id']]) {
            //     $return_value[$value['goods_id']]['items_num'] = $return_value[$value['goods_id']]['items_num']+$value['goods_number'];
            //     $return_value[$value['goods_id']]['order_items']['item'][] = array(
            //         'iid' => $value['product_id'],
            //         'bn' => $has_product?$value['product_sn']:$value['goods_sn'],
            //         'price' => $value['goods_price'],
            //         'name' => $value['goods_name'],
            //         'weight' => $value['goods_weight'],
            //         'num' => $value['goods_number'],
            //         'total_item_fee' => $value['goods_price'],
            //         'sku_properties' => str_replace(array("\r\n", "\r", "\n"), " ", $value['goods_attr']),
            //         'sendnum' => $value['send_number'],
            //         'item_type' => 'product',
            //         'sale_price' => $value['goods_price'],
            //         'discount_fee' => '0',
            //         'score' => $value['give_integral']<0?$value['goods_price']:$value['give_integral'],
            //         'item_status' => 'normal'
            //     );
            // }else{

            $sku_properties = '';
            if ($value['goods_attr']) {
                $sku_properties = str_replace(' ','',$value['goods_attr']);
                $sku_properties = str_replace(array("\r\n", "\r", "\n"), ";", $sku_properties);
                $sku_properties = trim($sku_properties,';');
            }
            $return_value[] = array(
                'iid' => $value['goods_id'],
                'title' => $value['goods_name'],
                'weight' => $value['goods_weight'],
                'bn' => $value['goods_sn'],
                // 'orders_bn' => $value['goods_sn'],
                'orders_bn' => $has_product?$value['product_sn']:$value['goods_sn'], //erp要求传跟order_items里的一样
                'items_num' => $value['goods_number'],
                'total_order_fee' => $value['goods_price'],
                'oid' => $order_sn,
                'status' => 'TRADE_ACTIVE',
                'type' => $value['is_gift']?'gift':'goods',
                'order_items' => array(
                    'item' => array(
                        0 => array(
                            'iid' => $value['product_id'],
                            'bn' => $has_product?$value['product_sn']:$value['goods_sn'],
                            'price' => $value['goods_price'],
                            // 'price' => $value['shop_price'],
                            'name' => $value['goods_name'],
                            'weight' => $value['goods_weight'],
                            'num' => $value['goods_number'],
                            'total_item_fee' => $value['goods_price'],
                            'sku_properties' => $sku_properties,
                            'sendnum' => $value['send_number'],
                            'item_type' => 'product',
                            'sale_price' => $this->format_number($value['goods_price']*$value['goods_number']-$value['discount_fee']),
                            'discount_fee' => $value['discount_fee'],
                            'score' => $value['give_integral']<0?$value['goods_price']:$value['give_integral'],
                            'item_status' => 'normal'
                        )
                    )
                )
            );
            // }
        }
        return $return_value;
    }


    function getGoods($order_id,$order_sn,$card=false,$use_gift=false,$card_fee=0){
        $return_has_pro = $return_no_pro = array();
        $sql = "SELECT og.*,p.product_sn,g.goods_weight,g.give_integral,g.shop_price,p.goods_attr as p_goods_attr FROM ".$GLOBALS['ecs']->table('order_goods')." as og LEFT JOIN ".$GLOBALS['ecs']->table('products')." as p on og.product_id = p.product_id LEFT JOIN ".$GLOBALS['ecs']->table('goods')." as g on p.goods_id = g.goods_id WHERE og.order_id = {$order_id} AND og.product_id>0";
        $res = $GLOBALS['db']->getAll($sql);
        if ($res) {
            $return_has_pro = $this->get_order_goods($res,$has_product=true);
        }
        $sql = "SELECT og.*,g.goods_weight,g.give_integral,g.shop_price FROM ".$GLOBALS['ecs']->table('order_goods')." as og LEFT JOIN ".$GLOBALS['ecs']->table('goods')." as g on og.goods_id = g.goods_id WHERE og.order_id = {$order_id} AND og.product_id=0";
        $res = $GLOBALS['db']->getAll($sql);
        if ($res) {
            $return_no_pro = $this->get_order_goods($res,$has_product=false);
        }
        if ($card) {
            $gift_value[] = $this->get_card_info($order_sn,$card);
        }else{
            $gift_value = array();
        }
        $return_value = array_merge($return_has_pro,$return_no_pro,$gift_value);
        return $return_value;
    }


    function getGoods_old($order_id,$order_sn,$card=false,$use_gift=false){

        $p_real_price = $this->getGoodsInfoByOid($order_id);
        $order_items = $this->get_order_items($order_id);//order_items数据
        $prduct_list = $this->getProductList($order_items,$p_real_price);
        $goods_list = $this->getGoodsList($prduct_list[2]);
        // $sql = "SELECT o.*, IF(o.product_id > 0, p.product_number, g.goods_number) AS storage, o.goods_attr, g.suppliers_id, IFNULL(b.brand_name, '') AS brand_name, p.product_sn
        //     FROM " . $ecs->table('order_goods') . " AS o
        //         LEFT JOIN " . $ecs->table('products') . " AS p
        //             ON p.product_id = o.product_id
        //         LEFT JOIN " . $ecs->table('goods') . " AS g
        //             ON o.goods_id = g.goods_id
        //         LEFT JOIN " . $ecs->table('brand') . " AS b
        //             ON g.brand_id = b.brand_id
        //     WHERE o.order_id = '$order_id'";
        $return_value = array();
        if( !empty($goods_list) ){
            foreach( $goods_list as $k=>$value ){
                $value['orders_bn'] = $value['bn'];
                $value['items_num'] = $prduct_list[1][$value['iid']]['items_num'];
                $value['total_order_fee'] = $this->format_number($prduct_list[1][$value['iid']]['total_order_fee']);
                $value['oid'] = $order_sn;
                $value['status'] = 'TRADE_ACTIVE';
                foreach($prduct_list[0][$value['iid']] as $v ){
                    $v['score'] = 0;
                    $v['item_status'] = 'normal';
                    $v['bn'] = $value['bn'];
                    unset($v['product_id'],$v['goods_id'],$v['goods_attr'],$v['attr_price'],$v['attr_value']);
                    $items[] = $v;
                    $tmp_items[$v['bn']] = $v;
                    $value['sale_price'] = $this->format_number($v['sale_price']);
                    $value['discount_fee'] = $this->format_number($v['discount_fee']);
                }
                $value['type'] = 'goods';
                $value['order_items']['order_item'] = $items;
                unset($value['goods_sn']);
                unset($items);
                $tmp_return_value[$value['iid']] = $value;
            }
            foreach($tmp_return_value as $v) $return_value[]=$v;
            return $return_value;
        }
    }

    //返回订单所有支付单信息
    function get_order_payments($order_id){
        $payment_list = array();
        $sql = 'select p.*,m.`name` as buy_name from sdb_payments as p LEFT JOIN sdb_members as m on m.member_id = p.member_id where p.order_id ='.$order_id.' ';

        foreach($this->db->select($sql) as $data){
            $send_data = array();
            if ($data['status'] == 'succ' || ($data['status'] == 'progress' && $data['pay_type'] == 'online') ){
                $send_data['payment_id']=$data['payment_id'];
                $send_data['tid']=$data['order_id'];
                $send_data['seller_bank']=$data['bank'];
                $send_data['seller_account']=$data['account'];
                $send_data['buyer_id']=$data['member_id'];
                $send_data['buy_name']=$data['buy_name'];
                $send_data['buyer_account']=$data['pay_account'];
                $send_data['pay_fee']=$this->format_number($data['money']);
                $send_data['paycost']=$this->format_number($data['paycost']);
                $send_data['currency']=$data['currency'];
                $send_data['currency_fee']=$this->format_number($data['cur_money']);
                $send_data['pay_type']=$data['pay_type'];
                $send_data['payment_code']=$data['payment'];
                $send_data['payment_name']=$data['paymethod'];
                $send_data['pay_time']=date('Y-m-d H:i:s',$data['t_begin']);
                $send_data['t_begin']=date('Y-m-d H:i:s',$data['t_begin']);
                $send_data['t_end']=date('Y-m-d H:i:s',$data['t_end']);
                $send_data['status']=strtoupper($data['status']);
                $send_data['memo']=$data['memo'];
                $send_data['outer_no']=$data['trade_no'];

                $payment_list['payment_list'][] = $send_data;
            }

        }
        return $payment_list;
    }

    //生成发给taoex 订单结构体
    function getOrderStruct($order_sn,$fields='*',$is_create=FALSE){
        include_once(ROOT_PATH . 'includes/lib_order.php');
        $order_status['status']=array(0=>'TRADE_ACTIVE',1=>'TRADE_ACTIVE',2=>'TRADE_CLOSED',3=>'TRADE_CLOSED',4=>'TRADE_CLOSED',5=>'TRADE_FINISHED',6=>'TRADE_ACTIVE');
        $order_status['ship_status']=array(0=>'SHIP_NO',1=>'SHIP_FINISH',2=>'SHIP_FINISH',3=>'SHIP_NO',4=>'SHIP_PART',5=>'SHIP_PREPARE',6=>'SHIP_PART');
        $order_status['pay_status']=array(0=>'PAY_NO',1=>'PAY_TO_MEDIUM',2=>'PAY_FINISH',3=>'REFUND_PART',4=>'REFUND_ALL');
        $fields = '*'==$fields?$fields:explode(',',$fields);
        //订单总体信息
        if (!$order_info = $this->get_order_info($order_sn)) return '';


        $paramss['tid'] = $order_info['order_sn'];//订单号
        $paramss['trade_valid_time'] = 0;//订单失效时间
        $paramss['out_time'] = '0';//订单失效时间


        $paramss['lastmodify'] = date('Y-m-d H:i:s',$order_info['lastmodify']);
        $paramss['pmt_order'] = '0.00';
        $paramss['pmt_goods'] = '0.00';
        $paramss['promotion_details'][] = array(
            'promotion_name' => '',
            'promotion_fee' => $order_info['discount'],//折扣价减不用减去去包装费用
        );
        $paramss['promotion_details'] = json_encode($paramss['promotion_details']);
        $paramss['total_weight'] = '0';


        $paramss['created'] = date('Y-m-d H:i:s',$order_info['add_time']);//订单创建时间
        // $paramss['modified'] = date('Y-m-d H:i:s',$order_info['add_time']);//订单修改时间，没有则用创建时间
        if(in_array($order_info['pay_status'], array(1,2))){ //订单支付时间 已支付或付款中
            $paramss['pay_time'] = date('Y-m-d H:i:s',$order_info['pay_time']);
        }

        // 订单支付信息详情
        $paramss['payment_lists'] = $this->get_payment_list($order_info);
        $paramss['payment_lists'] = json_encode($paramss['payment_lists']);

        $paramss['status'] = $order_status['status'][$order_info['order_status']];//交易状态
        // $paramss['pay_status'] = $order_status['pay_status'][$order_info['pay_status']];//支付状态
        // 如果是未支付，判断是否是全部退款
        if ($order_info['pay_status'] == '0' && $order_info['pay_time']>0) {
            $sql = "select count(user_money) as user_money from ".$GLOBALS['ecs']->table('account_log')." where user_id = '".$order_info[
                'user_id']."' and user_money>0 and change_desc like '%".$order_info['order_sn']."%'";
            $user_money = $GLOBALS['db']->getOne($sql);
            if (isset($user_money) && $user_money>0) {
                $paramss['pay_status'] = $order_status['pay_status'][4];//退款
            }else{
                $paramss['pay_status'] = $order_status['pay_status'][$order_info['pay_status']];//支付状态
            }
        }else{
            $paramss['pay_status'] = $order_status['pay_status'][$order_info['pay_status']];//支付状态
        }
        $paramss['ship_status'] = $order_status['ship_status'][$order_info['shipping_status']];//发货状态
        $paramss['payed_fee'] = $this->format_number($order_info['surplus'] + $order_info['money_paid']);//已支付金额
        // $paramss['total_goods_fee'] = $order_info['card_fee']?$this->format_number($order_info['goods_amount']+$order_info['card_fee']):$this->format_number($order_info['goods_amount']);//商品总额
        $paramss['total_goods_fee'] = $this->format_number($order_info['goods_amount']);//商品总额
        $total_fee = $order_info['goods_amount'] - $order_info['discount'] - $order_info['goods_discount_fee'] + $order_info['tax'] + $order_info['shipping_fee'] + $order_info['insure_fee'] + $order_info['pay_fee'] + $order_info['pack_fee'] + $order_info['card_fee'];
        $paramss['total_trade_fee'] = $this->format_number($total_fee);//交易总额

        // ECSHOP没有部分支付，如果部分支付，将ecshop的未支付改成部分支付
        if ($paramss['pay_status'] == 'PAY_NO' && $paramss['payed_fee']>0) {
            $paramss['pay_status'] = 'PAY_PART';
        }

        // 获取退款费用
        // if($paramss['payed_fee'] > 0){
        $refund_money = 0;
        $sql = "select action_note from ".$GLOBALS['ecs']->table('order_action')." where order_id = ".$order_info['order_id']." and order_status = 4 ";
        $refund_data = $this->db->getAll($sql);
        if($refund_data){
            foreach ($refund_data as $v) {
                $_refund_money = 0;
                if($pos = strpos($v['action_note'], '部分退款金额：')){
                    $_refund_money = substr($v['action_note'], $pos+21);
                    if(is_numeric($_refund_money)) $refund_money += $_refund_money;
                }
            }
        }
        // $paramss['payed_fee'] = $this->format_number($paramss['payed_fee'] - $refund_money);
        // $paramss['total_trade_fee'] = $this->format_number($paramss['total_trade_fee'] - $refund_money);
        //纯退款
        if($refund_money > 0 and $paramss['total_trade_fee'] >  ( $paramss['payed_fee'] + $order_info['bonus'] + $order_info['integral_money'] ) ){
            if($paramss['payed_fee'] > 0){
                $paramss['pay_status'] = 'REFUND_PART';
                // $paramss['status'] = 'TRADE_ACTIVE';
                $order_info['shipping_status'] == 1 and $paramss['status'] = 'TRADE_FINISHED';//已发货 订单状态就变成完成
            }else{
                $paramss['pay_status'] = 'REFUND_ALL';
                $paramss['status'] = 'TRADE_CLOSED';
            }
        }
        // }

        $sql = "select back_id from ".$GLOBALS['ecs']->table('back_order')." where order_id = ".$order_info['order_id']." AND order_sn='".$order_info['order_sn']."'";
        $back_id = $this->db->getOne($sql);
        if($back_id){
            $paramss['status'] = 'TRADE_FINISHED';
            $paramss['ship_status'] = $order_info['shipping_status']?'RESHIP_PART':'RESHIP_ALL';
        }


        $paramss['currency'] = 'CNY';//货币类型
        $paramss['currency_rate'] = 1;//货别汇率
        $paramss['buyer_obtain_point_fee'] = 0;//获得积分
        $paramss['is_protect'] = $order_info['insure_fee']>0?'true':'false';//是否保价
        $paramss['protect_fee'] = $this->format_number($order_info['insure_fee']);//保价费用
        $paramss['discount_fee'] = $this->format_number((-1)*($order_info['discount']-$order_info['pack_fee']));//折扣优惠金额
        $payment = $this->get_payment($order_info['pay_id']);

        $paramss['is_cod'] = $payment['pay_code']=='cod'?'true':'false';//是否货到付款

        $paramss['payment_tid'] = $order_info['pay_id'];//支付方式ID
        $paramss['payment_type'] = $order_info['pay_name'];//支付方式名名称

        $item = $this->getItemNum($order_info['order_id']);
        $paramss['orders_number'] = $item['itemnum'];//订单商品总数量
        $weight = order_weight_price($order_info['order_id']);
        $paramss['total_weight'] = $weight['weight'];//订单商品总重量
        $memberinfo = $this->getMemberByMid($order_info['user_id']);
        //订单购买者信息
        $paramss['buyer_uname'] = $memberinfo['user_name']?$memberinfo['user_name']:'匿名用户';//账号
        $paramss['buyer_name'] = $memberinfo['alias'];//姓名
        $paramss['buyer_mobile'] = $memberinfo['mobile_phone'];//移动电话
        $paramss['buyer_state'] = '';//省

        //订单收货者信息
        $paramss['receiver_name'] = $order_info['consignee'];//姓名
        $paramss['receiver_phone'] = $order_info['tel'];//固定电话
        $paramss['receiver_mobile'] = $order_info['mobile'];//移动电话
        $paramss['receiver_state'] = $this->get_region($order_info['province'],1);//省
        $paramss['receiver_city'] = $this->get_region($order_info['city'],2);//市
        $paramss['receiver_district'] = $this->get_region($order_info['district'],3);//区
        $paramss['receiver_address'] = $order_info['address'];//详细地区
        $paramss['receiver_zip'] = $order_info['zipcode'];//邮编

        // 红包+积分兑换金额
        $paramss['orders_discount_fee'] = 0;
        $bonus_integral = $order_info['bonus'] + $order_info['integral_money'];
        $orders_discount_fee = $paramss['total_trade_fee'] - $paramss['payed_fee'];

        if($bonus_integral > 0 and $orders_discount_fee > 0 and $bonus_integral >= $orders_discount_fee){
            $paramss['orders_discount_fee'] = $orders_discount_fee;
            $paramss['total_trade_fee'] = $this->format_number($paramss['total_trade_fee'] - $orders_discount_fee);
        }
        // $paramss['orders_discount_fee'] = $order_info['bonus'] + $order_info['integral_money'];

        //订单商品信息
        if($this->_filterParams('orders', $fields)){
            $order_card = array();
            if ($order_info['card_name']) {
                $order_card['card_name'] = $order_info['card_name'];
                $order_card['card_fee'] = $order_info['card_fee'];
                $paramss['discount_fee'] = $this->format_number($paramss['discount_fee']+ $order_card['card_fee']);//折扣优惠金额
            }
            $goods_orders = $this->getGoods($order_info['order_id'],$order_info['order_sn'],$order_card,true);
            $paramss['orders']['order'] = $goods_orders;
            $paramss['orders'] = json_encode($paramss['orders']);
            // $paramss['orders_discount_fee'] = 0;
            $paramss['goods_discount_fee'] = $this->format_number($order_info['goods_discount_fee']);
        }
        //订单配送信息
        $paramss['shipping_tid'] = $order_info['shipping_id'];//物流方式ID
        $paramss['shipping_type'] = $order_info['shipping_name'];//物流方式
        $paramss['shipping_fee'] = $this->format_number($order_info['shipping_fee']);//物流费用

        //订单详细信息
        $paramss['has_invoice'] = $order_info['inv_type'];//是否开发票
        $paramss['invoice_title'] = $order_info['inv_payee'];//发票抬头
        $paramss['invoice_fee'] = $this->format_number($order_info['tax']);//发票税金
        $paramss['pay_cost'] = $this->format_number($order_info['pay_fee']);//支付手续费
        $paramss['buyer_memo'] = $this->buyer_memo($order_info);
        $paramss['trade_memo'] = $order_info['to_buyer'];//订单附言
        $paramss['channel_ver'] = "";
        $paramss['consign_time'] = "";
        $paramss['step_trade_status'] = "";
        $paramss['invoice_desc'] = "";
        $paramss['trade_type'] = "";
        $paramss['buyer_email'] = "";
        $paramss['cod_status'] = "";
        $paramss['step_paid_fee'] = "";
        $paramss['end_time'] = "";
        $paramss['channel'] = "fast";
        $paramss['order_source'] = $order_info['referer'];//创建订单接口增加订单类型参数

        $paramss['logistics_no'] = $order_info['invoice_no'];

        $allow_params = array('rights_level','lastmodify','payment_lists','promotion_details','total_weight','buyer_name','currency_rate','app_id','shipping_type','receiver_address','has_invoice','receiver_district','from_type','callback_type','protect_fee','receiver_phone','to_node_id','order_source','logistics_no','pay_cost','buyer_uname','timestamp','_id','tid','receiver_mobile','goods_discount_fee','orders_number','invoice_fee','discount_fee','pay_status','buyer_obtain_point_fee','payment_type','v','real_time','shipping_fee','refresh_time','is_cod','msg_id','currency','node_type','pay_time','payment_tid','orders','receiver_city','channel_ver','orders_discount_fee','format','buyer_memo','from_node_id','shipping_tid','method','channel','status','total_trade_fee','buyer_state','receiver_zip','callback_type_id','to_type','node_id','total_goods_fee','date','buyer_mobile','task','created','ship_status','payed_fee','is_protect','receiver_state','receiver_name','consign_time','step_trade_status','trade_memo','invoice_desc   ','invoice_title','trade_type','buyer_email','cod_status','step_paid_fee','modified','end_time');

        foreach($paramss as $k=>$v){
            if(!in_array($k, $allow_params)) unset($paramss[$k]);
        }
        return $paramss;
    }


    function get_payment_list($order_info){
        $payment_list = array();
        $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('account_log')." WHERE change_desc like '%".$order_info['order_sn']."%' AND user_money<0 order by change_time asc";
        $res = $GLOBALS['db']->getAll($sql);
        if ($res) {
            foreach ($res as $key => $list) {
                $payment_list['payment_list'][] = array(
                    'tid' => $order_info['order_sn'],
                    'seller_bank' => '',
                    'seller_account' => '',
                    'buyer_id' => $_SESSION['user_id'],
                    'buy_name' => $_SESSION['user_name'],
                    'buyer_account' => '',
                    'pay_fee' => abs($list['user_money']),
                    'paycost' => abs($list['user_money']),
                    'currency' => 'CNY',
                    'currency_fee' => abs($list['user_money']),
                    'pay_type' => 'deposit',
                    'payment_code' => 'deposit',
                    'payment_name' => $order_info['pay_name'],
                    't_begin' => date('Y-m-d H:i:s',$list['change_time']),
                    't_end' => date('Y-m-d H:i:s',$list['change_time']),
                    'pay_time' => date('Y-m-d H:i:s',$list['change_time']),
                    'status' => 'SUCC',
                    'memo' => '',
                    'outer_no' => '',
                );
            }
        }
        $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('account_other_log')." WHERE order_sn='".$order_info['order_sn']."'";
        $pay_list = $GLOBALS['db']->getAll($sql);
        if ($pay_list) {
            foreach ($pay_list as $list) {
                $payment_list['payment_list'][] = array(
                    'tid' => $order_info['order_sn'],
                    'seller_bank' => '',
                    'seller_account' => '',
                    'buyer_id' => $_SESSION['user_id'],
                    'buy_name' => $_SESSION['user_name'],
                    'buyer_account' => '',
                    'pay_fee' => abs($list['money']),
                    'paycost' => abs($list['money']),
                    'currency' => 'CNY',
                    'currency_fee' => abs($list['money']),
                    'pay_type' => $list['pay_type']?$list['pay_type']:'deposit',
                    'payment_code' => $list['pay_type']?$list['pay_type']:'deposit',
                    'payment_name' => $order_info['pay_name'],
                    't_begin' => date('Y-m-d H:i:s',$list['pay_time']),
                    't_end' => date('Y-m-d H:i:s',$list['pay_time']),
                    'pay_time' => date('Y-m-d H:i:s',$list['pay_time']),
                    'status' => 'SUCC',
                    'memo' => '',
                    'outer_no' => '',
                );
            }
        }
        return $payment_list;
    }


    function buyer_memo($order_info){
        $buyer_memo = '缺货处理:'.$order_info['how_oos'];
        // 包装
        if ($order_info['pack_name']) {
            $buyer_memo .= " 使用包装:（".$order_info['pack_name']."）；";
        }
        if ($order_info['card_name']) {
            $buyer_memo .= " 贺卡:（".$order_info['card_name']."），祝福语:（".$order_info['card_message']."）；";
        }
        if ($buyer_memo && $order_info['postscript']) {
            $buyer_memo .= "以下是客户留言:";
        }

        return $buyer_memo.$order_info['postscript'];
    }


    function get_region($region_id,$region_type){
        $msg = '';
        $sql = "select region_name FROM ".$GLOBALS['ecs']->table('region')." WHERE region_id = {$region_id} AND region_type = {$region_type}";
        $msg = $GLOBALS['db']->getOne($sql);
        if ($msg) {
            return $msg;
        }else{
            return $region_id;
        }
    }

    function _filterParams($needle,$haystack){
        if('*'==$haystack) return true;
        return in_array($needle,$haystack);
    }

    //签名
    function get_matrix_sign($params,$token){
        //如果参数是数组的话将参数json
        foreach($params as $k=>$v){
            if(is_array($v)){
                $params[$k] = json_encode($v);
            }
        }
        return strtoupper(md5(strtoupper(md5($this->assemble($params))).$token));
    }

    function assemble($params){
        if(!is_array($params))  return null;
        ksort($params,SORT_STRING);
        $sign = '';
        foreach($params AS $key=>$val){
            $sign .= $key . (is_array($val) ? $this->assemble($val) : $val);
        }
        return $sign;
    }

    function create_task_id(){
        $i = rand(0,9999);
        if(9999==$i){
            $i=0;
        }
        $task_id = time().str_pad($i,4,'0',STR_PAD_LEFT);
        return $task_id;
    }

    function format_number($number){
        return number_format($number, 2, '.', '');

    }

    function getMemberByMid($user_id,$cols='*'){
        $sql = "SELECT ".$cols." FROM ".$this->ecs->table('users')." WHERE user_id = ".$user_id;
        return $this->db->getRow($sql);
    }

    // 取消订单
    function set_dead_order($order_id){
        if(!$this->get_bind_info($this->shopex_app)) return null;
        $sql = "update ".$this->ecs->table('order_info')." set lastmodify = ".time().",order_status = ".OS_CANCELED." where order_id = ".$order_id;
        $this->db->query($sql);
        $sql = "SELECT order_sn FROM ".$this->ecs->table('order_info')." WHERE order_id = ".$order_id;
        $order_sn = $this->db->getOne($sql);
        $this->createOrder($order_sn);
    }

    // 更新订单买家留言
    function update_order_buyer_message($data){
        if(!$this->get_bind_info($this->shopex_app)) return null;
        include_once(ROOT_PATH.'includes/cls_certificate.php');
        $cert = new certificate();
        $params['tid'] = $data['order_id'];//订单号
        $params['message'] = $data['msg_content'];//留言内容
        $params['title'] = $data['msg_title'];//标题
        $params['sender'] = $data['user_name']?$data['user_name']:'system';//'system';//发送者
        $params['add_time'] = date('Y-m-d H:i:s',time());//添加时间

        $params['method'] = 'store.trade.buyer_message.add';
        $params['callback_type'] = 'UPDATEORDERMESSAGE';
        $params['callback_type_id'] = $params['tid'];
        foreach($this->shopex_app as $k){
            error_log('-------kkk-------',3,__FILE__.".log");
            error_log(var_export($k,1),3,__FILE__.".log");
            if($cert->is_bind_sn($k,'bind_type')){
                error_log('-------kkkkk-------',3,__FILE__.".log");
                error_log(var_export($k,1),3,__FILE__.".log");
                $this->http_request_matrix($params,$k);
            }
        }
    }

    // 退款通知到erp
    function send_refund_to_matrix($msg){
        if(!$this->get_bind_info($this->shopex_app)) return null;
        $msg['method'] = 'store.trade.refund.add';
        $msg['callback_type'] = 'CREATEREFUND';
        $msg['callback_type_id'] = $msg['refund_id'];
        $this->http_request_matrix($msg);
    }

    // 退款通知到crm
    function send_refund_to_crm($msg){
        if(!$this->get_bind_info($this->shopex_app)) return null;
        $msg['method'] = 'store.trade.refund.add';
        $msg['callback_type'] = 'CREATEREFUND';
        $msg['callback_type_id'] = $msg['refund_id'];
        error_log(var_export($msg,1),3,__FILE__.".log");
        $this->http_request_matrix($msg,'ecos.taocrm');
    }




}
?>
