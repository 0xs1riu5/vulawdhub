<?php 
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
	$query = "SELECT * FROM $met_parameter where module<6  and lang='$lang' and access='0' order by no_order";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		$list['para']="para".$list['id'];
		if($list['class1']==0 or $list['class1']==$class1){
			if($list['type']==1 or $list['type']==2 or $list['type']==4 or $list['type']==6)$paralist[$list['module']][]=$list;
		}
	}
	$query1 = "select * from $met_plist where module='$module' and listid='$id'";
	$result1 = $db->query($query1);
	while($list1 = $db->fetch_array($result1)){
		$nowpara1="para".$list1['paraid'];
		$show[$nowpara1]=$list1['info'];
	}
	$show['imgurl']=($show['imgurl']<>"")?$show['imgurl']:'../'.$met_agents_img;
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
?>