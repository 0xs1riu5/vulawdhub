<?php

/**
 * ECSHOP 绑定矩阵callback文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: wangleisvn $
 * $Id: certificate.php 16075 2009-05-22 02:19:40Z wangleisvn $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 申请绑定矩阵callback
/*------------------------------------------------------ */
$data = $_POST;
if(!empty($data)){
    include_once(ROOT_PATH . 'includes/cls_matrix.php');
    include_once(ROOT_PATH."includes/cls_certificate.php");
    $cert = new certificate();
    $matrix = new matrix();
    $sign = $data["certi_ac"];
    $my_sign = $cert->make_shopex_ac($data);
    if( $sign != $my_sign ){
        die('{"res":"fail","msg":"error:000002","info":"sign error"}');
    }else{
        $node_type = trim($data['node_type']);
        if($data['status'] == 'bind'){
            $data['name'] = $data['shop_name'];
            unset($data['shop_name']);
            //同一种node_type只能绑定一个
            if($cert->is_bind_sn($node_type,'bind_type')){
                die('{"res":"fail","msg":"error:000002","info":"node_type is exists"}');
            }
            //保存绑定关系
            $matrix->save_shop_bind($data);
        }else{
            $matrix->delete_shop_bind($node_type);
        }
    }
}else{
    exit('{"res":"fail","msg":"error:000001","info":""}');
}

?>