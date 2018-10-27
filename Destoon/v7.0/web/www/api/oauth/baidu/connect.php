<?php
require '../../../common.inc.php';
require 'init.inc.php';
dheader(BD_CONNECT_URL.'?response_type=code&display=page&client_id='.BD_ID.'&redirect_uri='.urlencode(BD_CALLBACK));
?>