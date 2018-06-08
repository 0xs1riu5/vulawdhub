<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$admin_index=FALSE;
require_once '../include/common.inc.php';
if($class1)$selectedjob=$class1;
$classaccess= $db->get_one("SELECT * FROM $met_column WHERE module='6' and lang='$lang' ");
$metaccess=$classaccess[access];
$class1=$classaccess[id];
$job[cv]=$cv[url].$job[id];
require_once '../include/head.php';
$guanlian=$class_list[$class1][releclass];
    $class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
	$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];	
	
$query = "SELECT * FROM $met_parameter where lang='$lang' and module=6  order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=6  and access<=$metinfo_member_type order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==2 or $list[type]==4 or $list[type]==6){
  $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
  $result1 = $db->query($query1);
  while($list1 = $db->fetch_array($result1)){
  $paravalue[$list[id]][]=$list1;
  }}
  
$list[mark]=$list[name];
$list[para]="para".$list[id];
if($list[wr_ok]=='1')
{
	$list[wr_must]="*";
	$fdwr_list[]=$list;
}
$cv_para[]=$list;
}
$fdjs="<script language='javascript'>";
$fdjs=$fdjs."function Checkcv(){ ";
$fdjs=$fdjs."if (document.myform.jobid.value.length == 0) {
alert('{$lang_memberPosition} {$lang_Empty}');
document.myform.jobid.focus();
return false;}";
foreach($fdwr_list as $key=>$val){
if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
$fdjs=$fdjs."if (document.myform.para$val[id].value.length == 0) {\n";
$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
$fdjs=$fdjs."return false;}\n";
}elseif($val[type]==4){
 $lagerinput="";
 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
 }
 $lagerinput=$lagerinput."false\n";
 $fdjs=$fdjs."if(!($lagerinput)){\n";
 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
 $fdjs=$fdjs."return false;}\n";
}
}
$fdjs=$fdjs."}</script>";


	$selectjob = "";
	$serch_sql=" where lang='$lang' ";
	$metinfo_member_type=intval($metinfo_member_type);
	$query = "SELECT id,position FROM $met_job $serch_sql and  access <= $metinfo_member_type and ((TO_DAYS(NOW())-TO_DAYS(`addtime`)< useful_life) OR useful_life=0)";
    
	$result = $db->query($query);
	 while($list= $db->fetch_array($result)){
	 $selectok=$selectedjob==$list[id]?"selected='selected'":"";	 
	 $selectjob.="<option value='$list[id]' $selectok>{$list[position]}</option>";
	 }

$class2=$class_list[$class1][releclass]?$class1:$class2;
$class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
$class_info=$class2?$class2_info:$class1_info;
if($class2!=""){
$class_info[name]=$class2_info[name]."--".$class1_info[name];
}	 
     $show[description]=$met_keywords;
     $show[keywords]=$met_keywords;
	 $met_title=$met_title?$lang_cvtitle.'-'.$met_title:$lang_cvtitle;
if(!$guanlian){
     if(count($nav_list2)){
       $nav_list2[$class1][0]=$class1_info;
       $nav_list2[$class1][1]=array('id'=>10004,'url'=>$cv[url],'name'=>$lang_cvtitle);
      }else{
        $k=count($nav_list2);
        $nav_list2[$class1][$k]=array('id'=>10004,'url'=>$cv[url],'name'=>$lang_cvtitle);
     }
}
     require_once '../public/php/methtml.inc.php';
	 $nav_x[name]=$lang_cvtitle;
		 
     $methtml_cv.="<script type='text/javascript'>function pressCaptcha(obj){obj.value = obj.value.toUpperCase();}</script>\n";
     $methtml_cv.=$fdjs;
     $methtml_cv.="<form  enctype='multipart/form-data' method='POST' onSubmit='return Checkcv();' name='myform' action='save.php?action=add' target='_self'>\n";
     $methtml_cv.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $methtml_cv.="<table cellpadding='2' cellspacing='1' border='0' class='cv_table'>\n";
     $methtml_cv.="<tr class='cv_tr'>\n"; 
     $methtml_cv.="<td class='cv_td1' align='right' width='20%'>".$lang_memberPosition."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_select' align='left' width='70%'><select name='jobid' id='jobid'>".$selectjob."</select></td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>*</td>\n";
     $methtml_cv.="</tr>\n";
    foreach($cv_para as $key=>$val){
     switch($val[type]){
	 case 1:
     $methtml_cv.="<tr class='cv_tr'> \n";
     $methtml_cv.="<td class='cv_td1' align='right'>".$val[name]."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_input' align='left'><input name='".$val[para]."' type='text' class='input' size='40'></td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_cv.="</tr>\n";
	 break;
	 case 2:
	 $tmp="<select name='para$val[id]'>";
     $tmp=$tmp."<option value=''>$lang_Nolimit</option>";
     foreach($paravalue[$val[id]] as $key=>$val1){
      $tmp=$tmp."<option value='$val1[info]' $selected >$val1[info]</option>";
      }
     $tmp=$tmp."</select>";
     $methtml_cv.="<tr class='cv_tr'> \n";
     $methtml_cv.="<td class='cv_td1' align='right'>".$val[name]."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_input' align='left'>".$tmp."</td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_cv.="</tr>\n";
	 break;
	 case 3:
	 $methtml_cv.="<tr class='cv_tr'> \n";
     $methtml_cv.="<td class='cv_td1' align='right'>".$val[name]."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_input' align='left'><textarea name='".$val[para]."' cols='60' rows='5'></textarea></td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_cv.="</tr>\n";
     break;
	 case 4:
	 $tmp1="";
     $i=0;
     foreach($paravalue[$val[id]] as $key=>$val1){
     $i++;
     $tmp1=$tmp1."<input name='para$val[id]_$i' type='checkbox' value='$val1[info]' >$val1[info]  ";
     }
	 $methtml_cv.="<tr class='cv_tr'> \n";
     $methtml_cv.="<td class='cv_td1' align='right'>".$val[name]."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_input' align='left'>".$tmp1."</td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_cv.="</tr>\n";
     break;
	 case 5:
     $methtml_cv.="<tr class='cv_tr'> \n";
     $methtml_cv.="<td class='cv_td1' align='right'>".$val[name]."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_input' align='left'><input name='".$val[para]."' type='file' class='input' size='20' ></td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_cv.="</tr>\n";
	 break;
	 case 6:
	 $tmp2="";
     $i=0;
     foreach($paravalue[$val[id]] as $key=>$val2){
     $checked='';
     $i++;
     if($i==1)$checked="checked='checked'";
     $tmp2=$tmp2."<input name='para$val[id]' type='radio' value='$val2[info]' $checked>$val2[info]  ";
     }
     $methtml_cv.="<tr class='cv_tr'> \n";
     $methtml_cv.="<td class='cv_td1' align='right'>".$val[name]."&nbsp;</td>\n";
     $methtml_cv.="<td class='cv_input' align='left'>".$tmp2."</td>\n";
     $methtml_cv.="<td class='cv_info' align='left' style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_cv.="</tr>\n";
	 break;
    }
   }
$cvidnow=10004;
if($met_memberlogin_code==1){
     $methtml_cv.="<tr class='cv_tr'> \n";   
     $methtml_cv.="<td class='cv_td1' align='right'>".$lang_memberImgCode.":</b></td>\n";
     $methtml_cv.="<td class='cv_code' colspan='2' align='left'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $methtml_cv.="<img align='absbottom' src='ajax.php?action=code'  onclick=this.src='ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $methtml_cv.="</td>\n";
     $methtml_cv.="</tr>\n";
}	 
     $methtml_cv.="<tr class='cv_tr' >\n"; 
     $methtml_cv.="<td class='cv_td1'></td>\n";
     $methtml_cv.="<td class='cv_submit' colspan='2' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='Submit' value='".$lang_Submit."' class='tj'>&nbsp;&nbsp;<input type='reset' name='Submit' value='".$lang_Reset."' class='tj'> </td>\n";
     $methtml_cv.="</tr>";		
     $methtml_cv.="</table>";
     $methtml_cv.="</form>";

$csnow=$cvidnow?$cvidnow:$classnow;
include template('cv');
footer();

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>