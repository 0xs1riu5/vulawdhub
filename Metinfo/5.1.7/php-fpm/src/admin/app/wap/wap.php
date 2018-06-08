<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($action == 'modify'){
	if(!$met_wap_tpa)$met_wap_tpa=0;
	if(!$met_wap_tpb)$met_wap_tpb=0;
	$met_wap_url = ereg_replace(" ","",$met_wap_url);
	if(substr($met_wap_url,-1,1)!="/")$met_wap_url.="/";
	if(!strstr($met_wap_url,"http://"))$met_wap_url="http://".$met_wap_url;
	require_once $depth.'../include/config.php';
	metsave('../app/wap/wap.php?lang='.$lang.'&anyid='.$anyid,'',$depth);
}else{
	$met_wap1[$met_wap]="checked";
	$met_waplink1[$met_waplink]="checked";
	$met_wap_ok1[$met_wap_ok]="checked";
	$met_wap_tpa1[$met_wap_tpa]="checked";
	$met_wap_tpb1[$met_wap_tpb]="checked";
	$webmpa = $_SERVER["PHP_SELF"];
	$webmpa = dirname($webmpa);
	$webmpa = explode('/',$webmpa);
	$wnum = count($webmpa)-2;
	for($i=1;$i<$wnum;$i++){
		$webmp = $i==1?$webmpa[$i]:$webmp.'/'.$webmpa[$i];
	}
	if(substr($webmp,-1,1)!="/")$webmp.="/";
	$webml = 'http://'.$_SERVER['HTTP_HOST'].'/';
	$webwapurl = $webml.$webmp.'wap/';
	$listclass[1]='class="now"';
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('app/wap/wap');
	footer();
}
?>