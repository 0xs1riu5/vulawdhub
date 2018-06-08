<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';  
if($action=="add"){
$access=$access<>""?$access:"0";
$query = "INSERT INTO $met_news SET
                      title              = '$title',
                      ctitle             = '$ctitle',
					  keywords           = '$keywords',
					  description        = '$description',
					  content            = '$content',
					  class1             = '$class1',
					  class2             = '$class2',
					  class3             = '$class3',
					  img_ok             = '$img_ok',
					  imgurl             = '$imgurl',
					  imgurls            = '$imgurls',
				      com_ok             = '$com_ok',
				      wap_ok             = '$wap_ok',
					  issue              = '$issue',
					  hits               = '$hits', 
					  addtime            = '$addtime', 
					  updatetime         = '$updatetime',
					  access          	 = '$access',
					  filename       	 = '$filename',
					  no_order       	 = '$no_order',
					  lang          	 = '$lang',
					  top_ok             = '$top_ok'";
         $db->query($query);
$later_news=$db->get_one("select * from $met_news where updatetime='$updatetime' and lang='$lang'");
$id=$later_news[id];
$htmjs = contenthtm($class1,$id,'shownews',$filename).'$|$';
$htmjs.= indexhtm().'$|$';
$htmjs.= classhtm($class1,$class2,$class3);
$turl  ='../mobile/add/index.php?lang='.$lang;
metsave($turl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
