<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once substr(dirname(__FILE__), 0, -6).'common.inc.php';
$modulename[1] = array(0=>'show',1=>'show');
$modulename[2] = array(0=>'news',1=>'shownews');
$modulename[3] = array(0=>'product',1=>'showproduct');
$modulename[4] = array(0=>'download',1=>'showdownload');
$modulename[5] = array(0=>'img',1=>'showimg');
$modulename[6] = array(0=>'job',1=>'showjob');
$modulename[7] = array(0=>'message',1=>'index');
$modulename[8] = array(0=>'feedback',1=>'index');	
$modulename[9] = array(0=>'link',1=>'index');	
$modulename[10]= array(0=>'member',1=>'index');	
$modulename[11]= array(0=>'search',1=>'search');	
$modulename[12]= array(0=>'sitemap',1=>'sitemap');
$modulename[100]= array(0=>'product',1=>'showproduct');
$modulename[101]= array(0=>'img',1=>'showimg');

$navurl    = $index=='index'?'':'../';
$met_logox = explode('../',$met_logo);
$weburly   = $index=='index'?'':'../';
$met_logo  = $met_pseudo?$weburly.$met_logox[1]:($index=='index'?$met_logox[1]:$met_logo);
$skinurl   = 'templates/'.$met_skin_user;
$css_url   = $weburly.$skinurl.'/css/';
$img_url   = $weburly.$skinurl.'/images/';
$met_url   = $weburly.'public/';
$metweburl = ROOTPATH;
$weburly   = $index=='index'?'':'../';



$countlang = count($met_langok);
if($met_index_type==$lang)$countlang=1;
require_once file_exists($navurl.$skinurl.'/metinfo.inc.php')?$navurl.$skinurl.'/metinfo.inc.php':ROOTPATH.'config/metinfo.inc.php';
$metadmin[pagename]=1;
$cache_column=met_cache(ROOTPATH.'cache/'."column_".$lang.".inc.php");
if(!$cache_column){
	$cache_column=cache_column();
}
reset($cache_column);
while($columnid=current($cache_column)){
	$langnums=$countlang;
	$listc=&$cache_column[$columnid['id']];
/*url地址*/
	$listc['foldername'] = ereg_replace(" ","",$listc['foldername']);
	$listc['filename'] = ereg_replace(" ","",$listc['filename']);
	if($listc['filename'] && $listc['filename']!=''){
		$met_ahtmtype=$met_chtmtype;
	}else{
		$met_ahtmtype=$met_htmtype;
	}
	if($metadmin['categorymarkimage']){
		$listc['indeximgarray']=explode("../",$listc['indeximg']);
		$listc['indeximg']=($index=="index")?$listc['indeximgarray'][1]:$listc['indeximg'];
	}
	if($metadmin['categorymage']){
		$listc['columnimgarray']=explode("../",$listc['columnimg']);
		$listc['columnimg']=($index=="index")?$listc['columnimgarray'][1]:$listc['columnimg'];
	}
	if($listc['samefile']||$cache_column[$listc['bigclass']]['samefile']||$cache_column[$cache_column[$listc['bigclass']]['bigclass']]['samefile']){
		$langnums=2;
	}
	$urltop = $weburly.$listc['foldername'].'/';
	if($langnums==1&&($listc['classtype']==1||$listc['releclass'])){
		if($listc['url']==NULL){
			if($listc['module']==0){$listc['url'] = (strstr($listc['out_url'],"http://"))?$listc['out_url']:$navurl.$listc['out_url'];}
			else{$listc['url']=$urltop;}
		}
	}
	else{
		switch($listc['module']){
			default:
				if($met_pseudo){
					$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$listc['id'];
					$listc['url']=$urltop.'list-'.$psid.'-'.$lang.'.html';
				}
				else if($met_webhtm==2){
					$pudo_type= !$met_htmlistname?$modulename[$listc['module']][0]:$listc['foldername'];
					if($listc['filename']<>"" and $metadmin['pagename']){
						$listc['url']=$urltop.$listc['filename'].'_1'.$met_ahtmtype;
					}
					else{
						$psid=$pudo_type;
						if($met_listhtmltype==0&&($listc['classtype']==2||$listc['classtype']==3)&&!$listc['releclass']){
							if($listc['classtype']==2){
								$listc['url']=$urltop.$psid.'_'.$listc['bigclass'].'_'.$listc['id'].'_1'.$met_ahtmtype;
							}
							else{
								if($cache_column[$listc['bigclass']]['releclass']){
									$listc['url']=$urltop.$psid.'_'.$listc['bigclass'].'_'.$listc['id'].'_1'.$met_ahtmtype;
								}
								else{
									$listc['url']=$urltop.$psid.'_'.$cache_column[$listc['bigclass']]['bigclass'].'_'.$listc['bigclass'].'_'.$listc['id'].'_1'.$met_ahtmtype;
								}
							}								
						}
						else{
							$listc['url']=$urltop.$psid.'_'.$listc['id'].'_1'.$met_ahtmtype;
						}
					}
				}
				else{
				$urltop2 = $urltop.$modulename[$listc['module']][0].'.php?'.$langmark;
					if($listc['releclass']){
						$listc['url']=$urltop2."&class1=".$listc['id'];
					}
					else{
						$classtypenum=$cache_column[$listc['bigclass']]['releclass']?$listc['classtype']-1:$listc['classtype'];
						switch($classtypenum){
						case 1:
						$listc['url']=$urltop2."&class1=".$listc['id'];
						break;
						case 2:
						$listc['url']=$urltop2."&class2=".$listc['id'];
						break;
						case 3:
						$listc['url']=$urltop2."&class3=".$listc['id'];
						break;
						}
					}
				}
				break;
			case 0:	
				$listc['url'] = (strstr($listc['out_url'],"http://"))?$listc['out_url']:$navurl.$listc['out_url'];
				break;
			case 1:
				if($listc['isshow']!=0){
					if($met_pseudo){
						$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$listc['id'];
						$listc['url']=$urltop.$psid.'-'.$lang.'.html';
					}
					else if($met_webhtm==2){
						$pudo_type=$listc['foldername'];
						$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$pudo_type.$listc['id'];
						$listc['url']=$urltop.$psid.$met_ahtmtype;
					}
					else{
						$listc['url']=$urltop.'show.php?'.$langmark.'&id='.$listc['id'];
					}
				}
				break;
			case 6:
				if($met_pseudo){
					$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$listc['id'];
					$listc['url']=$urltop."list-".$psid.'-'.$lang.'.html';
				}
				else if($met_webhtm==2){
					$pudo_type= !$met_htmlistname?$modulename[$listc['module']][0]:$listc['foldername'];
					$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$pudo_type.'_'.$listc['id'];
					$listc['url']=$urltop.$psid.'_1'.$met_ahtmtype;
				}
				else{
					$listc['url']=$urltop.'index.php?'.$langmark;
				}
				break;
			case 7:
				if($met_pseudo){
					$listc['url']=$urltop.'index-'.$lang.'.html';
				}
				else if($met_webhtm==2){
					$pudo_type= !$met_htmlistname?"index_list_1":"message_list_1";
					$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename'].'_1':$pudo_type;
					$listc['url']=$urltop.$psid.$met_ahtmtype;
				}
				else{
					$listc['url']=$urltop.'index.php?'.$langmark;			
				}
				break;
			case 8:
				if($met_pseudo){
					$listc['url']=$urltop.'index-'.$lang.'.html';
				}
				else if($met_webhtm==2){
					$pudo_type="index";
					$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$pudo_type;
					$listc['url']=$urltop.$psid.$met_ahtmtype;
				}
				else{
					$listc['url']=$urltop.'index.php?'.$langmark.'&id='.$listc['id'];
				}	
				break;
			case 9:
			case 10:
			case 12:
				$listc['url']=(($met_pseudo)?$urltop.'index-'.$lang.'.html':(($met_webhtm==2)?$urltop.'index'.$met_ahtmtype:$urltop.'index.php?'.$langmark));
				break;	
			case 11:
				$listc['url']=($met_pseudo)?$urltop.'index-'.$lang.'.html':$urltop.'index.php?'.$langmark;
				break;
		}
	}	
	if($listc['module']==100||$listc['module']==101){
		$productimg= $listc['module']==100?'product':'img';
		if($listc['module']==100){$productlistid=$listc['id'];}
		else{$imglistid=$listc['id'];}
		if($met_pseudo){
			$listc['url']=$urltop.$productimg.'-list-'.$lang.'.html';
		}
		else if($met_webhtm==2){
			$psid= ($listc['filename']<>"" and $metadmin['pagename'])?$listc['filename']:$productimg;
			$listc['url']=$urltop.$psid.'_'.$listc['id']."_1".$met_ahtmtype;
		}
		else{
			$listc['url']=$urltop.$productimg.'.php?'.$langmark;
		}
	}
	if($listc['if_in'])$listc['url'] = $listc['out_url'];
	//===============================简介栏目只做栏目*/
	if($cache_column[$listc['bigclass']]['isshow']==0&&$cache_column[$listc['bigclass']]['url']==NULL&&$listc['classtype']!=1){
		$cache_column[$listc['bigclass']]['url']=$listc['url'];
	}
	next($cache_column);	
}

foreach($cache_column as $key=>$val){
	$column_no_order[$key]=$val['no_order'];
}
array_multisort($column_no_order,SORT_ASC,SORT_NUMERIC,$cache_column);
foreach($cache_column as $key=>$list){			
	$nav_listall[]=$list;
	$class_list[$list['id']]=$list;
	$module_listall[$list['module']][]=$list;
	if($list['classtype']==1){
		$nav_list_1[]=$list;
		$module_list1[$list['module']][]=$list;
		$class1_list[$list['id']]=$list;
		if($list['module']==2 or $list['module']==3 or $list['module']==4 or $list['module']==5)$nav_search[]=$list; 
	} 
	if($list['classtype']==2){
		$nav_list_2[]=$list;
		$module_list2[$list['module']][]=$list;
		$nav_list2[$list['bigclass']][]=$list;
		$class2_list[$list['id']]=$list;
	}
	if($list['classtype']==3){
		$nav_list_3[]=$list;
		$module_list3[$list['module']][]=$list;
		$nav_list3[$list['bigclass']][]=$list;
		$class3_list[$list['id']]=$list;
	}
	if($list['nav']==1 or $list['nav']==3)$nav_list[]=$list;
	if($list['nav']==2 or $list['nav']==3)$navfoot_list[]=$list;
	if($list['classtype']==1&&$list['module']==1&&$list['isshow']==1){$nav_listabout[]=$list;}
	if($list['index_num']!="" and $list['index_num']!=0){
		$list['classtype']=$list['releclass']?"class1":"class".$list['classtype'];
		$class_index[$list['index_num']]=$list;
	}
}

$addmessage_url=$met_pseudo?$navurl.'message/message-'.$lang.'.html':($met_webhtm?$navurl.'message/message'.$met_htmtype:$navurl.'message/message.php?'.$langmark);
$cv['url']=$met_pseudo?'jobcv-0-'.$lang.'.html':($met_webhtm?$navurl."job/cv".$met_htmtype:$navurl."job/cv.php?".$langmark);
$addfeedback_url=$met_pseudo?$navurl.'feedback/index-'.$lang.'.html':($met_webhtm?$navurl.'feedback/'.$addfeedback_url.$met_htmtype:$navurl.'feedback/index.php?'.$langmark);
$cv['url']=$met_pseudo?'jobcv-0-'.$lang.'.html':$navurl."job/cv.php?".$langmark."&selectedjob=";
$addfeedback_url=$navurl."feedback/index.php?".$langmark."&title=";
$member_indexurl=$navurl."member/".$member_index_url;
$member_registerurl=$navurl."member/".$member_register_url;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>