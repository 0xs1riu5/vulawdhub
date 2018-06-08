<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.

function methtml_news($listtype,$type,$topcolor,$titlenum,$desnum,$newwindow=1,$classname=1,$time=1,$news=1,$hot=1,$top=1,$hits=1,$listnav=1,$max){
 global $news_list,$news_list_com,$news_list_img,$news_class,$lang_Colunm,$lang_Hits,$lang_UpdateTime,$lang_Title,$lang_Detail;
 global $met_img_x,$met_img_y;
 $listarray=($type=='img')?$news_list_img:(($type=='com')?$news_list_com:$news_list);
 $listtext.="<ul>\n";
 if($listtype=='text' or $listtype==''){
   if($listnav==1){
    $listtext.="<li class='news_list_title'>";
    if($classname==1)$listtext.="<span class='info_class'> [".$lang_Colunm."]</span>"; 
    $listtext.="<span class='info_title'>".$lang_Title."</span>";
    if($hits==1)$listtext.="<span class='info_hits'>".$lang_Hits."</span>";
	if($time==1)$listtext.="<span class='info_updatetime'>".$lang_UpdateTime."</span>";
	$listtext.="</li>\n";
  }
 }
 $i=0;
 foreach($listarray as $key=>$val){
 $i++;
 if(intval($titlenum)<>0)$val[title]=utf8substr($val[title], 0, $titlenum); 
 if(intval($desnum)<>0)$val[description]=utf8substr($val[description], 0, $desnum); 
 $listtext.="<li>";
if($listtype=='img'){
 $listtext.="<span class='info_img' ><a href='".$val[url]."'";
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=" ><img src=".$val[imgurls]." alt=".$val[title]." width=".$met_img_x." height=".$met_img_y." /></a></span>";
 if($classname==2)$listtext.="<span class='info_class' ><a href='".$val[classurl]."' title='".$val[classname]."' >[".$val[classname]."]</a></span>";
 $listtext.="<span class='info_title' ><a title='".$val[title]."' href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($val[top_ok]==1)$listtext.="style='color:".$topcolor.";'";
 $listtext.=">".$val[title]."</a></span>";
 $listtext.="<span class='info_description' ><a title='".$val[title]."' href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=">".$val[description]."</a></span>"; 
 if($hits==1)$listtext.="<span class='info_hits'>".$lang_Hits.":<font>".$val[hits]."</font></span>";
 if($time==1)$listtext.="<span class='info_updatetime'>".$lang_UpdateTime.":".$val[updatetime]."</span>";
 $listtext.="<span class='info_detail' ><a title='".$val[title]."' href='".$val[url]."'";
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=">".$lang_Detail."</a></span>";
}elseif($listtype=='description'){
 if($classname==1)$listtext.="<span class='info_class' ><a href='".$val[classurl]."' title='".$val[classname]."' >[".$val[classname]."]</a></span>";
 $listtext.="<span class='info_title' ><a href='".$val[url]."'";
 $listtext.="<a title='".$val[title]."' href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($val[top_ok]==1)$listtext.="style='color:".$topcolor.";'";
 $listtext.=">".$val[title]."</a></span>";
 $listtext.="<span class='info_description' ><a title='".$val[title]."' href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=">".$val[description]."</a></span>"; 
 if($hits==1)$listtext.="<span class='info_hits'>".$lang_Hits.":<font>".$val[hits]."</font></span>";
 if($time==1)$listtext.="<span class='info_updatetime'>".$lang_UpdateTime.":".$val[updatetime]."</span>";
 $listtext.="<span class='info_detail'><a title='".$val[title]."' href='".$val[url]."'";
 if($newwindow==1)$listtext.=" target='_blank' ";
 $listtext.=">".$lang_Detail."</a></span>";
}else{
 if($classname==1)$listtext.="<span class='info_class'><a href='".$val[classurl]."'  title='".$val[classname]."' >[".$val[classname]."]</a></span>";
 $listtext.="<span class='info_title'><a href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($val[top_ok]==1)$listtext.=" style='color:".$topcolor.";'";
 $listtext.="  title='".$val[title]."' >".$val[title]."</a></span>";
 if($hits==1)$listtext.="<span class='info_hits'><font>".$val[hits]."</font></span>";
 if($top==1)$listtext.=$val[top];
 if($news==1)$listtext.=$val[news];
 if($hot==1)$listtext.=$val[hot];
 if($time==1)$listtext.="<span class='info_updatetime'>".$val[updatetime]."</span>";
}
 $listtext.="</li>\n";
 if($max&&$i>=$max)break;
 }
 $listtext.="</ul>";
 return $listtext;

}
?>