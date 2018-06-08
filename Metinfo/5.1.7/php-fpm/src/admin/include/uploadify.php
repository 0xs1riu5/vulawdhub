<?php
require_once 'common.inc.php';
if(!$metinfo_admin_id||!$metinfo_admin_pass){
	echo $lang_uplaoderr1;
	die();
}else{
	$admincp_ok = $db->get_one("SELECT * FROM $met_admin_table WHERE id='$metinfo_admin_id' and admin_pass='$metinfo_admin_pass' and usertype='3'");
	if(!$admincp_ok){
		echo $metinfo_admin_id.$lang_uplaoderr1;
		die();
	}
}
require_once 'upfile.class.php';
require_once 'watermark.class.php';
/*初始化*/
$metinfo=0;
$met_file_maxsize=$met_file_maxsize*1024*1024;
$file_size=$_FILES['Filedata']['size'];
if($file_size>$met_file_maxsize){
	echo $lang_filemaxsize;
	exit;
}
$filesize=round($_FILES['Filedata']['size']/1024,2);
/*批量上传内容csv文件*/
if($type=="contentup"){
	$met_file_format='csv';
	$f = new upfile($met_file_format,'',$met_file_maxsize,'','1','|');
	$filename=time().'.csv';
	$flienamecsv=$f->upload("Filedata",$filename);
	$flienamecsv='../../'.$flienamecsv;
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}
	$fileField=$_FILES['Filedata']['name'];
	$fileField=str_replace(".csv","",$fileField);
	$metinfo='1$'.$flienamecsv.'|'.$fileField;
/*单独上传缩略图*/
}elseif($type=="small") {  
	$f = new upfile($met_file_format,'',$met_file_maxsize,'','1','|');
	$f->savepath = $f->savepath.'thumb/';
	$imgurls = $f->upload('Filedata');
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}
	$metinfo='1$'.$imgurls;
/*大图上传-水印-缩略图生成*/
}elseif($type=='big_wate_img'){
	$f = new upfile($met_file_format,'',$met_file_maxsize,'','1','|');
	$imgurls = $f->upload('Filedata');
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}
	$met_big_img = $imgurls; 
	$img = new Watermark();
	if($met_big_wate==1){
		if($met_wate_class==2){
			$img->met_image_name = $met_wate_bigimg;
			$img->met_image_pos  = $met_watermark;
		}else{
			$img->met_text       = $met_text_wate;
			$img->met_text_size  = $met_text_bigsize;
			$img->met_text_color = $met_text_color;
			$img->met_text_angle = $met_text_angle;
			$img->met_text_pos   = $met_watermark;
			$img->met_text_font  = $met_text_fonts;
		}
		$img->src_image_name ="../".$imgurls;
		$img->save_file = $f->waterpath.$f->savename;
		$img->create();
		$imgurls ="../upload/".date('Ym')."/watermark/".$f->savename;
	}
	$met_dis_img='../'.$met_big_img;
	if($wate==3){$met_img_x=$met_productdetail_x;$met_img_y=$met_productdetail_y;}
	if($wate==5){$met_img_x=$met_imgdetail_x;$met_img_y=$met_imgdetail_y;}
	if($met_img_x&&$met_img_y){
		$met_dis_imgs=$f->createthumb($met_dis_img,$met_img_x,$met_img_y,'thumb_dis/');
		if($f->get_error()==1){
			echo $f->get_errorcode();
			die();
		}
		if($met_big_wate==1){
			if($met_wate_class==2){
				$img->met_image_name = $met_wate_bigimg;
				$img->met_image_pos  = $met_watermark;
			}else{
				$img->met_text       = $met_text_wate;
				$img->met_text_size  = $met_text_bigsize;
				$img->met_text_color = $met_text_color;
				$img->met_text_angle = $met_text_angle;
				$img->met_text_pos   = $met_watermark;
				$img->met_text_font  = $met_text_fonts;
			}
			$img->src_image_name =$met_dis_imgs;
			$img->save_file = $met_dis_imgs;
			$img->create();
		}
	}
	if($met_autothumb_ok && $module!=67 && $module){
		imgstyle($module);
		$met_big_img="../".$met_big_img;
		$imgurlss = $f->createthumb($met_big_img,$met_img_x,$met_img_y);
		if($f->get_error()==1){
			echo $f->get_errorcode();
			die();
		}
		if($met_thumb_wate==1){
			if($met_wate_class==2){
				$img->met_image_name = $met_wate_img;
				$img->met_image_pos = $met_watermark;
			}else {
				$img->met_text = $met_text_wate;
				$img->met_text_size = $met_text_size;
				$img->met_text_color = $met_text_color;
				$img->met_text_angle = $met_text_angle;
				$img->met_text_pos   = $met_watermark;
				$img->met_text_font = $met_text_fonts;
			}
			$img->src_image_name =$imgurlss;
			$img->save_file =$imgurlss;
			$img->create();
		}
		$imgurls_a=explode("../",$imgurlss);
		$imgurlss="../".$imgurls_a[2];
	}
	$metinfo='1$'.$imgurls.'|'.$imgurlss;
	if(!$module||$module==67)$metinfo='1$'.$imgurls;
/*ICO图标*/
}elseif($type=='metico'){
	$f = new upfile($met_file_format,'../../',$met_file_maxsize,'','1','|');
	$file = $f->upload('Filedata','favicon');
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}
	$metinfo='1$'.$file;
/*文件上传*/
}elseif($type=='upfile'){
	$f = new upfile($met_file_format,'../../upload/file/',$met_file_maxsize,'','1','|');
	$file = $f->upload('Filedata');
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}
	$metinfo='1$'.$file;
	if($module==4)$metinfo.='|'.$filesize;
/*图片上传*/
}elseif($type=='upimage'){
	$f = new upfile($met_file_format,'',$met_file_maxsize,'','1','|');
	$imgurls = $f->upload('Filedata');
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}	
	$metinfo='1$'.$imgurls;
}elseif($type=='upimage-met'){
	$f = new upfile($met_file_format,'',$met_file_maxsize,'','1','|');
	$imgurls = $f->upload('Filedata');
	if($f->get_error()){
		echo $f->get_errorcode();
		die();
	}	
	if($met_big_wate==1){
		$img = new Watermark();
		if($met_wate_class==2){
			$img->met_image_name = $met_wate_bigimg;
			$img->met_image_pos  = $met_watermark;
		}else{
			$img->met_text       = $met_text_wate;
			$img->met_text_size  = $met_text_bigsize;
			$img->met_text_color = $met_text_color;
			$img->met_text_angle = $met_text_angle;
			$img->met_text_pos   = $met_watermark;
			$img->met_text_font  = $met_text_fonts;
		}
		$img->src_image_name ="../".$imgurls;
		$img->save_file = $f->waterpath.$f->savename;
		$img->create();
		$imgurls ="../upload/".date('Ym')."/watermark/".$f->savename;
	}
	$metinfo='1$'.$imgurls;
}elseif($type=='skin'){
/*模板文件*/
	$filetype=explode('.',$_FILES['Filedata']['name']);
	if($filetype[count($filetype)-1]=='zip'){
		//if(!is_writable('../../templates/'))@chmod('../../templates/',0777);
		$filenamearray=explode('.zip',$_FILES['Filedata']['name']);
	    $skin_if=$db->get_one("SELECT * FROM {$met_skin_table} WHERE skin_file='{$filenamearray[0]}'");
	    if($skin_if){
			$metinfo=$lang_loginSkin;
		}else{
			$f = new upfile('zip','../../templates/','','');
			if(file_exists('../../templates/'.$filenamearray[0].'.zip'))$filenamearray[0]='metinfo'.$filenamearray[0];
			$met_upsql = $f->upload('Filedata',$filenamearray[0]); 
			include "pclzip.lib.php";
			$archive = new PclZip('../../templates/'.$filenamearray[0].'.zip');
			if($archive->extract(PCLZIP_OPT_PATH, '../../templates/') == 0)$metinfo=$archive->errorInfo(true);
			@unlink('../../templates/'.$filenamearray[0].'.zip');
			$metinfo='1$'.$filenamearray[0];
		}
	}else{
		$metinfo=$lang_uplaoderr2;
	}
/*数据库文件*/
}elseif($type=='sql'){
	if(strstr($_FILES['Filedata']['name'],'.sql')){
		$filenamearray=explode('.sql',$_FILES['Filedata']['name']);
		$f = new upfile('sql,zip','../databack/','','');
		if(file_exists('../databack/'.$filenamearray[0].'.sql'))$filenamearray[0]='metinfo'.$filenamearray[0];
		if($_FILES['Filedata']['name']!=''){
				$met_upsql   = $f->upload('Filedata',$filenamearray[0]); 
		}
		include "pclzip.lib.php";
		$archive = new PclZip('../databack/sql/'.'metinfo_'.$filenamearray[0].'.zip');
		$archive->add('../databack/'.$filenamearray[0].'.sql',PCLZIP_OPT_REMOVE_PATH,'../databack/');
		$metinfo='1$'.'../databack/'.$filenamearray[0].'.sql';
	}else{
		$filetype=explode('.',$_FILES['Filedata']['name']);
		if($filetype[count($filetype)-1]=='zip'){
			$filenamearray=explode('.zip',$_FILES['Filedata']['name']);
			$f = new upfile('sql,zip','../databack/sql/','','');
			if(file_exists('../databack/sql/'.$filenamearray[0].'.zip'))$filenamearray[0]='metinfo'.$filenamearray[0];
			if($_FILES['Filedata']['name']!=''){
					$met_upsql = $f->upload('Filedata',$filenamearray[0]); 
			}
			include "pclzip.lib.php";
			$archive = new PclZip('../databack/sql/'.$filenamearray[0].'.zip');
			if($archive->extract(PCLZIP_OPT_PATH, '../databack') == 0){
				$metinfo=$archive->errorInfo(true);
			}
			else{
				$list = $archive->listContent();
				$metinfo='1$'.'../databack/sql/'.$filenamearray[0].'.zip';
			}
		}else{
			$metinfo=$lang_uplaoderr3;
		}
	}
}
echo $metinfo;
?>