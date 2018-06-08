<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';

if($class1){
	if($class3){
		$sqlclass1.=" id=$class3 ";
	}else{
		if($class2){
			$sqlclass1.=" id=$class2 ";
			foreach($met_class3[$class2] as $key=>$val){
				if($val['module']==1)$sqlclass1.=" or id=$val[id] ";
			}
		}else{
			$sqlclass1.=" id='$class1'";
			if($met_class[$class1]['releclass']){	
				foreach($met_class3[$class1] as $key=>$val){
					if($val['module']==1)$sqlclass1.=" or id=$val[id] ";
				}
			}else{
				foreach($met_class2[$class1] as $key=>$val){
					if($val['module']==1&&!$val[releclass]){$sqlclass1.=" or id=$val[id] ";}
					else{continue;}
					foreach($met_class3[$val[id]] as $key1=>$val1){
						if($val['module']==1)$sqlclass1.=" or id=$val1[id] ";
					}
				}
			}
		}
	}
	$sqlclass1="and ($sqlclass1)";
}else{
	$sqlclass1="and module=1 ";
}
$serch_sql=" where lang='$lang' $sqlclass1 ";
if($search == "detail_search"){	
	if($title)$serch_sql .= " and name like '%$title%' "; 
	$total_count = $db->counter($met_column, "$serch_sql", "*");
}else{
	$total_count = $db->counter($met_column, "$serch_sql", "*");
}
require_once 'include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$list_num = 20;
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query = "SELECT * FROM {$met_column} $serch_sql order by no_order LIMIT $from_record, $list_num";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	if($list[classtype]==1||$list[releclass])$aboutid=$list[id];
	if($list[classtype]==2&&!$list[releclass])$aboutid=$met_class[$list[id]][bigclass];
	if($list[classtype]==3){
		if($met_class[$met_class[$list[id]][bigclass]][releclass]){
			$aboutid=$met_class[$list[id]][bigclass];
		}else{
			$aboutid=$met_class[$met_class[$list[id]][bigclass]][bigclass];
		}
	}
	$admin_column_power="admin_pop".$aboutid;
	if(!($metinfo_admin_pop=='metinfo'||$$admin_column_power=='metinfo'))continue;
	$list[updatetime] = date('Y-m-d',strtotime($list[updatetime]));
	$num = 38;
	if (preg_match("/[\x7f-\xff]/",$list['name']))$num=28;
	$list['titles']=utf8substr($list['name'],0,$num);
	$about_list[]=$list;
}
$page_list = $rowset->link("index.php?anyid={$anyid}&lang=$lang&class1=$class1&class2=$class2&class3=$class3&module={$module}&search=$search&title=$title&page=");

$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('content/about/index');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>