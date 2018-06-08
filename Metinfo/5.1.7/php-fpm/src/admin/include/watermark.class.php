<?php

Class Watermark{
var $src_image_name = "";      //Enter image file name (must contain the path name)
var $jpeg_quality = 90;        //jpeg picture quality
var $save_file = "";          //Output file name
var $met_image_name = "";            //Watermark image file name (must contain the path name.)
var $met_image_pos = 3;             //The location of the watermark image placement
// 0 = middle
// 1 = top left
// 2 = top right
// 3 = bottom right
// 4 = bottom left
// 5 = top middle
// 6 = middle right
// 7 = bottom middle
// 8 = middle left
//other = 3
var $met_image_transition = 80;            //Watermark image and the original image fusion degree (1 = 100)

var $met_text = "";                        //Watermark text (in English and Chinese, as well as support with the \ r \ n of the cross-bank text)
var $met_text_size = 20;                   //Watermark Text Size
var $met_text_angle = 5;                   //Watermark text point of view, this value is try not to change the
var $met_text_pos = 3;                     //Text watermark placement
var $met_text_font = "";                   //Watermark text font
var $met_text_color = "#cccccc";           //Watermark font color value


function create($filename="")
{
if ($filename) {
 $this->src_image_name = trim($filename);
}
$dirname=explode("/",$this->src_image_name);
if(!file_exists("$dirname[0]/$dirname[1]/$dirname[2]/$dirname[3]/")){
	@mkdir("$dirname[0]/$dirname[1]/$dirname[2]/$dirname[3]/", 0755);
}
if(stristr(PHP_OS,"WIN")){
	$this->src_image_name = @iconv("utf-8","GBK",$this->src_image_name);
	$this->met_image_name = @iconv("utf-8","GBK",$this->met_image_name);
}
$src_image_type = $this->get_type($this->src_image_name);
$src_image = $this->createImage($src_image_type,$this->src_image_name);
if (!$src_image) return;
$src_image_w=ImageSX($src_image);
$src_image_h=ImageSY($src_image);


if ($this->met_image_name){
       $this->met_image_name = strtolower(trim($this->met_image_name));
       $met_image_type = $this->get_type($this->met_image_name);
       $met_image = $this->createImage($met_image_type,$this->met_image_name);
       $met_image_w=ImageSX($met_image);
       $met_image_h=ImageSY($met_image);
       $temp_met_image = $this->getPos($src_image_w,$src_image_h,$this->met_image_pos,$met_image);
       $met_image_x = $temp_met_image["dest_x"];
       $met_image_y = $temp_met_image["dest_y"];
	   if($this->get_type($this->met_image_name)=='png'){imagecopy($src_image,$met_image,$met_image_x,$met_image_y,0,0,$met_image_w,$met_image_h);}
	   else{imagecopymerge($src_image,$met_image,$met_image_x,$met_image_y,0,0,$met_image_w,$met_image_h,$this->met_image_transition);}
}
if ($this->met_text){
       $temp_met_text = $this->getPos($src_image_w,$src_image_h,$this->met_text_pos);
       $met_text_x = $temp_met_text["dest_x"];
       $met_text_y = $temp_met_text["dest_y"];
      if(preg_match("/([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i", $this->met_text_color, $color))
      {
         $red = hexdec($color[1]);
         $green = hexdec($color[2]);
         $blue = hexdec($color[3]);
         $met_text_color = imagecolorallocate($src_image, $red,$green,$blue);
      }else{
         $met_text_color = imagecolorallocate($src_image, 255,255,255);
      }
       imagettftext($src_image, $this->met_text_size, $this->met_text_angle, $met_text_x, $met_text_y, $met_text_color,$this->met_text_font,  $this->met_text);
}
if(stristr(PHP_OS,"WIN")){
	$save_files=explode('/',$this->save_file);
	$save_files[count($save_files)-1]=@iconv("utf-8","GBK",$save_files[count($save_files)-1]);
	$this->save_file=implode('/',$save_files);
}
if ($this->save_file)
{
  switch ($this->get_type($this->save_file)){
   case 'gif':$src_img=ImagePNG($src_image, $this->save_file); break;
   case 'jpeg':$src_img=ImageJPEG($src_image, $this->save_file, $this->jpeg_quality); break;
   case 'png':$src_img=ImagePNG($src_image, $this->save_file); break;
   default:$src_img=ImageJPEG($src_image, $this->save_file, $this->jpeg_quality); break;
  }
}
else
{
if ($src_image_type = "jpg") $src_image_type="jpeg";
  header("Content-type: image/{$src_image_type}");
  switch ($src_image_type){
   case 'gif':$src_img=ImagePNG($src_image); break;
   case 'jpg':$src_img=ImageJPEG($src_image, "", $this->jpeg_quality);break;
   case 'png':$src_img=ImagePNG($src_image);break;
   default:$src_img=ImageJPEG($src_image, "", $this->jpeg_quality);break;
  }
}
imagedestroy($src_image);
}

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
createImage     According to the file name and type to create pictures
Internal function

$type:               Image types, including gif, jpg, png
$img_name:  Image file name, including path names, such as ". / Mouse.jpg"
*/
function createImage($type,$img_name){
         if (!$type){
              $type = $this->get_type($img_name);
         }
		 
          switch ($type){
                  case 'gif':
                        if (function_exists('imagecreatefromgif'))
                               $tmp_img=@imagecreatefromgif($img_name);
                        break;
                  case 'jpg':
                        $tmp_img=imagecreatefromjpeg($img_name);
                        break;
                  case 'png':
                        $tmp_img=imagecreatefrompng($img_name);
                        break;
				  case 'jpeg':
                        $tmp_img=imagecreatefromjpeg($img_name);
                        break;
                  default:
                        $tmp_img=imagecreatefromstring($img_name);
                        break;
          }
          return $tmp_img;
}

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
getPos               According to the source image of the length, width, location code, a watermark image id to generate the watermark placed in the location of the source image
Internal function

$sourcefile_width:        Source image width
$sourcefile_height: The original image of the high 
$pos:               Location Code
// 0 = middle 
// 1 = top left
// 2 = top right
// 3 = bottom right
// 4 = bottom left
// 5 = top middle
// 6 = middle right
// 7 = bottom middle
// 8 = middle left
$met_image:           Watermark Photo ID
*/
function getPos($sourcefile_width,$sourcefile_height,$pos,$met_image=""){
         if  ($met_image){
              $insertfile_width = ImageSx($met_image);
              $insertfile_height = ImageSy($met_image);
         }else {
              $lineCount = explode("\r\n",$this->met_text);
              $fontSize = imagettfbbox($this->met_text_size,$this->met_text_angle,$this->met_text_font,$this->met_text);
              $insertfile_width = $fontSize[2] - $fontSize[0];
              $insertfile_height = count($lineCount)*($fontSize[1] - $fontSize[5]);
			  $fontSizeone =imagettfbbox($this->met_text_size,$this->met_text_angle,$this->met_text_font,'e');
			  $fontSizeone = ($fontSizeone[2] - $fontSizeone[0])/2;
         }
		switch ($pos){
			case 0:
			   $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
			   $dest_y = ( $sourcefile_height / 2 ) + ( $insertfile_height / 2 );
			   break;

			case 1:
			   $dest_x = 0;
			   $dest_y = $insertfile_height;
			   break;
			case 2:
			  $dest_x = $sourcefile_width - $insertfile_width-$fontSizeone;
			  $dest_y = $insertfile_height;
			  break;

			case 3:
			  $dest_x = $sourcefile_width - $insertfile_width-$fontSizeone;
			  $dest_y = $sourcefile_height - ($insertfile_height/4);
			  break;

			case 4:
			  $dest_x = 0;
			  $dest_y = $sourcefile_height - ($insertfile_height/4);
			  break;

			case 5:
			 $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			 $dest_y = $insertfile_height;
			 break;

			case 6:
			 $dest_x = $sourcefile_width - $insertfile_width -$fontSizeone;
			 $dest_y = ( $sourcefile_height / 2 ) + ( $insertfile_height / 2 );
			 break;

			case 7:
			 $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			 $dest_y = $sourcefile_height - ($insertfile_height/4);
			 break;

			case 8:
			 $dest_x = 0;
			 $dest_y = ( $sourcefile_height / 2 ) + ( $insertfile_height / 2 );
			 break;

			default:
			  $dest_x = $sourcefile_width - $insertfile_width;
			  $dest_y = $sourcefile_height - $insertfile_height;
			  break;
		}	
		if($met_image){
			$dest_y=$dest_y-$insertfile_height;
		}
        return array("dest_x"=>$dest_x,"dest_y"=>$dest_y);
}
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
get_type              Get the picture formats, including jpg, png, gif
Internal function

$img_name：        Image file name, path name may include
*/
function get_type($img_name)//Obtain the image file type
{
$name_array = explode(".",$img_name);
if (preg_match("/\.(jpg|jpeg|gif|png)$/i", $img_name, $matches))
{
  $type = strtolower($matches[1]);
}
else
{
  $type = "string";
}
  return $type;
}

}
?>