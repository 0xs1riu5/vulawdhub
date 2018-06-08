<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
//head
$methtml_head="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
$methtml_head.="<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
$methtml_head.="<head>\n";
$methtml_head.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
$methtml_head.="<title>".$met_title."</title>\n";
$methtml_head.="<meta name=\"description\" content=\"".$show['description']."\" />\n";
$methtml_head.="<meta name=\"keywords\" content=\"".$show['keywords']."\" />\n";
$methtml_head.="<meta name=\"author\" content=\"{$met_webname}\" />\n";
$methtml_head.="<meta name=\"copyright\" content=\"Copyright 2008-".$m_now_year." MetInfo\" />\n";
$methtml_head.="<link href=\"".$navurl."favicon.ico\" rel=\"shortcut icon\" />\n";
if($met_js_access)$methtml_head.=$met_js_access."\n";
//memberjs
if($met_skin_css=='')$met_skin_css='metinfo.css';
$methtml_head.="<link rel=\"stylesheet\" type=\"text/css\" href=\"".$img_url."css/".$met_skin_css."\" />\n";
$methtml_head.="<script src=\"".$met_url."js/metinfo-min.js\" type=\"text/javascript\"></script>\n";
if($met_ch_lang and $lang==$met_ch_mark)$methtml_head.="<script src=\"".$met_url."js/ch.js\" type=\"text/javascript\"></script>\n";
//style
if($lang_fontfamily<>''||$lang_fontsize<>''||$lang_backgroundcolor<>''||$lang_fontcolor<>''||$lang_urlcolor<>''||$lang_hovercolor<>''){
$methtml_head.="<style type=\"text/css\">\n";
$methtml_head.="body{\n";
$lang_fontfamily=str_replace("&quot;","\"",$lang_fontfamily);
if($lang_fontfamily<>'')$methtml_head.=" font-family:".$lang_fontfamily.";\n";
if($lang_fontsize<>'')$methtml_head.="	font-size:".$lang_fontsize.";\n"; 
if($lang_backgroundcolor<>'')$methtml_head.="	background:".$lang_backgroundcolor."; \n";
if($lang_fontcolor<>'')$methtml_head.="	color:".$lang_fontcolor.";\n";
$methtml_head.="}\n";
if($lang_fontcolor<>'' or $lang_fontfamily<>''){
   $methtml_head.="table td{";
   if($lang_fontfamily<>'')$methtml_head.="font-family:".$lang_fontfamily.";"; 
   if($lang_fontcolor<>'')$methtml_head.="color:".$lang_fontcolor.";";
   $methtml_head.="}\n";
}
if($lang_fontcolor<>'' or $lang_fontfamily<>''){
   $methtml_head.="table th{";
   if($lang_fontfamily<>'')$methtml_head.="font-family:".$lang_fontfamily.";";
   if($lang_fontcolor<>'')$methtml_head.="color:".$lang_fontcolor.";";
   $methtml_head.="}\n";
}
if($lang_urlcolor<>'')$methtml_head.="a{color:".$lang_urlcolor.";}\n";
if($lang_hovercolor<>'')$methtml_head.="a:hover{color:".$lang_hovercolor.";}\n";
$methtml_head.="</style>\n";
}
if($appscriptcss)$methtml_head.="{$appscriptcss}\n";
$methtml_head.="<script src=\"".$navurl."public/js/public.js\" type=\"text/javascript\" language=\"javascript\"></script>\n";
$methtml_head.="</head>";

//time
$methtml_now="";
$methtml_now.=$lang_now."\n";
$methtml_now.="<script language=\"javascript\" type=\"text/javascript\">\n";
$methtml_now.="today=new Date();\n";
$methtml_now.="function initArray(){\n";
$methtml_now.="this.length=initArray.arguments.length\n";
$methtml_now.="for(var i=0;i<this.length;i++)\n";
$methtml_now.="this[i+1]=initArray.arguments[i]  }\n";
$methtml_now.="var d=new initArray(\n";
$methtml_now.="' ".$lang_sunday."',\n";
$methtml_now.="' ".$lang_monday."',\n";
$methtml_now.="' ".$lang_tuesday."',\n";
$methtml_now.="' ".$lang_wednesday."',\n";
$methtml_now.="' ".$lang_thursday."',\n";
$methtml_now.="' ".$lang_friday."',\n";
$methtml_now.="' ".$lang_saturday."');\n";
$methtml_now.="document.write(\n";
$methtml_now.="'',\n";
$methtml_now.="today.getFullYear(),'".$lang_year."',\n";
$methtml_now.="today.getMonth()+1,'".$lang_month."',\n";
$methtml_now.="today.getDate(),'".$lang_day."',\n";
$methtml_now.="d[today.getDay()+1],\n";
$methtml_now.="''); \n";
$methtml_now.="</script>\n";

if($index_hadd_ok){
//set home page
$methtml_sethome="<a href='#' onclick='SetHome(this,window.location,\"$lang_MessageInfo5\");' style='cursor:pointer;' title='".$lang_sethomepage."'  >".$lang_sethomepage."</a>";
//bookmark
$methtml_addfavorite="<a href='#' onclick='addFavorite(\"$lang_MessageInfo5\");' style='cursor:pointer;' title='".$lang_bookmark."'  >".$lang_bookmark."</a>";
$methtml_hadd=$methtml_sethome.'<span>|</span>'.$methtml_addfavorite;
}

//language switch
function methtml_lang($label,$type=1){
global $lang,$lang_chchinese,$met_ch_mark,$met_ch_lang,$met_langok,$met_url,$index_url,$met_index_url,$met_lang_mark,$met_waplink,$lang_wap,$met_wap_tpb,$met_wap_url,$met_wap,$met_index_type,$navurl,$app_file,$met_adminfile;
$metinfo='';
if($met_lang_mark){
	switch($type){
	case 1:
	$metinfo='';
	foreach($met_langok as $key=>$val){
	$urlnew=$val[newwindows]?"target='_blank'":"";
	if($val[useok] and $val[mark]!=$lang)$metinfo.="<a href='".$met_index_url[$val[mark]]."' title='$val[name]' $urlnew >".$val[name]."</a>".$label;
	}
	if($met_waplink && $met_wap){
		if($met_wap_tpb&&$met_wap_url){
			$metinfo="<a href='$met_wap_url' title='{$lang_wap}'>{$lang_wap}</a>".$label.$metinfo;
		}
		else{
			$indurl=$met_index_type==$lang?$index_url.'wap/':$navurl.'wap/index.php?lang='.$lang;
			$metinfo="<a href='{$indurl}' title='{$lang_wap}'>{$lang_wap}</a>".$label.$metinfo;
		}
	}
	if($met_ch_lang and $lang==$met_ch_mark){
	$metinfo="<a class=\"fontswitch\" id=\"StranLink\" href=\"javascript:StranBody()\">".$lang_chchinese."</a>".$label.$metinfo;
	}
	break;
	case 2:
	$metinfo='';
	foreach($met_langok as $key=>$val){
	$urlnew=$val[newwindows]?"target='_blank'":"";
	if($val[useok] and $val[mark]!=$lang)$metinfo.="<a href='".$met_index_url[$val[mark]]."' title='$val[name]' $urlnew ><img src='$val[flag]' border='0' /></a>".$label;
	}
	if($met_ch_lang and $lang==$met_ch_mark){
	$metinfo="<a class=\"fontswitch\" id=\"StranLink\" href=\"javascript:StranBody()\"><img src='".$met_langok[$met_ch_mark][flag]."' border='0' /></a>".$label.$metinfo;
	}
	break;
	}
	$labellen=strlen($label);
	$metinfo=$labellen?substr($metinfo, 0, -$labellen):$metinfo;
}else{
	if($met_waplink && $met_wap){
		if($met_wap_tpb&&$met_wap_url){
			$metinfo="<a href='$met_wap_url' title='{$lang_wap}'>{$lang_wap}</a>";
		}else{
			$indurl=$met_index_type==$lang?$index_url.'wap/':$navurl.'wap/index.php?lang='.$lang;
			$metinfo="<a href='{$indurl}' title='{$lang_wap}'>{$lang_wap}</a>";
		}
	}
	if($met_ch_lang and $lang==$met_ch_mark){
		$metinfol='';
		if($metinfo!='')$metinfol=$label.$metinfo;
		$metinfo="<a class=\"fontswitch\" id=\"StranLink\" href=\"javascript:StranBody()\">".$lang_chchinese."</a>".$metinfol;
	}
}
	$file_site = explode('|',$app_file[4]);
	foreach($file_site as $keyfile=>$valflie){
		if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)){require ROOTPATH."$met_adminfile".$valflie;}
	}
	return $metinfo;
}

//top nav
function methtml_topnav($type,$label,$max=100,$maxclass2=100,$classtitlemax=100,$homeclass=1,$homeok=1){
global $index_url,$lang_home,$nav_list,$lang,$classnow,$class1,$class_list,$class_index,$nav_list2;
switch($type){
case 1:
$metinfo="";
if($homeok)$metinfo.="<a id='nav_10001' href='".$index_url."'>".$lang_home."</a>".$label;
$i=$homeok;
foreach($nav_list as $key=>$val){
$i++;
$metinfo.="<a id='nav_".$val[id]."' href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."'>".$val[name]."</a>".$label;
if($i>=$max)break;
}
$labellen=strlen($label);
$metinfo=$labellen?substr($metinfo, 0, -$labellen):$metinfo;
return $metinfo;
break;

case 2:
if($class_list[$classnow][nav]==1 or $class_list[$classnow][nav]==3 or $classnow==10001){
    $navdown=$classnow;
  }else{
    $navdown=($class_list[$class1][nav]==1 or $class_list[$class1][nav]==3 )?$class1:'10001';
  }
$metinfo="<SCRIPT language=JavaScript type=text/JavaScript>\n";
$metinfo.="function topnavMouseOver(param1)\n";
$metinfo.="    {\n";
$metinfo.="     var param2='".$navdown."' \n";
$metinfo.="     document.getElementById('nav_'+param1).className='navdown';\n";
$metinfo.="		document.getElementById('nav_'+param2).className='navup';\n";
$metinfo.="    }	\n";
$metinfo.="	    function topnavMouseOut(param3)\n";
$metinfo.="    {   \n";
$metinfo.="     var param4='".$navdown."' \n";
$metinfo.="     document.getElementById('nav_'+param3).className='navup';\n";
$metinfo.="		document.getElementById('nav_'+param4).className='navdown';\n";
$metinfo.="    }\n";
$metinfo.="</SCRIPT>\n";
$metinfo.="<ul>\n";
if($homeok)$metinfo.="<li class='navdown' id='nav_10001'><a href='".$index_url."' onMouseOver=topnavMouseOver('10001') onMouseOut=topnavMouseOut('10001') >".$lang_home."</a></li>".$label."\n";
$i=$homeok;
foreach($nav_list as $key=>$val){
$i++;
$metinfo.="<li class='navup' id='nav_".$val[id]."'><a href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."' onMouseOver=topnavMouseOver('".$val[id]."') onMouseOut=topnavMouseOut('".$val[id]."')>".$val[name]."</a></li>".$label."\n";
if($i>=$max)break;
}
$metinfo.="<div style='clear:both;'></div>\n";
$metinfo.="</ul>\n";
$metinfo.="<SCRIPT language=JavaScript type=text/JavaScript>\n";
$metinfo.="     document.getElementById('nav_10001').className='navup';\n";
$metinfo.="   	document.getElementById('nav_'+".$navdown.").className='navdown';\n";
$metinfo.="</SCRIPT>\n";
return $metinfo;
break;

case 3:
if($homeok)$metinfo.="<div class='nav1'><a href='".$index_url."'  id='nav_10001'>".$lang_home."</a></div>".$label."\n";
$i=$homeok;
foreach($nav_list as $key=>$val){
$i++;
$metinfo.="<div class='nav1'  onmouseover=topnavMouseOver('".$val[id]."'); onMouseOut=topnavMouseOut('".$val[id]."');><a href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."' id='nav_".$val[id]."'>".$val[name]."</a>\n";
$metinfo.="<div class='nav2' style='display:none' id='nav2_".$val[id]."' >\n";
$j=0;
foreach($nav_list2[$val[id]] as $key=>$val2){
$j++;
$val2[name]=utf8substr($val2[name], 0, $classtitlemax); 
$metinfo.="		<li><a href='".$val2[url]."' ".$val2[new_windows]." title='".$val2[name]."'>".$val2[name]."</a></li>\n";
if($j>=$maxclass2)break;
} 
$metinfo.="</div>\n";	
$metinfo.="</div>".$label."\n";		 
if($i>=$max)break;
}
if($class_list[$classnow][nav]==1 or $class_list[$classnow][nav]==3 or $classnow==10001){
    $navdown=$classnow;
  }else{
    $navdown=($class_list[$class1][nav]==1 or $class_list[$class1][nav]==3 )?$class1:'10001';
  }
$metinfo.="<SCRIPT language=JavaScript type=text/JavaScript>\n";
$metinfo.="     document.getElementById('nav_".$navdown."').className='navdown';\n";
$metinfo.="		function topnavMouseOver(id){document.getElementById('nav2_'+id).style.display = ''; }\n";
$metinfo.="		function topnavMouseOut(id){document.getElementById('nav2_'+id).style.display = 'none';	 }\n";
$metinfo.="</script>	\n";	
$metinfo.="<div style='clear:both;'></div>\n";
return $metinfo;
break;

case 4:
$metinfo.="<ul>\n";
if($homeok)$metinfo.="<li class='navl' id='nav_10001' onMouseOver=topnavMouseOver('10001') ><a class='' href='".$index_url."'  title='".$lang_home."' id='navq_10001'><span>".$lang_home."</span></a></li>".$label."\n";
$i=$homeok;
foreach($nav_list as $key=>$val){
$i++;
$metinfo.="<li class='navl'  id='nav_".$val[id]."' onMouseOver=topnavMouseOver('".$val[id]."') ><a class='' href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."' id='navq_".$val[id]."'><span>".$val[name]."</span></a></li>".$label."\n";
if($i>=$max)break;
}
$metinfo.="<div style='clear:both;'></div>\n";
$metinfo.="</ul>\n";
if($homeok){
$metinfo.="<ul class='nav2' id='nav2_10001' style='display:none;' >\n";
$i=0;
foreach($nav_list2[$class_index[$homeclass][id]] as $key=>$val){
$i++;
$metinfo.="<li><a href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."'><span>".$val[name]."</span></a></li>\n";
if($i>=$maxclass2)break;
}
$metinfo.="</ul>\n";
}
$i=0;
foreach($nav_list as $key=>$val){
$i++;
$metinfo.="<ul id='nav2_".$val[id]."' class='nav2' style='display:none;' >\n";
$d=0;
foreach($nav_list2[$val[id]] as $key=>$val2){
$val2[name]=utf8substr($val2[name], 0, $classtitlemax); 
$d++;
$metinfo.="<li><a href='".$val2[url]."' ".$val2[new_windows]." title='".$val2[name]."'><span>".$val2[name]."</span></a></li>\n";
if($d>=$maxclass2)break;
}		 
$metinfo.="</ul>\n";
if($i>=$max)break;
}
if($class_list[$classnow][nav]==1 or $class_list[$classnow][nav]==3 or $classnow==10001){
    $navdown=$classnow;
  }else{
    $navdown=($class_list[$class1][nav]==1 or $class_list[$class1][nav]==3 )?$class1:'10001';
  }
$metinfo.="<script language=JavaScript type=text/JavaScript>\n";
$metinfo.="document.getElementById('navq_'+'".$navdown."').className='navdown';\n";
$metinfo.="document.getElementById('nav2_'+'".$navdown."').style.display = 'block';\n";
$metinfo.="var navcount;\n";
$metinfo.="subnav = new Array();\n";
if($homeok)$metinfo.="subnav[0] ='10001';\n";
$m=$homeok;
foreach($nav_list as $key=>$val){
$metinfo.="subnav[".$m."] = '".$val[id]."';\n";
$m++;
}
$metinfo.="navcount=".$m.";\n";
$metinfo.="function topnavMouseOver(ao){\n";
$metinfo.="      for(j=0;j<=navcount;j++){\n";
$metinfo.="			  if(ao==subnav[j]){\n";
$metinfo.="		    document.getElementById('nav2_' + subnav[j]).style.display = 'block';\n";
$metinfo.="			document.getElementById('navq_'+ subnav[j]).className='navdown';\n"; 
$metinfo.="			}else{\n";
$metinfo.="			document.getElementById('nav2_' + subnav[j]).style.display = 'none';\n";
$metinfo.="			document.getElementById('navq_'+ subnav[j]).className=''; \n";
$metinfo.="			} \n"; 
$metinfo.="			}\n";
$metinfo.="		   }\n";
$metinfo.="</script>\n";
return $metinfo;
break;
}

}

//foot nav
function methtml_footnav($label){
global $index_url,$lang_home,$navfoot_list,$lang;
$metinfo="";
foreach($navfoot_list as $key=>$val){
$metinfo.="<a href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."'>".$val[name]."</a>".$label;
}
$labellen=strlen($label);
$metinfo=$labellen?substr($metinfo, 0, -$labellen):$metinfo;
return $metinfo;
}

//x nav
$nav_x[name]="<a href=".$class1_info[url]." >".$class1_info[name]."</a>";
if($class2<>0){
$nav_x[name]=$nav_x[name]." > "."<a href=".$class2_info[url]." >".$class2_info[name]."</a>";
}
if($class3<>0){
$nav_x[name]=$nav_x[name]." > "."<a href=".$class3_info[url]." >".$class3_info[name]."</a>";
}
$nav_x[1]=$nav_x[name];
$nav_x[2]="<a href=".$class_list[$classnow][url]." >".$class_list[$classnow][name]."</a>";

//y nav
if($class_list[$class1][module]==100){
foreach($module_list1[3] as $key=>$val){
$navlist_y1.="<li><a href='$val[url]' $val[new_windows] title='$val[name]'>$val[name]</a></li>";
$class_2=$val[id];
foreach($nav_list2[$class_2] as $key=>$val2){
$navlist_y1.="<br />&nbsp;&nbsp;&nbsp;<font style='font-size:12px'><a href='$val2[url]' $val2[new_windows] title='$val2[name]' >-$val2[name]</a></font>";
}
}
}else{
foreach($nav_list2[$class1] as $key=>$val){
$navlist_y1.="<li><a href='$val[url]' $val[new_windows] title='$val[name]'>$val[name]</a></li>";
$class_2=$val[id];
foreach($nav_list3[$class_2] as $key=>$val2){
$navlist_y1.="<br />&nbsp;&nbsp;&nbsp;<font style='font-size:12px'><a href='$val2[url]' $val2[new_windows] title='$val2[name]' >-$val2[name]</a></font>";
}
}
}

function methtml_classlist($type,$namelen,$mark,$class3ok=1,$class3now=1,$class2char=1,$class3char=0){
global $navlist_y1,$class1,$class2,$class3,$classnow,$nav_list2,$nav_list3,$class_index,$module_list1,$class_list;
  switch($type){
  default:
  $metinfo=$navlist_y1;
  break;
  
  case 1:
    $metinfo='';
    $metinfo.="<ul>\n";
  if(intval($mark))$class1=$class_index[$mark][id];
  $nav2=$nav_list2[$class1];
  if($class_list[$class1][module]==100)$nav2=$module_list1[3];
  if($class_list[$class1][module]==101)$nav2=$module_list1[5];
  foreach($nav2 as $key=>$val){
    $val[name]=intval($namelen)?utf8substr($val[name], 0, $namelen):$val[name]; 
	$metinfo.="<li class='li_class2' id='product".$val[id]."' ";
	if($class3ok)$metinfo.="onmousedown='met_showhide1".$mark."(".$val[id].")'";
	$metinfo.="><a href='".$val[url]."' ".$val[new_windows]." title='".$val[name]."' >".$val[name]."</a></li>\n";
	if($class3ok){
	if($class3now){
	  $metinfo.="<span id='".$val[id]."' style='display:none;' class='span_class3'>\n";
	 }else{
	  $metinfo.="<span id='".$val[id]."' class='span_class3'>\n";
	 }
	$nav3=$nav_list3[$val[id]];
	if($class_list[$class1][module]==100 or $class_list[$class1][module]==101)$nav3=$nav_list2[$val[id]];
  foreach($nav3 as $key=>$val1){
    $val1[name]=intval($namelen)?utf8substr($val1[name], 0, $namelen):$val1[name];
    $metinfo.="<li class='li_class3' id='product".$val1[id]."' ><a href='".$val1[url]."' ".$val1[new_windows]." title='".$val1[name]."'>".$val1[name]."</a></li>\n";
   }
    $metinfo.="</span>\n";
   }}
    $metinfo.="</ul>\n";
    $metinfo.="<script type='text/javascript'>\n";
  if($class3ok){
    $metinfo.="function met_showhide1".$mark."(d)\n";
    $metinfo.="{  \n";     
    $metinfo.="var d1=document.getElementById(d);\n";
    $metinfo.="if(d1.style.display=='none')\n";
    $metinfo.="{ d1.style.display='block';\n";
    $metinfo.="}else{\n";
    $metinfo.="d1.style.display='none';\n";
    $metinfo.="}\n";
    $metinfo.="}\n";
	}
	if($class2){
	if($class3ok)$metinfo.="document.getElementById('".$class2."').style.display='block';\n";
	if($class2char)$metinfo.="document.getElementById('product".$class2."').className='classnow';\n";
	if($class3 and $class3ok and $class3char)$metinfo.="document.getElementById('product".$class3."').className='classnow3';\n";
	}
    $metinfo.="</script>\n";

  break;
   }
  return $metinfo;
}
//Flashimg
function methtml_flashimg($type,$width,$height,$imgurl,$imglink,$imgtitle){
global $met_flasharray,$classnow,$met_url,$met_flash_img,$met_flash_imglink,$met_flash_imgtitle;
if($width=='')$width=$met_flasharray[$classnow][x];
if($height=='')$height=$met_flasharray[$classnow][y];
if($imgurl=='')$imgurl=$met_flash_img;
if($imglink=='')$imglink=$met_flash_imglink;
if($imgtitle=='')$imgtitle=$met_flash_imgtitle;
$imglink=str_replace('&','%26',$imglink);
switch($type){
case 1:
   $methtml_flash.="<script type=\"text/javascript\">\n";
   $methtml_flash.="var pic_width=".$width.";\n";
   $methtml_flash.="var pic_height=".$height.";\n";
   $methtml_flash.="var text_height=0;\n";
   $methtml_flash.="var swfpath = '".$met_url."'+'flash/flash01.swf';\n";
   $methtml_flash.="var pics='".$imgurl."';\n";
   $methtml_flash.="var links='".$imglink."';\n";
   $methtml_flash.="var texts='';\n";
   $methtml_flash.="document.write('<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"";
   $methtml_flash.="codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"'+ pic_width +'\" height=\"'+ pic_height +'\">');\n";
   $methtml_flash.="document.write('<param name=\"allowScriptAccess\" value=\"sameDomain\"><param name=\"movie\" value=\"'+swfpath+'\"><param name=\"quality\" value=\"high\"><param name=\"bgcolor\" value=\"#ffffff\">');\n";
   $methtml_flash.="document.write('<param name=\"menu\" value=\"false\"><param name=wmode value=\"opaque\">');\n";
   $methtml_flash.="document.write('<param name=\"FlashVars\" value=\"pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+pic_width+'&borderheight='+pic_height+'&textheight='+text_height+'\">');\n";
   $methtml_flash.="document.write('<embed src=\"'+swfpath+'\" wmode=\"opaque\" FlashVars=\"pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+pic_width+'&borderheight='+pic_height+'&textheight='+text_height+'\" menu=\"false\" bgcolor=\"#ffffff\" quality=\"high\" width=\"'+ pic_width +'\" height=\"'+ pic_height +'\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />');document.write('</object>');\n";
   $methtml_flash.="</script>\n";
break;

case 2:
   $methtml_flash.="<script type=\"text/javascript\">\n";
   $methtml_flash.="var swf_width=".$width.";\n";
   $methtml_flash.="var swf_height=".$height.";\n";
   $methtml_flash.="var files='".$imgurl."';\n";
   $methtml_flash.="var links='".$imglink."';\n";
   $methtml_flash.="var texts='';\n";
   $methtml_flash.="var swfpath = '".$met_url."'+'flash/flash02.swf';\n";
   $methtml_flash.="var AutoPlayTime=6; //间隔时间：单位是秒\n";
   $methtml_flash.="document.write('<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\">');\n";
   $methtml_flash.="document.write('<param name=\"movie\" value='+swfpath+'><param name=\"quality\" value=\"high\">');\n";
   $methtml_flash.="document.write('<param name=\"menu\" value=\"false\"><param name=wmode value=\"opaque\">');\n";
   $methtml_flash.="document.write('<param name=\"FlashVars\" value=\"bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'&AutoPlayTime='+AutoPlayTime+'\">');\n";
   $methtml_flash.="document.write('<embed src='+swfpath+' wmode=\"opaque\" FlashVars=\"bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'&AutoPlayTime='+AutoPlayTime+'\" menu=\"false\" quality=\"high\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />'); \n";
   $methtml_flash.="document.write('</object>'); \n";
   $methtml_flash.="</script>\n";
break;

case 3:
   $methtml_flash.="<script src='".$met_url."js/flash3.js' type='text/javascript'></script> \n"; 
   $methtml_flash.="<SCRIPT language=javascript type=text/javascript>\n";
   $methtml_flash.="var titles = '".$imgtitle."';\n";
   $methtml_flash.="var imgs='".$imgurl."'; \n";
   $methtml_flash.="var urls='".$imglink."';\n";
   $methtml_flash.="var pw =".$width.";                //flash宽度\n";
   $methtml_flash.="var ph =".$height.";                //flash高度\n";
   $methtml_flash.="var sizes = 14;              //flash底部文字大小\n";
   $methtml_flash.="var Times = 4000;            //flash图片翻转时间\n";
   $methtml_flash.="var umcolor = 0xFFFFFF;      //flash按钮文字颜色\n";     
   $methtml_flash.="var btnbg =0xFF7E00;         //flash按钮背景颜色（当前图片，鼠标经过）\n";
   $methtml_flash.="var txtcolor =0xFFFFFF;      //flash图片名称 文字颜色\n";
   $methtml_flash.="var txtoutcolor = 0xff0000;\n";
   $methtml_flash.="var flash = new SWFObject('".$met_url."flash/flash03.swf', 'mymovie', pw, ph, '7', '');\n";
   $methtml_flash.="flash.addParam('allowFullScreen', 'true');\n";
   $methtml_flash.="flash.addParam('allowScriptAccess', 'always');\n";
   $methtml_flash.="flash.addParam('quality', 'high');\n";
   $methtml_flash.="flash.addParam('wmode', 'Transparent');\n";
   $methtml_flash.="flash.addVariable('pw', pw);\n";
   $methtml_flash.="flash.addVariable('ph', ph);\n";
   $methtml_flash.="flash.addVariable('sizes', sizes);\n";
   $methtml_flash.="flash.addVariable('umcolor', umcolor);\n";
   $methtml_flash.="flash.addVariable('btnbg', btnbg);\n";
   $methtml_flash.="flash.addVariable('txtcolor', txtcolor);\n";
   $methtml_flash.="flash.addVariable('txtoutcolor', txtoutcolor);\n";
   $methtml_flash.="flash.addVariable('urls', urls);\n";
   $methtml_flash.="flash.addVariable('Times', Times);\n";
   $methtml_flash.="flash.addVariable('titles', titles);\n";
   $methtml_flash.="flash.addVariable('imgs', imgs);\n";
   $methtml_flash.="flash.write('dplayer2');\n";
   $methtml_flash.="</SCRIPT>\n";
break;

case 4:
   $methtml_flash.="<script language='javascript' src='".$met_url."js/flash4.js'></script>\n";
   $methtml_flash.="<script language='javascript'>\n";
   $methtml_flash.="var pics='".$imgurl."';\n";
   $methtml_flash.="var mylinks='".$imglink."';\n";
   $methtml_flash.="var texts='".$imgtitle."';\n";
   $methtml_flash.="var _str='".$met_url."flash/flash04.swf';\n";
   $methtml_flash.="var ivWidth=".$width.";\n";
   $methtml_flash.="var ivHeight=".$height.";\n";
   $methtml_flash.="_str+='?Width='+ivWidth;\n";
   $methtml_flash.="_str+='&Height='+ivHeight;\n";
   $methtml_flash.="_str+='&Titles='+encodeURI(texts);\n";
   $methtml_flash.="_str+='&ImgUrls='+pics;\n";
   $methtml_flash.="_str+='&LinkUrls='+mylinks;\n";
   $methtml_flash.="creatHexunFlashObject(_str,ivWidth,ivHeight);\n";
   $methtml_flash.="</script>\n";
break;

case 5:
   $methtml_flash.="<SCRIPT language=javascript src='".$met_url."js/flash5.js' type=text/javascript></SCRIPT>\n";
   $methtml_flash.="<SCRIPT type=text/javascript>\n";
   $methtml_flash.="var focus_width=".$width.";\n";
   $methtml_flash.="var focus_height=".$height.";\n";
   $methtml_flash.="var text_height=20;\n";
   $methtml_flash.="var swf_height=focus_height + text_height;\n";
   $methtml_flash.="var pics='".$imgurl."';\n";
   $methtml_flash.="var links='".$imglink."';\n";
   $methtml_flash.="var texts='".$imgtitle."';\n";
   $methtml_flash.="var fo = new SWFObject('".$met_url."/flash/flash05.swf', '_FocusObj', focus_width, swf_height, '7','F6F8FA');\n";
   $methtml_flash.="fo.addVariable('pics', pics);\n";
   $methtml_flash.="fo.addVariable('links', links);\n";
   $methtml_flash.="fo.addVariable('texts', texts); \n";
   $methtml_flash.="fo.addVariable('borderwidth', focus_width);\n";
   $methtml_flash.="fo.addVariable('borderheight', focus_height);\n";
   $methtml_flash.="fo.addVariable('textheight', text_height);\n";
   $methtml_flash.="fo.addVariable('border_color', '0x000000'); \n";
   $methtml_flash.="fo.addVariable('fontsize', '14'); \n";
   $methtml_flash.="fo.addVariable('fontcolor', '5d5d5d');\n";
   $methtml_flash.="fo.addVariable('is_border', '');\n";
   $methtml_flash.="fo.addVariable('is_text', '1');\n";
   $methtml_flash.="fo.addParam('wmode', 'opaque');\n";
   $methtml_flash.="fo.write('FocusObj');\n";
   $methtml_flash.="</SCRIPT>\n";
break;

case 6:
   $methtml_flash.="<script src='".$met_url."js/flash7.js' type='text/javascript'></script> \n"; 
   $methtml_flash.="<script type='text/javascript'>\n";	
   $methtml_flash.="var speed = 4000;\n";		
   $methtml_flash.="var pics='".$imgurl."';\n";	
   $methtml_flash.="var mylinks='".$imglink."';\n";
   $methtml_flash.="var texts='".$imgtitle."';\n";			
   $methtml_flash.="var sohuFlash2 = new sohuFlash('".$met_url."flash/flash07.swf','flashcontent01','".$width."','".$height."','8','#ffffff');\n";										
   $methtml_flash.="sohuFlash2.addParam('quality', 'medium');\n";
   $methtml_flash.="sohuFlash2.addParam('wmode', 'opaque');\n";						
   $methtml_flash.="sohuFlash2.addVariable('speed',speed);\n";
   $methtml_flash.="sohuFlash2.addVariable('p',pics);\n";	
   $methtml_flash.="sohuFlash2.addVariable('l',mylinks);\n";
   $methtml_flash.="sohuFlash2.addVariable('icon',texts);\n";
   $methtml_flash.="sohuFlash2.write('flashcontent01');\n";
   $methtml_flash.="</script> \n";
break;

case 7:
   $methtml_flash.="<script src='".$met_url."js/flash9.js' type='text/javascript'></script> \n"; 
   $methtml_flash.="<div id=sasFlashFocus27></div>\n"; 
   $methtml_flash.="<SCRIPT>\n"; 
   $methtml_flash.="var sohuFlash2 = new sohuFlash(\"".$met_url."flash/flash09.swf\", \"27\", ".$width.", ".$height.", \"7\");\n"; 
   $methtml_flash.="sohuFlash2.addParam(\"quality\", \"high\");\n"; 
   $methtml_flash.="sohuFlash2.addParam(\"wmode\", \"opaque\");\n"; 
   $methtml_flash.="sohuFlash2.addVariable(\"image\",\"".$imgurl."\");\n"; 
   $methtml_flash.="sohuFlash2.addVariable(\"url\",\"".$imglink."\");\n";
   $methtml_flash.="sohuFlash2.addVariable(\"info\", \"".$imgtitle."\");\n";
   $methtml_flash.="sohuFlash2.addVariable(\"stopTime\",\"5000\");\n";
   $methtml_flash.="sohuFlash2.write(\"sasFlashFocus27\");\n";
   $methtml_flash.="</SCRIPT>\n";
break;

case 8:
   $methtml_flash.="<SCRIPT src='".$met_url."js/flash8.js' type='text/javascript'></SCRIPT>\n";
   $methtml_flash.="<SCRIPT type=text/javascript>\n";
   $methtml_flash.="var pics='".$imgurl."';\n";	
   $methtml_flash.="var mylinks='".$imglink."';\n";
   $methtml_flash.="var texts='".$imgtitle."';\n";
   $methtml_flash.="var sohuFlash2 = new sohuFlash('".$met_url."flash/flash08.swf','sohuFlashID01','".$width."','".$height."','5','#ffffff');\n";
   $methtml_flash.="sohuFlash2.addParam('quality', 'high');\n";
   $methtml_flash.="sohuFlash2.addParam('salign', 't');\n";
   $methtml_flash.="sohuFlash2.addParam('wmode','transparent');\n";
   $methtml_flash.="sohuFlash2.addVariable('p',pics);	\n";
   $methtml_flash.="sohuFlash2.addVariable('l',mylinks);\n";
   $methtml_flash.="sohuFlash2.addVariable('icon',texts);\n";
   $methtml_flash.="sohuFlash2.write('flashcontent01');\n";
   $methtml_flash.="</SCRIPT>\n";
break;

case 9:
   $methtml_flash.="<div id=met3dswf align='center'></div>\n";
   $methtml_flash.="<script language=JavaScript src='".$met_url."js/flash09.js'></script>\n";
   $methtml_flash.="<script language='javascript'>\n";
   $imgurlarray=explode('|',$imgurl);
   $imglinkarray=explode('|',$imglink);
   $imgtitlearray=explode('|',$imgtitle);
   $totalurl=count($imgurlarray);
   if($totalurl>4){
   $imgurl='';
   $imglink='';
   $imgtitle='';
   for($i=0;$i<$totalurl;$i++){
    $imgurl.=$i?"|".$imgurlarray[$i]:$imgurlarray[$i];
	if(($i==3&&$totalurl!==4) or ($i==7&&$totalurl!==8))$imgurl.="|_";
	$imglink.=$i?"|".$imglinkarray[$i]:$imglinkarray[$i];
	if(($i==3&&$totalurl!==4) or ($i==7&&$totalurl!==8))$imglink.="|_";
	$imgtitle.=$i?"|".$imgtitlearray[$i]:$imgtitlearray[$i];
	if(($i==3&&$totalurl!==4) or ($i==7&&$totalurl!==8))$imgtitle.="|_";
    }
	}
   $methtml_flash.="var pics='".$imgurl."'\n";
   $methtml_flash.="var links='".$imglink."'\n"; 	
   $methtml_flash.="var texts='".$imgtitle."'\n";	
   $methtml_flash.="var sohuFlash2 = new sohuFlash('".$met_url."flash/flash9.swf','sohuFlashID01','".$width."','".$height."','6','#ffffff');\n";
   $methtml_flash.="sohuFlash2.addParam('quality', 'high');\n";
   $methtml_flash.="sohuFlash2.addParam('salign', 't');\n";
   $methtml_flash.="sohuFlash2.addParam('wmode', 'opaque');\n";
   $methtml_flash.="sohuFlash2.addVariable('pics',pics);\n";
   $methtml_flash.="sohuFlash2.addVariable('links',links);\n";
   $methtml_flash.="sohuFlash2.addVariable('texts',texts);\n";
   $methtml_flash.="sohuFlash2.write('met3dswf')\n";
   $methtml_flash.="</script>\n";
break;
}

return $methtml_flash;
}

//Flash
$methtml_flash="";
switch($met_flasharray[$classnow][type]){
	case 0:
		$methtml_flash="";
	break;
	case 1:
		switch($met_flasharray[$classnow][imgtype]){
			case 1:
				$methtml_flash.="<div class=\"flash\">\n";
				$methtml_flash.=methtml_flashimg(1);
				$methtml_flash.="</div>";
			break;
			case 2:
				$methtml_flash.="<div class=\"flash\">\n";
				$methtml_flash.=methtml_flashimg(2);  
				$methtml_flash.="</div>";
			break;
			case 3:
				$methtml_flash.="<div class=\"flash\" id='dplayer2'>\n";
				$methtml_flash.=methtml_flashimg(3);
				$methtml_flash.="</div>\n";
			break;
			case 4:
				$methtml_flash.="<div class='flash' >\n";
				$methtml_flash.=methtml_flashimg(4);   
				$methtml_flash.="</div>\n";
			break;
			case 5:
				$methtml_flash.="<div class='flash' id=FocusObj></div>\n";
				$methtml_flash.=methtml_flashimg(5);
			break;
			case 6:
				$methtml_flash.="<link href='{$weburly}public/flash/flash6/css.css' rel='stylesheet' type='text/css' />\n";
				$methtml_flash.="<script src='{$weburly}public/flash/flash6/jquery.bxSlider.min.js'></script>\n";
				$methtml_flash.="<div class='flash flash6' style='width:".$met_flasharray[$classnow][x]."px; height:".$met_flasharray[$classnow][y]."px;'>\n";
				$methtml_flash.="<ul id='slider6' class='list-none'>\n";
				foreach($met_flashimg as $key=>$val){
					$methtml_flash.="<li><a href='".$val[img_link]."' target='_blank' title='{$val[img_title]}'>\n";
					$methtml_flash.="<img src='".$val[img_path]."' alt='".$val[img_title]."' width='{$met_flasharray[$classnow][x]}' height='{$met_flasharray[$classnow][y]}'></a></li>\n";
				}
				$methtml_flash.="</ul>\n";
				$methtml_flash.="</div>\n";
				$methtml_flash.="<script type='text/javascript'>
							$(document).ready(function(){
								$('#slider6').bxSlider({
									mode:'vertical',
									autoHover:true,
									auto: true,
									pager: true,
									pause: 5000, 
									controls:false
								});
							});
							</script>
				";
			break;
			case 7:
				$methtml_flash.="<div id='flashcontent01' class='flash'></div>\n";
				$methtml_flash.=methtml_flashimg(6);
			break;
			case 8:
				$thisflash_x=$met_flasharray[$classnow][x]-8;
				$thisflash_y=$met_flasharray[$classnow][y]-8;
				$methtml_flash.="<link rel='stylesheet' href='{$navurl}public/jq-flexslider/flexslider.css' type='text/css'>
<script src='{$navurl}public/jq-flexslider/jquery.flexslider-min.js'></script>";
				$methtml_flash.="<div class='flash'>
					<div class='flexslider flexslider_flash'>
					  <ul class='slides list-none'>";
			foreach($met_flashimg as $key=>$val){
				$methtml_flash.="<li><a href='".$val[img_link]."' target='_blank' title='{$val[img_title]}'>\n";
				$methtml_flash.="<img src='".$val[img_path]."' alt='".$val[img_title]."' width='{$met_flasharray[$classnow][x]}' height='{$met_flasharray[$classnow][y]}'></a></li>\n"; 
			}
    $methtml_flash.="
				  </ul>
				</div></div>
				";
				$methtml_flash.="<script type='text/javascript'>
					$(window).load(function() {
					  $('.flexslider').flexslider({
						animation: 'slide',
						controlNav:false
					  });
					});
				</script>
				";
			break;
		}
	break;
	case 2:
		if($met_flash_url<>''){
			$methtml_flash.="<div class=\"flash\" style=\"background-image: url(".$met_flash_back."); width:".$met_flash_x."px; height:".$met_flash_y."px;\">\n";
			$methtml_flash.="<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"".$met_flash_x."\" height=\"".$met_flash_y."\">\n";
			$methtml_flash.="            <param name=\"movie\" value=\"".$met_flash_url."\">\n";
			$methtml_flash.="            <param name=\"quality\" value=\"high\">\n";
			$methtml_flash.="            <param name=\"wmode\" value=\"transparent\" />\n";
			$methtml_flash.="		 <embed src=\"".$met_flash_url."\" width=\"".$met_flash_x."\" height=\"".$met_flash_y."\" quality=\"high\" wmode=\"transparent\"\n";
			$methtml_flash.="pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" wmode=\"transparent\"></embed>\n";
			$methtml_flash.="</object>\n";
			$methtml_flash.="</div>";
		}
	break;
	case 3:
		if($flash_imgone_img<>''){
			$flash_imgone_url=str_replace('%26','&',$flash_imgone_url);
			$methtml_flash.="<div class=\"flash\">\n";
			if($flash_imgone_url!='')$methtml_flash.="<a href='$flash_imgone_url' target='_blank' title='$flash_imgone_title'>";
			$methtml_flash.="<img src='".$flash_imgone_img."' width='".$met_flasharray[$classnow][x]."' alt='$flash_imgone_title' height='".$met_flasharray[$classnow][y]."'>";
			if($flash_imgone_url!='')$methtml_flash.="</a>\n";
			$methtml_flash.="</div>";
		}
	break;
}

//loop array 
function methtml_getarray($mark,$type,$order,$module,$listmx=-1,$para=0,$categoryname=0,$marktype=0,$txtmax=0,$descmax=0){
	global $met_member_use,$class_index,$met_listtime,$metinfo_member_type,$db,$met_news,$met_product,$met_download,$met_img,$met_job,$met_parameter,$met_plist,$class_list,$metpara,$module_list2;
	global $index_news_no,$index_product_no,$index_download_no,$index_img_no,$index_job_no;
	global $index,$navurl,$weburly,$lang,$pagename,$langmark,$met_htmpagename,$met_chtmtype,$met_htmtype,$met_pseudo,$met_webhtm;
	global $dataoptimize,$pagemark,$img_url,$met_hot,$m_now_date,$met_newsdays,$metmemberforce,$met_alt,$metblank,$met_agents_img;
	if($mark&&strstr($mark,"-")){
		$hngy5=explode('-',$mark);
		if($hngy5[1]=='cm'){
			$mark=$hngy5[0];
			$marktype=1;
		}
		if($hngy5[1]=='md'){
			$mark='';
			$module=metmodname($hngy5[0]);
		}
	}
	$listmx=$listmx==''?-1:$listmx;
	if($met_member_use==2)$access_sql= " and access<=$metinfo_member_type";
	$numname = ' id,title,description,class1,class2,class3,updatetime,addtime,filename,access,top_ok,hits,issue,com_ok,no_order,';
	$listitem['news']=array(0=>$numname.'img_ok,imgurls,imgurl',1=>$met_news,2=>'shownews');
	$listitem['product']=array(0=>$numname.'new_ok,imgurls,imgurl,displayimg',1=>$met_product,2=>'showproduct');
	$listitem['download']=array(0=>$numname.'downloadurl,filesize,downloadaccess',1=>$met_download,2=>'showdownload');
	$listitem['img']=array(0=>$numname.'new_ok,imgurls,imgurl,displayimg',1=>$met_img,2=>'showimg');
	$listitem['job']=array(0=>'*',1=>$met_job,2=>'showjob');
	$sqlorder=$order=='hits'?' order by top_ok desc,hits desc,id desc':' order by top_ok desc,updatetime desc,id desc';
	switch($type){
		default:
			$sqltype="";
		break;
		case 'com':
			$sqltype="and com_ok=1";
		break;
		case 'new':
			$sqltype="and new_ok=1";
		break;
		case 'img':
			$sqltype="and img_ok=1";
		break;
	}
	if($mark){
		if($marktype){
			$listnowid=$mark;
			$listnowclass="class{$class_list[$mark]['classtype']}";
			if($class_list[$mark]['releclass'])$listnowclass="class1";
			$modulex=metmodname($class_list[$mark]['module']);
			$sqlorder=$order?$sqlorder:list_order($class_list[$mark]['list_order']);
		}
		else{
			$listnowid=$class_index[$mark]['id'];
			$listnowclass="{$class_index[$mark]['classtype']}";
			$modulex=metmodname($class_index[$mark]['module']);
			$sqlorder=$order?$sqlorder:list_order($class_index[$mark]['list_order']);
		}
		$folderone=$db->get_one("SELECT * FROM $met_column WHERE bigclass='$listnowid' and module ='{$class_list[$listnowid][module]}' and releclass!='0' and lang='$lang'");
		if($folderone){
			$sqlclounm="and ($listnowclass='$listnowid' or class1='$folderone[id]')";
		}else{
			
		}
		if($listnowclass=='class1'){
			$class1sql=" $listnowclass='$listnowid' ";
			foreach($module_list2[$class_list[$listnowid]['module']] as $key=>$val){
				if($val['releclass']==$listnowid){
					$class1re.=" or $listnowclass=$val[id] ";
				}
			}
			if($class1re){
				$class1sql='('.$class1sql.$class1re.')';
			}
			$sqlclounm=' and '.$class1sql;
		}else{
			$sqlclounm="and $listnowclass='$listnowid'";
		}
		if(!$modulex){
			$module=$module?$module:'news';
			$sqlclounm='';
		}else{
			$module=$modulex;
		}
	}
	$module=$module?$module:'news';
	switch($module){
		case 'news':
			$modulenunm=2;
			break;
		case 'product':
			$modulenunm=3;
			break;
		case 'download':
			$modulenunm=4;
			break;
		case 'img':
			$modulenunm=5;
			break;
		case 'job':
			$modulenunm=6;
			break;
	}
	if($listmx==-1){
		switch($module){
			case 'news':
				$listmx=$index_news_no;
				break;
			case 'product':
				$listmx=$index_product_no;
				break;
			case 'download':
				$listmx=$index_download_no;
				break;
			case 'img':
				$listmx=$index_img_no;
				break;
			case 'job':
				$listmx=$index_job_no;
				break;
		}
	}
	else{
		$listmx=$listmx;
	}
	$select=$listitem[$module][0];
	$table=$listitem[$module][1];
	if($modulenunm==6){
		$query = "SELECT $select FROM $table where lang='$lang' and ((TO_DAYS(NOW())-TO_DAYS(`addtime`)< useful_life) OR useful_life=0) $access_sql order by top_ok desc,no_order desc,addtime desc limit 0, $listmx";	
	}
	else{
		$query = "SELECT $select FROM $table where lang='$lang' $sqlclounm $access_sql $sqltype and (recycle='0' or recycle='-1') $sqlorder limit 0, $listmx";
	}
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		if($modulenunm==6){$list['updatetime']=$list['addtime'];$list['title']=$list['position'];}
		$list['updatetime_original']=$list['updatetime'];
		$list['title_all']=$list['title'];
		$list['description_all']=$list['description'];
		if($txtmax)$list['title']=utf8substr($list['title'], 0,$txtmax);
		if($descmax)$list['description']=utf8substr($list['description'], 0,$descmax);
		if($dataoptimize[$pagemark]['categoryname']||$categoryname){
			$list['class1_name']=$class_list[$list['class1']]['name'];
			$list['class1_url']=$class_list[$list['class1']]['url'];
			$list['class2_name']=$list['class2']?$class_list[$list['class2']]['name']:$list['class1_name'];
			$list['class2_url']=$list['class2']?$class_list[$list['class2']]['url']:$list['class1_url'];
			$list['class3_name']=$list['class3']?$class_list[$list['class3']]['name']:($list['class2']?$class_list[$list['class2']]['name']:$list['class1_name']);
			$list['class3_url']=$list['class3']?$class_list[$list['class3']]['url']:($list['class2']?$class_list[$list['class2']]['url']:$list['class1_url']);
		}
		if($list['top_ok']==1){
			$list['top']="<img class='listtop' src='".$img_url."top.gif"."' alt='".$met_alt."' />";
			$list['hot']="";
			$list['news']="";
		}else{
			$list['top']="";
			$list['hot']=($list['hits']>=$met_hot)?"<img class='listhot' src='".$img_url."hot.gif"."' alt='".$met_alt."' />":"";
			$list['news']=(((strtotime($m_now_date)-strtotime($list['updatetime']))/86400)<$met_newsdays)?"<img class='listnew' src='".$img_url."news.gif"."' alt='".$met_alt."' />":"";
		}
		$list['status']=$list['top'].$list['hot'].$list['news'];
		if($index[index]=="index"){		
			$listarray[imgurls]=explode("../",$list['imgurls']);
			$list[imgurls]=$weburly.$listarray['imgurls'][1];
			$listarray[imgurl]=explode("../",$list['imgurl']);
			$list[imgurl]=$weburly.$listarray['imgurl'][1];
		}
		$list['imgurls']=($list['imgurls']<>"")?$list['imgurls']:$weburly.$met_agents_img;
		$list['imgurl']=($list['imgurl']<>"")?$list['imgurl']:$weburly.$met_agents_img;
		if(($dataoptimize[$pagemark]['para'][$modulenunm] and $dataoptimize[$pagemark]['parameter'])||$para){
			$query1 = "select * from $met_plist where lang='$lang' and listid='$list[id]' and module='$modulenunm' order by id";
			$result1 = $db->query($query1);
			$i=1;
			while($list1 = $db->fetch_array($result1)){
				$nowpara1="para".$i;
				$i++;
				$list[$nowpara1]=$list1['info'];
				$metparaaccess=$metpara[$list1['paraid']]['access'];
				if(intval($metparaaccess)>0&&$met_member_use){
					$paracode=authcode($list1[$nowpara1], 'ENCODE', $met_member_force);
					$paracode=codetra($paracode,1); 
					$list[$nowpara1]="<script language='javascript' src='".$navurl."include/access.php?metuser=para&metaccess=".$metparaaccess."&lang=".$lang."&listinfo=".$paracode."&paratype=".$metpara[$list1['paraid']]['type']."&index=".$index[index]."'></script>";
				}
				$nowparaname="";
				$nowparaname=$nowpara1."name";
				$list[$nowparaname]=($list1['imgname']<>"")?$list1['imgname']:$metpara[$list1['paraid']]['name'];
				if($metpara[$list1['paraid']]['type']==5&&$index[index]=="index"){
					$listarray['info']=explode("../",$list1['info']);
					$list1['info']=$listarray['info'][1];
				}
				if($metpara[$list1['paraid']]['type']==5){
					$fltp=metfiletype($list1['info']);
					$fltp=$fltp?'met_annex_'.$fltp:'';
					$list[$nowpara1]="<a href='{$list1['info']}' {$metblank} class='met_annex {$fltp}' title='{$list1['imgname']}'>{$list1['imgname']}</a>";
					$list[$nowpara1.'s']=$list1['info'];
				}
			}
		}
		//URL地址
		$filename=$modulenunm==6?$navurl.'job':$navurl.$class_list[$list['class1']]['foldername'];
		$filenamenow=$met_htmpagename==2?($modulenunm==6?'job':$class_list[$list['class1']]['foldername']):($met_htmpagename==1?date('Ymd',strtotime($list['addtime'])):$listitem[$module][2]);
		$htmname=($list['filename']<>"")?$filename."/".$list['filename']:$filename."/".$filenamenow.$list['id'];
		$panyid = $list['filename']!=''?$list['filename']:$list['id'];
		$met_ahtmtype = $list['filename']<>""?$met_chtmtype:$met_htmtype;
		$phpname=$met_pseudo?$filename."/".$panyid.'-'.$lang.'.html':$filename."/".$listitem[$module][2].".php?".$langmark."&id=".$list['id'];	
		$list['url']=$met_pseudo?$phpname:($met_webhtm?$htmname.$met_ahtmtype:$phpname);
		$list['updatetime'] = date($met_listtime,strtotime($list['updatetime']));
		$list['img_x']=met_imgxy(1,$module);
		$list['img_y']=met_imgxy(2,$module);
		$relist[]=$list;
	}
	return $relist;
}

//array info
function methtml_module($module){
  switch($module){
  case 2:
  return 'news';
  break;
  
  case 3:
  return 'product';
  break;
  
  case 4:
  return 'download';
  break;
  
  case 5:
  return 'img';
  break;
  }
}

//list display
function methtml_list($listtype,$mark,$type,$order,$module,$titlenum,$color,$max,$newwindow=1,$classname=1,$time=1,$news=1,$hot=1,$top=1,$hits=0,$description,$deslen){
global $class_index,$index;
global $met_img_x,$met_img_y;
 $maxmodule=($module=='')?methtml_module($class_index[$mark][module]):$module;
 $maxmodule=$maxmodule?$maxmodule:'news';
 $maxmodule=$maxmodule."_no";
 if(intval($max)==0)$max=$index[$maxmodule];
$listarray=methtml_getarray($mark,$type,$order,$module,$max);
 $i=0;
 $listtext.="<ul>\n";
 foreach($listarray as $key=>$val){
 $i++;
 if(intval($titlenum)<>0)$val[title]=utf8substr($val[title], 0, $titlenum); 
 if($i==1)$firsttitle=$color;
 $listtext.="<li>";
if($listtype=='img'){
 $listtext.="<span class='info_img'><a href='".$val[url]."'";
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=" ><img src=".$val[imgurls]." alt='".$val[title]."' width=".$met_img_x." height=".$met_img_y." /></a></span>";
 if($classname==2)$listtext.="<span class='info_class'>[<a href='".$val[class3_url]."' title='".$val[class3_name]."' >".$val[class3_name]."</a>]</span>";
 $listtext.="<span class='info_title'><a href='".$val[url]."'";
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($i==1)$listtext.="style='color:".$firsttitle.";'";
 $listtext.="  title='".$val[title]."' >".$val[title]."</a></span>";
 if($description==1){
 $listtext.="<span class='info_discription'><a href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if(intval($deslen))$val[description]=utf8substr($val[title], 0, $deslen); 
 $listtext.="  title='".$val[title]."' >".$val[description]."</a></span>";
  }
}else{
 if($classname==1)$listtext.="<span class='info_class'>[<a href='".$val[class3_url]."' title='".$val[class3_name]."' >".$val[class3_name]."</a>]</span>";
 $listtext.="<span class='info_title'><a  href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($i==1)$listtext.=" style='color:".$firsttitle.";'";
 $listtext.=" title='".$val[title]."' >".$val[title]."</a></span>";
 if($hits==1)$listtext.="<span class='info_hits'>[<font>".$val[hits]."</font>]</span>";
 if($top==1)$listtext.=$val[top];
 if($news==1)$listtext.=$val[news];
 if($hot==1)$listtext.=$val[hot];
 if($time==1)$listtext.="<span class='info_updatetime'>".$val[updatetime]."</span>";
}
 $listtext.="</li>\n";
 }
 $listtext.="</ul>";
 return $listtext;
}

function methtm_link($listtype='text',$type,$display,$max,$linkpage){
global $link_text_com,$link_text,$link_img_com,$link_img,$link,$link_com,$index,$lang_FriendlyLink;
if($index[link_ok]==1 || $linkpage){
  $index[link_img]=$max==""?$index[link_img]:$max;
  $index[link_text]=$max==""?$index[link_text]:$max;
  $linktext="<ul class='list-none'>\n";
  if($display=='select')$linktext="<select name='link' onchange=javascript:window.open(this.options[this.selectedIndex].value) >\n<option >".$lang_FriendlyLink."</option>\n";
  if($listtype=='img'){
     $linkarray=($type=='com')?$link_img_com:$link_img;
     $i=0;
     foreach($linkarray as $key=>$val){
      $i++;
	  if($i>$index[link_img])break;
      $linktext.="<li><a href='".$val[weburl]."' target='_blank'><img src='".$val[weblogo]."' alt='".$val[webname]."' /></a></li>\n";
	  }
   }else{
     $linkarray=($type=='com')?$link_text_com:$link_text;
	 $i=0;
     foreach($linkarray as $key=>$val){
      $i++;
	  if($i>$index[link_text])break;
	  if($display=='select'){
	    $linktext.="<option value='".$val[weburl]."' >".$val[webname]."</option>\n";
	    }else{
        $linktext.="<li><a href='".$val[weburl]."' target='_blank' title='".$val[info]."'>".$val[webname]."</a></li>\n";
		}
	  }
     }
  $linktext.=($display=='select')?"</select>":"</ul>\n";
}else{
  $linktext='';
}
return $linktext;
}

//foot info
$methtml_foot="<ul>\n";
if($met_footright<>"" or $met_footstat<>"")$methtml_foot.="<li>".$met_footright." ".$met_footstat."</li>\n";
if($met_footaddress<>"")$methtml_foot.="<li>".$met_footaddress."</li>\n";
if($met_foottel<>"")$methtml_foot.="<li>".$met_foottel."</li>\n";
if($met_footother<>"")$methtml_foot.="<li>".$met_footother."</li>\n";
if($met_foottext<>"")$methtml_foot.="<li>".$met_foottext."</li>\n";
$methtml_foot.="</ul>\n";
if($met_jiathis_ok)$methtml_foot.=$met_jiathis;

//online
function methtml_online(){
	global $met_online_type,$navurl,$met_onlineleft_left,$met_onlineright_right,$met_onlineleft_top,$met_onlineright_top,$lang,$met_stat_js;
	$metinfo='';
	if($met_stat_js)$metinfo.=$met_stat_js."\n";
	if($met_online_type!=3){
		$onlinex=$met_online_type<2?$met_onlineleft_left:$met_onlineright_right;
		$onliney=$met_online_type<2?$met_onlineleft_top:$met_onlineright_top;
		$metinfo.="<script src='{$navurl}public/js/online.js?t={$met_online_type}&u={$navurl}&x={$onlinex}&y={$onliney}&lang={$lang}' type='text/javascript' id='metonlie_js'></script>";
	}
	return $metinfo;
}

function methtml_hits($module){
global $news,$product,$img,$download,$job,$lang_Hits,$lang_Printing,$lang_Printing,$lang_UpdateTime,$lang_Close,$met_tools_ok,$met_tools_code;
global $met_pageclick,$met_pagetime,$met_pageprint,$met_pageclose;
	$listnow=$$module;
	if($module=='job')$listnow[updatetime]=$listnow[addtime];
	if($met_tools_ok)$metinfo.="<div class='metjiathis'>{$met_tools_code}</div>";
	if($module!='job' && $met_pageclick)$metinfo.=$lang_Hits."：<span><script language='javascript' src='../include/hits.php?type=".$module."&id=".$listnow[id]."'></script></span>";
	if($met_pagetime)$metinfo.='&nbsp;&nbsp;'.$lang_UpdateTime.'：'.$listnow['updatetime'];
	if($met_pageprint)$metinfo.='&nbsp;&nbsp;【<a href="javascript:window.print()">'.$lang_Printing.'</a>】';
	if($met_pageclose)$metinfo.='&nbsp;&nbsp;【<a href="javascript:self.close()">'.$lang_Close.'</a>】';
	return $metinfo;
}

function methtml_prenextinfo($type=0){
global $lang_Previous,$lang_Next,$lang_Noinfo,$preinfo,$nextinfo;
 if($type==1){
  $metinfo=$lang_Previous."：";
  $metinfo.=$preinfo?"<a href='".$preinfo[url]."' >".$preinfo[title]."</a>":$lang_Noinfo;
  $metinfo.="&nbsp;&nbsp;".$lang_Next."：";
  $metinfo.=$nextinfo?"<a href='".$nextinfo[url]."' >".$nextinfo[title]."</a>":$lang_Noinfo;
 }else{
  $metinfo=$lang_Previous."：";
  $metinfo.=$preinfo?"<a href='".$preinfo[url]."' >".$preinfo[title]."</a>":$lang_Noinfo;
  $metinfo.="<br>".$lang_Next."：";
  $metinfo.=$nextinfo?"<a href='".$nextinfo[url]."' >".$nextinfo[title]."</a>":$lang_Noinfo;
 }
 return $metinfo;
}

function methtml_login($type=1){
global $met_member_use,$navurl,$lang,$lang_memberName,$met_member_login,$lang_memberPs,$met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$lang_register,$lang_memberGo,$lang_memberIndex2,$metinfo_member_name,$lang_memberIndex1,$member_index_url,$lang_memberIndex10,$member_registerurl;
global $lang_memberPassword,$lang_memberRegisterName;
if($met_member_use){ 
  $metinfo.="<script type='text/javascript'>\n";
  $metinfo.="function check_main_login()\n";
  $metinfo.="{\n";
  $metinfo.="var m=document.main_login;\n";
  $metinfo.="if(m.login_name.value.length=='')\n";
  $metinfo.="{\n";
  $metinfo.="	alert('".$lang_memberRegisterName."');\n";
  $metinfo.="	m.login_name.focus();\n";
  $metinfo.="	return false;\n";
  $metinfo.="}\n";
  $metinfo.="if(m.login_pass.value.length=='')\n";
  $metinfo.="{\n";
  $metinfo.="	alert('".$lang_memberPassword."');\n";
  $metinfo.="	m.login_pass.focus();\n";
  $metinfo.="	return false;\n";
  $metinfo.="}\n";
  $metinfo.="}\n";
  $metinfo.="function checkcookie(){\n";
  $metinfo.="if (document.cookie == '')\n"; 
  $metinfo.="{	\n";
  $metinfo.="var tmp=document.getElementById('remember');\n";
  $metinfo.="	alert('{$lang_memberCookie}');\n";
  $metinfo.="	tmp.checked=false;\n";
  $metinfo.="}}\n";
  $metinfo.="function pressCaptcha(obj){obj.value = obj.value.toUpperCase();}\n";
  $metinfo.="</script>\n";
if($type){
  $metinfo.="<div class='login_x' id='login_x1' style='display:none'>";
  }else{
  $metinfo.="<div class='login_x' id='login_x1'>";
  }
  $metinfo.="<form method='post' action='".$navurl."member/login_check.php?lang=".$lang."' name='main_login' onSubmit='javascript: return check_main_login()'>";
  $metinfo.="<input type='hidden' name='action' value='login'/>";
  $metinfo.="<span class='log1'><span class='log1_name'>".$lang_memberName."</span><input type='text' name='login_name' id='user_name' /></span>";
  $metinfo.="<span class='log4'><span class='log4_name'>".$lang_memberPs."</span><input type='password' name='login_pass' id='user_pass'/></span>";
if($met_memberlogin_code==1){
  $metinfo.="<span class='log2'><span class='log2_name'>".$lang_memberImgCode."</span><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='inp' id='code' maxlength='8' />";
  $metinfo.="<img align='absbottom' src='".$navurl."member/ajax.php?action=code'  onclick=this.src='".$navurl."member/ajax.php?action=code&'+Math.random()  style='cursor: pointer;' title=".$lang_memberTip1."/>";
  $metinfo.="</span>";}
  $metinfo.="<span class='log3'><input type='submit' class='index_login1' value='".$lang_memberGo."' />";
if($met_member_login){
  $metinfo.="<span><a href='".$member_registerurl."' class='index_login2'/>".$lang_register."</a></span>";
}
  $metinfo.="</span>";
  $metinfo.="</form>";
  $metinfo.="</div>";
if($type){
  $metinfo.="<div class='login_x' id='login_x2' style='display:none' >";
  $metinfo.="<span class='login_okname' ><span class='login_okname1'>".$lang_memberIndex2."</span><span class='login_okname2'><font style='color:red'>";
  $metinfo.="<script language='javascript' src='".$navurl."member/member.php?memberaction=membername'></script>";
  $metinfo.="</font></span></span>&nbsp;&nbsp;";
  $metinfo.="<span class='login_okmember' ><span class='login_okmember1'><a href='".$navurl."member/".$member_index_url."' >".$lang_memberIndex1."</a></span><span class='login_okmember2'>|</span><span class='login_okmember3'><a href='".$navurl."member/login_out.php?lang=".$lang."' >".$lang_memberIndex10."</a></span></span>";
  $metinfo.="</div>";
  $metinfo.="<script language='javascript' src='".$navurl."member/member.php?memberaction=login'></script>";
 }
 }
  return $metinfo;
}
function memberlist(){
    global $lang,$met_pseudo,$weburly,$lang_memberIndex3,$lang_memberIndex4,$lang_memberIndex5,$lang_memberIndex6,$lang_memberIndex7,$lang_memberIndex10;
	$metinfo = '<ul class="member_nav">';
	$list[0]= array('url'=>"basic",'title'=>$lang_memberIndex3);
	$list[1]= array('url'=>"editor",'title'=>$lang_memberIndex4);
	$list[2]= array('url'=>"feedback",'title'=>$lang_memberIndex5);
	$list[3]= array('url'=>"message",'title'=>$lang_memberIndex6);
	$list[4]= array('url'=>"cv",'title'=>$lang_memberIndex7);
	$list[5]= array('url'=>"login_out",'title'=>$lang_memberIndex10);
	$i=0;
	foreach($list as $key=>$val){
	    $url=$met_pseudo?$val[url].'-'.$lang.'.html':$val[url].".php?lang=$lang";
	    $target=$i==5?'':'target="main"';
	    $metinfo .= "<li><a $target href='$url' title='$val[title]'>$val[title]</a></li>";
		$i++;
	}
	$metinfo .= "</ul>";
	return $metinfo;
}
require_once 'searchhtml.inc.php';
require_once 'metlabel.inc.php';
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>