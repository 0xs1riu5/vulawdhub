<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
 $query="select * from $met_admin_array where array_type='1' and lang='$lang'";
 $menber_array_temp=$db->get_all($query);
 foreach($menber_array_temp as $key=>$val){
	$menber_array[$val['id']]=$val['array_name'];
 }
 $serch_sql=" where usertype <> 3  and lang='$lang'";
 if($searchall=='all') $serch_sql=" where usertype <> 3 ";
    if($search == "detail_search") {        
        if($admin_id) { $serch_sql .= " and admin_id like '%$admin_id%' "; }
        if($usertype){ $serch_sql .= " and usertype = '$usertype' "; }
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
	$admin_list=array();
	 while($list = $db->fetch_array($result)) {
	 $list['usertype']=$menber_array[$list['usertype']];
	 $list['checked']=$list['checkid']==1?$lang_memberChecked:$lang_memberUnChecked;
     $admin_list[]=$list;
    }
$page_list = $rowset->link("index.php?admin_id=$admin_id&admin_name=$admin_name&search=$search&searchall=$searchall&lang=$lang&anyid={$anyid}&page=");
$lev=1;
$menbermanage=1;
$list_access['access']=$usertype;
require '../content/access.php';
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('member/member');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>