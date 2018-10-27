<?php
defined('IN_DESTOON') or exit('Access Denied');
preg_match("/^[a-z0-9_\-]{2,}$/", $item) or exit;
preg_match("/^[a-z0-9_\-\[\]]{2,}$/", $name) or exit;
echo type_select($item, 0, $name, $default, $itemid, 'id="typeid"');
?>