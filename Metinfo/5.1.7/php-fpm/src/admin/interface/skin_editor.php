<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
if($cs==2){
	$file_name="../../templates/{$skin_file}/lang/language_{$lang}.ini";
	$fp = @fopen($file_name,'r') or die("{$lang_fileerr1} {$file_name}");
	$i=0;
	$j=0;
	while ($conf_line = @fgets($fp, 1024)){
		if($i<4 && substr($conf_line,0,1)=="#"){
			$i++;  
			$linetop=$linetop.$conf_line;
			$lineno = ereg_replace("#.*$", "", $conf_line);
			$line="";
		}else{
			$line=$conf_line;
		}
		if(trim($line) == "") continue;
		if(substr($line,0,1)=="#"){
			$langarray[$j]=substr($line,1);
			$j++;
		}else{
			$linearray=explode ('=', $line);
			$linenum=count($linearray);
			if($linenum==2){
				list($name, $value) = explode ('=', $line);
			}else{
				for($n=0;$n<$linenum;$n++){
					$linetra=$n?$linetra."=".$linearray[$n]:$linearray[$n].'metinfo_';
				}
				list($name, $value) = explode ('metinfo_=', $linetra);
			}
			$value=str_replace("\"","&quot;",$value);
			list($value, $valueinfo)=explode ('/*', $value);
			list($valueinfo)=explode ('*/', $valueinfo);
			$name = daddslashes(trim($name),1,'metinfo');
			$langtext[]=array('name'=>$name,'value'=>$value,'valueinfo'=>$valueinfo);
		}
	}
}
if($action=='modify'){
	if($cs==1){
		$query="update {$met_skin_table} set
				skin_name='{$skin_name}',
				skin_info='{$skin_info}'
				where id='{$id}'";
		$db->query($query);
	}elseif($cs==2){
		$config_save="";
		$config_save=$config_save."#".$langarray[$m]."\n";
		$config_list='';
		foreach($langtext as $key=>$val){
			$namelist=$val[name]."_metinfo";
			$namemetinfo=$$namelist;
			if($namemetinfo!="")$namemetinfo=stripslashes($namemetinfo);
			$val[value]=($namemetinfo==""&&$val[valueinfo]=="")?$val[value]:$namemetinfo;
			$nameinfolist=$val[name]."_info_metinfo";
			$nameinfometinfo=$$nameinfolist;
			if($nameinfometinfo!="")$nameinfometinfo=stripslashes($nameinfometinfo);
			$val[valueinfo]=($nameinfometinfo=="")?$val[valueinfo]:$nameinfometinfo;
			$val[valueinfo]=($val[valueinfo]=="")?"":"/*".$val[valueinfo]."*/"."\n";
			if($val[valueinfo]=="" and $nameinfometinfo=="" and $namemetinfo!="")$val[valueinfo]="\n";
			$config_list.=$val[name]."=".$val[value].$val[valueinfo];
		}
		$config_save=$config_save.$config_list."\n";
		$config_save=$linetop."\n".$config_save;
		$fp = fopen($file_name,w);
		fputs($fp, $config_save);
		fclose($fp);
	}
	metsave('../interface/skin_editor.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&skin_file='.$skin_file.'&id='.$id);
}elseif($cs==1){
	$skin_list=$db->get_one("select * from $met_skin_table where id='{$id}'");
}
$langtextx=array();
foreach($langtext as $key=>$val){
	if(strstr($val[valueinfo],'$TYPE$')){
		$metcmsx=explode('$TYPE$',$val[valueinfo]);
		$mtypex=explode('$R$',$metcmsx[1]);
		$mtype=$mtypex[0];
		$val[valueinfo]=$metcmsx[0];
		$val[inputhtm]='';
		switch($mtype){
			case 1:
				$val[inputhtm] ="<input name='{$val[name]}_metinfo' type='text' class='text' value='{$val[value]}' />";
				$val[inputhtm].="<span class='tips'>".skin_desc($val[valueinfo])."</span>";
				if(trim($val[value])=='#MetInfo'){
					$val[inputhtm]="<span style='color:#f00; font-weight:bold; font-size:14px;'>{$val[name]}</span>";
					$val[name]='';
				}else{
					if($val[valueinfo])$val[valueinfo]=skin_desc($val[valueinfo],1).$lang_marks;
				}
			break;
			case 2:
				$val[inputhtm] ="<select name='{$val[name]}_metinfo'>";
				$vlist=explode('$M$',$mtypex[1]);
				foreach($vlist as $key=>$val2){
					$vz=explode('$T$',$val2);
					$select=$val['value']==$vz[1]?'selected':'';
					$val['inputhtm'].="<option value='".$vz[1]."' {$select}>".$vz[0]."</option>";
				}
				$val[inputhtm].="</select>";
				$val[inputhtm].="&nbsp;&nbsp;<span class='tips'>".skin_desc($val[valueinfo])."</span>";
				if($val[valueinfo])$val[valueinfo]=skin_desc($val[valueinfo],1).$lang_marks;
			break;
			case 3:
				$vlist=explode('$M$',$mtypex[1]);
				foreach($vlist as $key=>$val2){
					$val[inputhtm].="<label>";
					$vz=explode('$T$',$val2);
					$select=$val['value']==$vz[1]?'checked':'';
					$val['inputhtm'].="<input value='".$vz[1]."' name='{$val[name]}_metinfo' type='radio' {$select} />&nbsp;".$vz[0];
					$val[inputhtm].="</label>&nbsp;&nbsp;";
				}
				$val[inputhtm].="<span class='tips'>".skin_desc($val[valueinfo])."</span>";
				if($val[valueinfo])$val[valueinfo]=skin_desc($val[valueinfo],1).$lang_marks;
			break;
			case 4:
				$val[inputhtm]="
					<input name='{$val[name]}_metinfo' type='text' class='text' style='float:left;' value='{$val['value']}' />
					<input name='met_upsql_{$val[name]}' type='file' id='mod_upload_{$val[name]}' />
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#mod_upload_{$val[name]}','upimage','{$val[name]}_metinfo');
					});
					</script>
				";
				$val[inputhtm].="<span class='tips'>".skin_desc($val[valueinfo])."</span>";
				if($val[valueinfo])$val[valueinfo]=skin_desc($val[valueinfo],1).$lang_marks;
			break;
			case 5:
				$hngy5=$mtypex[1];
				$val[inputhtm] ="<select name='{$val[name]}_metinfo'>";
				$val['inputhtm'].="<option value=''>".$lang_skinerr3."</option>";
				switch($hngy5){
					case 1:
						foreach($met_class1 as $key=>$val2){
							if($val2[module]<6&&!$val2[if_in]){
							$select=$val['value']==$val2[id].'-cm'?'selected':'';
							$val['inputhtm'].="<option value='".$val2[id]."-cm' {$select} class='c1'>".$val2[name]."</option>";
							}
						}
					break;
					case 2:
						foreach($met_class1 as $key=>$val2){
							if($val2[module]<7&&!$val2[if_in]){
							$select=$val['value']==$val2[id].'-cm'?'selected':'';
							$val['inputhtm'].="<option value='".$val2[id]."-cm' {$select} class='c1'>==".$val2[name]."==</option>";
							foreach($met_class2[$val2['id']] as $key=>$val3){
								if($val3[module]<7&&!$val3[if_in]){
								$select2=$val['value']==$val3[id].'-cm'?'selected':'';
								$val['inputhtm'].="<option value='".$val3[id]."-cm' {$select2} class='c2'>".$val3[name]."</option>";
								foreach($met_class3[$val3['id']] as $key=>$val4){
									$select3=$val['value']==$val4[id].'-cm'?'selected':'';
									$val['inputhtm'].="<option value='".$val4[id]."-cm' {$select3} class='c3'>+".$val4[name]."</option>";
								}
								}
							}
							}
						}
						for($i=2;$i<6;$i++){
							$langmod="lang_mod".$i;
							$langmod1=$$langmod;
							$select=$val['value']==$i.'-md'?'selected':'';
							$val['inputhtm'].="<option value='".$i."-md' {$select} class='c0'>==".$langmod1."==</option>";
						}
					break;
					case 3:
						foreach($met_class1 as $key=>$val2){
							if(($val2[module]==2||$val2[module]==3||$val2[module]==5)&&!$val2[if_in]){
							$select=$val['value']==$val2[id].'-cm'?'selected':'';
							$val['inputhtm'].="<option value='".$val2[id]."-cm' {$select} class='c1'>==".$val2[name]."==</option>";
							foreach($met_class2[$val2['id']] as $key=>$val3){
								if(($val3[module]==2||$val3[module]==3||$val3[module]==5)&&!$val3[if_in]){
								$select2=$val['value']==$val3[id].'-cm'?'selected':'';
								$val['inputhtm'].="<option value='".$val3[id]."-cm' {$select2} class='c2'>".$val3[name]."</option>";
								foreach($met_class3[$val3['id']] as $key=>$val4){
									$select3=$val['value']==$val4[id].'-cm'?'selected':'';
									$val['inputhtm'].="<option value='".$val4[id]."-cm' {$select3} class='c3'>+".$val4[name]."</option>";
								}
								}
							}
							}
						}
						for($i=2;$i<6;$i++){
							if($i!=4){
							$langmod="lang_mod".$i;
							$langmod1=$$langmod;
							$select=$val['value']==$i.'-md'?'selected':'';
							$val['inputhtm'].="<option value='".$i."-md' {$select} class='c0'>==".$langmod1."==</option>";
							}
						}
					break;
					case 4:
						foreach($met_class1 as $key=>$val2){
							$select=$val['value']==$val2[id].'-cm'?'selected':'';
							$val['inputhtm'].="<option value='".$val2[id]."-cm' {$select} class='c1'>==".$val2[name]."==</option>";
							foreach($met_class2[$val2['id']] as $key=>$val3){
								$select2=$val['value']==$val3[id].'-cm'?'selected':'';
								$val['inputhtm'].="<option value='".$val3[id]."-cm' {$select2} class='c2'>".$val3[name]."</option>";
								foreach($met_class3[$val3['id']] as $key=>$val4){
									$select3=$val['value']==$val4[id].'-cm'?'selected':'';
									$val['inputhtm'].="<option value='".$val4[id]."-cm' {$select3} class='c3'>+".$val4[name]."</option>";
								}
							}
						}
					break;
				}
				$val[inputhtm].="</select>&nbsp;&nbsp;";
				$val[inputhtm].="<span class='tips'>".skin_desc($val[valueinfo])."</span>";
				if($val[valueinfo])$val[valueinfo]=skin_desc($val[valueinfo],1).$lang_marks;
			break;
		}
	}else{
		$val[inputhtm] ="<input name='{$val[name]}_metinfo' type='text' class='text' value='{$val[value]}' />";
		$val[inputhtm].="<span class='tips'>".skin_desc($val[valueinfo])."</span>";
		if(trim($val[value])=='#MetInfo'){
			$val[inputhtm]="<span style='color:#f00; font-weight:bold; font-size:14px;'>{$val[name]}</span>";
			$val[name]='';
		}else{
			if($val[valueinfo])$val[valueinfo]=skin_desc($val[valueinfo],1).$lang_marks;
		}
	}
	$langtextx[]=$val;
}
$langtext=$langtextx;
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('interface/skin_editor');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>