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
include_once(ROOT_PATH."includes/cls_certificate.php");
$uri = $ecs->url();
$allow_suffix = array('gif', 'jpg', 'png', 'jpeg', 'bmp');

/*------------------------------------------------------ */
//-- 移动端应用配置
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'list')
{
    /* 检查权限 */
    admin_priv('wxa_setting');
    $smarty->assign('ur_here', $_LANG['wxa_setting']);
    $auth_sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = "authorize"';
    $auth = $GLOBALS['db']->getRow($auth_sql);
    $params = unserialize($auth['value']);
    if($params['authorize_code'] != 'NDE'){
        $url = $params['authorize_code']=='NCH'?'http://account.shopex.cn/order/confirm/goods_2460-946 ':'https://account.shopex.cn/order/confirm/goods_2540-1050 ';
        $smarty->assign('url', $url);
        $smarty->display('accredit.html');
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
    $sql =  "SELECT * FROM " . $ecs->table('config')." WHERE 1";
    $group_items = $db->getAll($sql);
    $grouplist = get_params();
    foreach($grouplist as $key => $value){
        foreach($value['items'] as $k => $v){
            foreach($group_items as $item){
                if($item['code'] == $v['code']){
                    $config = json_decode($item['config'],1);
                    foreach($v['vars'] as $var_k => $var_v){
                        $grouplist[$key]['items'][$k]['vars'][$var_k]['value'] =$config[$var_v['code']];
                    }
                }
            }

        }
    }

    assign_query_info();

    $smarty->assign('group_list',$grouplist);
    $smarty->display('wxa_config.html');
}elseif($_REQUEST['act']== 'post'){
    /* 检查权限 */
    admin_priv('mobile_setting');
    $links[] = array('text' => $_LANG['wxa_setting'], 'href' => 'wxa_setting.php?act=list');

    foreach($_POST['value'] as $key => $value){
        $_POST['value'][$key] = trim($value);
    }
    if(!empty($_FILES['value']['name'])){
        foreach($_FILES['value']['name'] as $k => $v){
            if($v){
                $cert = $_FILES['value']['tmp_name']['cert'];
                $PSize = filesize($cert);
                $cert_steam = (fread(fopen($cert, "r"), $PSize));
                $cert_steam = addslashes($cert_steam);
                $_POST['value']['cert'] =  $_FILES['value']['name']['cert'];
            }else{
                sys_msg('证书不能为空', 1, $links);
            }
        }
    }
    $sql = "SELECT * FROM " . $ecs->table('config')." WHERE `code` = '".$_POST['code']."'";
    $res = $db->getRow($sql);
    $items = get_items($_POST['code']);

    $type = $items['type'];
    $name = $items['name'];
    $code = $items['code'];
    $description = $items['description'];
    $config = json_encode($_POST['value']);
    $status = $_POST['value']['status'];
    $time = date('Y-m-d H:i:s',time());

    if($res){
        $sql = "UPDATE ".$ecs->table('config')." SET `updated_at` = '$time',`status` = '$status' ,`config` = '$config' WHERE `code` = '$code'";
    }else{
        $sql = "INSERT INTO ".$ecs->table('config')." (`name`,`type`,`description`,`code`,`config`,`created_at`,`updated_at`,`status`) VALUES ('$name','$type','$description','$code','$config','$time','$time','$status')";
    }
    $db->query($sql);

    if($cert_steam){
        //处理文件
        $sql = "SELECT * FROM " . $ecs->table('config')." WHERE `code` = '".$_POST['code']."'";
        $setting = $db->getRow($sql);
        if($setting['id']){
            $id = $setting['id'];
            $cert_tmp = $db->getRow("SELECT * FROM " . $ecs->table('cert')." WHERE `config_id` = '$id'");
            if($cert_tmp){
                $db->query("UPDATE ".$ecs->table('cert')." SET `file` = '$cert_steam' WHERE `config_id` = '$id'");
            }else{
                $db->query("INSERT INTO ".$ecs->table('cert')." (`config_id`,`file`) VALUES ($id,'$cert_steam')");
            }
        }
    }
    sys_msg($_LANG['attradd_succed'], 0, $links);
}

function get_items($code){
    $params = get_params();
    foreach($params as $value){
        foreach($value['items'] as $val){
            if($val['code'] == $code)return $val;
        }
    }
}

function get_params(){

    $grouplist = array(
        0 => array(
            'name' => '小程序登陆配置',
            'code' => 'oauthwxa',
            'items' => array(
                0 => array(
                    'title' => '小程序登陆配置',
                    'submit' => '?act=post',
                    'url' => 'https://pay.weixin.qq.com',
                    'type' => 'oauth',
                    'name' => '小程序登陆配置',
                    'description' => '小程序登陆配置',
                    'code' => 'wechat.wxa',
                    'vars' => array(
                        0 => array(
                            'type' => 'radio',
                            'name' => '是否开启',
                            'code' => 'status',
                            'value' => '',
                        ),
                        1 => array(
                            'type' => 'text',
                            'name' => 'APP_ID',
                            'code' => 'app_id',
                            'value' => '',
                        ),
                        2 => array(
                            'type' => 'text',
                            'name' => 'APP_Secret',
                            'code' => 'app_secret',
                            'value' => '',
                        ),
                        3 => array(
                            'type' => 'text',
                            'name' => 'Cert',
                            'code' => 'cert',
                            'value' => '',
                        ),
                    ),
                ),
            ),
        ),
        1 => array(
            'name' => '小程序支付',
            'code' => 'paymentwxa',
            'items' => array(
                0 => array(
                    'title' => '小程序支付',
                    'submit' => '?act=post',
                    'url' => 'https://pay.weixin.qq.com',
                    'type' => 'payment',
                    'name' => '微信公号支付',
                    'description' => '小程序登陆配置',
                    'code' => 'wxpay.wxa',
                    'vars' => array(
                        0 => array(
                            'type' => 'radio',
                            'name' => '是否开启',
                            'code' => 'status',
                            'value' => '',
                        ),
                        1 => array(
                            'type' => 'text',
                            'name' => 'APP_ID',
                            'code' => 'app_id',
                            'value' => '',
                        ),
                        2 => array(
                            'type' => 'text',
                            'name' => 'APP_Secret',
                            'code' => 'app_secret',
                            'value' => '',
                        ),
                        3 => array(
                            'type' => 'text',
                            'name' => 'MCH_ID',
                            'code' => 'mch_id',
                            'value' => '',
                        ),
                        4 => array(
                            'type' => 'text',
                            'name' => 'MCH_Key',
                            'code' => 'mch_key',
                            'value' => '',
                        ),
                        5 => array(
                            'type' => 'text',
                            'name' => 'Cert',
                            'code' => 'cert',
                            'value' => '',
                        ),
                    ),
                ),
            ),
        ),
    );
    return $grouplist;
}

?>
