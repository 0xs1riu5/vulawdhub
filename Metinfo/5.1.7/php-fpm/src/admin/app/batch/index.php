<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
/////////////////
$query = "SELECT * FROM $met_column where (module=2 or module=3 or module=5) and lang='$lang'";
$result = $db->query($query);
while($list = $db->fetch_array($result)) {
$clist[]=$list;
if($list['classtype']==1||$list['releclass']){$clist1now[]=$list;}
}
$i=0;
$listjs = "<script language = 'JavaScript'>\n";
$listjs.= "var onecount;\n";
$listjs.= "lev = new Array();\n";
foreach($clist as $key=>$vallist){
	$vallist[name]=str_replace("'","\\'",$vallist[name]);
	if($vallist['releclass']){
		$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','0','".$vallist[id]."','".$vallist[access]."');\n";
		$i=$i+1;
	}
	else{
			$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".$vallist[access]."');\n";
			$i=$i+1;
	}
}
$listjs.= "onecount=".$i.";\n";
$listjs.= "</script>";
//////////////////
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>