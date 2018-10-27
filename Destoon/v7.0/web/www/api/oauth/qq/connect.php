<?php
require '../../../common.inc.php';
require 'init.inc.php';
$_SESSION['state'] = md5(uniqid(rand(), true));
dheader(QQ_CONNECT_URL.'?response_type=code&client_id='.QQ_ID.'&redirect_uri='.urlencode(QQ_CALLBACK).'&state='.$_SESSION['state'].'&scope=get_user_info,add_t,add_pic_t,add_share');
?>