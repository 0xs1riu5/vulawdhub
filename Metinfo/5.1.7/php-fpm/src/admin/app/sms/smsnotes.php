<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
if($action=='deqing'){
	$query = "delete from $met_sms ";
	$db->query($query);
	metsave('../app/sms/smsnotes.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs,'',$depth);
}
$total_pass = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
if($total_pass){
	$met_file='/sms/smsnotes.php';
	$post=array('md5'=>$total_pass['authpass']);
	$json = curl_post($post,30);
	if($json!='not'){
		$list = json_decode($json,true);
		foreach($list as $key=>$val){
			if($val['content']!=''){
				$query = "INSERT INTO $met_sms SET
					time     ='$val[time]',
					type     ='$val[type]',
					content  ='$val[content]',
					tel      ='$val[tel]',
					remark   ='$val[remark]'";
				$db->query($query);
			}
		}
	}
}

$serch_sql=" where time!='' ";
if($notes_type){
$serch_sql=" where time!='' and type='$notes_type' ";
$selec_notes_type[$notes_type]='selected';
}
if($search == "detail_search") {			
	if($title)$serch_sql .= " and content like '%$title%' or tel like '%$title%'";
	$total_count = $db->counter($met_sms, "$serch_sql", "*");
} else {
	$total_count = $db->counter($met_sms, "$serch_sql", "*");
}
require_once 'include/pager.class.php';
$page = (int)$page;
if($page_input){$page=$page_input;}
$list_num = 20;
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query = "SELECT * FROM $met_sms $serch_sql order by time desc LIMIT $from_record, $list_num";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$list['text']=utf8substr($list['content'],0,15);
	$total_tels = explode(',',$list['tel']);
	$telsnum=count($total_tels);
	$list['telnum']=$telsnum;
	$list['teltext']=utf8substr($list['tel'],0,11);
	$list['type']=sedsmstype($list['type']);
	$list['time']=date('Y-m-d H:i:s',$list['time']);
	$list['remark']=sedsmserrtype($list['remark'],1);
	$record_list[]=$list;
}
$page_list = $rowset->link("smsnotes.php?anyid={$anyid}&lang={$lang}&notes_type=$notes_type&page=");
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/sms/smsnotes');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>