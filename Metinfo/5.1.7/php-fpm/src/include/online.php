<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$met_oline=1;
require_once 'common.inc.php';
if($met_online_type<>3){
	$met_url   = $navurl.'public/';
	$cache_online = met_cache('online_'.$lang.'.inc.php');
	if(!$cache_online){$cache_online=cache_online();}
	foreach($cache_online as $key=>$list){
		$online_list[]=$list;
		if($list['qq']!="")$qq_list[]=$list;
		if($list['msn']!="")$msn_list[]=$list;
		if($list['taobao']!="")$taobao_list[]=$list;
		if($list['alibaba']!="")$alibaba_list[]=$list;
		if($list['skype']!="")$skype_list[]=$list;
	}
	$metinfo='<div id="onlinebox" class="onlinebox onlinebox_'.$met_online_skin.' onlinebox_'.$met_online_skin.'_'.$met_online_color.'" style="display:none;">';
	if($met_online_skin<3){
	$metinfo.='<div class="onlinebox-showbox">';
	$metinfo.='<span>'.$lang_Online.'</span>';
	$metinfo.='</div>';
	$metinfo.='<div class="onlinebox-conbox" style="display:none;">';
	}
	$stit=$met_online_skin<3?"title='{$lang_Online_tips}'":'';
	$metinfo.='		<div class="onlinebox-top" '.$stit.'>';
	$metinfo.='<a href="javascript:;" onclick="return onlineclose();" class="onlinebox-close" title="'.$lang_Close.'"></a><span>'.$lang_Online.'</span>';
	$metinfo.='		</div>';
	$metinfo.='		<div class="onlinebox-center">';
	$metinfo.='			<div class="onlinebox-center-box">';
	//online content
	foreach($online_list as $key=>$val){
		$metinfo.="<dl>";
		if(!$met_onlinenameok)$metinfo.="<dt>".$val[name]."</dt>";
		$metinfo.="<dd>";
		if($val[qq]!=""){
			$metinfo.='<a href="tencent://message/?uin='.$val[qq].'&Site=&Menu=yes" title="QQ'.$val[name].'"><img border="0" src="http://wpa.qq.com/pa?p=2:'.$val[qq].':'.$met_qq_type.'"></a>';
		}
		if($val[msn]!="")$metinfo.='<span class="met_msn"><a href="msnim:chat?contact='.$val[msn].'"><img border="0" alt="MSN'.$val[name].'" src="'.$met_url.'images/msn/msn_'.$met_msn_type.'.gif"/></a></span>';
		if($val[taobao]!="")$metinfo.='<span class="met_taobao"><a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v='.$met_taobao_type.'&uid='.$val[taobao].'&site=cntaobao&s=2&charset=utf-8" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid='.$val[taobao].'&site=cntaobao&s='.$met_taobao_type.'&charset=utf-8" alt="'.$val[name].'" /></a></span>';
		$metinfo.="</dd>"; 
		$metinfo.="</dl>"; 
		$metinfo.='<div class="clear"></div>'; 
	}
	foreach($skype_list as $key=>$val){
		$metinfo.='<div class="met_skype"><a href="callto://'.$val[skype].'"><img src="'.$met_url.'images/skype/skype_'.$met_skype_type.'.gif" border="0"></a></div>';
	}
	foreach($alibaba_list as $key=>$val){
		$metinfo.='<div class="met_alibaba">
		<a target="_blank" href="http://amos1.sh1.china.alibaba.com/msg.atc?v=1&uid='.$val[alibaba].'"><img border="0" src="http://amos1.sh1.china.alibaba.com/online.atc?v=1&uid='.$val[alibaba].'&s='.$met_alibaba_type.'" alt="'.$val[name].'"></a></div>';
	} 
	//online over
	$metinfo.='			</div>';
	$metinfo.='		</div>';
	if($met_onlinetel!=""){
	$metinfo.='		<div class="onlinebox-bottom">';
	$metinfo.='			<div class="onlinebox-bottom-box"><div class="online-tbox">';
	$metinfo.=$met_onlinetel;
	$metinfo.='			</div></div>';
	$metinfo.='		</div>';
	}
	$metinfo.='<div class="onlinebox-bottom-bg"></div>';
	if($met_online_skin<3)$metinfo.='</div>';
	$metinfo.='</div>';
	$_REQUEST['jsoncallback'] = strip_tags($_REQUEST['jsoncallback']);
	if($_REQUEST['jsoncallback']){
		$metinfo=str_replace("'","\'",$metinfo);
		$metinfo=str_replace('"','\"',$metinfo);
		$metinfo=preg_replace("'([\r\n])[\s]+'", "", $metinfo);
		echo $_REQUEST['jsoncallback'].'({"metcms":"'.$metinfo.'"})';
	}else{
		echo $metinfo;
	}
	die();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>