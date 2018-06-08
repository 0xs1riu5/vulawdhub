<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
/*子级栏目*/
function listjs(){
	global $met_class22,$met_class3,$class1,$met_class;
	$i=0;
	if($met_class[$class1][releclass]){
		$met_class22=$met_class3;
		$met_class3='';
	}
	$listjs = "<script language = 'JavaScript'>\n";
	$listjs.= "var onecount;\n";
	$listjs.= "subcat = new Array();\n";
	foreach($met_class22[$class1] as $key=>$vallist){
	$listjs.= "subcat[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".$vallist[access]."');\n";
		 $i=$i+1;
	    foreach($met_class3[$vallist[id]] as $key=>$vallist3){
			$listjs.= "subcat[".$i."] = new Array('".$vallist3[name]."','".$vallist3[bigclass]."','".$vallist3[id]."','".$vallist3[access]."');\n";
			$i=$i+1;
		}
	}
	$listjs.= "onecount=".$i.";\n";
	$listjs.= "</script>";
	return $listjs;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>