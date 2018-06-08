<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$rurls='../interface/skin_manager.php?anyid='.$anyid.'&cs='.$cs.'&lang='.$lang;
if($action=='openthis'){
	$oldfile      =$depth."../../templates/$met_skin_user/lang/language_$met_index_type.ini";   
	$newfile      =$depth."../../templates/$met_skin_user/lang/language_$lang.ini"; 
	//if(!is_writable($depth."../../templates/".$met_skin_user."/lang/"))@chmod($depth."../../templates/".$met_skin_user."/lang/", 0777); 
	if(!file_exists($newfile)){  
		if($met_index_type==$lang){
			$query="select * from $met_lang where lang!='metinfo' order by id asc";
			$langs=$db->get_all($query);
			$oldfile=$depth."../../templates/$met_skin_user/lang/language_$langs[0][mark].ini";  
		}
		if (!copy($oldfile,   $newfile))metsave('-1',$lang_langcopyfile);
	}
	require_once $depth.'../include/config.php';
	$replace_file=array('member','web','login','register','head');
	foreach($replace_file as $key=>$val){
		$dir=$depth."../../templates/$met_skin_user/$val";
		$dir.=file_exists($depth."../../templates/$met_skin_user/$val.php")?".php":".html";
		if(file_exists($dir)){
			$str=file_get_contents($dir); 
			if($val!=head){
				preg_match('/\<ul[\s\S]*?\$lang_memberIndex3[\s\S]*?\<\/ul\>/',$str,$out1);
				preg_match('/class=\"(.*)\"/',$out1[0],$out2);
				$re2="<!--\nEOT;\n\$met_mermber_metinfo_news_left_class='$out2[1]';\n\$mermber_metinfo_news_left=membernavlist();\necho <<<EOT\n-->\n\$mermber_metinfo_news_left";
				$re1="<!--\nEOT;\ninclude templatemember(\$mfname);\necho <<<EOT\n-->";
				$str=preg_replace('/(\<iframe)([\s\S]*)(\<\/iframe\>)/',$re1,$str);
				$str=preg_replace('/\<ul[\s\S]*?\$lang_memberIndex3[\s\S]*?\<\/ul\>/',$re2,$str);
			}else{
				$str=preg_replace('/(\<script).+?((metinfo-min.js)|(jquery-1.4.2.metinfo.js)|(jQuery1.7.2.js)|(jquery-1.4.2.min.js)).+?(\<\/script\>)/','',$str);
			}
			file_put_contents($dir,$str); 
		}
	}
	die;
}elseif($action=="add"){
	if($skin_name=='')metsave('-1',$lang_js8);
	if($skin_file=='')metsave('-1',$lang_js9);
	$skin_m=$db->get_one("SELECT * FROM $met_skin_table WHERE skin_file='$skin_file'");
	if($skin_m)metsave('-1',$lang_temexists1);
	$query="insert into {$met_skin_table} set
		skin_name='{$skin_name}',
		skin_file='{$skin_file}',
		skin_info='{$skin_info}'";
	$db->query($query);
	if($met_skin_user){
		$met_skin_user=$skin_file;
		require_once $depth.'../include/config.php';
	}
	$replace_file=array('member','web','login','register','head');
	foreach($replace_file as $key=>$val){
		$dir=$depth."../../templates/$skin_file/$val";
		$dir.=file_exists($depth."../../templates/$skin_file/$val.php")?".php":".html";
		if(file_exists($dir)){
			$str=file_get_contents($dir); 
			if($val!='head'){
				preg_match('/\<ul[\s\S]*?\$lang_memberIndex3[\s\S]*?\<\/ul\>/',$str,$out1);
				preg_match('/class=\"(.*?)\"/',$out1[0],$out2);
				$re2="<!--\nEOT;\n\$met_mermber_metinfo_news_left_class='$out2[1]';\n\$mermber_metinfo_news_left=membernavlist();\necho <<<EOT\n-->\n\$mermber_metinfo_news_left";
				$re1="<!--\nEOT;\ninclude templatemember(\$mfname);\necho <<<EOT\n-->";
				$str=preg_replace('/(\<iframe)([\s\S]*)(\<\/iframe\>)/',$re1,$str);
				$str=preg_replace('/\<ul[\s\S]*?\$lang_memberIndex3[\s\S]*?\<\/ul\>/',$re2,$str);
			}else{
				$str=preg_replace('/(\<script).+?((metinfo-min.js)|(jquery-1.4.2.metinfo.js)|(jQuery1.7.2.js)).+?(\<\/script\>)/','',$str);
			}
			file_put_contents($dir,$str);			
		}
	}
	metsave($rurls);
}elseif($action=="editor"){
	$allidlist=explode(',',$allid);	
	$k=count($allidlist)-1;
	for($i=0;$i<$k;$i++){
		$skin_m=$db->get_one("SELECT * FROM $met_skin_table WHERE id='$allidlist[$i]'");
		if(!$skin_m)metsave('-1',$lang_dataerror);
		if($skin_name[$allidlist[$i]]=='')metsave('-1',$lang_js8);
		if($skin_file[$allidlist[$i]]=='')metsave('-1',$lang_js9);
		$query="update $met_skin_table set
			skin_name='{$skin_name[$allidlist[$i]]}',
			skin_file='{$skin_file[$allidlist[$i]]}',
			skin_info='{$skin_info[$allidlist[$i]]}'
			where id='$allidlist[$i]'";
		$db->query($query);
	}
	metsave($rurls);
}elseif($action=="delete"){
	if($action_type=="del"){
		$allidlist=explode(',',$allid);
		foreach($allidlist as $key=>$val){
			$query = "delete from $met_skin_table where id='$val'";
			$db->query($query);
		}
		metsave($rurls);
	}else{
		$skin_m=$db->get_one("SELECT * FROM $met_skin_table WHERE id='$id'");
		if(!$skin_m)metsave('-1',$lang_dataerror);
		if($skin_m[skin_file]==$met_skin_user)metsave('-1',$lang_temexists2);
		$query="delete from $met_skin_table where id='$id'";
		$db->query($query);
		$filedir="../../templates/".$skin_m[skin_file];
		deldir($filedir);
		metsave($rurls);
	}
}else{	
    $total_count = $db->counter($met_skin_table, "", "*");
    require_once 'include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = 20;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
    $query = "SELECT * FROM $met_skin_table order BY id LIMIT $from_record, $list_num";
    $result = $db->query($query);
	while($list = $db->fetch_array($result)){
		$cssfile="../../templates/".$list[skin_file]."/images/css/css.inc.php";
		if(file_exists($cssfile)){
			require_once $cssfile;
			$list[cssname]=$cssnum; 
		}else{
			$list[cssname][0]=array($lang_setskinDefault,'metinfo.css');
		}
		$skin_list[]=$list;
		$cssnumnow[$list['id']]=$list['cssname'];
		unset($cssnum);
    }
	$page_list = $rowset->link("skin_manager.php?anyid=".$anyid."&lang=".$lang."&page=");
	$scriptcss = "<script language = 'JavaScript'>\n";
	$scriptcss .= "var onecount;\n";
	$scriptcss .= "subcat = new Array();\n";
	$i=0;
	foreach($skin_list as $key=>$val){
		for($j=0; $j<count($val[cssname]); $j++){
			$scriptcss .= "subcat[".$i."] = new Array('".$val[skin_file]."','".$val[cssname][$j][0]."','".$val[cssname][$j][1]."');\n";
			$i++;
		}
	}
	$skin_list_temp=$skin_list;
	$skin_list='';
	foreach($skin_list_temp as $key=>$val){
		if($met_skin_user==$val[skin_file]){
			$skin_list[]=$val;
		}
	}
	foreach($skin_list_temp as $key=>$val){
		if($met_skin_user!=$val[skin_file]){
			$skin_list[]=$val;
		}
	}
	$scriptcss .= "onecount=".$i.";\n";
	$scriptcss .= "</script>";
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('interface/skin');footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>