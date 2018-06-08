<?php
$depth='../';
require_once $depth.'../login/login_check.php';
$physicaldo=array(
	array('id'=>1,'name'=>$lang_physicaladmin),
	array('id'=>2,'name'=>$lang_physicalbackup),
	array('id'=>3,'name'=>$lang_physicalupdate),
	array('id'=>4,'name'=>$lang_physicalseo),
	array('id'=>5,'name'=>$lang_physicalstatic),
	array('id'=>6,'name'=>$lang_physicalunread),
	array('id'=>7,'name'=>$lang_physicalspam),
	array('id'=>8,'name'=>$lang_physicalmember),
	array('id'=>9,'name'=>$lang_physicalweb),
	array('id'=>10,'name'=>$lang_physicalfile)
);
/*项目1*/
switch($physical_admin){
	case 0:
		$physicaldo[0]['text']=$lang_physicaladmin1;
		$physicaldo[0]['type']=2;/*1为危险项目，2为可优化项目，3为安全项目*/
	break;
	case 1:
		$physicaldo[0]['text']=$lang_physicaladmin2;
		$physicaldo[0]['type']=3;
	break;
}
/*项目2*/
switch($physical_backup){
	case -2:
		$physicaldo[1]['text']=$lang_physicalbackup1;
		$physicaldo[1]['type']=1;
	break;
	default:
		$timedays=$physical_backup;
		if($timedays<=30){
			$physicaldo[1]['text']="{$lang_physicalbackup2}{$timedays} {$lang_physicalfiletime3}";
			$physicaldo[1]['type']=3;
		}else{
			$physicaldo[1]['text']="{$lang_physicalbackup2}{$timedays} {$lang_physicalbackup4}";
			$physicaldo[1]['type']=2;
		}
	break;
}
/*项目3*/
switch($physical_update){
	default:
		$timedays=$physical_update;
		if($timedays<=7){
			$physicaldo[2]['text']="{$lang_physicalupdate1}$timedays {$lang_physicalfiletime3}";
			$physicaldo[2]['type']=3;
		}elseif($timedays>7&&$timedays<=30){
			$physicaldo[2]['text']="{$lang_physicalupdate2}({$lang_physicalupdate1}$timedays {$lang_physicalfiletime3})";
			$physicaldo[2]['type']=2;
		}else{
			$physicaldo[2]['text']="{$lang_physicalupdate3}({$lang_physicalupdate1}$timedays {$lang_physicalfiletime3})";
			$physicaldo[2]['type']=1;
		}
	break;
}
/*项目4*/
if(strstr($physical_seo,'0')){
	$physical_seo=explode('|',$physical_seo);
	$i=0;
	$physicaldo[3]['text']='';
	foreach($physical_seo as $key=>$val){
	$i++;
		if($val!=''){
			if($val==0){
				if($i==1)$physicaldo[3]['text'].=$lang_physicalseo1.'<br/>';
				if($i==2)$physicaldo[3]['text'].=$lang_physicalseo2.'<br/>';
				if($i==3)$physicaldo[3]['text'].=$lang_physicalseo3;
			}
		}
	}
	$physicaldo[3]['type']=2;
}else{
	$physicaldo[3]['text']=$lang_physicalseo4;
	$physicaldo[3]['type']=3;
}
/*项目5*/
switch($physical_static){
	case 0:
		$physicaldo[4]['text']=$lang_physicalstatic1;
		$physicaldo[4]['type']=1;
	break;
	case 1:
		$physicaldo[4]['text']=$lang_physicalok;
		$physicaldo[4]['type']=3;
	break;
}
/*项目6*/
switch($physical_unread){
	default:
		$unread=explode('|',$physical_unread);
		if($unread[0]==0 && $unread[1]==0 && $unread[2] ==0){
			$physicaldo[5]['text']="{$lang_physicalunread}：{$lang_physicalunread1} $unread[0] {$lang_item}&nbsp;&nbsp;{$lang_physicalunread2} $unread[1] {$lang_item}&nbsp;&nbsp;{$lang_physicalunread3} $unread[2] {$lang_item}";
			$physicaldo[5]['type']=3;
		}else{
			$physicaldo[5]['text']=$lang_physicalnoneed;
			$physicaldo[5]['type']=3;
		}
	break;
}
/*项目7*/
switch($physical_spam){
	case '0':
		$physicaldo[6]['text']=$lang_physicalspam1;
		$physicaldo[6]['type']=2;
	break;
	case '1':
		$physicaldo[6]['text']=$lang_physicalnoneed;
		$physicaldo[6]['type']=3;
	break;
}
/*项目8*/
switch($physical_member){
	case 0:
		$physicaldo[7]['text']=$lang_physicalmember1.$count_member.' '.$lang_physicalmember2;
		$physicaldo[7]['type']=2;
	break;
	case 1:
		$physicaldo[7]['text']=$lang_physicalnoneed;
		$physicaldo[7]['type']=3;
	break;
}
/*项目9*/
switch($physical_web){
	case 0:
		$physicaldo[8]['text']=$lang_physicalweb1;
		$physicaldo[8]['type']=2;
	break;
	case 1:
		$physicaldo[8]['text']=$lang_physicalok;
		$physicaldo[8]['type']=3;
	break;
}
/*项目10*/
switch($physical_file){
	case "0":
		$physicaldo[9]['text']=$lang_physicalfile1;
		$physicaldo[9]['type']=1;
	break;
	default:
		if($physical_file=="1"){
			$physicaldo[9]['text']=$lang_physicalfile2;
			$physicaldo[9]['type']=3;
		}else{
		$fun=explode(',',$physical_file);
		$physical_file=NULL;
		foreach($fun as $key=>$val){
			$val1=explode('|',$val);
			if($val1[1]!=''){
				switch($val1[0]){
					case 1:
						$physical_file .="[{$lang_physicalfile3} - {$val1[1]}] {$lang_physicalfile5} <a href=\"javascript:void(0)\" name=\"download\" onclick=\"return physical_ajax($(this),'$val',2,2);\">{$lang_physicalfile7}</a><br/>";
						break;
					case 2:
						$physical_file .="[{$lang_physicalfile3} - {$val1[1]}] {$lang_physicalfile6} <a href=\"javascript:void(0)\" name=\"download\" onclick=\"return physical_ajax($(this),'$val',2,2);\">{$lang_physicalfile7}</a><br/>";
						break;
					case 3:
						$physical_file .="[{$lang_physicalfile4} - {$val1[1]}] {$lang_physicalfile5} <a name=\"download\">{$lang_physicalfile8}</a><br/>";
					break;
					case 4:
						$physical_file .="[{$lang_physicalfile3} - {$val1[1]}] {$lang_physicalfile5} <a href=\"javascript:void(0)\" name=\"download\" onclick=\"return physical_ajax($(this),'$val',2,3);\">{$lang_physicalfile9}</a><br/>";
						break;
					case 5:
						$physical_file .="[{$lang_physicalfile3} - {$val1[1]}] {$lang_physicalfile5} <a href=\"javascript:void(0)\" name=\"download\" onclick=\"return physical_ajax($(this),'$val',2,3);\">{$lang_physicalfile9}</a><br/>";
						break;
				}
			}
		}
		if($physical_file!='')$physical_file .="<a href=\"javascript:void(0)\" onclick=\"return physical_ajax($(this),'',4,'download');\">{$lang_cvall}{$lang_physicalfile7}</a>";
		$physicaldo[9]['text']=$physical_file;
		$physicaldo[9]['type']=1;
		}
	break;
}
$dfnum=0;
foreach($physicaldo as $key=>$val){
	switch($val['type']){
		case 1:$physical1[]=$val;break;
		case 2:$physical2[]=$val;break;
		case 3:$physical3[]=$val;$dfnum++;break;
	}
}
/*体检得分和上次体检时间等*/
if($physical_time==''){
	$sctimetxt=$lang_physicalfileno;
	$defen = 0;
	$notde = 0;
}else{
	$sctimes = strtotime(date("Y-m-d H:i:s"))-strtotime($physical_time);
	$sctime=floor($sctimes/3600/24);
	$sctimenum=$sctime==0?floor($sctimes/60):$sctime;
	$sctimetxt=$sctime==0?$lang_physicalfiletime1:$lang_physicalfiletime3;
	if($sctime==0 && floor($sctimes/60/60)>=1){
		$sctimenum=floor($sctimes/60/60);
		$sctimetxt=$lang_physicalfiletime2;
	}
	if($sctime>=7){
		$sctimenum=floor($sctime/7);
		$sctimetxt=$lang_physicalfiletime4;
	}
	if($sctime>=30){
		$sctimenum=floor($sctime/30);
		$sctimetxt=$lang_physicalfiletime5;
	}
	if($sctime>=365){
		$sctimenum=floor($sctime/365);
		$sctimetxt=$lang_physicalfiletime6;
	}
	$defen=$dfnum==10?100:$dfnum*floor(100/10);
	$notde=10-$dfnum;
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/physical/index');
footer();
?>


