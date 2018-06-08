<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once 'global.func.php';
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		$admin_list = $db->get_one("SELECT * FROM $met_column WHERE id='$val'");
		if($admin_list){
			$query1 = "select * from $met_column where bigclass='$admin_list[id]'";
			$result1 = $db->query($query1);
			while($list1= $db->fetch_array($result1)){
				if($list1['releclass']||$list1['classtype']==3){
					delcolumn($list1);
				}else{
					$query2 = "select * from $met_column where bigclass='$list1[id]'";
					$result2 = $db->query($query2);
					while($list2= $db->fetch_array($result2)){
						delcolumn($list2);
					}
					delcolumn($list1);
				}
			}
			delcolumn($admin_list);
		}
	}
	file_unlink("../../cache/column_$lang.inc.php");
	metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);
}elseif($action=="editor"){
	$tesumods[6] = 'job';
	$tesumods[7] = 'message';
	$tesumods[9] = 'link';
	$tesumods[10] = 'member';
	$tesumods[11] = 'search';
	$tesumods[12] = 'sitemap';
	$tesumods[100] = 'product';
	$tesumods[101] = 'img';
	$allidlist=explode(',',$allid);
	$adnum = count($allidlist)-1;
	$metinfo='';
	for($i=0;$i<$adnum;$i++){
		/*获取参数*/
		$name        = 'name_'.$allidlist[$i];        $name        = $$name;
		$no_order    = 'no_order_'.$allidlist[$i];    $no_order    = $$no_order;
		$bigclass    = 'bigclass_'.$allidlist[$i];    $bigclass    = $$bigclass;		
		$nav         = 'nav_'.$allidlist[$i];         $nav         = $$nav;		
		$foldername  = 'foldername_'.$allidlist[$i];  $foldername  = $$foldername;
		$module      = 'module_'.$allidlist[$i];  	  $module      = $$module;
		$out_url     = 'out_url_'.$allidlist[$i];     $out_url     = $$out_url;
		$if_in       = 'if_in_'.$allidlist[$i];       $if_in       = $$if_in;
		if(!$if_in)$if_in   = $module==999?1:0;
		$index_num   = 'index_num_'.$allidlist[$i];   $index_num   = $$index_num;
		$classtype   = 'classtype_'.$allidlist[$i];   $classtype   = $$classtype;
		//$access      = 'access_'.$allidlist[$i];      $access      = $$access;
		$access=0;
		
		$foldername=metdetrim($foldername);
		$ertxt = $name.'|';/*错误提示前缀*/
		$releclass=0;
		$releok=0;
		$tpif = is_numeric($allidlist[$i])?1:0;
		$sql = $tpif?"id='$allidlist[$i]'":'';
		if($sql!=''){
			$skin_m=$db->get_one("SELECT * FROM $met_column WHERE $sql");
			$bigclass    = $skin_m['bigclass'];		
			$foldername  = $skin_m['foldername'];		
			$module      = $skin_m['module'];		
			$out_url     = $skin_m['out_url'];		
			$if_in       = $skin_m['if_in'];		
			$classtype   = $skin_m['classtype'];		
			$access      = $skin_m['access'];	
			$foldername=metdetrim($foldername);
		}	
		$releclassok=$db->get_one("SELECT * FROM $met_column WHERE id='$bigclass'");
		if($classtype==2){
			if($skin_m['releclass']||$module!=$releclassok['module']){
				$releclass=$bigclass;
				if(($module>0 && $module<6) || $module==8)$releok=1;
			}else{
				$foldername=$releclassok['foldername'];
			}
		}
		if($classtype==3)$foldername=$releclassok['foldername'];
		if($module==999)$module=0;
		if(!$if_in)$if_in=0;
		if($if_in==1 && $out_url=="")$metinfo.=$ertxt.'out_url_'.$allidlist[$i].'|'.$lang_modOuturl.'$';
		if($module>5 && $module!=8)$foldername = $tesumods[$module];

		if($if_in==0){
			$out_url='';
			if($tpif){
				if(!$skin_m){$metinfo.=$ertxt.'|'.$lang_dataerror.'$';}
				$id = $allidlist[$i];
				if($met_member_use)require_once 'check.php';
				if($filename!=''){
					$filenameok = $db->get_one("SELECT * FROM $met_column WHERE filename='$filename'");
					if($filenameok)$metinfo.=$ertxt.'|'.$lang_modFilenameok.'$';
				}
			}else{
				if($foldername=="")$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$lang_modFoldername.'$';
				if(!preg_match('/^[a-z0-9_-]+$/i',$foldername)){
					$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$lang_columnerr1.'$';
				}else{
					if($bigclass==0 || $releclass){
						for($s=0;$s<$adnum;$s++){
							$foldernamess= 'foldername_'.$allidlist[$s];
							$foldernamess= $$foldernamess;
							$modules     = 'module_'.$allidlist[$s];
							$modules     = $$modules;
							$names       = 'name_'.$allidlist[$s];
							$names       = $$names;
							if($modules>5 && $modules!=8)$foldernamess = $tesumods[$modules]; 
							if((($modules<100 && $module<100) && $modules != $module && $foldername==$foldernamess) && ($allidlist[$s] != $allidlist[$i])){
								$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$names.$lang_columnerr2.'$';
								break;
							}
							if(($modules == $module && $foldername==$foldernamess) && ($allidlist[$s] != $allidlist[$i])){
								if($modules>5 && $module!=8){
									$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$lang_columnerr3.module($module).'$';
								}else{
									$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$names.$lang_columnerr2.'$';
								}
								break;
							}
						}
					}
					if($module=="")$metinfo.=$ertxt.'module_'.$allidlist[$i].'|'.$lang_modModule.'$';
					$filedir="../../".$foldername;
					if($module>5 && $module!=8){
						$modulewy = $db->get_one("SELECT * FROM $met_column WHERE module='$module' and lang='$lang'");
						if($modulewy['id'])$metinfo.=$ertxt.'module_'.$allidlist[$i].'|'.$lang_modmodulewyok.'$';
					}
					if($bigclass==0 && (($module>0 && $module<6) || $module==8))$releok=1;
					if($releok){
						$folder_m=$db->get_one("SELECT * FROM $met_column WHERE foldername='$foldername' and lang='$lang'");
						if($folder_m){
							if($module<13 && file_exists($filedir))$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$lang_columnerr4.'$';
						}elseif(file_exists($filedir)){
							$folder_m=1;
						}
						$folder_ms=$db->get_one("SELECT * FROM $met_column WHERE foldername='$foldername' and lang!='$lang'");
						if($folder_ms){
							if($folder_ms['module']!=$module && $module<13)$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$lang_columnerr4.'$';
						}elseif($folder_m && morenfod($foldername,$module)){
							$metinfo.=$ertxt.'foldername_'.$allidlist[$i].'|'.$lang_columnerr4.'$';
						}
					}
				}
			}
		}
		if($metinfo==''){
			$column[$i]['id']        = $allidlist[$i];
			$column[$i]['name']      = $name;
			$column[$i]['out_url']   = $out_url;
			$column[$i]['no_order']  = $no_order;
			$column[$i]['bigclass']  = $bigclass;
			$column[$i]['nav']       = $nav;
			$column[$i]['foldername']= $foldername;
			$column[$i]['module']    = $module;
			$column[$i]['index_num'] = $index_num;
			$column[$i]['classtype'] = $classtype;
			$column[$i]['access']    = $access;
			$column[$i]['if_in']     = $if_in;
			$column[$i]['releclass'] = $releclass;
			$column[$i]['releok']    = $releok;
			$column[$i]['folder_m']  = $folder_m;
			$column[$i]['tpif']      = $tpif;
		}
	}
	//if($metinfo!='')metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_loginFail);
	if($metinfo!=''){
		echo $metinfo;
		die();
	}
	$metinfo_admin_pop1='';
	foreach($column as $key=>$val){
		if($if_in==0){
			$filedir="../../".$val['foldername'];
			if(!file_exists($filedir))@mkdir($filedir, 0777); 		
			if(!file_exists($filedir))metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_modFiledir);
		}
		$uptp = $val['tpif']?"update":"insert into";
		$upbp = $val['tpif']?"where id='$val[id]'":"";
		$query="$uptp $met_column set
				name               = '$val[name]',
				out_url            = '$val[out_url]',
				no_order           = '$val[no_order]',
				bigclass           = '$val[bigclass]',
				nav                = '$val[nav]',
				if_in              = '$val[if_in]',
				foldername         = '$val[foldername]',
				module             = '$val[module]',
				index_num          = '$val[index_num]',					  
				classtype          = '$val[classtype]',					  
				releclass          = '$val[releclass]',					  
				access      	   = '$val[access]',
				lang			   = '$lang'
			$upbp";
		$db->query($query);
		$upid=$val['tpif']?$val[id]:mysql_insert_id();
		if(($val['classtype']==1 || $val['releclass']) && !$val['tpif'])$metinfo_admin_pop1.=$upid.'-';
		column_copyconfig($val['foldername'],$val['module'],$upid);
	}
	if($metinfo_admin_pop1!=''){
		if($metinfo_admin_pop!="metinfo"){
			$metinfo_admin_pop1=$metinfo_admin_pop.$metinfo_admin_pop1;
			$metinfo_admin_pop1=metdetrim($metinfo_admin_pop1);
			$query = "update $met_admin_table SET admin_type = '$metinfo_admin_pop1' where id='$admin_list[id]'";
			$db->query($query);
		}
		$admin_list = $db->get_all("SELECT * FROM $met_admin_table where usertype = 3 && admin_type!='metinfo' &&  admin_type like '%9999%' and id!='$admin_list[id]'");
		foreach($admin_list as $key=>$val){
			$val['admin_type1']=$val['admin_type'].$metinfo_admin_pop1;
			$val['admin_type1']=metdetrim($val['admin_type1']);
			$query = "update $met_admin_table SET admin_type = '$val[admin_type1]' where id='$val[id]'";
			$db->query($query);
		}
	}
	file_unlink("../../cache/column_$lang.inc.php");
	echo 0;
}
elseif($action=="editorok"){
metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);
}else{
	$admin_list = $db->get_one("SELECT * FROM {$met_column} WHERE id='$id'");
	if(!$admin_list)metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_dataerror);
	$query1 = "select * from $met_column where bigclass='$admin_list[id]'";
	$result1 = $db->query($query1);
	while($list1= $db->fetch_array($result1)){
		if($list1['releclass']||$list1['classtype']==3){
			delcolumn($list1);
		}
		else{
			$query2 = "select * from $met_column where bigclass='$list1[id]'";
			$result2 = $db->query($query2);
			while($list2= $db->fetch_array($result2)){
				delcolumn($list2);
			}
			delcolumn($list1);
		}
	}
	delcolumn($admin_list);
	file_unlink("../../cache/column_$lang.inc.php");
	metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
