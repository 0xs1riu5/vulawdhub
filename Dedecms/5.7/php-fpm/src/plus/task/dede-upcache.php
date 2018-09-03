<?php
require_once(dirname(__FILE__).'/../../include/common.inc.php');
//成功返回信息
$dsql->ExecuteNoneQuery("Update `#@__sys_task` set sta='成功' where dourl='dede-upcache.php' ");
echo "Welcome to DedeCMS!";
exit();
?>