<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$met_weburls=explode('/',$met_weburl);
$url_now=$_SERVER['SERVER_NAME']?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];
$domain=$met_weburl;
if($url_now!=$met_weburls[2]){
	$domain=str_replace($met_weburls[2],$url_now,$met_weburl);
}
$sidebarcolumn=$db->get_all("select * from $met_admin_column order by type desc,list_order");
foreach($sidebarcolumn as $key=>$val){
	if((($val[name]=='lang_indexcode')||($val[name]=='lang_indexebook')||($val[name]=='lang_indexbbs')||($val[name]=='lang_indexskinset'))&&$met_agents_type>1)continue;
	if((($val[name]=='lang_webnanny')||($val[name]=='lang_smsfuc'))&&$met_agents_sms==0)continue;
	if((($val[name]=='lang_myapp'))&&$met_agents_app==0)continue;
	if(strstr($val['name'],"lang_")){
		if(strstr($val['name'],"|lang_")){
			$linshi = '';
			$linshi = explode('|',$val['name']);
			$val['name']=$$linshi[0].$$linshi[1];
		}else{
			$val['name']=$$val['name'];
		}
	}
	switch($val['type']){
		case 1:
			$metinfocolumn[]=$val;
		break;
		case 2:
			$purview='admin_pop'.$val['field'];
			$purview=$val['field']==0?'metinfo':$$purview;
			if($metinfo_admin_pop=="metinfo" || $purview=='metinfo'){
				if(strstr($val['url'],"http://")){
					$val['property']='target="_blank"';
				}else{
					$val['property']="target='main' id='nav_{$val[bigclass]}_{$val[id]}'";
					if($val['url']=='/interface/info.php'){
						$val['property']="target='_blank' id='nav_{$val[bigclass]}_{$val[id]}'";
						$val['url']=$domain.$met_adminfile.$val['url'];
					}
					if(strstr($val['url'],"?")){
						$val['url'].='&anyid='.$val['id'].'&lang='.$lang;
					}else{
						$val['url'].='?anyid='.$val['id'].'&lang='.$lang;
					}
				}
				$sidebarcolumns[]=$val;
				$letplace[$val['id']]=$val;
				$ad_navlist2[$val['bigclass']][]=$val;
			}
		break;
	}
}
$i=0;
foreach($sidebarcolumns as $key=>$val){
	if($val['bigclass']==2)$i++;
}
if($i==1)$metinfocolumn[1]['display']=1;
$sidebarcolumn=$sidebarcolumns;
foreach($metinfocolumn as $key=>$val){
	$toplace[$val['id']]=$val;
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>