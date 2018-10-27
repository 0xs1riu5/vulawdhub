<?php
require '../../common.inc.php';
$_groupid == 1 or exit('Access Denied');
isset($mediaid) or exit('Access Denied');
require DT_ROOT.'/api/weixin/init.inc.php';
dheader('http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$mediaid);
?>