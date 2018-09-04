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
 * $Author: yangyichao $
 * $Id: logistic_tracking.php 2016-04-25 yangyichao$
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');



$smarty->assign('ur_here', $_LANG['logistic_tracking_here']);
$smarty->assign('iframe_url', YUNQI_LOGISTIC_URL . '?ctl=exp&act=index&source='.iframe_source_encode('ecshop'));
$smarty->display('yq_iframe.htm');




?>