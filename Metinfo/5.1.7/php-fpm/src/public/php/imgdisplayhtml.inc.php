<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

function methtml_imgdisplay($type='img'){
global $img_paraimg,$img,$product,$product_paraimg,$lang_BigPicture,$met_url,$met_img_x,$met_img_y,$met_imgdetail_x,$met_imgdetail_y,$met_img_detail,$met_productdetail_x,$met_productdetail_y,$met_product_detail,$lang_displayimg,$lang_defualt,$navurl;

if($type=='product'){
$img_paraimg=$product_paraimg;
$img=$product;
$met_imgdetail_x=$met_productdetail_x;
$met_imgdetail_y=$met_productdetail_y;
$met_img_detail=$met_product_detail;
}
 $metinfoimglist=0;
$pg=0;
if($img['displayimg']!=''){
	$displayimg=explode('|',$img['displayimg']);
	$pg=count($displayimg);
	for($i=0;$i<$pg;$i++){
		$newdisplay=explode('*',$displayimg[$i]);
		$displaylist[$i]['title']=$newdisplay[0];
		$displaylist[$i]['imgurl']=$newdisplay[1];
		$imgurl_diss=explode('/',$displaylist[$i]['imgurl']);
		$displaylist[$i][imgurl_dis]=$imgurl_diss[0].'/'.$imgurl_diss[1].'/'.$imgurl_diss[2].'/thumb_dis/'.$imgurl_diss[count($imgurl_diss)-1];
		$filename=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$displaylist[$i][imgurl_dis]):$displaylist[$i][imgurl_dis];
		$displaylist[$i][imgurl_dis]=file_exists($filename)?$displaylist[$i][imgurl_dis]:$displaylist[$i]['imgurl'];
	}
	if($pg)$metinfoimglist=1;
}
$imgurl_diss=explode('/',$img[imgurl]);
$img[imgurl_dis]=$imgurl_diss[0].'/'.$imgurl_diss[1].'/'.$imgurl_diss[2].'/thumb_dis/'.$imgurl_diss[count($imgurl_diss)-1];
$filename=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$img[imgurl_dis]):$img[imgurl_dis];
$img[imgurl_dis]=file_exists($filename)?$img[imgurl_dis]:$img[imgurl];
if($metinfoimglist){
if($met_img_detail>2)$met_img_detail=1;
switch($met_img_detail){
case 1:
   $metinfo.="<style>\n";
   $metinfo.=".spic{margin-right:5px;}\n";
   $metinfo.=".spic a img{-moz-opacity:0.5; filter:alpha(opacity=50);border:0px;}\n";
   $metinfo.=".spic a:hover{font-size:9px;}\n";
   $metinfo.=".spic a:hover img{-moz-opacity:0.5; filter:alpha(opacity=100);cursor:hand;}\n";
   $metinfo.="#view_bigimg{ display:block; margin:0px auto; font-size:0px;}\n";
   $metinfo.=".smallimg{ margin-top:5px;}\n";
   $metinfo.="</style>\n";
   $metinfo.="<script  LANGUAGE='JavaScript'>\n";
   $metinfo.="function metseeBig(nowimg,mgrc) {\n";
   $metinfo.="document.getElementById('view_img').src=document.getElementById(nowimg).src;\n";
   $metinfo.="$('#view_bigimg').attr('href',mgrc);\n";
   $metinfo.="}\n";
   $metinfo.="</script>\n";
   $metinfo.="<span class='info_img' id='imgqwe'><a id='view_bigimg' href='".$img[imgurl]."' title=".$lang_BigPicture." target='_blank'><img id='view_img' border='0' alt='".$img[title]."' title='".$img[title]."' width=".$met_imgdetail_x." height=".$met_imgdetail_y." src='".$img[imgurl_dis]."'></a></span>\n";
   $metinfo.="<script type='text/javascript'>";
   $metinfo.="var zoomImagesURI   = '".$met_url."images/zoom/';"; 
   $metinfo.="</script>\n"; 
   $metinfo.="<script src='".$met_url."js/metzoom.js' language='JavaScript' type='text/javascript'></script>\n";
   $metinfo.="<script src='".$met_url."js/metzoomHTML.js' language='JavaScript' type='text/javascript'></script>\n";
   $metinfo.="<script type='text/javascript'>	window.onload==setupZoom();	</script>\n";
   $metinfo.="<div class='smallimg' style='width:{$met_imgdetail_x}px;'>\n";
	if($displaylist)array_unshift($displaylist,array('title'=>"$img[title]",'imgurl'=>"$img[imgurl]",'imgurl_dis'=>"$img[imgurl_dis]"));
	$i=0;
	foreach($displaylist as $key=>$val){
	$i++;
	$title=$val['title']==''?$lang_displayimg.$i:$val['title'];
   $metinfo.="<span class='spic'><a href='javascript:;' onclick=metseeBig('smallimg".$i."','".$val['imgurl']."') title='".$title."' style='cursor:pointer'><img border='0'  id='smallimg".$i."' src='".$val['imgurl_dis']."' width='50' height='50' alt='".$title."' title='".$title."' ></a></span>\n";
	}
   $metinfo.="</div>\n";
break;
case 2:
   $metinfo.="
		<script src='{$navurl}public/js/jquery.jqzoom-core.js' type='text/javascript'></script>
		<link rel='stylesheet' href='{$navurl}public/css/jquery.jqzoom.css' type='text/css'>
		<script type='text/javascript'>
		var imgdetail_x= {$met_imgdetail_x};
		var imgdetail_y= {$met_imgdetail_y};
		$(document).ready(function() {
		var bimht=$('#metshowtype_2').parent('dt');
			bimht=bimht.size()>0?bimht.parent('dl').width():700;
		var dwef=bimht-{$met_imgdetail_x}-10;
			$('.jqzoom').jqzoom({
					zoomWidth: dwef,
					zoomHeight:{$met_imgdetail_y},
					xOffset:10,
					yOffset:0,
					zoomType: 'standard',
					lens:true,
					preloadImages: false,
					alwaysOn:false
				});
			
		});
		</script>
   ";
	$dtwidth=$met_productdetail_x + 14;
   $metinfo.="
<div class='clearfix' id='metshowtype_2'>
    <div class='clearfix' style='border:1px solid #ccc;'>
        <a href='{$img[imgurl]}' class='jqzoom' rel='gal1'  title='{$img[title]}' >
            <img src='{$img[imgurl_dis]}' alt='{$img[title]}' title='{$img[title]}' id='view_img' width='{$met_imgdetail_x}' height='{$met_imgdetail_y}' />
        </a>
    </div>
 <div class='clearfix' style='width:{$met_imgdetail_x}px;'>
	<ul id='thumblist' class='clearfix' >
	<li><a href=\"javascript:void(0);\" rel=\"{gallery: 'gal1', smallimage: '{$img[imgurl_dis]}',largeimage: '{$img[imgurl]}'}\" class='zoomThumbActive'><img src='{$img[imgurl_dis]}' alt='{$img[title]}' /></a></li>
	";
foreach($displaylist as $key=>$val){
	$metinfo.="<li><a href=\"javascript:void(0);\" rel=\"{gallery: 'gal1', smallimage: '{$val[imgurl_dis]}',largeimage: '{$val[imgurl]}'}\"><img src='{$val[imgurl_dis]}' alt='{$val[title]}' /></a></li>";
}
	$metinfo.="</ul></div></div>";
break;
}  

}else{
	$metinfo.="<span class='info_img' id='imgqwe'><a href='".$img[imgurl]."' title=".$lang_BigPicture." target='_blank'><img src=".$img[imgurl_dis]." alt='".$img[title]."' title='".$img[title]."' width=".$met_imgdetail_x." height=".$met_imgdetail_y."  /></a></span>\n";
	$metinfo.="<script type='text/javascript'>";
	$metinfo.="var zoomImagesURI   = '".$met_url."images/zoom/';"; 
	$metinfo.="</script>\n"; 
	$metinfo.="<script src='".$met_url."js/metzoom.js' language='JavaScript' type='text/javascript'></script>\n";
	$metinfo.="<script src='".$met_url."js/metzoomHTML.js' language='JavaScript' type='text/javascript'></script>\n";
	$metinfo.="<script type='text/javascript'>	window.onload==setupZoom();	</script>\n";
}
return $metinfo;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>