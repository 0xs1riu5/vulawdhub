<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
$mb_index=1;
$navoks[1]='ui-btn-active';
foreach ($met_class as $key=>$val){
	if($val['module']>5 && $val['module']<11){
		$purview='admin_pop'.$val['id'];
		if($val['module']==9)$purview='admin_pop1406';
		if($val['module']==10)$purview='admin_pop1601';
		$purview=$$purview;
		$metcmspr=$metinfo_admin_pop=="metinfo" || $purview=='metinfo'?1:0;
		if($metcmspr){
			switch($val['module']){
				case 6:
					$val['name']=$lang_cvinfo;
					$val['count'] = $db->counter($met_cv, " where readok=0 and lang='$lang' ", "*");
					if($val['count'])$val['url'] = 'cv/cv.php?lang='.$lang;
				break;
				case 7:
					$val['count'] = $db->counter($met_message, " where readok=0 and lang='$lang' ", "*");
					if($val['count'])$val['url'] = 'message/index.php?lang='.$lang;
				break;
				case 8:
					$val['count'] = $db->counter($met_feedback, " where readok=0 and class1='$val[id]' and lang='$lang' ", "*");
					if($val['count'])$val['url'] = 'feedback/index.php?lang='.$lang.'&class1='.$val[id];
				break;
				case 9:
					$val['count'] = $db->counter($met_link, " where show_ok=0 and lang='$lang' ", "*");
					if($val['count'])$val['url'] = 'link/index.php?lang='.$lang;
				break;
				case 10:
					$sql=$met_member_login==3?'checkid=0':'admin_approval_date is null';
					$val['count'] = $db->counter($met_admin_table, " where {$sql} and lang='$lang' and usertype<3 ", "*");
					if($val['count'])$val['url'] = 'member/index.php?lang='.$lang;
				break;
			}
			$contentlistes[] = $val;
		}
	}
}
include template('mobile/index');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>