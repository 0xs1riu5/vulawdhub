<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
require_once $depth.'../include/pager.class.php';
$cs=2;
$listclass[$cs]='class="now"';
$page = (int)$page;
if($page_input){$page=$page_input;}
$list_num = 15;
$where ="where lang='$lang' order by no_order";
if($search=='detail_search' && $ftype!='all'){
	$tp = $ftype==1?"and img_path!=''":"and flash_path!=''";
	$where ="where lang='$lang' {$tp} order by no_order";
}
$ftype1[$ftype]='selected';
if($module<>""){
	$dule = $met_flasharray[$module]['type']==2?"(flash_path !='' and module = 'metinfo')":"(img_path !='' and module = 'metinfo')";
	$where ="where lang='$lang' and (module like '%,{$module},%' or {$dule}) order by no_order";
	$module1[$module]='selected';
}
$total_count = $db->counter($met_flash, "$where", "*");
$rowset = new Pager($total_count,$list_num,$page);
$from_record = $rowset->_offset();
$query = "SELECT * FROM {$met_flash} {$where} LIMIT {$from_record}, {$list_num}";
if($module<>"" && !$met_flasharray[$module]['type'])$query='';
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	if($list[module]=='metinfo'){
		$list['modulename']=$lang_allcategory;
	}else{
		$lmod = explode(',',$list[module]);
		$cname=',';
		for($i=0;$i<count($lmod);$i++){
			if($lmod[$i]!=''){
				if($lmod[$i]==10001){
					$cname.=$lang_htmHome.',';
				}else{
					$columnids=$db->get_one("select * from {$met_column} where id='{$lmod[$i]}' and lang='{$lang}'");
					$cname.=$columnids[name].',';
				}
			}
		}
		$list['modulename']=$cname;
	}
	$flashrec[]=$list;
}
$page_list = $rowset->link("flash.php?lang={$lang}&page=");
$query1="select * from $met_column where if_in='0' and lang='$lang' order by no_order";
$result1= $db->query($query1);
$mod1[0]=$mod[10001]=array(
			id=>10001,
			name=>"$lang_flashHome",
			bigclass=>0
		);
$i=1;
while($list = $db->fetch_array($result1)){
if($list[classtype]==1){
						$mod1[$i]=$list;
						$i++;
}
if($list[classtype]==2)$mod2[$list[bigclass]][]=$list;
if($list[classtype]==3)$mod3[$list[bigclass]][]=$list;
$mod[$list['id']]=$list;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('interface/flash/flash');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>