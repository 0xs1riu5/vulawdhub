<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
if($action=='admin'){
    if (isblank($id)) {
        echo $lang_loginIntput;
        exit;
    } 
    $id = dhtmlchars(trim($id));
    foreach($char_key as $value){
		if(strpos($id,$value)!==false){
			echo $lang_loginUserErr;
			exit;
		}
	}
	unset($id_list);
    $id_list = $db->get_one("select admin_id from $met_admin_table where admin_id = '$id'");
    if ($id_list[admin_id]) {
        echo $lang_loginUserMudb;
        exit;
    } else {
        echo $lang_loginRegok;
        exit;
    }
} 

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>