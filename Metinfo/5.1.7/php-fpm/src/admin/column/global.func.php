<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
function column_copyconfig($foldername,$module,$id){
	global $anyid,$lang_columntip13,$lang,$db,$met_column;
	switch($module){
		case 1:
			$indexaddress ="../about/index.php";
			$newfile  =ROOTPATH.$foldername."/show.php";  
			$address  ="../about/show.php";				
			Copyfile($address,$newfile);
		break;
		case 2:
			$indexaddress ="../news/index.php";
			$newfile =ROOTPATH.$foldername."/news.php"; 
			$address ="../news/news.php"; 
			Copyfile($address,$newfile); 
			$newfile =ROOTPATH.$foldername."/shownews.php"; 
			$address  ="../news/shownews.php"; 
			Copyfile($address,$newfile);
		break;
		case 3:
			$indexaddress ="../product/index.php";
			$newfile =ROOTPATH.$foldername."/product.php";  
			$address  ="../product/product.php"; 
			Copyfile($address,$newfile); 
			$newfile =ROOTPATH.$foldername."/showproduct.php";  
			$address  ="../product/showproduct.php"; 
			Copyfile($address,$newfile);
		break;
		case 4:
			$indexaddress ="../download/index.php";
			$newfile =ROOTPATH.$foldername."/download.php";  
			$address  ="../download/download.php"; 
			Copyfile($address,$newfile); 
			$newfile =ROOTPATH.$foldername."/showdownload.php";  
			$address  ="../download/showdownload.php"; 
			Copyfile($address,$newfile);
			$newfile =ROOTPATH.$foldername."/down.php";  
			$address  ="../download/down.php"; 
			Copyfile($address,$newfile);
		break;
		case 5:
			$indexaddress ="../img/index.php";
			$newfile =ROOTPATH.$foldername."/img.php";  
			$address  ="../img/img.php"; 
			Copyfile($address,$newfile);
			$newfile =ROOTPATH.$foldername."/showimg.php";  
			$address  ="../img/showimg.php"; 
			Copyfile($address,$newfile);
		break;
		case 7:  
			$array[1][0]='met_fd_time';
			$array[1][1]='120';
			$array[2][0]='met_fd_word';
			$array[2][1]='';
			$array[3][0]='met_fd_email';
			$array[3][1]='0';
			$array[4][0]='met_fd_type';
			$array[4][1]='1';
			$array[5][0]='met_fd_to';
			$array[5][1]='';
			$array[6][0]='met_fd_back';
			$array[6][1]='0';
			$array[7][0]='met_fd_title';
			$array[7][1]='';
			$array[8][0]='met_fd_content';
			$array[8][1]='';
			$array[9][0]='met_fd_ok';
			$array[9][1]='1';
			verbconfig($array,$id);
		break;
		case 8:  
			$indexaddress ="../feedback/index.php";
			$newfile =ROOTPATH.$foldername."/uploadfile_save.php";  
			$address ="../feedback/uploadfile_save.php";
			Copyfile($address,$newfile);
			$array[1][0]='met_fd_time';
			$array[1][1]='120';
			$array[2][0]='met_fd_word';
			$array[2][1]='';
			$array[3][0]='met_fd_type';
			$array[3][1]='1';
			$array[4][0]='met_fd_to';
			$array[4][1]='';
			$array[5][0]='met_fd_back';
			$array[5][1]='0';
			$array[6][0]='met_fd_email';
			$array[6][1]='1';
			$array[7][0]='met_fd_title';
			$array[7][1]='';
			$array[8][0]='met_fd_content';
			$array[8][1]='';
			$array[9][0]='met_fdtable';
			$fd_title=$db->get_one("SELECT * FROM $met_column WHERE id='$id'");
			$array[9][1]=$fd_title['name'];
			$array[10][0]='met_fd_class';
			$array[10][1]='1';
			$array[11][0]='met_fd_ok';
			$array[11][1]='1';
			verbconfig($array,$id);
		break;
	}
	Copyindx(ROOTPATH.$foldername.'/index.php',$module);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>