<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$column_list = $db->get_one("SELECT * FROM $met_column WHERE id='$id'");
$column_list_copy = $db->get_one("SELECT * FROM $met_column WHERE id='$copyculmnid' and lang='$lang'");
if(!$column_list){
	metsave('-1',$lang_dataerror);
}
$config_list = $db->get_all("SELECT * FROM $met_config WHERE columnid='$copyculmnid'");
foreach($config_list as $key=>$val){
	if($val['name']!='met_fdtable'){
		$query = "update $met_config SET value = '$val[value]' where name ='$val[name]' and columnid='$id'";
		$db->query($query);
	}
}
$query = "SELECT * FROM $met_parameter where class1 = '$copyculmnid' and module='8'";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$query="select * from $met_parameter where id='{$list[id]}'";
	$copy=$db->get_one($query);
	$query = "insert into $met_parameter set name='$copy[name]',no_order='$copy[no_order]',type='$copy[type]',access='$copy[access]',wr_ok='$copy[wr_ok]',class1='$id',module='$copy[module]',lang='$copy[lang]'";
	$db->query($query);
	$list['newid']=mysql_insert_id();
	if($list[type]==2||$list[type]==4||$list[type]==6){
		$query = "select * from $met_list where bigid='{$list[id]}'";
		$copy = $db->get_all($query);
		foreach ($copy as $key=>$val){
			$query = "insert into $met_list set bigid='$list[newid]',info='$val[info]',no_order='$val[no_order]',lang='$val[lang]'";
			$db->query($query);
		}
	}
}
echo 'metinfo';
die;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>