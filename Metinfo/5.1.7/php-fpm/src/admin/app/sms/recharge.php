<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
$total_pass = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
if($total_pass){
	$met_file='/sms/recharge.php';
	$post=array('md5'=>$total_pass['authpass']);
	if($notes_type){
		$post=array('md5'=>$total_pass['authpass'],'notes_type'=>$notes_type);
		$selec_type[$notes_type]='selected';
	}
	$json = curl_post($post,30);
	if($json!='not'){
		$list = json_decode($json,true);
		foreach($list as $key=>$val){
			$val['time']=date('Y-m-d H:i:s',$val['time']);
			$val['cost'] = sprintf("%.2f",$val['cost']);
			$val['balance'] = sprintf("%.2f",$val['balance']);
			$val['type'] = $val['type']==1?$lang_smsrecharge:$lang_smschargeback;
			$val['remark'] = $val['remark']!=''?sedsmstype((int)$val['remark']):$lang_smsreonlinecharge;
			$record_list[]=$val;
		}
	}
	/*分页*/
	$total_count = count($record_list);
	$list_num = 15;
	require_once $depth.'../include/pager.class.php';
	$page = (int)$page;
	if($page_input){$page=$page_input;}
	$rowset = new Pager($total_count,$list_num,$page);
	$from_record = $rowset->_offset();
	$i=0;
	foreach($record_list as $key=>$val){
		$i++;
		$maxl=$from_record+$list_num;
		if($i>$from_record and $i<=$maxl){
			$val['order']=$i;
			$newvisit[$key]=$val;
		}
	}
	if($total_count>$list_num){
		$page_list = $rowset->link("recharge.php?lang={$lang}&anyid={$anyid}&notes_type={$notes_type}&page=");
	}
	$record_list=$newvisit;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/sms/recharge');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>