<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once 'common.inc.php';
switch($type){
case 'product':
$met_hits=$met_product;
break;
case 'news':
$met_hits=$met_news;
break;
case 'download':
$met_hits=$met_download;
break;
case 'img':
$met_hits=$met_img;
break;
default :
$met_hits='';
break;
}
$query="select * from $met_hits where id='$id'";
$hits_list=$db->get_one($query);
$hits_list[hits]=$hits_list[hits]+1;
$query = "update $met_hits SET hits='$hits_list[hits]' where id='$id'";
$db->query($query); 
$query="select * from $met_hits where id='$id'";
$hits_list=$db->get_one($query);
$hits=$hits_list[hits];
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
$hits="<?php echo $hits; ?>";
document.write($hits)