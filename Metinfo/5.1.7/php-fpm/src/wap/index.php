<?php 
require_once '../include/common.inc.php';
if(!$met_wap)okinfo('../index.php?lang='.$lang,$lang_metwapok);
require_once 'wap.php';
include waptemplate($temp);
wapfooter();
?> 