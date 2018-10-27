<?php
defined('IN_DESTOON') or exit('Access Denied');
isset($MODULE[$mid]) or exit;
tag("moduleid=$mid&table=keyword&condition=moduleid=$mid and status=3&pagesize=10&order=total_search desc&template=list-search_kw");
?>