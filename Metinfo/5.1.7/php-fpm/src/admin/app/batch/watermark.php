<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
@set_time_limit(0);
function concentwatermark($str,$field){
global $met_wate_class,$met_wate_bigimg,$met_text_wate,$met_text_bigsize,$met_text_color,$met_text_angle,$met_watermark,$met_text_fonts;
global $img,$depth;
$tmp1 = explode("<",$str);
$concentflag=0;
$i=0;
foreach($tmp1 as $key=>$val){
	$tmp2=explode(">",$val);
	if(strcasecmp(substr(trim($tmp2[0]),0,3),'img')==0){
		preg_match("/http:\/\/[^\"]*/i",$tmp2[0],$url);
		if($url[0]){
			$urls=explode('/',$url[0]);
			$filename=$urls[count($urls)-1];
			if(stristr(PHP_OS,"WIN"))$filename=@iconv("utf-8","gbk",$filename);
			if(file_exists($depth."../../upload/images/".$filename)){
				$filename=$urls[count($urls)-1];
				$img->src_image_name = $depth."../../upload/images/".$filename;
				$img->save_file = $depth."../../upload/images/watermark/".$filename;
				$img->create();
				if(!stristr($tmp2[0],'/watermark/')){
					$concentflag=1;
					$tmp2[0]=str_ireplace("/images/","/images/watermark/",$tmp2[0]);
					$tmp1[$i]=implode(">",$tmp2);
				}
			}
		}
	}
	$i++;
}
if($concentflag==1){
	$str=implode("<",$tmp1);
	return "$field='$str'";
}
else{
	return false;
}
}
if($action=="class"){
	$class=$class3?$class3:($class2?$class2:$class1);
	$remark=$db->get_one("select * from $met_column where id='$class'");
	$table=moduledb($remark['module']);
	$resql="class1='$class1'";
	$resql.=$class2?" and class2='$class2'":"";
	$resql.=$class3?" and class3='$class3'":"";
	$renow=$db->get_all("select * from $table where $resql and (recycle='0' or recycle='-1')");
	echo $remark['module'].'|';
	foreach($renow as $key=>$val){
		echo $val['id'].'-';
	}
die();
}
if($action=="do"){
require_once $depth.'../include/watermark.class.php';
require_once $depth.'../include/upfile.class.php';
$met_img_maxsize=$met_img_maxsize*1024*1024;
$module=$table;
$table=moduledb($table);
$para_list=$db->get_all("select * from $met_parameter where lang='$lang' and module='$module' and (class1='$class1' or class1=0) and type='5'");
$img = new Watermark();
if($met_wate_class==2){
	$img->met_image_pos  = $met_watermark;
}else {
	$img->met_text       = $met_text_wate;
	$img->met_text_color = $met_text_color;
	$img->met_text_angle = $met_text_angle;
	$img->met_text_pos   = $met_watermark;
	$img->met_text_font  = $depth.$met_text_fonts;
}
$query="select * from $table where id='$id'";
$renow[0]=$db->get_one($query);
foreach($renow as $key=>$val){
	if($met_wate_class==2){
		$img->met_image_name = $depth.$met_wate_bigimg;
	}else {
		$img->met_text_size  = $met_text_bigsize;
	}
	/*原图水印*/
	$met_big_img='';
	
	if($met_big_wate==1&&$val['imgurl']!=''){
		$imgurl=$val['imgurl'];
		$imgurlsql='';
		if(!stristr($val['imgurl'],'watermark')){
			$setimgurl   = explode("/",$imgurl);
			$imgurl=$setimgurl[0]."/".$setimgurl[1]."/".$setimgurl[2]."/watermark/".$setimgurl[3];
			$imgurlsql="imgurl='$imgurl'";
		}
		$met_big_img = str_ireplace("/watermark","",$val['imgurl']);
		$img->src_image_name = $depth."../".$met_big_img;
		$img->save_file = $depth."../".$imgurl;
		$img->create();
		$met_bigthumb_img=$depth."../".$met_big_img;
		//内容页缩略图
		$met_img_x='';
		$met_img_y='';
		if($module==3){$met_img_x=$met_productdetail_x;$met_img_y=$met_productdetail_y;}
		if($module==5){$met_img_x=$met_imgdetail_x;$met_img_y=$met_imgdetail_y;}
		$setthumb   = explode("/",$met_big_img);
		$f = new upfile($met_img_type,"../../../upload/$setthumb[2]/",$met_img_maxsize,'',1);
		$f->savename=$setthumb[3];
		$imgurls = $f->createthumb($met_bigthumb_img,$met_img_x,$met_img_y,'thumb_dis/');
		$img->src_image_name = $imgurls;
		$img->save_file =$imgurls;
		$img->create();
	}
	
	/*展示图片*/
	if($met_big_wate==1&&$val['displayimg']!=''){
		$displayurl=explode("|",$val['displayimg']);
		foreach($displayurl as $key1=>$val1){
			$displayurls[]=explode("*",$val1);
		}
		$displayflag=0;
		$displaysql='';
		foreach($displayurls as $key2=>$val2){
			$imgurl=$val2[1];
			if(!stristr($val2[1],'watermark')){
				$setimgurl   = explode("/",$imgurl);
				$imgurl=$setimgurl[0]."/".$setimgurl[1]."/".$setimgurl[2]."/watermark/".$setimgurl[3];
				$displayflag=1;
			}
			$setdisplayimg.="$val2[0]*$imgurl|";
			$met_bigdisplay_img = str_ireplace("/watermark","",$val2[1]);
			if($met_big_wate==1){
				$img->src_image_name = $depth."../".$met_bigdisplay_img;
				$img->save_file = $depth."../".$imgurl;
				$img->create();
			}
			//内容页缩略图
			$setthumb   = explode("/",$met_bigdisplay_img);
			$f = new upfile($met_img_type,"../../../upload/$setthumb[2]/",$met_img_maxsize,'',1);
			$f->savename=$setthumb[3];
			$met_dis_img=$depth."../".$met_bigdisplay_img;
			$met_bigdisplay_img_iconv=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$met_dis_img):$met_dis_img;
			if(file_exists($met_bigdisplay_img_iconv)){
				$met_img_x='';
				$met_img_y='';
				if($module==3){$met_img_x=$met_productdetail_x;$met_img_y=$met_productdetail_y;}
				if($module==5){$met_img_x=$met_imgdetail_x;$met_img_y=$met_imgdetail_y;}
				$imgurls = $f->createthumb($met_dis_img,$met_img_x,$met_img_y,'thumb_dis/');
				$img->src_image_name = $imgurls;
				$img->save_file =$imgurls;
				$img->create();
			}
		}
		if($displayflag==1){
			$setdisplayimg=trim($setdisplayimg,'|');
			$displaysql="displayimg='$setdisplayimg'";
		}
	}
	/*产品内容图片*/
	if($met_big_wate==1&&$val['content']!=''){
		$contentsql='';
		$contentsql=concentwatermark($val['content'],'content');
	}
	if($met_big_wate==1&&$val['content1']!=''){
		$contentsql1='';
		$contentsql1=concentwatermark($val['content1'],'content1');
	}
	if($met_big_wate==1&&$val['content2']!=''){
		$contentsql2='';
		$contentsql2=concentwatermark($val['content2'],'content2');
	}
	if($met_big_wate==1&&$val['content3']!=''){
		$contentsql3='';
		$contentsql3=concentwatermark($val['content3'],'content3');
	}
	if($met_big_wate==1&&$val['content4']!=''){
		$contentsql4='';
		$contentsql4=concentwatermark($val['content4'],'content4');
	}
	$sql='';
	if($imgurlsql)$sql.="$imgurlsql,";
	if($displaysql)$sql.="$displaysql,";
	if($contentsql)$sql.="$contentsql,";
	if($contentsql1)$sql.="$contentsql1,";
	if($contentsql2)$sql.="$contentsql2,";
	if($contentsql3)$sql.="$contentsql3,";
	if($contentsql4)$sql.="$contentsql4,";
	$sql=substr($sql,0,-1);
	$query="update $table set $sql where id='$val[id]'";
	$db->query($query);
	/*字段图片*/
	if($met_big_wate==1&&$para_list){
			foreach($para_list as $key3=>$val3){
				$imagelist=$db->get_one("select * from $met_plist where lang='$lang' and  paraid='$val3[id]' and listid='$val[id]'");
				$imgurl=$imagelist['info'];
				if(!stristr($imagelist['info'],'watermark')){
					$setimgurl   = explode("/",$imgurl);
					$imgurl=$setimgurl[0]."/".$setimgurl[1]."/".$setimgurl[2]."/watermark/".$setimgurl[3];
					$query="update $met_plist set info='$imgurl' where id='$imagelist[id]'";
					$db->query($query);					
				}
				$met_bigpara_img = str_ireplace("/watermark","",$imagelist['info']);
				if($met_big_wate==1){
					$img->src_image_name = $depth."../".$met_bigpara_img;
					$img->save_file = $depth."../".$imgurl;
					$img->create();
				}
			}
	}	
	/*缩略图*/
	if($met_thumb_wate==1&&$val['imgurls']!=''){
		$imgurls=$depth.'../'.$val['imgurls'];
		if($met_big_img==''){
			$imgurl=$val['imgurl'];
			if(!stristr($val['imgurl'],'watermark')){
				$setimgurl   = explode("/",$imgurl);
				$imgurl=$setimgurl[0]."/".$setimgurl[1]."/".$setimgurl[2]."/watermark/".$setimgurl[3];
			}
			$met_big_img = str_ireplace("/watermark","",$val['imgurl']);
		}
		
		$setthumb   = explode("/",$met_big_img);
		$f = new upfile($met_img_type,"../../../upload/$setthumb[2]/",$met_img_maxsize,'',1);
		$f->savename=$setthumb[3];
		$met_bigthumb_img=$depth."../".$met_big_img;
		$met_big_img_iconv=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$met_bigthumb_img):$met_bigthumb_img;
		if(file_exists($met_big_img_iconv)){
			//列表和首页缩略图
			if($met_big_img==str_ireplace("/thumb","",$val['imgurls'])){
				$met_img_x='';
				$met_img_y='';
				if($met_img_style==1)imgstyle($module);
				$met_thumb_img=$depth."../".$met_big_img;
				$imgurls = $f->createthumb($met_thumb_img,$met_img_x,$met_img_y);
				if($met_wate_class==2){
					$img->met_image_name = $depth.$met_wate_img;
				}else {
					$img->met_text_size  = $met_text_size;
				}
				$img->src_image_name = $imgurls;
				$img->save_file =$imgurls;
				$img->create();
			}
		}
	}	
}
echo 'ok';
die();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>