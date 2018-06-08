<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
@set_time_limit(0);
require_once '../login/login_check.php';
if(!function_exists('json_encode')){
    include('JSON.php');
    function json_encode($val){
        $json = new Services_JSON();
        return $json->encode($val);
    }
    function json_decode($val){
        $json = new Services_JSON();
        return $json->decode($val);
    }
}
if($action=="all"){
	/*全站静态打包*/
	if($htmpack){
		$adminfile=$url_array[count($url_array)-2];
		$met_htmpack_url='../databack/htmpack/';
		if(is_dir($met_htmpack_url))deldir($met_htmpack_url);
		$lista = $met_htmpack_url.'templates/'.$met_skin_user.'/images/';
		metnew_dir($lista);
		$lista = $met_htmpack_url.'public/';
		metnew_dir($lista);
		$dira=$met_htmpack_url.'templates/'.$met_skin_user.'/images/';
		$dirb="../../templates/".$met_skin_user.'/images';
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'upload/';
		$dirb="../../upload/";
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'public/flash/';
		$dirb="../../public/flash/";
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'public/images/';
		$dirb="../../public/images/";
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'public/js/';
		$dirb="../../public/js/";
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'public/css/';
		$dirb="../../public/css/";
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'public/ui/';
		$dirb="../../public/ui/";
		xCopy($dirb,$dira,1);
		$dira=$met_htmpack_url.'favicon.ico';
		$dirb="../../favicon.ico";
		copy($dirb,$dira);
	}
	$methtm[]= indexhtm(1,$htmpack);
	//module 1
	foreach($met_classindex[1] as $key=>$val){
		if($val[isshow])$methtm[]= showhtm($val[id],1,$htmpack);
		if($val['releclass']){
			foreach($met_class3[$val[id]] as $key=>$val3){
				if($val3[isshow])$methtm[]= showhtm($val3[id],1,$htmpack);
			}
		}
		else{
			foreach($met_class22[$val[id]] as $key=>$val2){
				if($val2[isshow])$methtm[]= showhtm($val2[id],1,$htmpack);
				foreach($met_class3[$val2[id]] as $key=>$val3){
					if($val3[isshow])$methtm[]= showhtm($val3[id],1,$htmpack);
				}
			}
		}
	}
	$methtmx='';
	//module 2
	foreach($met_classindex[2] as $key=>$val){
		$methtmx.=classhtm($val[id],0,0,1,0,$htmpack).'$|$';
		if($val['releclass']){
			foreach($met_class3[$val[id]] as $key=>$val3){
				$methtmx.=classhtm($val[id],$val3[id],0,1,2,$htmpack).'$|$';
			}
		}
		else{
			foreach($met_class22[$val[id]] as $key=>$val2){
				$methtmx.=classhtm($val[id],$val2[id],0,1,2,$htmpack).'$|$';
				foreach($met_class3[$val2[id]] as $key=>$val3){
					$methtmx.=classhtm($val[id],$val2[id],$val3[id],1,3,$htmpack).'$|$';
				}
			}
		}
		$query="select * from $met_news where class1='$val[id]' and lang='$lang' and (recycle='0' or recycle='-1')";
		$result= $db->query($query);
		while($list = $db->fetch_array($result)){
		 $methtm[]=contenthtm($val[id],$list[id],'shownews',$list[filename],1,$val[foldername],$list[addtime],$htmpack);
		}
	}
	 //module 3
	 foreach($met_classindex[3] as $key=>$val){
		$methtmx.=classhtm($val[id],0,0,1,0,$htmpack).'$|$';
		if($val['releclass']){
			foreach($met_class3[$val[id]] as $key=>$val3){
				$methtmx.=classhtm($val[id],$val3[id],0,1,2,$htmpack).'$|$';
			}
		}
		else{
			foreach($met_class22[$val[id]] as $key=>$val2){
				$methtmx.=classhtm($val[id],$val2[id],0,1,2,$htmpack).'$|$';
				foreach($met_class3[$val2[id]] as $key=>$val3){
					$methtmx.=classhtm($val[id],$val2[id],$val3[id],1,3,$htmpack).'$|$';
				}
			}
		}
		$query="select * from $met_product where class1='$val[id]' and lang='$lang' and (recycle='0' or recycle='-1')";
		$result= $db->query($query);
		while($list = $db->fetch_array($result)){
			$methtm[]=contenthtm($val[id],$list[id],'showproduct',$list[filename],1,$val[foldername],$list[addtime],$htmpack);
		}
	 }
	 //module 4
	 foreach($met_classindex[4] as $key=>$val){
		$methtmx.=classhtm($val[id],0,0,1,0,$htmpack).'$|$';
		if($val['releclass']){
			foreach($met_class3[$val[id]] as $key=>$val3){
				$methtmx.=classhtm($val[id],$val3[id],0,1,2,$htmpack).'$|$';
			}
		}
		else{
			foreach($met_class22[$val[id]] as $key=>$val2){
				$methtmx.=classhtm($val[id],$val2[id],0,1,2,$htmpack).'$|$';
				foreach($met_class3[$val2[id]] as $key=>$val3){
					$methtmx.=classhtm($val[id],$val2[id],$val3[id],1,3,$htmpack).'$|$';
				}
			}
		}
		$query="select * from $met_download where class1='$val[id]' and lang='$lang' and (recycle='0' or recycle='-1')";
		$result= $db->query($query);
		 while($list = $db->fetch_array($result)){
			$methtm[]=contenthtm($val[id],$list[id],'showdownload',$list[filename],1,$val[foldername],$list[addtime],$htmpack);
		}
	 }
	 //module 5
	 foreach($met_classindex[5] as $key=>$val){
		$methtmx.=classhtm($val[id],0,0,1,0,$htmpack).'$|$';
		if($val['releclass']){
			foreach($met_class3[$val[id]] as $key=>$val3){
				$methtmx.=classhtm($val[id],$val3[id],0,1,2,$htmpack).'$|$';
			}
		}
		else{
			foreach($met_class22[$val[id]] as $key=>$val2){
				$methtmx.=classhtm($val[id],$val2[id],0,1,2,$htmpack).'$|$';
				foreach($met_class3[$val2[id]] as $key=>$val3){
					$methtmx.=classhtm($val[id],$val2[id],$val3[id],1,3,$htmpack).'$|$';
				}
			}
		}
			 
		$query="select * from $met_img where class1='$val[id]' and lang='$lang' and (recycle='0' or recycle='-1')";
		$result= $db->query($query);
		 while($list = $db->fetch_array($result)){
		 $methtm[]=contenthtm($val[id],$list[id],'showimg',$list[filename],1,$val[foldername],$list[addtime],$htmpack);
		}
	 }

	 //module 6
	 foreach($met_classindex[6] as $key=>$val){
		$methtmx.=classhtm($val[id],0,0,1,0,$htmpack).'$|$';
		$methtm[]=onepagehtm('job','cv',1,$htmpack); 
		$query="select * from $met_job where lang='$lang'";
		$result= $db->query($query);
		 while($list = $db->fetch_array($result)){
		 $methtm[]=contenthtm($val[id],$list[id],'showjob',$list[filename],1,$val[foldername],$list[addtime],$htmpack);
		}
	 }
	 //module 7
	if(count($met_module[7])){
	 foreach($met_module[7] as $key=>$val){
	 $methtmx.=classhtm($val[id],0,0,1,0,$htmpack).'$|$';
	}
	 $methtm[]=onepagehtm('message','message',1,$htmpack); 
	}
	 //module 8
		foreach($met_classindex[8] as $key=>$val){
			$methtm[]=onepagehtm($val['foldername'],'index',1,$htmpack,$val['filename'],$val['id']);
		}

	 //module 9
	if(count($met_module[9])){
	$methtm[]=onepagehtm('link','index',1,$htmpack);
	if($met_addlinkopen)$methtm[]=onepagehtm('link','addlink',1,$htmpack);
	}
	//module 10 
	if($met_member_use and count($met_module[10])){
	$methtm[]=onepagehtm('member','login',1,$htmpack);
	$methtm[]=onepagehtm('member','register',1,$htmpack);
	}
	//module 12 
	if(count($met_module[12])){
		$methtmx.=onepagehtm('sitemap','sitemap',1,$htmpack);
	}
	/*分页*/
	$htmlist=explode('$|$',$methtmx);
	foreach($htmlist as $key=>$valx1){
		if($valx1!='')$methtm[]=$valx1;
	}
	/*分页*/
	echo json_encode($methtm);
	die();
}elseif($action=='htmzip'){
	include "../include/pclzip.lib.php";
	if(!file_exists('../databack/'))@mkdir ('../databack/', 0777);  
	$sqlzip='../databack/metinfo_htmpack_'.date('YmdHis',time()).'.zip';
	$zipfile='../databack/htmpack/';
	$archive = new PclZip($sqlzip);
	$zip_list = $archive->create($zipfile,PCLZIP_OPT_REMOVE_PATH,$zipfile);
	if($zip_list==0){
		die("Error : ".$archive->errorInfo(true));
	}
	deldir($zipfile);
	$fnames = 'metinfo_htmpack_'.date('YmdHis',time());
	header("Content-type:application/zip;");
	header("Content-Disposition:attachment;filename=$fnames.zip;");
	readfile("$sqlzip");
	@file_unlink("$sqlzip");
}else{
	$methtmx='';
	if($index=="index"){
		$methtm[]=indexhtm(1);
	}
	if($module==1){
		$folder=$met_class[$class1];
		if($met_class[$class1][isshow])$methtm[]=showhtm($class1,1);
		if($met_class[$class1]['releclass']){
			foreach($met_class3[$class1] as $key=>$val1){
				if($val1[isshow])$methtm[]=showhtm($val1[id],1);
			}
		}
		else{
			foreach($met_class22[$class1] as $key=>$val){
				if($val[isshow])$methtm[]=showhtm($val[id],1);
				foreach($met_class3[$val[id]] as $key=>$val1){
					if($val1[isshow])$methtm[]=showhtm($val1[id],1);
				}
			}
		}
	}
	if($module>=2 && $module<=5){
	    if($listall=="all"){
			if($met_class[$class1]['releclass']){
				$methtmx.=classhtm($class1,0,0,1).'$|$';
				foreach($met_class3[$class1] as $key=>$val3){
					$methtmx.=classhtm($class1,$val3[id],0,1,2).'$|$';
				}
			}else{
				$methtmx.=classhtm($class1,0,0,1).'$|$';
				foreach($met_class22[$class1] as $key=>$val){
					$methtmx.=classhtm($class1,$val[id],0,1,2).'$|$';
					foreach($met_class3[$val[id]] as $key=>$val3){
						$methtmx.=classhtm($class1,$val[id],$val3[id],1,3).'$|$';
					}
				}
			}
	    }else{
			switch($module){
			case 2:
				$tablename=$met_news;
				$filename='shownews';
				break;
			case 3:
				$tablename=$met_product;
				$filename='showproduct';
				break;
			case 4:
				$tablename=$met_download;
				$filename='showdownload';
				break;
			case 5:
				$tablename=$met_img;
				$filename='showimg';
				break;
			}
			$query="select * from $tablename where class1='$class1' and lang='$lang' and (recycle='0' or recycle='-1')";
			$result= $db->query($query);
			while($list = $db->fetch_array($result)){
				$methtm[]=contenthtm($class1,$list[id],$filename,$list[filename],1,$met_class[$class1][foldername],$list[addtime]);
			}
			$methtm=count($methtm)==0?0:$methtm;
	    }
	}
	if($module==6){
		if($listall=="all"){
			$methtmx.=classhtm($class1,0,0,1).'$|$';
		}else{
			$query="select * from $met_job where lang='$lang'";
			$result= $db->query($query);
			while($list = $db->fetch_array($result)){
				$methtm[]=contenthtm($class1,$list[id],'showjob',$list['filename'],1,'job',$list[addtime]);
			}
			$methtm[]=onepagehtm('job','cv',1);
		}
	}

	if($module==7){
		if($listall=="all"){
			$methtmx.=classhtm($class1,0,0,1).'$|$';
		}else{
			$methtm[]=onepagehtm('message','message',1); 

		}
	}

	if($module==8){
		foreach($met_classindex[8] as $key=>$val){
			if($val['id']==$class1)$methtm[]=onepagehtm($val['foldername'],'index',1,$htmpack,$val['filename'],$class1);
		}
	}

	if($module==9){
		$methtm[]=onepagehtm('link','index',1);
		$methtm[]=onepagehtm('link','addlink',1);
	}

	if($class1=='login'&&$met_member_use!=0){
		$methtm[]=onepagehtm('member','index',1);
		$methtm[]=onepagehtm('member','login',1);
		$methtm[]=onepagehtm('member','register',1);
	}

	if($action=='sitemap'){
		$methtmx.=onepagehtm('sitemap','sitemap',1);
	}
	if($module || $action || $index || $class1=='login'){
		if($methtmx!=''){
			/*分页*/
			$htmlist=explode('$|$',$methtmx);
			foreach($htmlist as $key=>$valx1){
				if($valx1!='')$methtm[]=$valx1;
			}
			/*分页*/
		}
		echo json_encode($methtm);
		die();
	}
	$cs=2;
	$listclass[2]='class="now"';
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('seo/htm');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>