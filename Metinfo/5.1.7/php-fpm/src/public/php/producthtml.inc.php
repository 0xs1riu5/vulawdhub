<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

//product list 
function methtml_product($listtype,$type,$titlenum,$paranum,$detail,$feedback,$time,$hits,$newwindow=1,$desription,$desnum,$classname,$news,$hot,$top,$listnav=1,$max,$topcolor){
 global $product_list,$product_list_com,$product_list_img,$product_class,$lang_Colunm,$lang_Hits,$lang_UpdateTime,$lang_Title,$lang_Detail,$lang_Buy,$lang_ProductTitle;
 global $product_paralist,$met_img_x,$met_img_y,$addfeedback_url,$met_product_page;
 global $class1,$class2,$class3,$nav_list2,$nav_list3,$class_list,$module_list1,$search,$metblank;
 $listarray=($type=='new')?$product_list_new:(($type=='com')?$product_list_com:$product_list);
 $metproductok=0;
 if($met_product_page && $listtype=='img' &&($search<>'search')){
 if($class2 && count($nav_list3[$class2])&& (!$class3) ){
     $listarray=$nav_list3[$class2];
	 $metproductok=1;
	}elseif((!$class2) && count($nav_list2[$class1]) && $class1 && (!$class3)){
	 $listarray=$nav_list2[$class1];
	 $metproductok=1;
	}elseif($class_list[$class1][module]==100){
      $listarray=$module_list1[3];
	  $metproductok=1;
    }}
 $listtext.="<ul>\n";
 if($listtype=='text' or $listtype==''){
   if($listnav==1){
    $listtext.="<li class='product_list_title'>";
    if($classname==1)$listtext.="<span class='info_class' >[".$lang_Colunm."]</span>"; 
	$listtext.="<span class='info_title'>".$lang_ProductTitle."</span>";
    $i=0;
  foreach($product_paralist as $key=>$val1){
    $i++;
	if($i>$paranum)break;
    $listtext.="<span class='info_para".$i."'>".$val1[name]."</span>";
   }
    if($hits==1)$listtext.="<span class='info_hits'>".$lang_Hits."</span>";
	if($time==1)$listtext.="<span class='info_updatetime'>".$lang_UpdateTime."</span>";
	if($detail==1)$listtext.="<span class='info_detail'>".$lang_Detail."</span>";
	if($feedback==1)$listtext.="<span class='info_feedback'>".$lang_Buy."</span>";
	$listtext.="</li>\n";
  }
 }
 $i=0;
 foreach($listarray as $key=>$val){
 $val[title]=($val[title]=='')?$val[name]:$val[title];
 $val[name]=($val[name]=='')?$val[title]:$val[name];
 $addfeedback_url1=$addfeedback_url.$val[title];
 $i++;
 if(intval($titlenum)<>0)$val[title]=utf8substr($val[title], 0, $titlenum); 
 if(intval($desnum)<>0)$val[description]=utf8substr($val[description], 0, $desnum); 
 $listtext.="<li>";
if($listtype=='img'){
 $listtext.="<span class='info_img' ><a href='".$val[url]."' title='".$val[name]."'";
 if($metproductok){
 $listtext.=" ".$val[new_windows]."><img src=".$val[columnimg]." alt='".$val[name]."' title='".$val[name]."' width='".$met_img_x."' height='".$met_img_y."' /></a></span>";
 $listtext.="<span class='info_title' ><a title='".$val[name]."' href=".$val[url]." ".$val[new_windows]." >".$val[name]."</a></span>";
 
 if($paranum)$listtext.="<span class='info_description' ><a title='".$val[name]."' href=".$val[url]." ".$val[new_windows]." >".$val[description]."</a></span>";
 if($detail==1)$listtext.="<span class='info_detail' ><a href=".$val[url]." ".$val[new_windows]." >".$lang_Detail."</a></span>";
 if($feedback==1)$listtext.="<span class='info_feedback'><a href='".$addfeedback_url1."' >".$lang_Buy."</a></span>";
 }else{
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=" ><img src=".$val[imgurls]." alt='".$val[title]."' title='".$val[title]."' width='".$met_img_x."' height='".$met_img_y."' /></a></span>";
 if($classname==1)$listtext.="<span class='info_class' ><a href='".$val[classurl]."' title='".$val[classname]."' >[".$val[classname]."]</a></span>";
 $listtext.="<span class='info_title' ><a title='".$val[title]."' href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($val[top_ok]==1)$listtext.="style='color:".$topcolor.";'";
 $listtext.=">".$val[title]."</a></span>";
 if($desription==1 && $val[description]){
 $listtext.="<span class='info_description' ><a title='".$val[title]."' href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=">".$val[description]."</a></span>"; 
 }
 $j=0;
 foreach($product_paralist as $key=>$val1){
 $j++;
 if($j>$paranum)break;
    $listtext.="<span class='info_para".$j."' ><b>".$val1[name].":</b> ".$val[$val1[para]]."</span>";
  }
 if($hits==1)$listtext.="<span class='info_hits'>".$lang_Hits.":<font>".$val[hits]."</font></span>";
 if($time==1)$listtext.="<span class='info_updatetime'>".$lang_UpdateTime.":".$val[updatetime]."</span>";
 if($detail==1){
    $listtext.="<span class='info_detail' ><a href=".$val[url];
    if($newwindow==1)$listtext.=" target='_blank' ";
    $listtext.=">".$lang_Detail."</a></span>";
  }
 if($feedback==1)$listtext.="<span class='info_feedback'><a href='".$addfeedback_url1."' >".$lang_Buy."</a></span>";
 }
}else{
 if($classname==1)$listtext.="<span class='info_class'><a href='".$val[classurl]."' title='".$val[classname]."' >[".$val[classname]."]</a></span>";
 $listtext.="<span  class='info_title'><a href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($val[top_ok]==1)$listtext.=" style='color:".$topcolor.";'";
 $listtext.="  title='".$val[title]."' >".$val[title]."</a></span>";
 $j=0;
 foreach($product_paralist as $key=>$val1){
 $j++;
 if($j>$paranum)break;
    $listtext.="<span class='info_para".$j."' >".$val[$val1[para]]."</span>";
  }
 if($hits==1)$listtext.="<span class='info_hits' ><b>".$val[hits]."</b></span>";
 if($top==1)$listtext.=$val[top];
 if($news==1)$listtext.=$val[news];
 if($hot==1)$listtext.=$val[hot];
 if($time==1)$listtext.="<span class='info_updatetime' >".$val[updatetime]."</span>";
 if($detail==1){
    $listtext.="<span class='info_detail' ><a href=".$val[url];
    if($newwindow==1)$listtext.=" target='_blank' ";
    $listtext.=">".$lang_Detail."</a></span>";
  }
 if($feedback==1)$listtext.="<span class='info_feedback'><a href='".$addfeedback_url1."' >".$lang_Buy."</a></span>";
}
 $listtext.="</li>\n";
 if($max&&$i>=$max)break;
 }
 $listtext.="</ul>";
 return $listtext;
 }

function methtml_showproduct($type,$feedback=1,$desription){
 global $product,$lang_Colunm,$lang_Hits,$lang_UpdateTime,$lang_Title,$lang_Detail,$lang_Buy,$lang_BigPicture;
 global $productpara,$product_paralist,$met_img_x,$met_img_y,$addfeedback_url,$met_url,$product_paraimg;
 $addfeedback_url1=$addfeedback_url.$product[title];
if($type=='all'){
   $listtext.=methtml_imgdisplay('product');
 $j=0;
 $k=0;
 $listtext.="<ul>\n";
 foreach($product_paralist as $key=>$val){
     $j++;
    $listtext.="<li class='info_para".$j."' ><b>".$val[name].":</b>".$product[$val[para]]."</li>";
 }
 foreach($productpara[3] as $key=>$val){  
    $k++;
    $listtext.="<li class='info_bigpara".$k."' ><b>".$val[name].":</b>".$product[$val[para]]."</li>";
   }
  $listtext.="</ul>\n"; 
 if($desription==1 && $val[description]){
 $listtext.="<span class='info_description' >".$val[description]."</span>"; 
 }
 if($feedback==1)$listtext.="<span class='info_feedback'><a href='".$addfeedback_url1."' >".$lang_Buy."</a></span>";
 
}elseif($type=='para'){
 $j=0;
 $k=0;
 $listtext.="<ul>\n";
 foreach($product_paralist as $key=>$val){
     $j++;
    $listtext.="<li class='info_para".$j."' ><b>".$val[name].":</b>".$product[$val[para]]."</li>";
   }
 foreach($productpara[3] as $key=>$val){
     $k++;
    $listtext.="<li class='info_bigpara".$k."' ><b>".$val[name].":</b>".$product[$val[para]]."</li>";
   }
  $listtext.="</ul>\n"; 
} 
  return $listtext;
}
require_once 'imgdisplayhtml.inc.php';
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>