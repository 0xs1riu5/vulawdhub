<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
/*子级栏目*/
function listjs($module){
	global $db,$met_column,$lang,$lang_modClass2,$lang_modClass3,$metinfo_admin_pop;
	$query = "SELECT * FROM $met_column where module='$module' and lang='$lang'";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {
		$clist[]=$list;
	}
	$i=0;
	$listjs = "<script language = 'JavaScript'>\n";
	$listjs.= "var onecount;\n";
	$listjs.= "lev = new Array();\n";
	foreach($clist as $key=>$vallist){
		$admin_column_power="admin_pop".$vallist[id];
		global $$admin_column_power;
		if(!($metinfo_admin_pop=='metinfo'||$$admin_column_power=='metinfo')&&($vallist[classtype]==1||$vallist['releclass']))continue;
		$vallist[name]=str_replace("'","\\'",$vallist[name]);
		if($vallist['releclass']){
			$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','0','".$vallist[id]."','".$vallist[access]."');\n";
			$i=$i+1;
		}
		else{
				$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".$vallist[access]."');\n";
				$i=$i+1;
		}
	}
	$j=$i;
	$listjs.= "lev[".$j."] = new Array('".$lang_modClass2."','0','','0');\n";
	$j++;
	$listjs.= "lev[".$j."] = new Array('".$lang_modClass3."','0','','0');\n";
	$j++;
	$listjs.= "lev[".$j."] = new Array('----------','0','0','-1');\n";
	$listjs.= "onecount=".$i.";\n";
	$listjs.= "</script>";
	return $listjs;
}
/*para参数处理*/
function para_list_with($mod_list){
	global $db,$lang_modnull,$lang_imagename,$met_class,$class1,$met_list,$met_parameter,$lang;
	$query = "select * from $met_parameter where lang='$lang' and module='".$met_class[$class1]['module']."' and (class1=$class1 or class1=0) order by no_order";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
		if($list[type]==2 or $list[type]==4 or $list[type]==6){
			$query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
			$result1 = $db->query($query1);
			while($list1 = $db->fetch_array($result1)){
				$paravalue[$list[id]][]=$list1;
			}
		}
		$para_list[]=$list;
	}

	foreach($para_list as $key=>$val){
		$mrok='';
		$para='para'.$val[id];
		switch($val['type']){
			case 1:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont']="<input name='para{$val[id]}' type='text' class='text {$mrok}' value='{$mod_list[$para]}'>";
			break;
			case 2:
				if($val['wr_ok']){
					$mrok='class="noselect"';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont'] ="<select name='para{$val[id]}' {$mrok}>";
				$val['inputcont'].="<option value=''>{$lang_modnull}</option>";
				foreach($paravalue[$val[id]] as $key=>$val1){
					$selected='';
					if($mod_list[$para]==$val1['info']) $selected="selected=selected";
					$val['inputcont'].="<option value='{$val1[info]}' {$selected}>{$val1[info]}</option>";
				}
				$val['inputcont'].="</select>";
			break;
			case 3:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont'] ="<textarea name='para{$val[id]}' class='textarea gen {$mrok}' cols='60' rows='5'>{$mod_list[$para]}</textarea>";
			break;
			case 4:
				$val['inputcont']='';
				$i=0;
				$nowinfo="-".$mod_list[$para]."-";
				foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;$checked='';
					if(strstr($nowinfo, "-".$val1['info']."-"))$checked='checked';
					$val['inputcont'].="
						<label>
							<input name='para{$val[id]}_{$i}' type='checkbox' class='checkbox' value='{$val1[info]}' {$checked} />{$val1[info]}
						</label>&nbsp;&nbsp;&nbsp;
					";
				}
			break;
			case 5:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$paraname=$para.'name';
				$val['inputcont']="
					<div style='height:30px;'>
						<input name='para{$val[id]}name' type='text' class='text med' value='{$mod_list[$paraname]}'>
						<span class='tips'>{$lang_imagename}</span>
					</div>
					<input name='para{$val[id]}' type='text' class='text {$mrok}' value='{$mod_list[$para]}' />
					<input name='met_upsql_{$val[id]}' type='file' id='mod_upload_{$val[id]}' />
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#mod_upload_{$val[id]}','upfile','para{$val[id]}');
					});
					</script>
				";
			break;
			case 6:
				$val['inputcont']='';
				$i=0;
				foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;$checked='';
					if($action=="add" && $i==1)$checked='checked';
					if($mod_list[$para]==$val2['info'])$checked='checked';
					$val['inputcont'].="
						<label>
						<input name='para{$val[id]}' type='radio' class='radio' value='{$val2[info]}' {$checked} />{$val2[info]}
						</label>&nbsp;&nbsp;&nbsp;
					";
				}
			break;
		}
		$para_lists[] = $val;
	}
	return $para_lists;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>