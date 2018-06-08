<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
    class Pager
    {
      var $_total;                          //total
      var $pagesize;                       //
      var $pages;                         //pages number
      var $_cur_page;                    //now page
      var $offset;                      //
      var $pernum = 10;                //
	  var $SELF;
    
      function Pager($total,$pagesize,$_cur_page)
    {   
		global $PHP_SELF;
        $this->_total=$total;
        $this->pagesize=$pagesize;
        $this->_offset();
        $this->_pager();
        $this->cur_page($_cur_page);
		$SELFs=explode('/',$PHP_SELF);
		$this->SELF=$SELFs[count($SELFs)-2];
    }
    
    function _pager()//total
    { 
    return $this->pages = ceil($this->_total/$this->pagesize);
    }
   
   
     function cur_page($_cur_page) //
    {     
   	    if (isset($_cur_page)&&$_cur_page!=0)
           {
           $this->_cur_page=intval($_cur_page);
           }
           else
           {
            $this->_cur_page=1; //first page
           }
        return  $this->_cur_page;
   }
   
 //
  function _offset()
   {
   return $this->offset=$this->pagesize*($this->_cur_page-1);
   }
      //html
 function num_link($tex='?',$url='')
  {
       $setpage  = $this->_cur_page ? ceil($this->_cur_page/$this->pernum) : 1;
        $pagenum   = ($this->pages > $this->pernum) ? $this->pernum : $this->pages;
        if ($this->_total  <= $this->pagesize) {
            $text  = '';
        } else {
            $text = '<span class=list_total>'.$this->_cur_page.'/'.$this->pages.'&nbsp;</span>';
            if ($setpage > 1) {
                $lastsetid = ($setpage-1)*$this->pernum;
                $text .= '<a  class=webdings href='.$tex.'pager='.$lastsetid.$url.'> 8 </a>';
            }
            if ($this->_cur_page > 1) {
                $pre = $this->_cur_page-1;
                $text .= '<a    class=webdings  href='.$tex.'pager='.$pre.$url.'>3</a>';
            }
            $i = ($setpage-1)*$this->pernum;
            for($j=$i; $j<($i+$pagenum) && $j<$this->pages; $j++) {
                $newpage = $j+1;
                if ($this->_cur_page == $j+1) {
                    $text .= '<span>'.($j+1).'</span>';
                } else {
                    $text .= '<a href='.$tex.'pager='.$newpage.$url.'>'.($j+1).'</a>';
                }
            }            
            if ($this->_cur_page < $this->pages){
                $next = $this->_cur_page+1;
                $text .= '<a  class=webdings href='.$tex.'pager='.$next.$url.'>4</a>';
            }
            if ($setpage < $this->_total) {
                $nextpre = $setpage*($this->pernum+1);
                if($nextpre<$this->pages)
                $text .= '<a  class=webdings  href='.$tex.'pager='.$nextpre.$url.'>7</a>';
            }
         }
            return $text;
         }
 //html
function link($url, $exc='')
  {
      global $lang_PageTotal,$lang_Page,$lang_PageLocation,$lang_PageHome,$lang_PageEnd,$lang_PagePre,$lang_PageNext,$lang_PageGo,$met_pageskin,$total_count;
	  global $lang_Total,$lang_Pagenum,$met_url,$langnums,$metinfouiok,$class1,$class2,$class3,$class_list,$classnow,$met_pseudo;
	  if(!$exc&&($class_list[$classnow][module]<6)){
		$urlx=explode('&',$url);
		if($class3){
			$url = $urlx[0].'&'.$urlx[3].'&'.$urlx[4];
		}elseif($class2){
			$url = $urlx[0].'&'.$urlx[2].'&'.$urlx[4];
		}else{
			$url = $urlx[0].'&'.$urlx[1].'&'.$urlx[4];
		}
	  }
 if($met_pageskin=='' or $met_pageskin==0 or $met_pageskin>9)$met_pageskin=1;
 $firestpage=$langnums==1?'../'.$this->SELF.'/':$url.'1'.$exc;
if($class_list[$classnow][module]==11)$firestpage=$url.'1'.$exc;
 if($exc){
	if($class2||$class3){
		$firestpage=$url.'1'.$exc;
		if($met_pseudo){
			$urlx= substr($url, 0, -1) ;
			$firestpage=$urlx.$exc;
		}
	}
 }else{
	if($class2||$class3){
		$urlx = str_ireplace("&page=", "", $url);
		$firestpage=$urlx.$exc;
	}
 }
 $prepage=$langnums==1?'../'.$this->SELF.'/':$url.($this->_cur_page-1).$exc;
  switch($met_pageskin){
case 1:
	$metpgs=$this->pages==0?1:$this->pages;
    $text= "<div class='metpager_1'>$lang_PageTotal<span>$metpgs</span>$lang_Page $lang_PageLocation<span style='color:#990000'>$this->_cur_page</span>$lang_Page ";
      if ($this->_cur_page == 1 && $this->pages>1) 
        {
            //first page
            $text.= "$lang_PageHome $lang_PagePre <a href=".$url.($this->_cur_page+1).$exc.">$lang_PageNext</a>  <a href=".$url.$this->pages.$exc.">$lang_PageEnd</a>";
        } 
        elseif($this->_cur_page == $this->pages && $this->pages>1) 
        {
		echo 1;
            //last page
			 $pageurl=$this->_cur_page-1==1?$firestpage:$url.($this->_cur_page-1).$exc;
             $text.= "<a href=".$firestpage.">$lang_PageHome</a> <a href=".$pageurl.">$lang_PagePre</a> $lang_PageNext $lang_PageEnd";
        } 
        elseif ($this->_cur_page > 1 && $this->_cur_page <= $this->pages) 
        {
			//middle
			if($this->_cur_page==2){
             $text.= "<a href=".$firestpage.">$lang_PageHome</a> <a href=".$prepage.">$lang_PagePre</a> <a href=".$url.($this->_cur_page+1).$exc.">$lang_PageNext</a>  <a href=".$url.$this->pages.$exc.">$lang_PageEnd</a>";
			 }else{
		     $text.= "<a href=".$firestpage.">$lang_PageHome</a> <a href=".$url.($this->_cur_page-1).$exc.">$lang_PagePre</a> <a href=".$url.($this->_cur_page+1).$exc.">$lang_PageNext</a>  <a href=".$url.$this->pages.$exc.">$lang_PageEnd</a>";
			 }
        }
            $text.=" $lang_PageGo <select onchange='javascript:window.location.href=this.options[this.selectedIndex].value'>";
         for($i=1;$i<=$metpgs;$i++){
			$pageurl=$i==1?$firestpage:$url.$i.$exc;
            if($i==$this->_cur_page){
              $text.="<option selected='selected' value='".$pageurl."' >".$i."</option>";
            }else{
              $text.="<option value='".$pageurl."' >".$i."</option>";
            }
          }
              $text.="</select> $lang_Page </div>";

  break;
  
  case 2:
 
      if ($this->_cur_page == 1 && $this->pages>1) 
        {

            $text= "<div class='metpager_2'>$lang_PageHome $lang_PagePre <a href=".$url.($this->_cur_page+1).$exc.">$lang_PageNext</a>  <a href=".$url.$this->pages.$exc.">$lang_PageEnd</a>";
        } 
        elseif($this->_cur_page == $this->pages && $this->pages>1) 
        {
			$pageurl=$this->_cur_page-1==1?$firestpage:$url.($this->_cur_page-1).$exc;
             $text.= "<a href=".$firestpage.">$lang_PageHome</a> <a href=".$pageurl.">$lang_PagePre</a> $lang_PageNext $lang_PageEnd";
        } 
        elseif ($this->_cur_page > 1 && $this->_cur_page <= $this->pages) 
        {
			if($this->_cur_page==2){
             $text.= "<a href=".$firestpage.">$lang_PageHome</a> <a href=".$prepage.">$lang_PagePre</a> <a href=".$url.($this->_cur_page+1).$exc.">$lang_PageNext</a>  <a href=".$url.$this->pages.$exc.">$lang_PageEnd</a>";
			}else{
			 $text.= "<a href=".$firestpage.">$lang_PageHome</a> <a href=".$url.($this->_cur_page-1).$exc.">$lang_PagePre</a> <a href=".$url.($this->_cur_page+1).$exc.">$lang_PageNext</a>  <a href=".$url.$this->pages.$exc.">$lang_PageEnd</a>";
			}
		}
		$metpgs=$this->pages==0?1:$this->pages;
		 $text .="&nbsp;&nbsp;".$lang_PageTotal."<span>$this->_total</span>$lang_Total $lang_PageLocation<span style='color:#990000'>$this->_cur_page</span>/".$metpgs.$lang_Pagenum;
		 $text .="</div>";
        
  break;
  
  case 3:
	$text="<div class='metpager_3'>";
    if ($this->_cur_page == 1){
			if($this->pages==0){
				$text.="<span style='font-family: Tahoma, Verdana;'><b>«</b></span> <span style='font-size:12px;font-family: Tahoma, Verdana;'>‹</span>";
			}else{
			 $text.="<a style='font-family: Tahoma, Verdana;' href=".$firestpage."><b>«</b></a> <a style='font-size:12px;font-family: Tahoma, Verdana;' href=".$firestpage.">‹</a>";
			}
		}else{
			if($this->_cur_page == 2){
			 $text.="<a style='font-family: Tahoma, Verdana;' href=".$firestpage."><b>«</b></a> <a style='font-family: Tahoma, Verdana;' href=".$prepage.">‹</a>";				
			}else{
			 $text.="<a style='font-family: Tahoma, Verdana;' href=".$firestpage."><b>«</b></a> <a style='font-family: Tahoma, Verdana;' href=".$url.($this->_cur_page-1).$exc.">‹</a>";

			}
		}
	if($this->pages >10 ){
	   if($this->_cur_page>5){
	     if(($this->pages-$this->_cur_page)>5){
	        $startnum=$this->_cur_page-4;
		    $endnum=$this->_cur_page+5;
		  }else{
		    $startnum=$this->pages-9;
		    $endnum=$this->pages;
		   }
		}else{
		    $startnum=1;
		    $endnum=10;		
		}
	 }else{
	   	$startnum=1;
		$endnum=$this->pages;	
	 }
	for($i=$startnum;$i<=$endnum;$i++){
	   $pageurl=$i==1?$firestpage:$url.$i.$exc;
	   if($i==$this->_cur_page)$page_stylenow="style='font-weight:bold;'";
	    $text.=" <a $page_stylenow href=".$pageurl.">[".$i."]</a> ";
		$page_stylenow='';
	   }
	 if ($this->_cur_page == $this->pages){
        $text.="<a style='font-family: Tahoma, Verdana;' href=".$url.$this->pages.$exc.">›</a> <a style='font-family: Tahoma, Verdana;' href=".$url.$this->pages.$exc."><b>»</b></a>";
	  }else{
		if($this->pages==0){
			$text.="<span style='font-family: Tahoma, Verdana;'>›</span> <span style='font-family: Tahoma, Verdana;'><b>»</b></span>";
		}else{
	    $text.="<a style='font-family: Tahoma, Verdana;' href=".$url.($this->_cur_page+1).$exc.">›</a> <a style='font-family: Tahoma, Verdana;'  href=".$url.$this->pages.$exc."><b>»</b></a>";
		}
	   }
	   $text .="</div>";
  break;
  
  case 4 or 5 or 6 or 7 or 8 or 9:
  if(!$metinfouiok){
	  if($met_pageskin==4){
	   $text.="<style>";
	   $text.=".digg4 { padding:3px; margin:3px; text-align:center; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;}";
	   $text.=".digg4 a,.digg4 span.miy{ border:1px solid #aaaadd; padding:2px 5px 2px 5px; margin:2px; color:#000099; text-decoration:none;}";
	   $text.=".digg4 a:hover { border:1px solid #000099; color:#000000;}";
	   $text.=".digg4 a:active {border:1px solid #000099; color:#000000;}";
	   $text.=".digg4 span.current { border:1px solid #000099; background-color:#000099; padding:2px 5px 2px 5px; margin:2px; color:#FFFFFF; text-decoration:none;}";
	   $text.=".digg4 span.disabled { border:1px solid #eee; padding:2px 5px 2px 5px; margin:2px; color:#ddd;}";
	   $text.=".digg4 .disabledfy { font-family: Tahoma, Verdana;}"; 
	   $text.="</style>";
	  }elseif($met_pageskin==5){
	   $text.="<style>";
	   $text.=".digg4 { padding:3px; margin:3px; text-align:center; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;}";
	   $text.=".digg4  a,.digg4 span.miy{ border:1px solid #ccdbe4; padding:2px 8px 2px 8px; background-position:50%; margin:2px; color:#0061de; text-decoration:none;}";
	   $text.=".digg4  a:hover { border:1px solid #2b55af; color:#fff; background-color:#3666d4;}";
	   $text.=".digg4  a:active {border:1px solid #000099; color:#000000;}";
	   $text.=".digg4  span.current { padding:2px 8px 2px 8px; margin:2px; color:#000; text-decoration:none;}";
	   $text.=".digg4  span.disabled { border:1px solid #ccdbe4; padding:2px 8px 2px 8px; margin:2px; color:#ddd;}";
	   $text.=".digg4 .disabledfy { font-family: Tahoma, Verdana;}"; 
	   $text.=" </style>";
	  }elseif($met_pageskin==6){
	   $text.="<style>";
	   $text.=".digg4 { padding:3px; color:#ff6500; margin:3px; text-align:center; font-family: Tahoma, Arial, Helvetica, Sans-serif; font-size: 12px;}";
	   $text.=".digg4 a,.digg4 span.miy{ border:1px solid  #ff9600; padding:2px 7px 2px 7px; background-position:50% bottom; margin:2px; color:#ff6500; background-image:url(".$met_url."images/page6.jpg); text-decoration:none;}";
	   $text.=".digg4 a:hover { border:1px solid #ff9600; color:#ff6500; background-color:#ffc794;}";
	   $text.=".digg4 a:active {border:1px solid #ff9600; color:#ff6500; background-color:#ffc794;}";
	   $text.=".digg4 span.current {border:1px solid #ff6500; padding:2px 7px 2px 7px; margin:2px; color:#ff6500; background-color:#ffbe94; text-decoration:none;}";
	   $text.=".digg4 span.disabled { border:1px solid #ffe3c6; padding:2px 7px 2px 7px; margin:2px; color:#ffe3c6;}";
	   $text.=".digg4 .disabledfy { font-family: Tahoma, Verdana;}"; 
	   $text.=" </style>";  
	  }elseif($met_pageskin==7){
	   $text.="<style>";
	   $text.=".digg4  { padding:3px; margin:3px; text-align:center; font-family: Tahoma, Arial, Helvetica, Sans-serif, sans-serif; font-size: 12px;}";
	   $text.=".digg4  a,.digg4 span.miy{ border:1px solid  #2c2c2c; padding:2px 5px 2px 5px; background:url(".$met_url."images/page7.gif) #2c2c2c; margin:2px; color:#fff; text-decoration:none;}";
	   $text.=".digg4  a:hover { border:1px solid #aad83e; color:#fff;background:url(".$met_url."images/page7_2.gif) #aad83e;}";
	   $text.=".digg4  a:active { border:1px solid #aad83e; color:#fff;background:urlurl(".$met_url."images/page7_2.gif) #aad83e;}";
	   $text.=".digg4  span.current {border:1px solid #aad83e; padding:2px 5px 2px 5px; margin:2px; color:#fff;background:url(".$met_url."images/page7_2.gif) #aad83e; text-decoration:none;}";
	   $text.=".digg4  span.disabled { border:1px solid #f3f3f3; padding:2px 5px 2px 5px; margin:2px; color:#ccc;}";
	   $text.=".digg4 .disabledfy { font-family: Tahoma, Verdana;}"; 
	   $text.=" </style>";  
	  }elseif($met_pageskin==8){
	   $text.="<style>";
	   $text.=".digg4  { padding:3px; margin:3px; text-align:center; font-family:Tahoma, Arial, Helvetica, Sans-serif;  font-size: 12px;}";
	   $text.=".digg4  a,.digg4 span.miy{ border:1px solid #ddd; padding:2px 5px 2px 5px; margin:2px; color:#aaa; text-decoration:none;}";
	   $text.=".digg4  a:hover { border:1px solid #a0a0a0; }";
	   $text.=".digg4  a:hover { border:1px solid #a0a0a0; }";
	   $text.=".digg4  span.current {border:1px solid #e0e0e0; padding:2px 5px 2px 5px; margin:2px; color:#aaa; background-color:#f0f0f0; text-decoration:none;}";
	   $text.=".digg4  span.disabled { border:1px solid #f3f3f3; padding:2px 5px 2px 5px; margin:2px; color:#ccc;}";
	   $text.=".digg4 .disabledfy { font-family: Tahoma, Verdana;}"; 
	   $text.=" </style>";  
	  }elseif($met_pageskin==9){
	   $text.="<style>";
	   $text.=".digg4 { padding:3px; margin:3px; text-align:center; font-family:Tahoma, Arial, Helvetica, Sans-serif;  font-size: 12px;}"; 
	   $text.=".digg4  a,.digg4 span.miy{ border:1px solid #ddd; padding:2px 5px 2px 5px; margin:2px; color:#88af3f; text-decoration:none;}"; 
	   $text.=".digg4  a:hover { border:1px solid #85bd1e; color:#638425; background-color:#f1ffd6; }"; 
	   $text.=".digg4  a:hover { border:1px solid #85bd1e; color:#638425; background-color:#f1ffd6; }"; 
	   $text.=".digg4  span.current {border:1px solid #b2e05d; padding:2px 5px 2px 5px; margin:2px; color:#fff; background-color:#b2e05d; text-decoration:none;}"; 
	   $text.=".digg4  span.disabled { border:1px solid #f3f3f3; padding:2px 5px 2px 5px; margin:2px; color:#ccc;}"; 
	   $text.=".digg4 .disabledfy { font-family: Tahoma, Verdana;}"; 
	   $text.=" </style>";  
	  }
  }
   if($this->pages >12 ){
     $startnum=floor(($this->_cur_page/10))*10+1;
	 if(floor(($this->_cur_page/10))==($this->_cur_page/10))$startnum=floor(($this->_cur_page/10))*10+1-10;
	   if(($this->pages-$startnum)>=12){
	      if($startnum!=1){
		    $endnum=$startnum+9;
			$middletext="...";
			$prepagenow="<a href='".$url.($startnum-1).$exc."' class='disabledfy'>‹</a>";
			$nextpagenow="<a href='".$url.($startnum+10).$exc."' class='disabledfy'>›</a>";
		   }else{
			$endnum=$startnum+9;
			$middletext="...";
			$prepagenow="<span class='disabled disabledfy'>‹</span>";
			$nextpagenow="<a href='".$url.($startnum+10).$exc."' class='disabledfy'>›</a>";
		   }
		 }else{
		    $prepagenow="<a href='".$url.($startnum-1).$exc."' class='disabledfy'>‹</a>";
			$nextpagenow="<span class='disabled disabledfy'>›</span>";
		    $endnum=$this->pages-2;
			$middletext="";
		  }
	 }else{
	   	$startnum=1;
		$endnum=$this->pages-2;	
		$middletext="";
		$prepagenow="<span class='disabled disabledfy'>‹</span>";
		$nextpagenow="<span class='disabled disabledfy'>›</span>";
	 }
	  $text.="<div class='digg4 metpager_{$met_pageskin}'>";
    if ($this->_cur_page == 1){
         $text.="<span class='disabled disabledfy'><b>«</b></span>".$prepagenow;
		}else{
		 $text.="<a class='disabledfy' href=".$firestpage."><b>«</b></a>".$prepagenow;
		}  
   for($i=$startnum;$i<=$endnum;$i++){
		$pageurl=$i==1?$firestpage:$url.$i.$exc;
	   if($i==$this->_cur_page){
	    $text.="<span class='current'>".$i."</span>";
		}else{
		$text.=" <a href=".$pageurl.">".$i."</a> ";
		}
	   }   
        $text.=$middletext;
 if($this->pages>1){
	if(($this->pages-1)==$this->_cur_page){
	     $text.="<span class='current'>".($this->pages-1)."</span>";
	 }else{
		$pageurl=$this->pages-1==1?$firestpage:$url.($this->pages-1).$exc;
        $text.=" <a href=".$pageurl.">".($this->pages-1)."</a> ";
	 }
	}
	if($this->pages==$this->_cur_page){
	     $text.="<span class='current'>".$this->pages."</span>";
	 }else{
		if($this->pages==0){
			$text.=" <span class='disabled'>".$this->pages."</a></span> ";
		}else{
			$text.=" <a href=".$url.$this->pages.$exc.">".$this->pages."</a> ";
		}
	 }
    if ($this->_cur_page == $this->pages){
         $text.=$nextpagenow."<span class='disabled disabledfy'><b>»</b></span>";
		}else{
			if($this->pages==0){
				$text.=$nextpagenow."<span class='disabled disabledfy'><b>»</b></span>";
			}else{
			$text.=$nextpagenow."<a class='disabledfy' href=".$url.$this->pages.$exc."><b>»</b></a>";
			}
		}  
        $text.="</div>";
  break;
  
  }
         return $text; 
  }
  
  
 }
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>