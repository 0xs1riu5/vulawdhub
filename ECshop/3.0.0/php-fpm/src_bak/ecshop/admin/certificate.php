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
 * $Id: certificate.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
/*------------------------------------------------------ */
//-- 授权绑定编辑页
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'list_edit')
{
    /* 检查权限 */
    admin_priv('certificate');
    /* 检查是否绑定了淘打 */
    $is_bind_taoda = $cert->is_bind_sn('taodali','bind_type');
    $is_bind_erp = $cert->is_bind_sn('ecos.ome','bind_type');
    $is_bind_crm = $cert->is_bind_sn('ecos.taocrm','bind_type');
    //echo"<pre>";var_dump($is_bind_crm,$is_bind_erp,$is_bind_taoda);exit;
    $is_bind_taoda or $is_bind_erp and $smarty->assign('is_bind',true);
    $is_bind_crm and $smarty->assign('is_bind_crm',true);
    
    if(!$certificate['certificate_id']){
        $callback = $GLOBALS['ecs']->url()."admin/certificate.php?act=get_certificate";
        $iframe_url = $cert->get_authorize_url($callback);
        $smarty->assign('iframe_url',$iframe_url);
    }
    //版本检查
    $release_url = VERSION_UTF8;
//    if(strtoupper(EC_CHARSET) == 'GBK') $release_url = VERSION_GBK;
    $_content = file_get_contents($release_url);
    $version_all = array_filter(explode("\n",$_content));
    $message = "您现在已经是最新版本了,当前版本:ECSHOP ".EC_CHARSET." ".VERSION;
    $app_version = get_appserver_verison();
    $h5_version = get_h5_version();
    foreach($version_all as $v){
        $item = json_decode($v,1);
        if(intval($item['date']) > intval(RELEASE)){
            $message = "您现在不是最新版本，新版本(".$item['version']."-".$item['date'].")下载地址：<a href='".$item['url']."' target='_blank'>".$item['url']."</a>";
            break;
        }
        if( $h5_version && ( $h5_version < intval(str_replace(array('.','v'),'',$item['h5_version'])))){
            $message = "您的H5现在不是最新版本，新版本(".$item['version']."-".$item['date'].")下载地址：<a href='".$item['url']."' target='_blank'>".$item['url']."</a>";
            break;
        }
        if( $app_version && ( $app_version < intval(str_replace(array('.','v'),'',$item['app_version'])))){
            $message = "您的APPSERVER现在不是最新版本，新版本(".$item['version']."-".$item['date'].")下载地址：<a href='".$item['url']."' target='_blank'>".$item['url']."</a>";
            break;
        }
    }

    //crm历史同步数据
    $bind_crm_member_push = $cert->get_push_count('bind_crm_member_push');
    $bind_crm_order_push = $cert->get_push_count('bind_crm_order_push');
    $order_count =$cert->crm_get_count('order');
    $member_count = $cert->crm_get_count('member');
    if($bind_crm_member_push>$member_count)$bind_crm_member_push=$member_count;
    if($bind_crm_order_push>$order_count)$bind_crm_order_push=$order_count;
    $bind_crm_member_push_no = $member_count-$bind_crm_member_push;
    $bind_crm_order_push_no = $order_count-$bind_crm_order_push;
    
    $smarty->assign('certi',$certificate);
    $smarty->assign('ur_here', $_LANG['certificate_here']);
    $smarty->assign('bind_crm_member_push',$bind_crm_member_push);
    $smarty->assign('bind_crm_order_push',$bind_crm_order_push);
    $smarty->assign('bind_crm_member_push_no',$bind_crm_member_push_no);
    $smarty->assign('bind_crm_order_push_no',$bind_crm_order_push_no);
    $smarty->assign('message',$message);
    $smarty->display('certificate.htm');
}


/*------------------------------------------------------ */
//-- 申请绑定矩阵
/*------------------------------------------------------ */

elseif($_REQUEST['act']=='apply_bindrelation')
{
    /* 检查权限 */
    admin_priv('certificate');
    /* 检查是否绑定了淘打 */
    if($cert->is_bind_sn('taodali','bind_type')){
        echo "已经绑定了淘打，不能再进行绑定";
    }
    $domain_url = $ecs->url();
    $array_data['certi_id'] = $certificate['certificate_id'];
    $array_data['node_id'] = $certificate['node_id'];
    $array_data['sess_id'] = md5($array_data['node_id']);
    $array_data['certi_ac'] = $cert->make_shopex_ac($array_data,$certificate['token']);
    $array_data['source'] = 'apply';
    $array_data['bind_type'] = 'shopex';
    $array_data['api_url'] = $ecs->url()."api.php";
    foreach ($array_data as $str_key=>$str_value) {
        $array_params[] = $str_key."=".rawurlencode($str_value);
    }
    $str_url = MATRIX_REALTION_URL.implode("&", $array_params);
    $callback = urlencode($domain_url."matrix_callback.php");
    $smarty->assign('callback',$callback);
    $smarty->assign('str_url',$str_url);
    $smarty->display('apply_bindrelation.htm');
}

/*------------------------------------------------------ */
//-- 查看绑定关系
/*------------------------------------------------------ */

elseif($_REQUEST['act']=='accept_bindrelation')
{
    /* 检查权限 */
    admin_priv('certificate');
    $domain_url = $ecs->url();
    $array_data=array();

    $array_data['certi_id'] = $certificate['certificate_id'];
    $array_data['node_id'] = $certificate['node_id'];
    $array_data['sess_id'] = md5($array_data['node_id']);
    $array_data['certi_ac'] = $cert->make_shopex_ac($array_data,$certificate['token']);
    $array_data['source'] = 'accept';
    $array_data['api_url'] = $ecs->url()."api.php";
    $array_data['callback'] = $domain_url."matrix_callback.php";

    foreach ($array_data as $str_key=>$str_value) {
        $array_params[] = $str_key."=".rawurlencode($str_value);
    }
    $str_url = MATRIX_REALTION_URL.implode("&", $array_params);
    $smarty->assign('str_url',$str_url);
    $smarty->display('accept_bindrelation.htm');
}

/*------------------------------------------------------ */
//-- 证书下载
/*------------------------------------------------------ */

elseif ($_REQUEST['act']== 'download')
{
    /* 检查权限 */
    admin_priv('certificate');

    if ($certificate['certificate_id'] == '' || $certificate['token'] == '')
    {
        $links[] = array('text' => $_LANG['back'], 'href' => 'certificate.php?act=list_edit');
        sys_msg($_LANG['no_license_down'], 0, $links);
    }
    /* 文件下载 */
    ecs_header("Content-Type:text/plain");
    ecs_header("Accept-Ranges:bytes");
    ecs_header("Content-Disposition: attachment; filename=CERTIFICATE.CER");
    echo $certificate['certificate_id'] . '|' . $certificate['token'];
    exit;
}
/*------------------------------------------------------ */
//-- 获取云起认证
/*------------------------------------------------------ */
elseif($_REQUEST['act']=='get_certificate'){
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    if(isset($_GET['code']) && $_GET['code']){
        $code = $_GET['code'];
        $rs = $cert->oauth_set_callback($code,$res);
        $_SESSION['TOKEN'] = $res['token'];
        $base_url = $GLOBALS['ecs']->url();
        if($_GET['type']=='index'){
            $url = $base_url."admin/index.php";
        }else{
            $url = $base_url."admin/certificate.php?act=list_edit";
        }
        echo '<script type="text/javascript">parent.location.href="'.$url.'";</script>';exit;
    }
}
elseif($_REQUEST['act']=='authority_url'){
   $authority_url = $cert->get_authority_url();
    $smarty->assign('authority_url',$authority_url);
    $smarty->display('authority.htm');
}
elseif($_REQUEST['act']=='delete'){
    /* 检查权限 */
    admin_priv('certificate');
    $callback = $GLOBALS['ecs']->url()."admin/certificate.php?act=list_edit";
    $url = $cert->logout_url($callback);
    if(!$cert->delete_cert($msg)){
        sys_msg($msg, 1);
    }
    echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';exit;
}

function get_appserver_verison(){
    $path_arr = explode('/',ROOT_PATH);
    $count = count($path_arr)-2;
    $name = $path_arr[$count].'/';
    $path = str_replace($name,'',ROOT_PATH).'appserver/version.txt';
    $content = file_get_contents($path);
    return str_replace(array('.','v'),'',$content);
}
function get_h5_version(){
    $path_arr = explode('/',ROOT_PATH);
    $count = count($path_arr)-1;
    $name = $path_arr[$count].'/';
    $path = str_replace($name,'',ROOT_PATH).'h5/version.txt';
    $content = file_get_contents($path);
    return str_replace(array('.','v'),'',$content);
}

?>