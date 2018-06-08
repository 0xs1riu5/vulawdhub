<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
 $serch_sql=" where usertype = 3 ";
    if($search == "detail_search") {
        if($admin_id) { $serch_sql .= " and admin_id like '%$admin_id%' "; }
        if($admin_name){ $serch_sql .= " and admin_name like '%$admin_name%' "; }
        $total_count = $db->counter($met_admin_table, "$serch_sql", "*");
    } else {
        $total_count = $db->counter($met_admin_table, "$serch_sql", "*");
    }
    require_once 'include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = 16;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
    $query = "SELECT * FROM $met_admin_table $serch_sql ORDER BY admin_modify_date DESC LIMIT $from_record, $list_num";
    $result = $db->query($query);
	$admin_list1=array();
	 while($list = $db->fetch_array($result)) {
		$admin_list1[]=$list;
    }
foreach($admin_list1 as $key=>$val){
	$val[admin_grouptd]=admin_grouptp($val[admin_group]);
	$editok=0;
	if($val[id] == $admin_list[id])$editok=1;
	if($admin_list[admin_group] == 3 && $val[admin_group]!=3 && $val[admin_group]!=10000)$editok=1;
	if($admin_list[admin_group]==10000)$editok=1;
	$grplok=$admin_list[admin_group]!=$val[admin_group]?1:0;
	$grplok1=$val[admin_group]!=10000 && $admin_list[admin_group]!=0?1:0;
	$val[grplok]=$grplok&&$grplok1?1:0;
	$val[editok]=$editok;
	if($admin_list[langok]!='metinfo'){
		if($val[langok]!='metinfo'){
			$thslng=explode('-',$admin_list[langok]);
			$thslng=array_filter($thslng);
			$vallng=explode('-',$val[langok]);
			$vallng=array_filter($vallng);
			$newlng=array_intersect($thslng,$vallng);
			if(count($newlng)==count($vallng)){
				$admin_list1x[]=$val;
			}
		}else{
			$val[editok]=0;
			$val[grplok]=0;
			$admin_list1x[]=$val;
		}
	}else{
		$admin_list1x[]=$val;
	}
}
$admin_list1=$admin_list1x;
$page_list = $rowset->link("index.php?admin_id=$admin_id&admin_name=$admin_name&search=$search&lang=$lang&anyid={$anyid}&page=");
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('admin/admin');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>