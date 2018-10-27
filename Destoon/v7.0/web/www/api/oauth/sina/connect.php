<?php
require '../../../common.inc.php';
require 'init.inc.php';
$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
dheader($o->getAuthorizeURL(WB_CALLBACK_URL));
?>