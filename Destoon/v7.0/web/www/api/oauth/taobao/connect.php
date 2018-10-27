<?php
require '../../../common.inc.php';
require 'init.inc.php';
dheader(TB_CONNECT_URL.'?response_type=code&client_id='.TB_ID.'&redirect_uri='.urlencode(TB_CALLBACK));
?>