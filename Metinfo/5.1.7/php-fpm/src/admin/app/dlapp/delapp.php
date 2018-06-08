<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action='del'){
$query="select * from $met_app where id='$id' and download=1";
$app=$db->get_one($query);
deldir('../'.$app['file']);
$query="delete from $met_app where id='$id' and download=1";
$db->query($query);
}
echo $lang_appuninstall;
metsave('../app/dlapp/index.php?anyid='.$anyid.'&lang='.$lang,'',$depth);
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>