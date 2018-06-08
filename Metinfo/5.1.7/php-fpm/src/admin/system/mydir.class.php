<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

class myDIR{ 
var $mask = ""; 
var $find = ""; 
var $root = array(); 
var $temp = array(); 
var $result = array(); 

//** setFIND

function setFIND($val) 
{ 
$this->find = $val; 
} 

//** setMASK 

function setMASK($val="") 
{ 
$this->mask = $val; 
} 

//** setROOT 

function setROOT($val="") 
{ 
$this->root[] = $val; 
} 

//** setARRAY

function setARRAY($folder="",$file="",$size="",$time="") 
{ 
$this->result[] = array("folder"=>$folder,"file"=>$file,"size"=>$size,"time"=>$time); 
} 

//** doSEARCH

function doSEARCH() 
{ 
for($i=0; $i<count($this->root); $i++) 
{ 
unset($this->temp); 

$handle = @opendir($this->root[$i]); 
while ($file = @readdir ($handle)) 
{ 
if (eregi("^\.{1,2}$",$file)) 
{ 
continue; 
} 
$this->temp[] = $this->root[$i]."/$file"; 
} 
@closedir($handle); 

if (count($this->temp) > 0) 
{ 
natcasesort($this->temp); 

foreach ($this->temp as $val) 
{ 
switch ($this->find) 
{ 
case "folder": 
$this->doFOLDER($val); 
break; 
case "files": 
$this->doFILES($val); 
break; 
case "all": 
$this->doFILES($val); 
$this->doFOLDER($val); 
break; 
} 
} 
} 
} 
} 

//** doFOLDER

function doFOLDER($val) 
{ 
if( is_dir($val) ) 
{ 
if ($this->find == "all") 
{ 
$this->root[] = $val; 
} 
} 
} 

//** doFILES 

function doFILES($val) 
{ 
if( is_file($val) && $this->doMATCH($val) ) 
{ 
$this->doINFO($val); 
} 
} 

//** doINFO

function doINFO($val) 
{ 
$fSIZE = filesize($val); 
$fTIME = filemtime($val); 

$offset = strrpos ($val, "/"); 
$folder = substr ($val, 0, $offset); 
$file = substr ($val, $offset+1); 

$this->setARRAY($folder,$file,$fSIZE,$fTIME); 
} 

//** getRESULT 

function doMATCH($file) 
{ 
$mask = $this->mask; 
$mask = str_replace(".", "\.", $mask); 
$mask = str_replace("*", "(.*)", $mask); 

$mask_array = explode(',',$mask); 
foreach ($mask_array as $valid) 
{ 
if(eregi("^$valid", $file, $geek)) 
{ 
return true; 
} 
} 
} 

//** getRESULT 

function getRESULT(){
	$version = split ("\.", phpversion()); 
	if($version<4){
		echo "ERROR: phpMyAdmin does only works with PHP-Versions 4.0 or higher.\n<br>"; 
		echo "Your Version is (".phpversion().")."; 
		exit; 
	}
	$this->doSEARCH(); 
	if(!$this->result){
		global $lang_setfileno,$file_classnow;
		if($file_classnow==3){
			return '';
			exit; 
		} 
	}
	return $this->result; 
	} 
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>