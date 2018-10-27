<?php
defined('IN_DESTOON') or exit('Access Denied');
$area_title = isset($area_title) ? strip_tags($area_title) : '';
$area_extend = isset($area_extend) ? decrypt($area_extend, DT_KEY.'ARE') : '';
$areaid = isset($areaid) ? intval($areaid) : 0;
$area_deep = isset($area_deep) ? intval($area_deep) : 0;
$area_id= isset($area_id) ? intval($area_id) : 1;
echo get_area_select($area_title, $areaid, $area_extend, $area_deep, $area_id);
?>