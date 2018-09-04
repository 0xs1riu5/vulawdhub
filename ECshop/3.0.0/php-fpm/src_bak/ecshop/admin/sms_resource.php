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
 * $Author: gexinfeng $
 * $Id: sms_resource.php.php 2016-04-25 gexinfeng$
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
define('SOURCE_ID', '620386');

$smarty->assign('ur_here', $_LANG['sms_resource_here']);
$data[] = base64_encode(SOURCE_ID);
$data[] = get_certificate_info('passport_uid');
$data[] = get_certificate_info('yunqi_code');
$data[] = time();
$data[] = getRandChar(6);
$data[] = getRandChar(6);
$source_str = implode('|', $data);
$smarty->assign('resource_url', SMS_RESOURCE_URL . '/index.php?source='.base64_encode($source_str));
$smarty->display('sms_resource.htm');

function getRandChar($length){
   $str = null;
   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;
   for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)];
   }
   return $str;
  }
?>