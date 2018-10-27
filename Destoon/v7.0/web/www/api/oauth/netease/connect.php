<?php
require '../../../common.inc.php';
require 'init.inc.php';
dheader(NE_CONNECT_URL.'?response_type=code&client_id='.NE_ID.'&redirect_uri='.urlencode(NE_CALLBACK));
?>