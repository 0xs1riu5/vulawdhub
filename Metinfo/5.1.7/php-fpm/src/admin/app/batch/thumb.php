<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
@set_time_limit(0);
if($action=="class"){
	$class=$class3?$class3:($class2?$class2:$class1);
	$remark=$db->get_one("select * from $met_column where id='$class'");
	$table=moduledb($remark['module']);
	$resql="class1='$class1'";
	$resql.=$class2?" and class2='$class2'":"";
	$resql.=$class3?" and class3='$class3'":"";
	$renow=$db->get_all("select * from $table where $resql  and (recycle='0' or recycle='-1')");
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
$img = new Watermark();
if($met_wate_class==2){
	$img->met_image_pos = $met_watermark;
}else {
	$img->met_text = $met_text_wate;
	$img->met_text_color = $met_text_color;
	$img->met_text_angle = $met_text_angle;
	$img->met_text_pos   = $met_watermark;
	$img->met_text_font = $depth.$met_text_fonts;
}
$mou=$table;
$table=moduledb($table);
$query="select * from $table where id='$id'";
$renow[0]=$db->get_one($query);
foreach($renow as $key=>$val){
	if($val['imgurls']){
		$met_big_img = str_ireplace("/watermark","",$val['imgurl']);
		$imgurls=$depth.'../'.$val['imgurls'];
		if($met_img_style==1)imgstyle($mou);
		$setthumb   = explode("/",$met_big_img);
		$f = new upfile($met_img_type,"../../../upload/$setthumb[2]/",$met_img_maxsize,'',1);
		$f->savename=$setthumb[3];
		$met_thumb_img=$depth."../".$met_big_img;
		$met_big_img_iconv=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$met_thumb_img):$met_thumb_img;
		if(file_exists($met_big_img_iconv)){
			$imgurls = $f->createthumb($met_thumb_img,$met_img_x,$met_img_y);
			if($f->get_error()==0){
				if($met_thumb_wate==1){
					if($met_wate_class==2){
						$img->met_image_name = $depth.$met_wate_img;
					}else {
						$img->met_text_size  = $met_text_size;
					}
					$img->save_file =$imgurls;
					$img->create($imgurls);
					$imgurls_a=explode("../",$imgurls);
					$imgurls="../".$imgurls_a[3];
				}
				if($met_thumb_img!=$depth."../".str_ireplace("/thumb","",$val['imgurls'])){
					$imgurls='../'.str_ireplace("../","",$imgurls);
					$query="update $table set imgurls='$imgurls' where id='$val[id]'";
					if($met_deleteimg==1&&$db->query($query)){@file_unlink("../../$val[imgurls]");}
				}
			}
			$met_img_x='';
			$met_img_y='';
			if($mou==3){$met_img_x=$met_productdetail_x;$met_img_y=$met_productdetail_y;}
			if($mou==5){$met_img_x=$met_imgdetail_x;$met_img_y=$met_imgdetail_y;}
			$met_bigthumb_img=$depth."../".$met_big_img;
			$imgurls = $f->createthumb($met_bigthumb_img,$met_img_x,$met_img_y,'thumb_dis/');
			if($met_big_wate){
				if($met_wate_class==2){
					$img->met_image_name = $depth.$met_wate_bigimg;
				}else {
					$img->met_text_size  = $met_text_bigsize;
				}
				$img->src_image_name = $imgurls;
				$img->save_file =$imgurls;
				$img->create();
			}
		}
	}	
	/*Õ¹Ê¾Í¼Æ¬*/
	if($val['displayimg']){
		$displayurl=explode("|",$val['displayimg']);
		foreach($displayurl as $key1=>$val1){
			$displayurls[]=explode("*",$val1);
		}
		foreach($displayurls as $key2=>$val2){
			$met_bigdisplay_img = str_ireplace("/watermark","",$val2[1]);
			//ÄÚÈÝÒ³ËõÂÔÍ¼
			$setthumb   = explode("/",$met_bigdisplay_img);
			$f = new upfile($met_img_type,"../../../upload/$setthumb[2]/",$met_img_maxsize,'',1);
			$f->savename=$setthumb[3];
			$met_dis_img=$depth."../".$met_bigdisplay_img;
			$met_bigdisplay_img_iconv=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$met_dis_img):$met_dis_img;
			if(file_exists($met_bigdisplay_img_iconv)){
				$met_img_x='';
				$met_img_y='';
				if($mou==3){$met_img_x=$met_productdetail_x;$met_img_y=$met_productdetail_y;}
				if($mou==5){$met_img_x=$met_imgdetail_x;$met_img_y=$met_imgdetail_y;}
				$imgurls = $f->createthumb($met_dis_img,$met_img_x,$met_img_y,'thumb_dis/');
				if($met_big_wate){
					if($met_wate_class==2){
						$img->met_image_name = $depth.$met_wate_bigimg;
					}else {
						$img->met_text_size  = $met_text_bigsize;
					}
					$img->src_image_name = $imgurls;
					$img->save_file =$imgurls;
					$img->create();
				}
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