<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
class Captcha
{
 var  $mCheckCodeNum  = 4;

  var $mCheckCode   = '';
 
 var $mCheckImage  = '';

 var$mDisturbColor  = '';

  var $mCheckImageWidth = '80';

  var $mCheckImageHeight  = '20';
  
  //font
  var $FNT="../../font/arial.ttf";

 function OutFileHeader()
 {
  header ("Content-type: image/png");
 }


function CreateCheckCode()
 {
  session_start();
  $this->mCheckCode = strtoupper(substr(md5(rand()),0,$this->mCheckCodeNum));
   unset($_SESSION['met_capcha_member1']);
  $_SESSION['met_capcha_member1'] = $this->mCheckCode;
  return $this->mCheckCode;
 }


 function CreateImage()
 {
  $this->mCheckImage = @imagecreate ($this->mCheckImageWidth,$this->mCheckImageHeight);
  imagecolorallocate($this->mCheckImage, 200, 200, 200);
  return $this->mCheckImage;
 }

 /**
 *
 */
 function SetDisturbColor()
 {
  for ($i=0;$i<=128;$i++)
  {
   $this->mDisturbColor = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,255), rand(0,255));
   imagesetpixel($this->mCheckImage,rand(2,128),rand(2,38),$this->mDisturbColor);
  }
 }

 /**
 *
 * @set image size
 */
function SetCheckImageWH($width,$height)
 {
  if($width==''||$height=='')return false;
  $this->mCheckImageWidth  = $width;
  $this->mCheckImageHeight = $height;
  return true;
 }

 /**
 * @write code to Image
 */
function WriteCheckCodeToImage()
 {
  for ($i=0;$i<=$this->mCheckCodeNum;$i++)
  {
   $bg_color = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,128), rand(0,255));
   $x = floor($this->mCheckImageWidth/$this->mCheckCodeNum)*$i;
   $y = rand(0,$this->mCheckImageHeight-15);
   imagechar ($this->mCheckImage, 5, $x, $y, $this->mCheckCode[$i], $bg_color);
  }
 }

 /**
 *
 * @Out Image
 *
 */
 function OutCheckImage()
 {
  $this ->OutFileHeader();
  $this ->CreateCheckCode();
  $this ->CreateImage();
  $this ->SetDisturbColor();
  $this ->WriteCheckCodeToImage();
  imagepng($this->mCheckImage);
  imagedestroy($this->mCheckImage);
 }
 /**
 *
 * @chech Code
 *
 */
 function CheckCode($code)
 {
  session_start();
  if(empty($code)){
      return false;
  }elseif($_SESSION['met_capcha_member1']===$code){
     return true; 
  }else {
     return false;
  }
 } 
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>