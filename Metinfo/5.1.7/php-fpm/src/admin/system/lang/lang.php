<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once 'lang.func.php';
if($action=="modify"){
	$lancount=count($met_langok);
	$thisurl = 'lang.php?lang='.$lang;
	if($langmark=='metinfo')metsave('-1',$lang_langadderr1,$depth);
	$langmark=trim($langmark);
	$langorder=trim($langorder);
	$langoname=trim($langname);
	$langoflag=trim($langflag);
	$langolink=trim($langlink);
	$langlink = ereg_replace(" ","",$langlink);
	if($langlink!=''){
	if(!strstr($langlink,"http://"))$langlink="http://".$langlink;
	}
	switch($langsetaction){
		case 'set':  
			$met_ch_lang=$met_ch_lang1;
			$met_ch_mark=$met_ch_mark1;
			$met_index_type=$met_index_type1;
			$met_admin_type=$met_admin_type1;
			$met_admin_type_ok=$met_admin_type_ok1;
			$met_url_type=$met_url_type1;
			$met_lang_mark=$met_lang_mark1;
			require_once $depth.'../include/config.php';
		break;
		case 'add':
			if($langname=='')metsave('-1',$lang_langnamenull,$depth);
			if($langautor!='')$langmark=$langautor;
			if($langautor!='')$synchronous=$langautor;
			if(!$langdlok)$synchronous='';
			$lancount=count($met_langok);
			$isaddlang=1;
			$met_langok[0]=array(
							'name'		=>$langname,
							'useok'		=>$languseok,
							'order'		=>$langorder,
							'mark'		=>$langmark,
							'flag'		=>$langflag,
							'link'		=>$langlink,
							'newwindows'=>$langnewwindows);
			foreach($met_langok as $key=>$val){
				if($key){
					if($langmark==$val['mark'])metsave('-1',$lang_langnamerepeat,$depth);
					if($val['order'] == $langorder)metsave('-1',$lang_langnameorder,$depth);
				}
			}
			$met_webhtm =$met_langok[$langfile]['met_webhtm'];
			$met_htmtype=$met_langok[$langfile]['met_htmtype'];
			$met_weburl =$met_langok[$langfile]['met_weburl'];
			$query = "INSERT INTO $met_lang SET
				name          = '$langname',
				useok         = '$languseok',
				no_order      = '$langorder',
				mark          = '$langmark',
				synchronous   = '$synchronous',
				flag          = '$langflag',
				link          = '$langlink',
				newwindows    = '$langnewwindows',
				met_webhtm    = '$met_webhtm',
				met_htmtype   = '$met_htmtype',
				met_weburl    = '$met_weburl',
				lang          = '$langmark'
			";
			$db->query($query);
			$query="INSERT INTO $met_admin_array set array_name='$lang_access1',admin_type='',admin_ok='0',admin_op='',admin_issueok='0',admin_group='0',user_webpower='1',array_type='1',lang='$langmark',langok=''";
			$db->query($query);
			$query="INSERT INTO $met_admin_array set array_name='$lang_access2',admin_type='',admin_ok='0',admin_op='',admin_issueok='0',admin_group='0',user_webpower='2',array_type='1',lang='$langmark',langok=''";
			$db->query($query);
			$re=copyconfig();
			if($re!=1){
				metsave('../system/lang/lang.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$lang_langadderr4.dlerror($re),$depth,'','',$prent);
				die();
			}
		break;
		case 'edit':
			if($langname=='')metsave('-1',$lang_langnamenull,$depth);
			$met_langok[$langmark]=array(
									'name'	=>$langname,
									'useok'	=>$languseok,
									'order'	=>$langorder,
									'mark'	=>$langmark,
									'flag'	=>$langflag,
									'link'	=>$langlink,
									'newwindows'=>$langnewwindows);
			$i=0;
			$useoknow=0;
			foreach($met_langok as $key=>$val){
				$i++;
				if($val['mark']!=$langmark && $val['order'] == $langorder)metsave('-1',$lang_langnameorder,$depth);
				if($val['useok']==1)$useoknow++;
			}
			if($useoknow==0&&$languseok==0)metsave('-1',$lang_langclose1,$depth);
			if($met_index_type==$langmark&&$languseok==0)metsave('-1',$lang_langclose2,$depth);
			$query = "update $met_lang SET
				name          = '$langname',
				useok         = '$languseok',
				no_order      = '$langorder',
				mark          = '$langmark',
				synchronous   = '$synchronous',
				flag          = '$langflag',
				link          = '$langlink',
				newwindows    = '$langnewwindows'
			    where lang='$langmark'";
			$db->query($query);
			if($synchronous!=$synchronous1&&$synchronous){
				$post=array('newlangmark'=>$synchronous,'metcms_v'=>$metcms_v);
				$file_basicname=$depth.'../update/lang/lang_'.$synchronous.'.ini';
				$re=syn_lang($post,$file_basicname,$langmark,0,0);
				unlink('../../../cache/lang_'.$langmark.'.php');
				if($re==1){
					metsave('../system/lang/lang.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$lang_success,$depth);
				}else{
					metsave('../system/lang/lang.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$lang_langadderr4.dlerror($re),$depth);
				}
				die();
			}
		break;
		case 'delete':
			if(count($met_langok)==1)metsave('-1',$lang_langone,$depth);
			if($langeditor==$lang)metsave('-1',$lang_langadderr2,$depth);
			$query = "delete from $met_language where site='0' and lang='$langeditor'";
			$db->query($query);
			$query = "delete from $met_config where lang='$langeditor'";
			$db->query($query);
			if(file_exists($depth."../../templates/".$met_skin_user."/lang/language_".$langeditor.".ini"))@unlink($depth."../../templates/".$met_skin_user."/lang/language_".$langeditor.".ini");
			$query = "select * from $met_column where lang='$langeditor'";
			$result = $db->query($query);
			while($list = $db->fetch_array($result)){
				delcolumn($list);
			}
			$query = "delete from $met_lang where lang='$langeditor'";
			$result = $db->query($query);
			$query = "delete from $met_admin_array where lang='$langeditor'";
			$db->query($query);
			$query = "delete from $met_admin_table where lang='$langeditor'";
			$db->query($query);
		break;
		case 'addadmin':
			if($langname=="")metsave('-1',$lang_langnamenull,$depth);
			$met_langadmin[0]=array(
							'name'	=>$langname,
							'useok'	=>$languseok,
							'order'	=>$langorder,
							'mark'	=>$langmark);
			foreach($met_langadmin as $key=>$val){
				if($key){
					if($langmark==$val['mark'])metsave('-1',$lang_langnamerepeat,$depth);
					if($val['order'] == $langorder)metsave('-1',$lang_langnameorder,$depth);
				}
			}
			$query = "INSERT INTO $met_lang SET
				name          = '$langname',
				useok         = '$languseok',
				no_order      = '$langorder',
				mark          = '$langmark',
				lang          = 'metinfo'
			";
			$db->query($query);
			$query="select * from $met_language where site='1' and lang='$langfile'";
			$languages=$db->get_all($query);
			foreach($languages as $key=>$val){
				$val[value] = str_replace("'","''",$val[value]);
				$val[value] = str_replace("\\","\\\\",$val[value]);
				$query = "insert into $met_language set name='$val[name]',value='$val[value]',site='1',no_order='$val[no_order]',array='$val[array]',lang='$langmark'";
				$db->query($query);
			}
		break;
		case 'editadmin':
			if($langname=="")metsave('-1',$lang_langnamenull,$depth);
			$met_langadmin[$langmark]=array('name'=>$langname,'useok'=>$languseok,'order'=>$langorder,'mark'=>$langmark);
			$i=0;
			foreach($met_langadmin as $key=>$val){
			$i++;
				if($val['mark']!=$langmark && $val['order'] == $langorder)metsave('-1',$lang_langnameorder,$depth);
			}
			$query = "update $met_lang SET
				name          = '$langname',
				useok         = '$languseok',
				no_order      = '$langorder',
				mark          = '$langmark'
			    where lang='metinfo' and mark='$langmark'";
			$db->query($query);
		break;
		case 'deleteadmin':
			if(count($met_langadmin)==1)metsave('-1',$lang_langone,$depth);
			$query = "delete from $met_language where site='1' and lang='$langeditor'";
			$result = $db->query($query);
			$query = "delete from $met_lang where lang='metinfo' and mark='$langeditor'";
			$result = $db->query($query);
		break;
	}
	unlink('../../../cache/lang_'.$langeditor.'.php');
	unlink('../../../cache/lang_'.$langmark.'.php');
	$prent=$langsetaction=='add'&&$lancount==1?2:'';
	$txt=$isaddlang?$lang_langadderr3:'';
	metsave('../system/lang/lang.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$txt,$depth,'','',$prent);
}elseif($action=='flag'){
    $dir = $depth.'../../public/images/flag';
    $handle = opendir($dir);
    while(false !== $file=(readdir($handle))){
        if($file !== '.' && $file != '..'){
		    $flags[] = $file;
		}
	}
    closedir($handle);
	$k=count($flags);
	for($i=0;$i<$k;$i++){
	    $data.='<img src="'.$dir.'/'.$flags[$i].'" />';
	}
    echo $data;
}elseif($action=='syn'){
	$post=array('newlangmark'=>$syn,'metcms_v'=>$metcms_v);
	$file_basicname=$depth.'../update/lang/lang_'.$syn.'.ini';
	$re=syn_lang($post,$file_basicname,$nowmark,0,0);
	unlink('../../../cache/lang_'.$syn.'.php');
	if($re==1){
		metsave('../system/lang/lang.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$lang_success,$depth);
	}else{
		metsave('../system/lang/lang.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,$lang_langadderr4.dlerror($re),$depth);
	}
}else{
	if($met_ch_lang==1)$met_ch_lang1="checked";
	if($met_ch_lang==0)$met_ch_lang2="checked";
	if($met_admin_type_ok==1)$met_admin_type_yes="checked";
	if($met_admin_type_ok==0)$met_admin_type_no="checked";
	if($met_url_type==1)$met_url_type_yes="checked";
	if($met_url_type==0)$met_url_type_no="checked";
	if($met_lang_mark==1)$met_lang_mark_yes="checked";
	if($met_lang_mark==0)$met_lang_mark_no="checked";
	foreach($met_langok as $key=>$val){
		if($val[link]==''){
		$met_indextype.="<label><input name='met_index_type1' type='radio' class='radio' value='".$val[mark]."'";
		if($met_index_type==$val[mark])$met_indextype.=" checked ";
		$met_indextype.="/>".$val[name]."</label>&nbsp;&nbsp;"; 
		}
	}
	foreach($met_langadmin as $key=>$val){
		$met_admintype.="<label><input name='met_admin_type1' id='met_admin_type1_$i' type='radio' class='radio' value='".$val[mark]."'";
		if($met_admin_type==$val[mark])$met_admintype.=" checked ";
		$met_admintype.=">".$val[name]."</label>&nbsp;&nbsp;";
	}
	$cs=isset($cs)?$cs:3;
	$listclass[$cs]='class="now"';
	if($cs==3&&$langadminok!="metinfo"){
		header('location:lang.php?lang='.$lang.'&anyid='.$anyid.'&cs=1');
	}
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('system/lang/lang');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>