<?php

    class Pager
    {
      var   $_total;                      
      var  $pagesize;                    
      var     $pages;                     
      var   $_cur_page;                   
      var  $offset;                     
      var  $pernum = 10;                
    
      function Pager($total,$pagesize,$_cur_page)
        {   
        $this->_total=$total;
        $this->pagesize=$pagesize;
        $this->_offset();
        $this->_pager();
        $this->cur_page($_cur_page);
    }
    
    function _pager()//total pages
    { 
    return $this->pages = ceil($this->_total/$this->pagesize);
    }
   
   
     function cur_page($_cur_page) //page number
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
      global $lang_pageTotal,$lang_pages,$lang_pageLoction,$lang_pageHome,$lang_pageEnd,$lang_pageReturn,$lang_pageNext,$lang_pageGo;
   $text="<form method='POST' name='page1'  action='".$url."' target='_self'>";
   $text.="<style>";
   $text.=".digg4 a{ border:1px solid #ccdbe4; padding:2px 8px 2px 8px; background:#fff; background-position:50%; margin:2px; color:#666; text-decoration:none;}\n";
   $text.=".digg4 a:hover { border:1px solid #999; color:#fff; background-color:#999;}\n";
   $text.=".digg4 a:active {border:1px solid #000099; color:#000000;}\n";
   $text.=".digg4 span.current { padding:2px 8px 2px 8px; margin:2px; text-decoration:none;}\n";
   $text.=".digg4 span.disabled { border:1px solid #ccc; background:#fff; padding:1px 8px 1px 8px; margin:2px; color:#999;}\n";
   $text.=" </style>";
   if($this->pages >12 ){
     $startnum=floor(($this->_cur_page/10))*10+1;
	 if(floor(($this->_cur_page/10))==($this->_cur_page/10))$startnum=floor(($this->_cur_page/10))*10+1-10;
	   if(($this->pages-$startnum)>12){
	      if($startnum!=1){
		    $endnum=$startnum+9;
			$middletext="...";
			$prepagenow="<a href='".$url.($startnum-1).$exc."' style='font-family: Tahoma, Verdana;'>‹</a>";
			$nextpagenow="<a href='".$url.($startnum+10).$exc."' style='font-family: Tahoma, Verdana;'>›</a>";
		   }else{
			$endnum=$startnum+9;
			$middletext="...";
			$prepagenow="<span class='disabled' style='font-family: Tahoma, Verdana;'>‹</span>";
			$nextpagenow="<a href='".$url.($startnum+10).$exc."' style='font-family: Tahoma, Verdana;'>›</a>";
		   }
		 }else{
		    $prepagenow="<a href='".$url.($startnum-1).$exc."' style='font-family: Tahoma, Verdana;'>‹</a>";
			$nextpagenow="<span class='disabled' style='font-family: Tahoma, Verdana;'>›</span>";
		    $endnum=$this->pages-2;
			$middletext="";
		  }
	 }else{
	   	$startnum=1;
		$endnum=$this->pages-2;	
		$middletext="";
		$prepagenow="<span class='disabled' style='font-family: Tahoma, Verdana;'>‹</span>";
		$nextpagenow="<span class='disabled' style='font-family: Tahoma, Verdana;'>›</span>";
	 }
	  $text.="<div class='digg4'>";
    if ($this->_cur_page == 1){
         $text.="<span class='disabled' style='font-family: Tahoma, Verdana;'><b>«</b></span>".$prepagenow;
		}else{
		 $text.="<a style='font-family: Tahoma, Verdana;' href=".$url."1".$exc."><b>«</b></a>".$prepagenow;
		}   
   for($i=$startnum;$i<=$endnum;$i++){
	   if($i==$this->_cur_page){
	    $text.="<span class='current'>".$i."</span>";
		}else{
	    $text.=" <a href=".$url.$i.$exc.">".$i."</a> ";
		}
	   }   
        $text.=$middletext;
 if($this->pages>1){
	if(($this->pages-1)==$this->_cur_page){
	     $text.="<span class='current'>".($this->pages-1)."</span>";
	 }else{
        $text.=" <a href=".$url.($this->pages-1).$exc.">".($this->pages-1)."</a> ";
	 }
	}
	if($this->pages==$this->_cur_page){
	     $text.="<span class='current'>".$this->pages."</span>";
	 }else{
        $text.=" <a href=".$url.$this->pages.$exc.">".$this->pages."</a> ";
	 }
    if ($this->_cur_page == $this->pages){
         $text.=$nextpagenow."<span class='disabled' style='font-family: Tahoma, Verdana;'><b>»</b></span>";
		}else{
		$text.=$nextpagenow."<a style='font-family: Tahoma, Verdana;' href=".$url.$this->pages.$exc."><b>»</b></a>";
		}  

$text.=" $lang_pageGo<input name='page_input' class='page_input' />$lang_pages ";
$text.="<input type='submit' name='Submit3' value=' go ' class='submit' /></form>";	
        $text.="</div>";	
        return $text;
  }
 }
 
   