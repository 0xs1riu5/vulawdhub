<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($physical_function!="1"){
	$fun=explode(',',$physical_function);
	$physical_function=NULL;
	$kyfile=0;
	$zhfile=0;
	$wjfile=0;
	foreach($fun as $key=>$val){
		$val1=explode('|',$val);
		switch($val1[0]){
			case 1:
				$physical_function.="[{$lang_physicalfunction1} - {$val1[1]}] {$lang_physicalfunction2} $val1[2] <a href=\"javascript:void(0)\" name=\"delete\" onclick=\"return physical_ajax($(this),'$val',1,1);\">{$lang_delete}</a><br />";
				$kyfile++;
			break;
			case 2:
				$physical_function .="[{$val1[1]}] {$lang_physicalfunction3} <a href=\"javascript:void(0)\" name=\"delete\" onclick=\"return physical_ajax($(this),'$val',1,1);\">{$lang_delete}</a><br/>";
				$zhfile++;
			break;
			case 3:
				$physical_function.="[{$lang_physicalfunction4} - {$val1[1]}] {$lang_physicalfunction5} <a href=\"javascript:void(0)\" name=\"delete\" onclick=\"return physical_ajax($(this),'$val',1,1);\">{$lang_delete}</a><br />";
				$wjfile++;
			break;			
		}	
	}
	$physical_function.="<a href=\"javascript:void(0)\" onclick=\"return physical_ajax($(this),'',4,'delete');\">{$lang_physicalfunction6}</a>";
}else{
	$physical_function=$lang_physicalfunctionok;
}
if($physical_fingerprint=="1"){
	$physical_fingerprint=$lang_physicalfingerprintok;
}
elseif($physical_fingerprint==-1){
	$physical_fingerprint=$lang_physicalfingerprintno;
}
else{
	$fun=explode(',',$physical_fingerprint);
	$physical_fingerprint=NULL;
	$zwnum=0;
	foreach($fun as $key=>$val){
		$val1=explode('|',$val);		
		if(preg_match_all("/\.(php|html|htm|asp|jsp)/i",$val1[1],$out)){
				$aurl="<a href=\"physical.php?action=op&valphy=$val&type=3&op=4\" target=\"_blank\" >{$lang_view}</a>";
			}
			else{$aurl="<a href=\"../../../$val1[1]\" target=\"_blank\">{$lang_view}</a>";}
		switch($val1[0]){
			case 1:
			$physical_fingerprint.="{$lang_physicalfunction1} $val1[1] {$lang_physicalfingerprint2} $aurl<br />";
			break;
			case 2:
			$physical_fingerprint.="{$lang_physicalfunction1} $val1[1] {$lang_physicalfingerprint3} $aurl<br />";
			break;
			case 3:
			$physical_fingerprint.="{$lang_physicalfingerprint1} $val1[1] {$lang_physicalfile5} <br />";
			break;
		}
		$zwnum++;
	}
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/physical/advanced');
footer();
?>