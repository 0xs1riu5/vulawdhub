<?php
/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: wangleisvn $
 * $Id: lead.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require('leancloud_push.php');
include_once(ROOT_PATH."includes/cls_certificate.php");
$platform = array(1 => 'iOS' , 2 => 'Android',3 => '全平台');
$status = array(1 => '等待中',2 => '已发送');//数据库0：等待中，1：已发送
/*------------------------------------------------------ */
//-- 移动端应用配置
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'list'){
    /* 检查权限 */
    admin_priv('leancloud');
    $cert = new certificate;
    $isOpenWap = $cert->is_open_sn('fy');
    if($isOpenWap==false && $_SESSION['yunqi_login'] && $_SESSION['TOKEN'] ){
        $result = $cert->getsnlistoauth($_SESSION['TOKEN'] ,array());
        if($result['status']=='success'){
            $cert->save_snlist($result['data']);
            $isOpenWap = $cert->is_open_sn('fy');
        }
    }
    $tab = !$isOpenWap ? 'open' : 'enter';
    $charset = EC_CHARSET == 'utf-8' ? "utf8" : 'gbk';
    $push_list = get_list(1,$db,$ecs);
    $count = $push_list['count'];
    unset($push_list['count']);
    $order_by = array('ORDER BY created_at ASC' => '创建时间从新到旧','ORDER BY created_at DESC' => '创建时间从旧到新','ORDER BY push_at ASC' => '推送时间从新到旧','ORDER BY push_at DESC' => '推送时间从旧到新');
    $filter= array('page' => '1','page_size' => '10','page_count' => ceil($count/10),'record_count' =>$count,'status' => '0','platform' => '0','title' => '','order_by' =>'0');
    $smarty->assign('filter',$filter);
    $smarty->assign('order_by',$order_by);
    $smarty->assign('platform',$platform);
    $smarty->assign('status',$status);
    $smarty->assign('ur_here',$_LANG['leancloud']);
    $smarty->assign('record_count',$count);
    $smarty->assign('page_count',ceil($count/10));
    $smarty->assign('push_list',$push_list);
    $smarty->assign('full_page',1);

    $smarty->display('leancloud.html');
}elseif($_REQUEST['act']== 'edit'){
    /* 检查权限 */
    admin_priv('leancloud');
    $config = get_config($db,$ecs);
    if (!$config || !json_decode($config['config'],true)) {
        $links[] = array('text' => $_LANG['mobile_setting'], 'href' => 'ecmobile_setting.php?act=list');
        sys_msg($_LANG['push_off'], 1, $links);
        exit;
    }
    $cert = new certificate;
    $isOpenWap = $cert->is_open_sn('fy');
    if($isOpenWap==false && $_SESSION['yunqi_login'] && $_SESSION['TOKEN'] ){
        $result = $cert->getsnlistoauth($_SESSION['TOKEN'] ,array());
        if($result['status']=='success'){
            $cert->save_snlist($result['data']);
            $isOpenWap = $cert->is_open_sn('fy');
        }
    }
    $tab = !$isOpenWap ? 'open' : 'enter';
    $charset = EC_CHARSET == 'utf-8' ? "utf8" : 'gbk';
    if($_GET['id']){
        $params = getrow($_GET['id'],$db,$ecs);
        $smarty->assign('params',$params);
    }
    $links = get_url();
    $push_type = array(0 => '立即发送',1 => '定时发送');
    $smarty->assign('ur_here',$_LANG['leancloud']);
    $smarty->assign('platform',$platform);
    $smarty->assign('links',$links);
    $smarty->assign('push_type',$push_type);
    $smarty->display('leancloud_edit.html');
}elseif($_REQUEST['act']== 'remove'){
    /* 检查权限 */
    admin_priv('leancloud');
    $id = intval($_GET['id']);
    $sql = "DELETE FROM " .$ecs->table('push'). " WHERE id = '$id'";
    $res = $db->query($sql);
    admin_log('', 'remove', 'leancloud');
    $url = 'leancloud.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");
    exit;

}elseif($_REQUEST['act']== 'do_edit'){
    /* 检查权限 */
    admin_priv('leancloud');
    $links[] = array('text' => $_LANG['mobile_setting'], 'href' => 'leancloud.php?act=list');
    $params = $_POST['msg'];
//    echo'<pre>';print_r($params);exit;
    $time = date('Y-m-d H:i:s');
    if($params['push_time'] && $params['push_type'] == '1'){
        $push_at =str_replace('T',' ',$params['push_time']);
    }else{
        $push_at=$time;
    }
    if($params['link_type'] && $params['link_arg']){
        $link = $params['link'];
        if($params['link_type']=='key_words')$link=str_replace('?k=关键字','?k='.$params['link_arg'],$params['link']);
        if($params['link_type']=='id')$link=str_replace(':id',':'.$params['link_arg'],$params['link']);
    }else{
        $link = '';
    }
    $content = $params['content'];
    $title = $params['title'];
    $platform = $params['platform'];
    $send_type = $params['send_type'];
    if($params['id']){
        $id = $params['id'];
        $sql = "UPDATE ".$ecs->table('push')." SET `content`='$content',`title`='$title',`link`='$link',`platform`='$platform',`push_type`='$send_type',`push_at`='$push_at',`updated_at`='$time',`isPush`='0' WHERE id = $id";
    }else{
        $sql ="INSERT INTO ".$ecs->table('push')." (`title`,`content`,`link`,`platform`,`push_type`,`message_type`,`push_at`,`created_at`,`updated_at`) VALUES ('$title','$content','$link','$platform','$push_type','1','$push_at','$time','$time')";
    }
    $res = $db->query($sql);
    if(!$id&& $res){
        $id = $db->getRow("SELECT id FROM ".$ecs->table('push')." ORDER BY `id` DESC LIMIT 1");
    }
    $is_push = push($id,$db,$ecs);
    sys_msg($_LANG['attradd_succed'], 0, $links);
}elseif($_REQUEST['act']== 'resend'){
    /* 检查权限 */
    admin_priv('leancloud');
    $id = $_GET['id'];
    $is_push = push($id,$db,$ecs);
    $is_push =$is_push?1:0;
    $time = date('Y-m-d H:i:s',time());
    $sql = "UPDATE ".$ecs->table('push')." SET `push_at`='$time',`updated_at`='$time',`isPush`='$is_push' WHERE id = $id";
    $db->query($sql);
    $links[] = array('text' => $_LANG['mobile_setting'], 'href' => 'leancloud.php?act=list');
    sys_msg($_LANG['attradd_succed'], 0, $links);
}elseif($_REQUEST['act']== 'query') {
    /* 检查权限 */
    admin_priv('leancloud');
    $_POST = array_merge($_POST,$_GET);
    if ($_POST['platform'] && $_POST['platform'] != '0') $filter['platform'] = $_POST['platform'];
    if ($_POST['status'] != '0')$filter['isPush'] = $_POST['status']-1;
    if ($_POST['title'] && $_POST['title'] != '' && $_POST['title'] != '请输入标题') $filter['title'] = $_POST['title'];
    $isfirst = true;
    $filter_sql = '';
    if ($filter) {
        foreach ($filter as $k => $v) {
            if ($isfirst) {
                if ($k == 'title') {
                    $filter_sql .= $k . " LIKE '%" . $v . "%'";
                } else {
                    $filter_sql .= $k . "='" . $v . "'";
                }
                $isfirst = false;
            } else {
                if ($k == 'title') {
                    $filter_sql .= " AND " . $k . " LIKE '%" . $v . "%' ";
                } else {
                    $filter_sql .= " AND " . $k . "='" . $v . "' ";
                }
            }

        }
    } else {
        $filter_sql = '1 ';
    }
    $sql = "SELECT count(*) as count FROM " . $ecs->table('push') . " WHERE " . $filter_sql;
    $count = $db->getAll($sql);
    $count = $count[0]['count'];
    $page = $_POST['page'];
    $page_size = $_POST['page_size']?$_POST['page_size']:$_COOKIE['ECSCP']['page_size'];
    $order_by = $_POST['order_by']?$_POST['order_by']:' ORDER BY id DESC ';
    $page_count = ceil($count / $page_size);
    $start = ($page - 1) * $page_size;
    $end = $page_size;
    $sql = "SELECT * FROM " . $ecs->table('push') . " WHERE " . $filter_sql . $order_by . " LIMIT $start,$end";
    $push_list = $db->getALL($sql);
    foreach($push_list as $k => $v){
        $push_list[$k]['isPush']++;
    }
    $filter['page_size'] = $page_size;
    $filter['page_count'] = $page_count;
    $filter['page'] = $page;
    $filter['record_count'] =$count;
    $filter['title'] =$_POST['title'];
    $filter['platform'] =$_POST['platform'];
    $filter['order_by'] =$_POST['order_by'];
    $filter['status'] =$_POST['status'];

    $smarty->assign('platform',$platform);
    $smarty->assign('status',$status);
    $smarty->assign('push_list',    $push_list);
    $smarty->assign('filter',       $filter);
    $smarty->assign('record_count', $count);
    $smarty->assign('page_count',   $page_count);

    make_json_result($smarty->fetch('leancloud.html'), '', array('filter' => $filter, 'page_count' => $page_count));
}elseif($_REQUEST['act'] == 'batch_remove'){
    /* 检查权限 */
    admin_priv('leancloud');
//    echo'<pre>';print_r($_POST);exit;
    $items = $_POST['checkboxes'];
    foreach($items as $v){
        $id = intval($v);
        $sql = "DELETE FROM " .$ecs->table('push'). " WHERE id = '$id'";
        $res = $db->query($sql);
    }
    admin_log('', 'remove', 'leancloud');
    $links[] = array('text' => $_LANG['mobile_setting'], 'href' => 'leancloud.php?act=list');
    sys_msg($_LANG['attradd_succed'], 0, $links);
    exit;
}

function get_list($page,$db,$ecs){
    $sql =  "SELECT COUNT(*) FROM " . $ecs->table('push');
    $count = $db->getONE($sql);
    $start = ($page-1)*10;
    $end = 10;
    $sql =  "SELECT * FROM " . $ecs->table('push')." WHERE 1 ORDER BY id DESC LIMIT $start,$end";
    $push_list = $db->getAll($sql);
    foreach($push_list as $k => $v){
        $push_list[$k]['isPush']++;
    }
    $push_list['count'] = $count;
    return $push_list?$push_list:false;
}

function getrow($id,$db,$ecs){
    $sql =  "SELECT * FROM " . $ecs->table('push') . " WHERE id = $id";
    $push_list = $db->getAll($sql);
    $params = $push_list[0];
    if($params['link']){
        $link = get_linkcode($params['link']);
        $params['link_code'] = $link['link'];
        $params['link_type'] = $link['arg_type'];
        $params['link_value'] = $link['arg_value'];
    }
    return $params;
}

function get_linkcode($link){
    $links = get_url();
    $link_temp = explode(':',$link);
    $link = $link_temp[2]?$link_temp[0].':'.$link_temp[1].':id':$link;
    if($link_temp[2]){
        $params['arg_type'] = 'id';
        $params['arg_value'] = $link_temp[2];
        unset($link_temp);
    }
    $link_temp = explode('?k=',$link);
    $link = $link_temp[1]?$link_temp[0].'?k=关键字':$link;
    if($link_temp[1]){
        $params['arg_type'] = 'key_words';
        $params['arg_value'] = $link_temp[1];
    }
    $params['link'] = $link;
    return $params;
}

function get_url(){
    $url_list = array(
        'user-defined' => '自定义链接',
        'deeplink://goto/index' => '商城首页',
        'deeplink://goto/cart' => '购物车',
        'deeplink://goto/search' =>'搜索界面',
        'deeplink://goto/category/all' => '分类列表',
        'ecnative://goto/shop/all' => '店铺列表',
        'ecnative://goto/brand/all' => '品牌列表',
//        'deeplink://goto/notice/all' => '公告列表',
        'deeplink://goto/product/all' => '商品列表',
//        'deeplink://goto/product/:id' => '商品详情' ,
        'deeplink://goto/scanner' => '二维码界面',
        'deeplink://goto/home' => '个人中心',
        'deeplink://goto/setting' => '系统设置',
        'deeplink://goto/cardpage/index' => '卡片页详情',
        'deeplink://goto/profile' => '个人资料',
        'deeplink://goto/address/all' => '收货地址列表',
//        'deeplink://goto/address/new' => '新建收货地址',
//        'deeplink://goto/address/:id' =>  '编辑收货地址',
        'deeplink://goto/order/all' => '订单列表',
//        'deeplink://goto/order/created' => '代付款订单',
//        'deeplink://goto/order/paid' => '待发货订单',
//        'deeplink://goto/order/delivering' => '发货中订单',
//        'deeplink://goto/order/delivered' => '待评价订单',
//        'deeplink://goto/order/finished' => '已完成订单',
//        'deeplink://goto/order/cancelled' => '已取消订单',
//        'deeplink://goto/order/:id' => '订单详情',
//        'ecnative://goto/favorite/shop' => '我收藏的店铺',
        'deeplink://goto/favorite/product' => '我收藏的商品',
        'deeplink://goto/message/all' => '消息列表',
        'deeplink://goto/orderMessage/all' => '订单消息列表',
        'deeplink://goto/cashgift/available' => '未使用红包列表',
//        'deeplink://goto/cashgift/expired' => '已过期红包列表',
//        'deeplink://goto/cashgift/used' => '已使用红包列表',
        'deeplink://goto/coupon/available' =>  '未使用优惠券列表',
//        'deeplink://goto/coupon/expired' => '已过期优惠券列表',
//        'deeplink://goto/coupon/used' => '已使用优惠券列表',
        'deeplink://goto/shipping/:id' => '物流详情页面',
        'deeplink://goto/score/all' => '全部积分',
//        'deeplink://goto/score/income' => '收入积分',
//        'deeplink://goto/score/expenditure' =>  '支出积分',
        'deeplink://goto/article' => '文章列表页面',
        'deeplink://goto/invoice' => '发票页面',
//        'deeplink://search/shop?k=关键字' => '店铺搜索',
        'deeplink://search/product?k=关键字' => '商品搜索',
    );
    return $url_list;
}

?>