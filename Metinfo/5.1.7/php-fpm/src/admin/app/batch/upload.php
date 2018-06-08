<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
function fdisimg($dis){
	$list = explode('|',$dis);
	$metinfo='';
	$p = count($list);
	for($i=0;$i<$p;$i++){
		if($list[$i]!=''){
			$sm = explode('*',$list[$i]);
			if($sm[1]!=''&&$sm[1]!='undefined' && $sm[1]!='///watermark/')$metinfo.=$i==($p-1)?$list[$i]:$list[$i].'|';
		}
	}
	if(substr($metinfo,-1,1)=='|')$metinfo=substr($metinfo,0,-1);
	return $metinfo;
}
if($ignore){
	$table=moduledb($module);
	$listid=explode(',',$listid);
	foreach($listid as $key=>$val){
		if($val){
			$bigimg  = 'bigimgold'.$val;
			$imgurl  = $$bigimg;
			$thumimg = 'thumbold'.$val;
			$imgurls =$$thumimg;
			$disname = 'dis-'.$val;
			$displayimg = $$disname;
			switch($module){
				case 2:
					$querysql="imgurl='$imgurl',imgurls='$imgurls'";
				break;
				case 3:
					$displayimg=fdisimg($displayimg);
					$querysql="imgurl='$imgurl',imgurls='$imgurls',displayimg='$displayimg'";
				break;
				case 4:
					$querysql="downloadurl='$flieurl'";
				break;
				case 5:
					$displayimg=fdisimg($displayimg);
					$querysql="imgurl='$imgurl',imgurls='$imgurls',displayimg='$displayimg'";
				break;
			}
			$query ="update $table set $querysql,recycle='0' where id='$val'";
			$db->query($query);
		}
	}
}
$page=$page+1;
if($page*5-$numcsv>=5){
	metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang",$lang_jsok,$depth);
}else{
	metsave("../app/batch/fileup.php?anyid=$anyid&lang=$lang&class1=$class1&class2=$class2&class3=$class3&fid=$fid&lid=$lid&numcsv=$numcsv&fileup=$fileup&action=do&page=$page",$lang_contentuppage,$depth);
}


# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>