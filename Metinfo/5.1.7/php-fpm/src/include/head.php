<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
	require_once 'common.inc.php';
	require_once ROOTPATH.'include/global/pseudo.php';
	$class1    = $index=='index'?10001:($class1==''?0:$class1);
	$class2    = $index=='index'?0:($class2==''?0:$class2);
	$class3    = $index=='index'?0:($class3==''?0:$class3);
	$classnow  = $classnow==''?($class3?$class3:($class2?$class2:$class1)):$classnow;
	$class_list[10001]['module'] = 10001;
    $tempfie=ROOTPATH."templates/".$met_skin_user."/database.inc.php";
    $conffie=ROOTPATH.'config/database.inc.php';
	require_once file_exists($tempfie)?$tempfie:$conffie;
	$pagemark=$class_list[$classnow]['module'];
/*Standby field */
	if(!isset($dataoptimize[$pagemark]['otherinfo']))$dataoptimize[$pagemark]['otherinfo']=$dataoptimize[10000]['otherinfo'];
	if($dataoptimize[$pagemark]['otherinfo']){
	    $otherinfo=met_cache('otherinfo_'.$lang.'.inc.php');
		if(!$otherinfo){
			$otherinfo=cache_otherinfo();
		}
		if($index=="index"){
			$otherinfo['imgurl1']=explode("../",$otherinfo['imgurl1']);
			$otherinfo['imgurl1']=$otherinfo['imgurl1'][1];
			$otherinfo['imgurl2']=explode("../",$otherinfo['imgurl2']);
			$otherinfo['imgurl2']=$otherinfo['imgurl2'][1];
		}
	}
/*Flash*/
	if($metview_flash==$met_member_force){
		$met_flasharray[$classnow]['type']=$metview_flash_type;
		$met_flasharray[$classnow]['imgtype']=$metview_flash_imgtype;
		$met_flasharray[$classnow]['x']=$metview_flash_x;
		$met_flasharray[$classnow]['y']=$metview_flash_y;
	}
	if(!isset($met_flasharray[$classnow]['type']))$met_flasharray[$classnow]=$met_flasharray[10000];
	if($met_flasharray[$classnow]['type']){
		$query_x=$met_flasharray[$classnow]['type']==2?"and flash_path!=''":"and img_path!=''";
		$query="select * from $met_flash where lang='$lang' and (module like '%,{$classnow},%' or module='metinfo') {$query_x} order by no_order";
		$result= $db->query($query);
		if(mysql_affected_rows()==0){
			$superior=$class_list[$classnow]['bigclass'];
			$query_x=$met_flasharray[$superior]['type']==2?"and flash_path!=''":"and img_path!=''";
			$query="select * from $met_flash where lang='$lang' and (module like '%,{$superior},%' or module='metinfo') {$query_x} order by no_order";
			$result= $db->query($query);			
		}
		if(mysql_affected_rows()==0){
			$superior=$class_list[$superior]['bigclass'];
			$query_x=$met_flasharray[$superior]['type']==2?"and flash_path!=''":"and img_path!=''";
			$query="select * from $met_flash where lang='$lang' and (module like '%,{$superior},%' or module='metinfo') {$query_x} order by no_order";
			$result= $db->query($query);			
		}
		while($list = $db->fetch_array($result)){
			if($index=="index"){
				$list['img_path_array']=explode("../",$list['img_path']);
				$list['img_path']=$list['img_path_array'][1];
				$list['flash_path_array']=explode("../",$list['flash_path']);
				$list['flash_path']=$list['flash_path_array'][1];
				$list['flash_back_array']=explode("../",$list['flash_back']);
				$list['flash_back']=$list['flash_back_array'][1];
			}
			$met_flashall[]=$list;
			$listmodule_x=explode(",",$list['module']);
			$flash_mx = count($listmodule_x);
			if($list['flash_path']!=""){
				$met_flashflashall[]=$list; 
				if($list['module']=='metinfo'){
					if(!$flash_flash_module[$classnow])$flash_flash_module[$classnow]=$list;
				}else{
					for($i=0;$i<$flash_mx;$i++){
						if(!$flash_flash_module[$listmodule_x[$i]] && $listmodule_x[$i]!='')$flash_flash_module[$listmodule_x[$i]]=$list;
					}
				}
			}else{
				$met_flashimgall[]=$list;
				if($list['module']=='metinfo'){
					if(!$flash_img_module[$classnow])$flash_img_module[$classnow]=$list;
				}else{
					for($i=0;$i<$flash_mx;$i++){
						if((!$flash_img_module[$listmodule_x[$i]]) && $listmodule_x[$i]!='')$flash_img_module[$listmodule_x[$i]]=$list;
					}
				}
			}
		}
		
		if($met_flasharray[$classnow]['type']==3){
			foreach($met_flashall as $key=>$val){
				$val['nowmod']=','.$classnow.',';
				if($val['module']==$val['nowmod'])$flash_img_module[$classnow]=$val;
				$val['nowmod']=','.$superior.',';
				if($val['module']==$val['nowmod'])$flash_img_module[$classnow]=$val;
			}
		}
		if($met_flasharray[$classnow]['type']==2){
			if(count($flash_flash_module[$classnow])==0){
				if($class3<>0){
					if($class2<>0&&count($flash_flash_module[$class2])<>0){
						$flash_nowarray=$flash_flash_module[$class2];
						$met_flash_x=$met_flasharray[$class2]['x'];
						$met_flash_y=$met_flasharray[$class2]['y'];
					}elseif($class1<>0&&count($flash_flash_module[$class1])<>0){
						$flash_nowarray=$flash_flash_module[$class1];
						$met_flash_x=$met_flasharray[$class1]['x'];
						$met_flash_y=$met_flasharray[$class1]['y'];
					}else{
						$flash_nowarray=$flash_flash_module[10000];
						$met_flash_x=$met_flasharray[10000]['x'];
						$met_flash_y=$met_flasharray[10000]['y'];
					}
				}elseif($class2<>0){
					if($class1<>0&&count($flash_flash_module[$class1])<>0){
						$flash_nowarray=$flash_flash_module[$class1];
						$met_flash_x=$met_flasharray[$class1]['x'];
						$met_flash_y=$met_flasharray[$class1]['y'];
					}else{
						$flash_nowarray=$flash_flash_module[10000];
						$met_flash_x=$met_flasharray[10000]['x'];
						$met_flash_y=$met_flasharray[10000]['y'];
					}
				}else{
					$flash_nowarray=$flash_flash_module[10000];
					$met_flash_x=$met_flasharray[10000]['x'];
					$met_flash_y=$met_flasharray[10000]['y'];
				}
			}else{
				$flash_nowarray=$flash_flash_module[$classnow];
				$met_flash_x=$met_flasharray[$classnow]['x'];
				$met_flash_y=$met_flasharray[$classnow]['y'];
			}

			if(count($flash_nowarray)<>0){
				$met_flash_ok=1;
				$met_flash_type=1;
				$met_flash_url=$flash_nowarray['flash_path'];
				$met_e_flash_url=$flash_nowarray['e_flash_path'];
				$met_flash_back=$flash_nowarray['flash_back'];
				$met_e_flash_back=$flash_nowarray['e_flash_back'];
			}
		}elseif($met_flasharray[$classnow][type]==1){
			$met_flash_ok=1;
			$met_flash_type=0;
			foreach($met_flashimgall as $key=>$val){
				if($val['img_path']!=""){
						$met_flash_img=$met_flash_img.$val['img_path']."|";
						$met_flash_imglink=$met_flash_imglink.$val['img_link']."|";
						$met_flash_imgtitle=$met_flash_imgtitle.$val['img_title']."|";
						$met_flashimg[]=$val;
				}
			}
			$met_flash_x=$met_flasharray[$classnow]['x'];
			$met_flash_y=$met_flasharray[$classnow]['y'];
		}elseif($met_flasharray[$classnow]['type']==3){
			if(count($flash_img_module[$classnow])){
				$flash_imgone_img=$flash_img_module[$classnow]['img_path'];
				$flash_imgone_url=$flash_img_module[$classnow]['img_link'];
				$flash_imgone_title=$flash_img_module[$classnow]['img_title'];
			}else{
				if($flash_imgone_img==""){
					$flash_imgone_img=$flash_img_module[$class2]['img_path'];
					$flash_imgone_url=$flash_img_module[$class2]['img_link'];
					$flash_imgone_title=$flash_img_module[$class2]['img_title'];
				}
				if($flash_imgone_img==""){
					$flash_imgone_img=$flash_img_module[$class1]['img_path'];
					$flash_imgone_url=$flash_img_module[$class1]['img_link'];
					$flash_imgone_title=$flash_img_module[$class1]['img_title'];
				}
				if($flash_imgone_img==""){
					$flash_imgone_img=$flash_img_module[10000]['img_path'];
					$flash_imgone_url=$flash_img_module[10000]['img_link'];
					$flash_imgone_title=$flash_img_module[10000]['img_title'];
				}
			}
		}elseif($met_flasharray[$classnow]['type']==0){
			$met_flash_ok=0;
		}
		$met_flash_img=substr($met_flash_img, 0, -1);
		$met_flash_imglink=substr($met_flash_imglink, 0, -1);
		$met_flash_imgtitle=substr($met_flash_imgtitle, 0, -1);
		$met_flashurl=$met_flash_imglink;
		$met_flash_xpx=$met_flash_x."px";
		$met_flash_ypx=$met_flash_y."px";
	}
/*parameter*/
	if(!isset($dataoptimize[$pagemark]['parameter']))$dataoptimize[$pagemark]['parameter']=$dataoptimize[10000]['parameter'];
	if($dataoptimize[$pagemark]['parameter']||$search=='search'){
		$query = "SELECT * FROM $met_parameter where module<6  and lang='$lang' order by no_order";
		$result = $db->query($query);
		while($list= $db->fetch_array($result)){
			$list['para']="para".$list['id'];
			$list['paraname']="para".$list['id']."name";
			$metpara[$list['id']]=$list;
			if($list['class1']==0 or $list['class1']==$class1){
				switch($list['module']){
					case 3:
						$product_para[]=$list;
						$productpara[$list['type']][]=$list;
						$product_paralist[]=$list;
						/*2.0*/
						if($list[type]==1 or $list[type]==2)$product_para200[]=$list;
						if($list[type]==5)$product_paraimg[]=$list;
						if($list[type]==2)$product_paraselect[]=$list;
						/*2.0*/
						break;
					case 4:
						$download_para[]=$list;
						$downloadpara[$list['type']][]=$list;
						$download_paralist[]=$list;
						/*2.0*/
						if($list[type]==1)$download_para200[]=$list;
						/*2.0*/
						break;
					case 5:
						$img_para[]=$list;
						$imgpara[$list['type']][]=$list;
						$img_paralist[]=$list;
						/*2.0*/
						if($list[type]==1)$img_para200[]=$list;
						if($list[type]==5)$img_paraimg[]=$list;
						if($list[type]==2)$img_paraselect[]=$list;
						/*2.0.*/
						break;
				}
			}
		}
		$query = "SELECT * FROM $met_list where lang='$lang' order by no_order";
		$result = $db->query($query);
		while($list= $db->fetch_array($result)){
			$para_select[$list['bigid']][]=$list;
		}
	}
	/*friendly link	*/
	if(!isset($dataoptimize[$pagemark]['link']))$dataoptimize[$pagemark]['link']=$dataoptimize[10000]['link'];
	if($dataoptimize[$pagemark]['link']){	
		$query = "SELECT * FROM $met_link where show_ok='1' and lang='$lang' order by orderno desc";
		$result = $db->query($query);
		while($list= $db->fetch_array($result)){
		if($index=='index' && strstr($list['weblogo'],"../")){
		$linkweblogo=explode('../',$list['weblogo']);
		$list['weblogo']=$linkweblogo[1];
		}
		if($list['link_type']=="0"){
		if($list['com_ok']=="1")$link_text_com[]=$list;
		$link_text[]=$list;
		}
		if($list['link_type']=="1"){
		if($list['com_ok']=="1")$link_img_com[]=$list;
		$link_img[]=$list;
		}
		if($list['com_ok']=="1")$link_com[]=$list;
		$link[]=$list;
	}
	}
	if($met_member_use and $metaccess){
		if($index!="index"){
$met_js_access="<script type='text/javascript' id='metccde'>
var jsFile = document.createElement('script');
jsFile.setAttribute('type','text/javascript');
jsFile.setAttribute('src','../include/access.php?metuser={$metuser}&lang={$lang}&metaccess={$metaccess}&random='+Math.random());
document.getElementsByTagName('head').item(0).appendChild(jsFile);
</script>";
			$query="select * from $met_admin_array where id='$metaccess'";
			$metaccess=$db->get_one($query);
			if(intval($metinfo_member_type)<intval($metaccess)){
				session_unset();
				$_SESSION['metinfo_member_name']=$metinfo_member_name;
				$_SESSION['metinfo_member_pass']=$metinfo_member_pass;
				$_SESSION['metinfo_member_type']=$metinfo_member_type;
				$_SESSION['metinfo_admin_name']=$metinfo_admin_name;
				okinfo('../member/'.$member_index_url.'&referer='.urlencode(request_uri()),$lang_access);
			}
		}
	}
	$listimg['news']=$listnew['news'];
	$hitslistimg['news']=$hitslistnew['news'];
	$classlistimg['news']=$classlistnew['news'];
	$hitsclasslistimg['news']=$hitsclasslistnew['news'];

	if($class_list[$class_list[$classnow]['releclass']]['module']>5 and count($nav_list2[$class_list[$classnow]['releclass']])){
		$nav_list2[$class_list[$classnow]['releclass']][count($nav_list2[$class_list[$classnow]['releclass']])]=$class_list[$class_list[$classnow]['releclass']];
	}
	if($met_img_style){
		switch($class_list[$classnow]['module']){
			case 2:
				$met_img_x=$met_newsimg_x?$met_newsimg_x:$met_img_x; 
				$met_img_y=$met_newsimg_y?$met_newsimg_y:$met_img_y;
				break;
			case 3:
				$met_img_x=$met_productimg_x?$met_productimg_x:$met_img_x; 
				$met_img_y=$met_productimg_y?$met_productimg_y:$met_img_y;
				break;
			case 5:
				$met_img_x=$met_imgs_x?$met_imgs_x:$met_img_x; 
				$met_img_y=$met_imgs_y?$met_imgs_y:$met_img_y;
				break;
		}
	}
	$navdown=$class1;
	if($class1 == 0 || $class_list[$class1]['na'] == 2 || $class_list[$class1][nav] == 0)$navdown="10001";
	if($class_list[$classnow]['nav'] == 1 || $class_list[$classnow]['nav'] == 3)$navdown=$classnow;
	if($class_list[$classnow]['nav'] == 0 || $class_list[$classnow]['nav'] == 2){
		if($class_list[$classnow]['releclass'])$navdown=$class_list[$classnow]['releclass'];
		$higher=$class_list[$classnow]['bigclass'];
		if($class_list[$higher]['releclass'])$navdown=$class_list[$higher]['releclass'];
		if($class_list[$higher]['nav']==1||$class_list[$higher]['nav']==3)$navdown=$higher;
	}
	if(!$navdown)$navdown=10001;
	$metblank=$met_urlblank?"target='_blank'":"target='_self'";
	$onlinex=$met_online_type<2?$met_onlineleft_left:$met_onlineright_right;
	$onliney=$met_online_type<2?$met_onlineleft_top:$met_onlineright_top;
	/*站长统计*/
	$settings = parse_ini_file(ROOTPATH."config/webstat.inc.php");
	@extract($settings);
	if($met_stat){
		$stat_d=$classnow.'-'.$id.'-'.$lang;
		$met_stat_js='<script src="'.$navurl.'include/stat/stat.php?type=para&u='.$navurl.'&d='.$stat_d.'" type="text/javascript"></script>';
	}
	$class_index=imgxytype($class_index,'index_num');
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>