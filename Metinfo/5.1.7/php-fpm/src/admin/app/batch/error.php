<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$class=$class3?$class3:($class2?$class2:$class1);
$classimg=$db->get_one("select * from $met_column where id='$class'");
$class1title=$db->get_one("select * from $met_column where id='$class1'");
$classtitle=$class1title['name'];
if($class2){$class2title=$db->get_one("select * from $met_column where id='$class2'");$classtitle.="->$class2title[name]";}
if($class3){$class3title=$db->get_one("select * from $met_column where id='$class3'");$classtitle.="->$class3title[name]";}
$table=moduledb($classimg['module']);
$errorones=explode(',',$error);
foreach($errorones as $key=>$val){
	$errortone=explode('|',$val);
	$error_list[$errortone[0]]=$db->get_one("select * from $table where id='$errortone[0]'");
	$error_list[$errortone[0]]['bigerror']=$errortone[1];
	$errordis=explode('/',$errortone[2]);
	foreach($errordis as $key1=>$val1){	
		$errordisone=explode('-',$val1);
		$error_list[$errortone[0]]['diserror'][$errordisone[0]]=$errordisone[1];
	}
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/batch/error');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>