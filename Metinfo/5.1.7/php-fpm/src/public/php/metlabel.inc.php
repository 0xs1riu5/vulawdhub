<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
//Head部分
function metlabel_html5($closure=1,$iehack=1){
	global $met_title,$show,$m_now_year,$navurl,$met_js_access,$met_skin_css,$img_url,$met_webname,$metcms_v,$appscriptcss,$met_ch_lang,$lang,$met_ch_mark,$met_url,$metinfouiok,$classnow,$class_list;
	$metinfo="<!DOCTYPE HTML>\n";
	$metinfo.="<html>\n";
	$metinfo.="<head>\n";
	$metinfo.="<meta charset=\"utf-8\" />\n";
	$metinfo.="<title>".$met_title."</title>\n";
	$metinfo.="<meta name=\"description\" content=\"".$show['description']."\" />\n";
	$metinfo.="<meta name=\"keywords\" content=\"".$show['keywords']."\" />\n";
	$metinfo.="<meta name=\"generator\" content=\"MetInfo {$metcms_v}\" />\n";
	$metinfo.="<link href=\"".$navurl."favicon.ico\" rel=\"shortcut icon\" />\n";
	if($met_js_access)$metinfo.=$met_js_access."\n";
	if($met_skin_css=='')$met_skin_css='metinfo.css';
	if($metinfouiok==1)$metinfo.="<link rel=\"stylesheet\" type=\"text/css\" href=\"{$navurl}public/ui/met/css/metinfo_ui.css\" id=\"metuimodule\" data-module =\"{$class_list[$classnow][module]}\" />\n";
	$metinfo.="<link rel=\"stylesheet\" type=\"text/css\" href=\"".$img_url."css/".$met_skin_css."\" />\n";
	$metinfo.="<script src=\"{$navurl}public/js/jQuery1.7.2.js\" type=\"text/javascript\"></script>\n";
	if($metinfouiok==1)$metinfo.="<script src=\"{$navurl}public/ui/met/js/metinfo_ui.js\" type=\"text/javascript\"></script>\n";
	if($met_ch_lang and $lang==$met_ch_mark)$metinfo.="<script src=\"".$met_url."js/ch.js\" type=\"text/javascript\"></script>\n";
	if($appscriptcss)$metinfo.="{$appscriptcss}\n";
	if($iehack){
	$metinfo.="<!--[if IE]>\n";
	$metinfo.="<script src=\"{$navurl}public/js/html5.js\" type=\"text/javascript\"></script>\n";
	$metinfo.="<![endif]-->";
	}
	if($closure)$metinfo.="\n</head>";
	return $metinfo;
}
//网站默认样式
function metlabel_style($closure=1){
	global $lang_fontfamily,$lang_fontsize,$lang_backgroundcolor,$lang_fontcolor,$lang_urlcolor,$lang_hovercolor;
	if($lang_fontfamily<>''||$lang_fontsize<>''||$lang_backgroundcolor<>''||$lang_fontcolor<>''||$lang_urlcolor<>''||$lang_hovercolor<>''){
		$metinfo.="<style type=\"text/css\">\n";
		$metinfo.="body{\n";
		$lang_fontfamily=str_replace("&quot;","\"",$lang_fontfamily);
		if($lang_fontfamily<>'')$metinfo.=" font-family:".$lang_fontfamily.";\n";
		if($lang_fontsize<>'')$metinfo.="	font-size:".$lang_fontsize.";\n"; 
		if($lang_backgroundcolor<>'')$metinfo.="	background:".$lang_backgroundcolor."; \n";
		if($lang_fontcolor<>'')$metinfo.="	color:".$lang_fontcolor.";\n";
		$metinfo.="}\n";
		if($lang_fontcolor<>'' or $lang_fontfamily<>''){
		   $metinfo.="table td{";
		   if($lang_fontfamily<>'')$metinfo.="font-family:".$lang_fontfamily.";"; 
		   if($lang_fontcolor<>'')$metinfo.="color:".$lang_fontcolor.";";
		   $metinfo.="}\n";
		}
		if($lang_fontcolor<>'' or $lang_fontfamily<>''){
		   $metinfo.="table th{";
		   if($lang_fontfamily<>'')$metinfo.="font-family:".$lang_fontfamily.";"; 
		   if($lang_fontcolor<>'')$metinfo.="color:".$lang_fontcolor.";";
		   $metinfo.="}\n";
		}
		if($lang_urlcolor<>'')$metinfo.="a{color:".$lang_urlcolor.";}\n";
		if($lang_hovercolor<>'')$metinfo.="a:hover{color:".$lang_hovercolor.";}\n";
		if($closure)$metinfo.="</style>\n";
		return $metinfo;
	}
}
function metlabel_flash(){
	global $methtml_flash,$met_flasharray,$classnow,$met_flashimg,$navurl;
	if($met_flasharray[$classnow][type]){
		if($met_flasharray[$classnow][type]==1){
		switch($met_flasharray[$classnow][imgtype]){
			case 6:
					$metinfo.="\n<link href='{$navurl}public/flash/flash6/css.css' rel='stylesheet' type='text/css' />\n";
					$metinfo.="<script src='{$navurl}public/flash/flash6/jquery.bxSlider.min.js'></script>";
					$metinfo.="<div class='flash flash6' style='width:".$met_flasharray[$classnow][x]."px; height:".$met_flasharray[$classnow][y]."px;'>\n";
					$metinfo.="<ul id='slider6' class='list-none'>\n";
					foreach($met_flashimg as $key=>$val){
						$val[img_link]=str_replace('%26','&',$val[img_link]);
						$metinfo.="<li><a href='".$val[img_link]."' target='_blank' title='{$val[img_title]}'>\n";
						$metinfo.="<img src='".$val[img_path]."' alt='".$val[img_title]."' width='{$met_flasharray[$classnow][x]}' height='{$met_flasharray[$classnow][y]}'></a></li>\n"; 
					}
					$metinfo.="</ul>\n";
					$metinfo.="</div>\n";
					$metinfo.="<script type='text/javascript'>$(document).ready(function(){ $('#slider6').bxSlider({ mode:'vertical',autoHover:true,auto:true,pager: true,pause: 5000,controls:false});});</script>";
			break;
			case 8:
					$metinfo.="\n<link rel='stylesheet' href='{$navurl}public/jq-flexslider/flexslider.css' type='text/css'>\n";
					$metinfo.="<script src='{$navurl}public/jq-flexslider/jquery.flexslider-min.js'></script>";
					$thisflash_x=$met_flasharray[$classnow][x]-8;
					$thisflash_y=$met_flasharray[$classnow][y]-8;
					$metinfo.="<div class='flash'><div class='flexslider flexslider_flash flashfld'><ul class='slides list-none'>";
					foreach($met_flashimg as $key=>$val){
						$val[img_link]=str_replace('%26','&',$val[img_link]);
						$metinfo.="<li><a href='".$val[img_link]."' target='_blank' title='{$val[img_title]}'>\n";
						$metinfo.="<img src='".$val[img_path]."' alt='".$val[img_title]."' width='{$met_flasharray[$classnow][x]}' height='{$met_flasharray[$classnow][y]}'></a></li>\n"; 
					}
					$metinfo.="</ul></div></div>";
					$metinfo.="<script type='text/javascript'>$(document).ready(function(){ $('.flashfld').flexslider({ animation: 'slide',controlNav:false});});</script>";
			break;
			default:
				if(!$hd)$metinfo=$methtml_flash;
			break;
		}
		}else{
			$metinfo=$methtml_flash;
		}
		return $metinfo;
	}
}
function metlabel_foot(){
	global $met_footright,$met_footstat,$met_footaddress,$met_foottel,$met_footother,$met_foottext;
	if($met_footright<>"" or $met_footstat<>"")$metinfo.="<p>".$met_footright." ".$met_footstat."</p>\n";
	if($met_footaddress<>"")$metinfo.="<p>".$met_footaddress."</p>\n";
	if($met_foottel<>"")$metinfo.="<p>".$met_foottel."</p>\n";
	if($met_footother<>"")$metinfo.="<p>".$met_footother."</p>\n";
	if($met_foottext<>"")$metinfo.="<p>".$met_foottext."</p>\n";
	return $metinfo;
}
//顶部导航函数
function metlabel_nav($type=1,$label='',$z,$l){
	global $index_url,$lang_home,$nav_list,$nav_list2,$nav_list3,$navdown,$lang;
	if($z){
		$navnum=count($nav_list)+1;
		$width=($z/$navnum)-$l+($l/$navnum);
		$dwidth=array();
		if(strstr($width,".")){
			$width=sprintf("%.1f",$width);
			$y=explode('.',$width);
			$f=(int)$y[0];
			$k='0.'.$y[1];
			$k=round($k*$navnum);
			for($i=0;$i<$navnum;$i++){
				$m=$k<1?$f:$f+1;
				$dwidth[$i]=$m;
				$k=$k-1;
			}
		}else{
			for($i=0;$i<$navnum;$i++){
				$dwidth[$i]=$width;
			}
		}
	}
	$style0=$dwidth[0]?"style='width:{$dwidth[0]}px;'":'';
	$cdown=$navdown==10001?"class='navdown'":'';
	switch($type){
		case 1:
			$metinfo ='<ul class="list-none">';
			$metinfo.="<li id=\"nav_10001\" {$style0} {$cdown}>";
			$metinfo.="<a href='{$index_url}' title='{$lang_home}' class='nav'><span>{$lang_home}</span></a>";
			$metinfo.="</li>";
			$p=0;
			foreach($nav_list as $key=>$val){
			$p++;
			$stylei=$dwidth[$p]?"style='width:{$dwidth[$p]}px;'":'';
			$cdown=$val['id']==$navdown?"class='navdown'":'';
			$metinfo.=$label;
			$metinfo.="<li id='nav_{$val[id]}' {$stylei} {$cdown}>";
			$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='hover-none nav'><span>{$val[name]}</span></a>";
			$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			break;
		case 2:
			$metinfo ='<ul class="list-none">';
			$metinfo.="<li id=\"nav_10001\" {$style0} {$cdown}>";
			$metinfo.="<a href='{$index_url}' title='{$lang_home}' class='nav'><span>{$lang_home}</span></a>";
			$metinfo.="</li>";
			$p=0;
			foreach($nav_list as $key=>$val){
			$p++;
			$stylei=$dwidth[$p]?"style='width:{$dwidth[$p]}px;'":'';
			$cdown=$val['id']==$navdown?"class='navdown'":'';
			$metinfo.=$label;
			$metinfo.="<li id='nav_{$val[id]}' {$stylei} {$cdown}>";
			$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='hover-none nav'><span>{$val[name]}</span></a>";
			if(count($nav_list2[$val['id']])){
				$metinfo.="<dl>";
				foreach($nav_list2[$val['id']] as $key=>$val2){
					$metinfo.="<dd><a href='{$val2[url]}' title='{$val2[name]}' {$val2[new_windows]}>{$val2[name]}</a></dd>";
				}
				$metinfo.="</dl>";
			}
			$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			break;
		case 3:
			$metinfo ='<ul class="list-none">';
			$metinfo.="<li id=\"nav_10001\" {$style0}>";
			$metinfo.="<a href='{$index_url}' title='{$lang_home}' class='nav'><span>{$lang_home}</span></a>";
			$metinfo.="</li>";
			$p=0;
			foreach($nav_list as $key=>$val){
			$p++;
			$stylei=$dwidth[$p]?"style='width:{$dwidth[$p]}px;'":'';
			$cdown=$val['id']==$navdown?"class='navdown'":'';
			$metinfo.=$label;
			$metinfo.="<li id='nav_{$val[id]}' {$stylei} {$cdown}>";
			$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='hover-none nav'><span>{$val[name]}</span></a>";
			if(count($nav_list2[$val['id']])){
				$metinfo.="<dl>";
				foreach($nav_list2[$val['id']] as $key=>$val2){
					$metinfo.="<dd><a href='{$val2[url]}' title='{$val2[name]}' {$val2[new_windows]}>{$val2[name]}</a>";
					if(count($nav_list3[$val2['id']])){
					$metinfo.="<p>";
						foreach($nav_list3[$val2['id']] as $key=>$val3){
							$metinfo.="<a href='{$val3[url]}' title='{$val3[name]}' {$val3[new_windows]}>{$val3[name]}</a>";
						}
					$metinfo.="</p>";
					}
					$metinfo.="</dd>";
				}
				$metinfo.="</dl>";
			}
			$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			break;
	}
	return $metinfo;
}
function metlable_lang($dt,$tp=1){
	global $methtml_sethome,$methtml_addfavorite,$index_hadd_ok,$app_file,$met_adminfile;
	$metinfo=methtml_lang($dt,$tp);
	if($index_hadd_ok)$metinfo=$metinfo==''?$methtml_sethome.$dt.$methtml_addfavorite:$methtml_sethome.$dt.$methtml_addfavorite.$dt.$metinfo;
	$file_site = explode('|',$app_file[4]);
	foreach($file_site as $keyfile=>$valflie){
		if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)){require_once ROOTPATH."$met_adminfile".$valflie;}
	}
	return $metinfo;
}
//内页左侧栏目标签
function metlabel_sidebar($title=0,$msow=0){
	global $class_list,$classnow,$nav_list2,$class1;
	$thismod=$class_list[$classnow]['module'];
	if($title){
		$metinfo=$class_list[$class1]['name'];
		if($thismod==11 || $thismod==10)$metinfo=$class_list[$classnow]['name'];
	}else{
		$metinfo=$thismod==11?methtml_advsearch():($thismod==10?membernavlist(1):($nav_list2[$class1]!=''?metlabel_navnow(2,'','','','',$msow):0));
		if($thismod>99)$metinfo=metlabel_navnow(2,'','','','',$msow);
	}
	return $metinfo;
}
//侧边导航函数
function metlabel_navnow($type=1,$label='',$indexnum,$listyy=0,$listmax=8,$msow=0){
	global $index_url,$nav_list,$nav_list2,$nav_list3,$class1,$class_list,$module_list1,$class_index,$classlistall,$lang;
	$class=$indexnum?$class_index[$indexnum]['id']:$class1;
	if($indexnum&&strstr($indexnum,"-")){
		$hngy5=explode('-',$indexnum);
		if($hngy5[1]=='cm')$class=$hngy5[0];
	}
	$mod=$class_index[$indexnum]['module'];
	if($class_list[$class1]['module']>99 && !$indexnum){
		$mod=$class_list[$class1]['module']==100?3:5;
		$type=3;
	}
	$module=metmodname($mod);
	switch($type){
		case 1:
			$metinfo ='<ul class="list-none navnow">';
			$i=0;
			foreach($nav_list2[$class] as $key=>$val){
				$i++;
				if($i!=1)$metinfo.=$label;
				$metinfo.="<li id='navnow1_{$val[id]}'>";
				$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='nav'><span>{$val[name]}</span></a>";
				$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			return $metinfo;
			break;
		case 2:
			$i=0;
			foreach($nav_list2[$class] as $key=>$val){
				$metinfo.='<dl class="list-none navnow">';
				$i++;
				if($i!=1)$metinfo.=$label;
				$lst3cun=count($nav_list3[$val['id']]);
				$zm=$lst3cun?'':'class="zm"';
				$metinfo.="<dt id='part2_{$val[id]}'>";
				$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' {$zm}><span>{$val[name]}</span></a>";
				$metinfo.="</dt>";
				$modlist=($listyy && $listmax)?methtml_getarray($val['id'],'','',$mod,$listmax,0,0,1):"";
				foreach($modlist as $key=>$list){
					$classlistall[$module][$val['id']][]=$list;
				}
				if($lst3cun){
					$msows=$msow==2?'style="display:none;"':'';
					$metinfo.='<dd class="sub" '.$msows.'>';
					foreach($nav_list3[$val['id']] as $key=>$val2){
						$metinfo.="<h4 id='part3_{$val2[id]}'>";
						$metinfo.="<a href='{$val2[url]}' {$val2[new_windows]} title='{$val2[name]}' class='nav'><span>{$val2[name]}</span></a>";
						$modlist=($listyy && $listmax)?methtml_getarray($val2['id'],'','',$mod,$listmax,0,0,1):"";
						foreach($modlist as $key=>$list){
							$classlistall[$module][$val2['id']][]=$list;
						}
						if(count($classlistall[$module][$val2['id']]) && $listyy && $listmax){
							$metinfo.="<p>";
							$i=0;
							foreach($classlistall[$module][$val2['id']] as $key=>$val3){
								$i++;
								$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
								if($i>=$listmax)break;
							}
							$metinfo.="</p>";
						}
						$metinfo.="</h4>";
					}
					$metinfo.="</dd>";
				}elseif($listyy && $listmax && count($classlistall[$module][$val['id']])>0){
					$metinfo.="<dd class='sub'>";
					$metinfo.="<p>";
					$i=0;
					foreach($classlistall[$module][$val['id']] as $key=>$val3){
						$i++;
						$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
						if($i>=$listmax)break;
					}
					$metinfo.="</p>";
					$metinfo.="</dd>";
				}
				$metinfo.="</dl>";
			}
			return $metinfo;
			break;
		case 3:
			foreach($module_list1[$mod] as $key=>$val0){
				$class=$val0[id];
				$metinfo.="<h2><a href='{$val0[url]}' title='{$val0[name]}' {$val0[new_windows]}>{$val0[name]}</a></h2>";
				$i=0;
				foreach($nav_list2[$class] as $key=>$val){
					$metinfo.='<dl class="list-none navnow">';
					$i++;
					if($i!=1)$metinfo.=$label;
					$metinfo.="<dt id='part2_{$val[id]}'>";
					$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='nav'><span>{$val[name]}</span></a>";
					$metinfo.="</dt>";
					if(count($nav_list3[$val['id']])){
						$msows=$msow==2?'style="display:none;"':'';
						$metinfo.='<dd class="sub" '.$msows.'>';
						foreach($nav_list3[$val['id']] as $key=>$val2){
							$metinfo.="<h4 id='part3_{$val2[id]}'>";
							$metinfo.="<a href='{$val2[url]}' {$val2[new_windows]} title='{$val2[name]}' class='nav'><span>{$val2[name]}</span></a>";
							$modlist=($listyy && $listmax)?methtml_getarray($val2['id'],'','',$mod,$listmax,0,0,1):"";
							foreach($modlist as $key=>$list){
								$classlistall[$module][$val2['id']][]=$list;
							}
							if(count($classlistall[$module][$val2['id']]) && $listyy && $listmax){
								$metinfo.="<p>";
								$i=0;
								foreach($classlistall[$module][$val2['id']] as $key=>$val3){
									$i++;
									$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
									if($i>=$listmax)break;
								}
								$metinfo.="</p>";
							}
							$metinfo.="</h4>";
						}
						$metinfo.="</dd>";
					}elseif($listyy && $listmax){
						$metinfo.="<dd class='sub'>";
						$metinfo.="<p>";
						$i=0;
						$modlist=($listyy && $listmax)?methtml_getarray($val['id'],'','',$mod,$listmax,0,0,1):"";
						foreach($modlist as $key=>$list){
							$classlistall[$module][$val['id']][]=$list;
						}
						foreach($classlistall[$module][$val['id']] as $key=>$val3){
							$i++;
							$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
							if($i>=$listmax)break;
						}
						$metinfo.="</p>";
						$metinfo.="</dd>";
					}
					$metinfo.="</dl>";
				}
			}
			return $metinfo;
			break;
	}
}
//模块列表信息调用函数
function metlabel_list($listtype='text',$mark,$type,$order,$module,$time=0,$titleok=1,$bian=1,$listmx,$txtmax){
	global $class_index,$index,$lang,$class_list,$metblank;
	global $index_news_no,$index_product_no,$index_download_no,$index_img_no,$index_job_no;
	$modules=$mark?$class_index[$mark]['module']:$module;
	$modules=$modules?$modules:2;
	$marktype=0;
	if($mark&&strstr($mark,"-")){
		$hngy5=explode('-',$mark);
		if($hngy5[1]=='cm'){
			$mark=$hngy5[0];
			$modules=$class_list[$mark]['module'];
			$marktype=1;
			$module=$modules;
		}
		if($hngy5[1]=='md'){
			$mark='';
			$modules=$hngy5[0];
			$module=metmodname($hngy5[0]);
		}
	}
	$listarray=methtml_getarray($mark,$type,$order,$module,$listmx,'','',$marktype,$txtmax);
	
	switch($listtype){
		case 'img':
			$metinfo.="<ol class='list-none metlist'>";
			$i=0;
			foreach($listarray as $key=>$val){
			$i++;
			$metinfo.="<li class='list'>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank} class='img'><img src='{$val[imgurls]}' alt='{$val[title]}' title='{$val[title]}' width='{$val[img_x]}' height='{$val[img_y]}' /></a>";
if($titleok)$metinfo.="<h3 style='width:{$val[img_x]}px;'><a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a></h3>";
			$metinfo.="</li>";
			}
			$metinfo.="</ol>";
			break;
		case 'text':
			$metinfo.="<ol class='list-none metlist'>";
			$i=0;
			foreach($listarray as $key=>$val){
			$i++;$top='';
			if($i==1)$top='top';
			$metinfo.="<li class='list {$top}'>";
	if($bian){$a='[';$b=']';}
   if($time)$metinfo.="<span class='time'>{$a}{$val[updatetime]}{$b}</span>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a>{$val[hot]}{$val[news]}{$val[top]}";
			$metinfo.="</li>";
			}
			if($modules==1)$metinfo.=$marktype==1?$class_list[$mark]['description']:$class_index[$mark]['description'];
			$metinfo.="</ol>";
		    break;
	}
	return $metinfo;
}

//会员侧导航
function membernavlist($type=0){
	global $lang,$lang_memberIndex3,$lang_memberIndex4,$lang_memberIndex5,$lang_memberIndex6,$lang_memberIndex7,$lang_memberIndex10,$app_file,$met_adminfile,$met_mermber_metinfo_news_left_class;
	$class=$met_mermber_metinfo_news_left_class?$met_mermber_metinfo_news_left_class:'membernavlist';/*兼容以前模板*/
	if($type==1){
		$metinfo.="<dl class='$class'>";
		$metinfo.="<dt><a href='basic.php?lang={$lang}' title='{$lang_memberIndex3}'>{$lang_memberIndex3}</a></dt>";
		$metinfo.="<dt><a href='editor.php?lang={$lang}' title='{$lang_memberIndex4}'>{$lang_memberIndex4}</a></dt>";
		$metinfo.="<dt><a href='feedback.php?lang={$lang}' title='{$lang_memberIndex5}'>{$lang_memberIndex5}</a></dt>";
		$metinfo.="<dt><a href='message.php?lang={$lang}' title='{$lang_memberIndex6}'>{$lang_memberIndex6}</a></dt>";
		$metinfo.="<dt><a href='cv.php?lang={$lang}' title='{$lang_memberIndex7}'>{$lang_memberIndex7}</a></dt>";
		$file_site = explode('|',$app_file[3]);
		foreach($file_site as $keyfile=>$valflie){
			if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)){require ROOTPATH."$met_adminfile".$valflie;}
		}
		$metinfo.="<dt><a href='login_out.php?lang={$lang}' title='{$lang_memberIndex10}'>{$lang_memberIndex10}</a></dt>";
		$metinfo.="</dl>";
	}else{
		$metinfo.="<ul class='$class'>";
		$metinfo.="<li><a href='basic.php?lang={$lang}' title='{$lang_memberIndex3}'>{$lang_memberIndex3}</a></li>";
		$metinfo.="<li><a href='editor.php?lang={$lang}' title='{$lang_memberIndex4}'>{$lang_memberIndex4}</a></li>";
		$metinfo.="<li><a href='feedback.php?lang={$lang}' title='{$lang_memberIndex5}'>{$lang_memberIndex5}</a></li>";
		$metinfo.="<li><a href='message.php?lang={$lang}' title='{$lang_memberIndex6}'>{$lang_memberIndex6}</a></li>";
		$metinfo.="<li><a href='cv.php?lang={$lang}' title='{$lang_memberIndex7}'>{$lang_memberIndex7}</a></li>";
		$file_site = explode('|',$app_file[3]);
		foreach($file_site as $keyfile=>$valflie){
			if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)){require ROOTPATH."$met_adminfile".$valflie;}
		}
		$metinfo.="<li><a href='login_out.php?lang={$lang}' title='{$lang_memberIndex10}'>{$lang_memberIndex10}</a></li>";
		$metinfo.="</ul>";
	}
	return $metinfo;
}
//文章模块列表函数
function metlabel_news($time=1,$desc=0,$dlen,$dt=1,$n=0){
	global $news_list,$metblank,$id;
	$metinfo.="<ul class='list-none metlist'>";
	$i=0;
	foreach($news_list as $key=>$val){
		if(!$n || $id!=$val[id]){
			$i++;$top='';
			if($dlen)$val['description']=utf8substr($val['description'],0,$dlen);
			if($i==1)$top='top';
			$metinfo.="<li class='list {$top}'>";
			if($dt){$a='[';$b=']';}
			if($time)$metinfo.="<span>{$a}{$val[updatetime]}{$b}</span>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a>{$val[hot]}{$val[news]}{$val[top]}";
			if($desc&&$val['description']!='')$metinfo.="<p>{$val[description]}</p>";
			$metinfo.="</li>";	
		}
	}
	$metinfo.="</ul>";
	return $metinfo;
}
//产品模块列表函数
function metlabel_product($z,$w,$l,$n=0){
	global $product_list,$metblank,$met_img_style,$met_img_x,$met_img_y,$met_product_page,$class1,$class2,$class3,$search,$nav_list2,$nav_list3,$weburly,$id,$met_agents_img;
	$met_img_x=$met_img_style?met_imgxy(1,'product'):$met_img_x;
	$met_img_y=$met_img_style?met_imgxy(2,'product'):$met_img_y;
	$metinfo.="<ul class='list-none metlist'>";
	$listarray=$product_list;
	$metok=0;
	if($met_product_page && $search<>'search'){
		if($class2 && count($nav_list3[$class2]) && !$class3){
			$listarray=$nav_list3[$class2];
			$metok=1;
		}
		if(!$class2 && count($nav_list2[$class1]) && $class1 && !$class3){
			$listarray=$nav_list2[$class1];
			$metok=1;
		}
	}
	if($z){
		$l=$l?$l:floor($z/$w);
		$margin=(($z/$l)-$w)/2;
		$margin=$margin<0?(($z/(floor($z/$w)))-$w)/2:$margin;
		$dwidth=array();
		if(strstr($margin,".")){
			$margin=sprintf("%.1f",$margin);
			$y=explode('.',$margin);
			$f=(int)$y[0];
			$k='0.'.$y[1];
			$k=intval($k*$l);
			for($i=0;$i<$l;$i++){
				$m=$k<1?$f:$f+1;
				$dwidth[$i]=$m;
				$k=$k-1;
			}
		}else{
			for($i=0;$i<$l;$i++){
				$dwidth[$i]=$margin;
			}
		}
	}
	$i=0;
	foreach($listarray as $key=>$val){
		if(!$n || $id!=$val[id]){
			if($metok){
				$val['title']=$val['name'];
				$val['imgurls']=$val['columnimg']==''?$weburly.$met_agents_img:$val['columnimg'];
			}
			$style=$dwidth[$i]?"style='width:{$w}px; margin-left:{$dwidth[$i]}px; margin-right:{$dwidth[$i]}px;'":'';
			$metinfo.="<li class='list' {$style}>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank} class='img'><img src='{$val[imgurls]}' alt='{$val[title]}' title='{$val[title]}' width='{$met_img_x}' height='{$met_img_y}' /></a>";
			$metinfo.="<h3><a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a></h3>";
			$metinfo.="</li>";
			$i++;
			if($i==$l)$i=0;
		}
	}
	$metinfo.="</ul>";
	return $metinfo;
}
//图片模块列表函数
function metlabel_img($z,$w,$l,$n=0){
	global $img_list,$metblank,$met_img_style,$met_img_x,$met_img_y,$met_img_page,$class1,$class2,$class3,$search,$nav_list2,$nav_list3,$weburly,$id;
	$met_img_x=$met_img_style?met_imgxy(1,'img'):$met_img_x;
	$met_img_y=$met_img_style?met_imgxy(2,'img'):$met_img_y;
	$metinfo.="<ul class='list-none metlist'>";
	$listarray=$img_list;
	$metok=0;
	if($met_img_page && $search<>'search'){
		if($class2 && count($nav_list3[$class2]) && !$class3){
			$listarray=$nav_list3[$class2];
			$metok=1;
		}
		if(!$class2 && count($nav_list2[$class1]) && $class1 && !$class3){
			$listarray=$nav_list2[$class1];
			$metok=1;
		}
	}
	if($z){
		$l=$l?$l:floor($z/$w);
		$margin=(($z/$l)-$w)/2;
		$margin=$margin<0?(($z/(floor($z/$w)))-$w)/2:$margin;
		$dwidth=array();
		if(strstr($margin,".")){
			$margin=sprintf("%.1f",$margin);
			$y=explode('.',$margin);
			$f=(int)$y[0];
			$k='0.'.$y[1];
			$k=intval($k*$l);
			for($i=0;$i<$l;$i++){
				$m=$k<1?$f:$f+1;
				$dwidth[$i]=$m;
				$k=$k-1;
			}
		}else{
			for($i=0;$i<$l;$i++){
				$dwidth[$i]=$margin;
			}
		}
	}
	$i=0;
	foreach($listarray as $key=>$val){
		if(!$n || $id!=$val[id]){
			if($metok){
				$val['title']=$val['name'];
				$val['imgurls']=$val['columnimg']==''?$weburly.$met_agents_img:$val['columnimg'];
			}
			$style=$dwidth[$i]?"style='width:{$w}px; margin-left:{$dwidth[$i]}px; margin-right:{$dwidth[$i]}px;'":'';
			$metinfo.="<li class='list' {$style}>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank} class='img'><img src='{$val[imgurls]}' alt='{$val[title]}' title='{$val[title]}' width='{$met_img_x}' height='{$met_img_y}' /></a>";
			$metinfo.="<h3><a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a></h3>";
			$metinfo.="</li>";
			$i++;
			if($i==$l)$i=0;
		}
	}
	$metinfo.="</ul>";
	return $metinfo;
}
//下载模块列表函数
function metlabel_download(){
	global $download_list,$metblank,$lang_Detail,$lang_Download,$lang_FileSize,$lang_Hits,$lang_UpdateTime;
	$i=0;
	foreach($download_list as $key=>$val){
	$i++;$top='';
	if($i==1)$top='top';
	$fiz=sprintf("%.2f",$val['filesize']/1024);
	$val['filesize']=$fiz>1?$fiz:$val['filesize'];
	$bd=$fiz>1?'Mb':'Kb';
		$metinfo.="<dl class='list-none metlist {$top}'>";
		$metinfo.="<dt>";
		$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a>";
		$metinfo.="</dt>";
		$metinfo.="<dd>";
		$metinfo.="<div>";
		$metinfo.="<a href='{$val[url]}' {$metblank} title='{$lang_Detail}'>{$lang_Detail}</a> - ";
		$metinfo.="<a href='{$val[downloadurl]}' class='down' {$metblank} title='{$lang_Download}'>{$lang_Download}</a>";
		$metinfo.="</div>";
		$metinfo.="<span><b>{$lang_FileSize}</b>：{$val[filesize]} {$bd}</span>";
		$metinfo.="<span><b>{$lang_Hits}</b>：{$val[hits]}</span>";
		$metinfo.="<span><b>{$lang_UpdateTime}</b>：{$val[updatetime]}</span>";
		$metinfo.="</dd>";
		$metinfo.="</dl>";
	}
	return $metinfo;
}
//招聘模块列表函数
function metlabel_job($type){
	global $job_list,$metblank,$lang_cvtitle,$lang_Detail,$lang_AddDate,$lang_WorkPlace,$lang_PersonNumber,$lang_Position,$lang_several;
	if($type==1){
		foreach($job_list as $key=>$val){
			$i++;$top='';
			if($i==1)$top='top';
			$val['count']=$val['count']?$val['count']:$lang_several;
			$metinfo.="<dl class='list-none metlist'>";
			$metinfo.="<dt><a href='{$val[url]}' title='{$val[position]}' {$metblank}>{$val[position]}</a></dt>";
			$metinfo.="<dd class='list {$top}'>";
			$metinfo.="<div class='mis'><span>{$lang_AddDate}：{$val[addtime]}</span>";
			$metinfo.="<span>{$lang_WorkPlace}：{$val[place]}</span>";
			$metinfo.="<span>{$lang_PersonNumber}：{$val[count]}</span></div>";
			$metinfo.="<div class='editor'>{$val[content]}</span></div>";
			$metinfo.="<div class='dtail'><span><a href='{$val[cv]}' title='{$lang_cvtitle}' {$metblank}>{$lang_cvtitle}</a></span>";
			$metinfo.="<span><a href='{$val[url]}' title='{$lang_Detail}' {$metblank}>{$lang_Detail}</a></span></div>";
			$metinfo.="</dl>";
		}
	}else{
		$metinfo.="<dl class='list-none metlist'>";
		$metinfo.="<dt>";
		$metinfo.="<span>{$lang_cvtitle}</span>";
		$metinfo.="<span>{$lang_Detail}</span>";
		$metinfo.="<span>{$lang_AddDate}</span>";
		$metinfo.="<span>{$lang_WorkPlace}</span>";
		$metinfo.="<span>{$lang_PersonNumber}</span>";
		$metinfo.="{$lang_Position}";
		$metinfo.="</dt>";
		$i=0;
		foreach($job_list as $key=>$val){
		$i++;$top='';
		if($i==1)$top='top';
		$val['count']=$val['count']?$val['count']:$lang_several;
			$metinfo.="<dd class='list {$top}'>";
			$metinfo.="<span><a href='{$val[cv]}' title='{$lang_cvtitle}' {$metblank}>{$lang_cvtitle}</a></span>";
			$metinfo.="<span><a href='{$val[url]}' title='{$lang_Detail}' {$metblank}>{$lang_Detail}</a></span>";
			$metinfo.="<span>{$val[addtime]}</span>";
			$metinfo.="<span>{$val[place]}</span>";
			$metinfo.="<span>{$val[count]}</span>";
			$metinfo.="<a href='{$val[url]}' title='{$val[position]}' {$metblank}>{$val[position]}</a>";
		}
		$metinfo.="</dl>";
	}
	return $metinfo;
}
//留言提交表单函数
function messagelabel_table($dy){
	global $lang,$fdjs,$lang_Name,$lang_Phone,$lang_Email,$lang_OtherContact,$lang_Info5,$lang_SubmitContent,$fromurl,$m_user_ip,$lang_SubmitInfo,$lang_Reset,$lang_MessageInfo3,$lang_MessageInfo4;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl;
	$lujin='';
	if($dy)$lujin=$navurl.'message/';
	$metinfo.="<form method='POST' name='myform' onSubmit='return metmessagesubmit(\"{$lang_MessageInfo3}\",\"{$lang_MessageInfo4}\");' action='{$lujin}message.php?action=add' target='_self'>\n";
	$metinfo.="<table class='message_table'>\n";
	$metinfo.="<tr>\n";
	$metinfo.="<td class='text'>".$lang_Name."</td>\n";
	$metinfo.="<td class='input'><input name='pname' type='text' class='input-text' /><span class='info'>*</span></td>\n";
	$metinfo.="</tr>\n";
	$metinfo.="<tr>\n";
	$metinfo.="<td class='text'>".$lang_Phone."</td>\n";
	$metinfo.="<td class='input'><input name='tel' type='text' class='input-text' /></td>\n";
	$metinfo.="</tr>\n";
	$metinfo.="<tr>\n";
	$metinfo.="<td class='text'>".$lang_Email."</td>\n";
	$metinfo.="<td class='input'><input name='email' type='text' class='input-text' /></td>\n";
	$metinfo.="</tr>\n";
	$metinfo.="<tr>\n";
	$metinfo.="<td class='text'>".$lang_OtherContact."</td>\n";
	$metinfo.="<td class='input'><input name='contact' type='text' class='input-text' />".$lang_Info5."</td>\n";
	$metinfo.="</tr>\n";
	$metinfo.="<tr>\n";
	$metinfo.="<td class='text'>".$lang_SubmitContent."</td>\n";
	$metinfo.="<td class='input'><textarea name='info' cols='50' rows='6' class='textarea-text'></textarea><span class='info'>*</span></td>\n";
	$metinfo.="</tr>\n";
if($met_memberlogin_code==1){
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='{$navurl}member/ajax.php?action=code'  onclick=this.src='{$navurl}member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	$metinfo.="<tr><td class='text'></td><td class='submint'>\n";
	$metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
	$metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
	$metinfo.="<input type='submit' name='Submit' value='".$lang_SubmitInfo."' class='submit button orange'></td></tr>\n";
	$metinfo.="</table>\n";
	$metinfo.="</form>\n";
	return $metinfo;
}
//留言列表函数
function metlabel_messagelist(){
	global $lang,$message_list,$lang_SubmitContent,$lang_Reply;
	$i=count($message_list);
	foreach($message_list as $key=>$val){
	$metinfo.="<dl class='list-none metlist'>\n";
	$metinfo.="<dt class='title'><span class='tt'>{$i}<sup>#</sup></span><span class='name'>{$val[name]}</span><span class='time'>{$lang_Publish} {$val[addtime]}</span></dt>\n";
	$metinfo.="<dd class='info'><span class='tt'>{$lang_SubmitContent}</span><span class='text'>{$val[info]}</span></dd>\n";
	$metinfo.="<dd class='reinfo'><span class='tt'>{$lang_Reply}</span><span class='text'>{$val[useinfo]}</span></dd>\n";
	$metinfo.="</dl>\n";
	$i--;
	}
	return $metinfo;
}
//反馈提交表单函数
function metlabel_feedback($fid){
	global $lang,$message_list,$lang_Submit,$lang_Reset,$lang_Publish,$lang_Reply,$fromurl,$m_user_ip,$id;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl,$settings_arr;
	global $db,$met_parameter,$met_member_use,$metinfo_member_type,$met_list,$met_class,$class_list,$met_product,$lang_Choice,$lang_Empty;
	if($fid)$id=$fid;
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$id && $val['name']=='met_fdtable'){
			$title=$val['value'];
		}
	}
	$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 and class1='$id' order by no_order";
	if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 and class1='$id'  and access<=$metinfo_member_type order by no_order";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	 if($list[type]==2 or $list[type]==4 or $list[type]==6){
		$listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
		$listinfoid=intval(trim($listinfo[info]));
		if($listinfo){
		$listmarknow='metinfo';
		$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
		$query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' order by updatetime desc";
	   $result1 = $db->query($query1);
	   $i=0;
	   while($list1 = $db->fetch_array($result1)){
		 $list1[info]=$list1[title];
		 $i++;
		 $list1[no_order]=$i;
	   $paravalue[$list[id]][]=$list1;
	   }
		}else{
	   $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
	   $result1 = $db->query($query1);
	   while($list1 = $db->fetch_array($result1)){
	   $paravalue[$list[id]][]=$list1;
	   }
	   }}
	if($list[wr_ok]=='1')$list[wr_must]="*";
	switch($list[type]){
	case 1:
	$list[input]="<input name='para$list[id]' type='text' size='30' class='input-text' />";
	break;
	case 2:
	$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
	foreach($paravalue[$list[id]] as $key=>$val){
	$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
	}
	$list[input]=$list[input]."</select>";
	break;
	case 3:
	$list[input]="<textarea name='para$list[id]' class='textarea-text' cols='50' rows='5'></textarea>";
	break;
	case 4:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$i++;
	$list[input]=$list[input]."<input name='para$list[id]_$i' class='checboxcss' id='para$i$list[id]' type='checkbox' value='$val[info]' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
	}
	$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
	$lagernum[$list[id]]=$i;
	break;
	case 5:
	$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
	break;
	case 6:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$checked='';
	$i++;
	if($i==1)$checked="checked='checked'";
	$list[input]=$list[input]."<input name='para$list[id]' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
	 }
	break;
	}
	$fd_para[]=$list;
	if($list[wr_ok])$fdwr_list[]=$list;
	}
	$fdjs="<script language='javascript'>";
	$fdjs=$fdjs."function Checkfeedback(){ ";
	foreach($fdwr_list as $key=>$val){
	if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
	$fdjs=$fdjs."if (document.myform.para$val[id].value.length == 0) {\n";
	$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
	$fdjs=$fdjs."return false;}\n";
	}elseif($val[type]==4){
	 $lagerinput="";
	 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
	 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
	 }
	 $lagerinput=$lagerinput."false\n";
	 $fdjs=$fdjs."if(!($lagerinput)){\n";
	 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
	 $fdjs=$fdjs."return false;}\n";
	}
	}
	$fdjs=$fdjs."}</script>";
	$lujin='';
	if($fid)$lujin=$navurl.'feedback/';
     $metinfo =$fdjs;
     $metinfo.="<form enctype='multipart/form-data' method='POST' name='myform' onSubmit='return Checkfeedback();' action='{$lujin}index.php?action=add&lang=".$lang."' target='_self'>\n";
     $metinfo.="<table class='feedback_table' >\n";
    foreach($fd_para as $key=>$val){
     $metinfo.="<tr>\n";
     $metinfo.="<td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$val[input]."<span class='info'>{$val[wr_must]}</span></td>\n";
     $metinfo.="</tr>\n";
    }
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='{$navurl}member/ajax.php?action=code'  onclick=this.src='{$navurl}member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	 $metinfo.="<tr><td class='text'></td>\n";
	 $metinfo.="<td class='submint'>\n";
     $metinfo.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
     $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
	 $metinfo.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
	 $metinfo.="<input type='hidden' name='id' value='".$id."' />\n";
	 if($fid)$metinfo.="<input type='hidden' name='fid_url' value='1' />\n";//5.0.4
     $metinfo.="<input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange'></td></tr>\n";
     $metinfo.="</table>\n";
     $metinfo.="</form>\n";
	return $metinfo;
}
//友情链接提交表单函数
function metlabel_addlink($tt=1){
	global $lang_Info4,$lang_LinkInfo2,$lang_LinkInfo3,$lang_OurWebName,$met_linkname,$lang_OurWebUrl,$met_weburl,$lang_OurWebLOGO,$met_logo,$lang_OurWebKeywords,$met_keywords,$lang_YourWebName,$lang_YourWebUrl,$lang_LinkType,$lang_TextLink,$lang_PictureLink,$lang_YourWebLOGO,$lang_YourWebKeywords,$lang_Contact,$lang_Submit,$lang_Reset,$lang;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile;
	$metinfo.="<form method='POST' name='myform' onSubmit='return addlinksubmit(\"{$lang_LinkInfo2}\",\"{$lang_LinkInfo3}\");' action='addlink.php?action=add' target='_self'>\n";
	$metinfo.="<table class='addlink_table'>\n";
	if($tt)$metinfo.="<tr><td class='title' colspan='2'>{$lang_Info4}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebName}</td>\n";
	$metinfo.="<td class='input'>{$met_linkname}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebUrl}</td>\n";
	$metinfo.="<td class='input'>{$met_weburl}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebLOGO}</td>\n";
	$metinfo.="<td class='input'><img src='{$met_logo}' alt='{$lang_OurWebName}' title='{$lang_OurWebName}' /></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebKeywords}</td>\n";
	$metinfo.="<td class='input'>{$met_keywords}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebName}</td>\n";
	$metinfo.="<td class='input'><input name='webname' type='text' class='input-text' size='30' /><span class='info'>*</span></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebUrl}</td>\n";
	$metinfo.="<td class='input'><input name='weburl' type='text' class='input-text' size='30' value='http://' /><span class='info'>*</span></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_LinkType}</td>\n";
	$metinfo.="<td class='input'><input name='link_type' type='radio' value='0' id='textlinkradio' checked='checked' /><label for='textlinkradio'>{$lang_TextLink}</label>  <input name='link_type' type='radio' value='1' id='imglinkradio' /><label for='imglinkradio'>{$lang_PictureLink}</label><span class='info'>*</span></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebLOGO}</td>\n";
	$metinfo.="<td class='input'><input name='weblogo' type='text' class='input-text' size='30' value='http://'/></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebKeywords}</td>\n";
	$metinfo.="<td class='input'><input name='info' type='text' class='input-text' size='30' /></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_Contact}</td>\n";
	$metinfo.="<td class='input'><textarea name='contact' cols='50' class='textarea-text' rows='6'></textarea></td></tr>\n";
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	$metinfo.="<tr><td class='text'></td>\n";
	$metinfo.="<td class='submint'>\n";
	$metinfo.="<input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange'>\n";
	$metinfo.="<input type='hidden' name='lang' value='".$lang."'></tr>\n";
	$metinfo.="</table>\n";
	$metinfo.="</form>\n";
	return $metinfo;
}
//在线应聘提交表单函数
function metlabel_cv(){
	global $fdjs,$lang,$lang_Nolimit,$lang_memberPosition,$selectjob,$cv_para,$paravalue,$met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$lang_Submit,$lang_Reset,$met_adminfile;
     $metinfo.=$fdjs;
     $metinfo.="<form  enctype='multipart/form-data' method='POST' onSubmit='return Checkcv();' name='myform' action='save.php?action=add' target='_self'>\n";
     $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $metinfo.="<table class='cv_table'>\n";
     $metinfo.="<tr><td class='text'>".$lang_memberPosition."</td>\n";
     $metinfo.="<td class='input'><select name='jobid' id='jobid'>".$selectjob."</select><span class='info'>*</span></td></tr>\n";
    foreach($cv_para as $key=>$val){
     switch($val[type]){
	 case 1:;
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'><input name='".$val[para]."' type='text' class='input-text' size='40'><span class='info'>".$val[wr_must]."</span></td></tr>\n";
	 break;
	 case 2:
	 $tmp="<select name='para$val[id]'>";
     $tmp=$tmp."<option value=''>{$lang_Nolimit}</option>";
     foreach($paravalue[$val[id]] as $key=>$val1){
      $tmp=$tmp."<option value='$val1[info]' $selected >$val1[info]</option>";
      }
     $tmp=$tmp."</select>";;
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$tmp."<span class='info'>".$val[wr_must]."</span></td></tr>\n";
	 break;
	 case 3:
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'><textarea name='".$val[para]."' class='textarea-text' cols='60' rows='5'></textarea><span class='info'>".$val[wr_must]."</span></td></tr>\n";
     break;
	 case 4:
	 $tmp1="";
     $i=0;
     foreach($paravalue[$val[id]] as $key=>$val1){
     $i++;
     $tmp1=$tmp1."<input name='para$val[id]_$i' type='checkbox' id='para$val[id]_$i' value='$val1[info]' ><label for='para$val[id]_$i'>{$val1[info]}</label>  ";
     }
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$tmp1."<span class='info'>".$val[wr_must]."</span></td></tr>\n";
     break;
	 case 5:
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'><input name='".$val[para]."' type='file' class='input-file' size='20' /><span class='info'>".$val[wr_must]."</span></td></tr>\n";
	 break;
	 case 6:
	 $tmp2="";
     $i=0;
     foreach($paravalue[$val[id]] as $key=>$val2){
     $checked='';
     $i++;
     if($i==1)$checked="checked='checked'";
     $tmp2=$tmp2."<input name='para$val[id]' type='radio' id='para$val[id]_$i' value='$val2[info]' $checked /><label for='para$val[id]_$i'>$val2[info]</label>  ";
     }
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$tmp2."<span class='info'>".$val[wr_must]."</span></td></tr>\n";
	 break;
    }
   }
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}	  
     $metinfo.="<tr><td class='text'></td>\n";
     $metinfo.="<td class='submint'><input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange' /></td>\n";
     $metinfo.="</tr>";		
     $metinfo.="</table>";
     $metinfo.="</form>";
	 return $metinfo;
}
//网站地图
function sitemaplist(){
	global $db,$nav_listall,$m_now_date,$met_sitemap_not1,$met_sitemap_not2,$lang,$met_langok,$met_index_url,$met_webname,$met_weburl;
	$indexar=array('title'=>$met_webname,'url'=>$met_index_url[$lang],'updatetime'=>date("Y-m-d"),'priority'=>1);
	$sitemaplist[]=$indexar;
	foreach($nav_listall as $key=>$val){
		$no1ok=$val[nav]?1:($met_sitemap_not1 && !$val['bigclass']?0:1);
		$no2ok=$val[if_in]==0?1:($met_sitemap_not2?0:1);
		if($val[module]!=10 && $val[module]!=11 && $no1ok && $no2ok && $val[isshow]==1){
			$val[updatetime]=date("Y-m-d",strtotime($m_now_date));
			$val[title]=$val[name];
			$val[url]=str_replace('../','',$val[url]);
			$val[url]=$met_weburl.$val[url];
			$sitemaplist[]=$val;
		}
	}
	foreach(methtml_getarray('','all','time','news',50000) as $key=>$val){
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','product',50000) as $key=>$val){
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','download',50000) as $key=>$val){
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','img',50000) as $key=>$val){
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','job',50000) as $key=>$val){
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val[title]=$val[position];
		$val[updatetime]=$val[addtime];
		$sitemaplist[]=$val;
	}
	return $sitemaplist;
}
$csnow=$csnow?$csnow:$classnow;
$methtml_flash=metlabel_flash();
$file_site = explode('|',$app_file[2]);
foreach($file_site as $keyfile=>$valflie){
	if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)){require_once ROOTPATH."$met_adminfile".$valflie;}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>