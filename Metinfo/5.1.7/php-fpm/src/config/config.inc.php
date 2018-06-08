<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
/*读语言配置*/
$query = "SELECT * FROM $met_lang order by no_order";
$result = $db->query($query);
while($list_config= $db->fetch_array($result)){
	$list_config['order']=$list_config['no_order'];
	if($list_config['lang']=='metinfo'){
		$met_langadmin[$list_config['mark']]=$list_config;
	}else{
		$met_langok[$list_config['mark']]=$list_config;
	}
}
/*域名跳转判断*/
$met_url_type = $db->get_one("SELECT * FROM $met_config WHERE name='met_url_type' and lang='metinfo'");
$met_url_type = $met_url_type['value'];
if($met_url_type and $lang==""){
	foreach($met_langok as $key=>$val){
		if(strstr($val[met_weburl],"http://".$_SERVER["HTTP_HOST"].'/'))$lang=$val[mark];
	}
}
/*默认语言*/
$met_index_type = $db->get_one("SELECT * FROM $met_config WHERE name='met_index_type' and lang='metinfo'");
$met_index_type = $met_index_type['value'];
$lang=($lang=="")?$met_index_type:$lang;
$langoks = $db->get_one("SELECT * FROM $met_lang WHERE lang='$lang'");
if(!$langoks)die('No data in the database,please reinstall.');
if(!$langoks[useok]&&!$metinfoadminok)okinfo('../404.html');
if(count($met_langok)==1)$lang=$met_index_type;
/*读配置数据*/
$query = "SELECT * FROM $met_config WHERE lang='$lang' or lang='metinfo'";
$result = $db->query($query);
while($list_config= $db->fetch_array($result)){
	if($metinfoadminok)$list_config['value']=str_replace('"', '&#34;', str_replace("'", '&#39;',$list_config['value']));
	$settings_arr[]=$list_config;
	if($list_config['columnid']){
		$settings[$list_config['name'].'_'.$list_config['columnid']]=$list_config['value'];
	}else{
		$settings[$list_config['name']]=$list_config['value'];
	}
	if($list_config['flashid']){
		$list_config['value']=explode('|',$list_config['value']);
		$falshval['type']=$list_config['value'][0];
		$falshval['x']=$list_config['value'][1];
		$falshval['y']=$list_config['value'][2];
		$falshval['imgtype']=$list_config['value'][3];
		$met_flasharray[$list_config['flashid']]=$falshval;
	}
}
@extract($settings);
/*app引用*/
$query="select * from $met_app where site is not null and download=1";
$app_file_temp = $db->get_all($query);
$app_file=array();
foreach($app_file_temp as $key=>$val){
	$sites=explode('-',$val['site']);
	$urls=explode('-',$val['url']);
	foreach($sites as $keysite=>$valsite){
		$app_file[$valsite].='|'.$urls[$keysite];
	}
}
foreach($app_file as $key=>$val){		
	$app_file[$key]=trim($app_file[$key],'|');
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>