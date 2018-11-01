<?php
class authcode {
	private $width, $height, $codenum;
	public $checkcode;     //产生的验证码
	private $checkimage;    //验证码图片
	private $disturbColor = ''; //干扰像素
	/*
	* 参数：（宽度，高度，字符个数）
	*/
	function __construct($width = '100', $height = '30', $codenum = '4')
	{
		$this->width = $width;
		$this->height = $height;
		$this->codenum = $codenum;
	}
	function get()
	{
		//产生验证码
		$this->docode();
		//产生图片
		$this->doimage();
		//设置干扰像素
		$this->dodisturb();
		//往图片上写验证码
		$this->writeCheckCodeToImage();
		ob_clean();
		header("Content-type:image/png");
		imagepng($this->checkimage);
		imagedestroy($this->checkimage);
	}
	/**
	* 产生验证码
	*/
	private function docode()
	{
		$this->checkcode = strtoupper(substr(md5(rand()),0,$this->codenum));
		session_start();
		//$_SESSION['authcode'] = strtolower($this->checkcode);
		$_SESSION['authcode'] = $this->checkcode;
	}
	/**
	* 产生验证码图片
	*/
	private function doimage()
	{
		$this->checkimage = @imagecreate($this->width, $this->height);
		$back = imagecolorallocate($this->checkimage,255,255,255);
		$border = imagecolorallocate($this->checkimage,0,0,0);  
		imagefilledrectangle($this->checkimage,0,0,$this->width - 1,$this->height - 1,$back); // 白色底
		imagerectangle($this->checkimage,0,0,$this->width - 1,$this->height - 1,$border);   // 黑色边框
	}
	/**
	* 设置图片的干扰像素
	*/
	private function dodisturb()
	{
		for ($i=0;$i<=200;$i++)
		{
			$this->disturbColor = imagecolorallocate($this->checkimage, rand(0,255), rand(0,255), rand(0,255));
			imagesetpixel($this->checkimage,rand(2,128),rand(2,38),$this->disturbColor);
		}
	}
	/**
	*
	* 在验证码图片上逐个画上验证码
	*
	*/
	private function writeCheckCodeToImage()
	{
		for ($i=0;$i<=$this->codenum;$i++)
		{
			$bg_color = imagecolorallocate ($this->checkimage, rand(0,255), rand(0,128), rand(0,255));
			$x = floor($this->width/$this->codenum)*$i + 10;
			$y = rand(0,$this->height-20);
			imagechar ($this->checkimage, rand(5,8), $x, $y, $this->checkcode[$i], $bg_color);
		}
	}
	function __destruct()
	{
		unset($this->width,$this->height,$this->codenum);
	}
}
$authcode = new authcode();
echo $authcode->get();
?>