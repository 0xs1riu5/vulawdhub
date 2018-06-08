<?php
class upfile {
  var $filename;
  // name
  var $savename;
  // Path
  var $savepath = '../../upload';
  //Watermark Save Path
  var $waterpath =  '../../upload/watermark';
  // File format defined for the space-time format does not limit
  var $format = "";
  // Overwrite mode
  var $overwrite = 1;
  /* $overwrite = 0 not Overwrite
   * $overwrite = 1 Overwrite
   */
  //Maximum file size bytes
  var $maxsize = 210000000;
  //File extension
  var $ext;
  //error
  var $errnotype = 0;
  var $error= 0;
  var $errorcode;
  //separator
  var $separator;
  /* function
   * $path save path
   * $format file fromat
   * $maxsize file max size
   * $over Overwrite parameter
   */
  function upfile($format = '',$path = '',$maxsize = 0, $over = 0 ,$errno = 0,$separator =',') {
	global $lang_upfileFail,$lang_upfileFail1,$lang_upfileFail2,$depth;
		$this->errnotype=$errno;
		$this->savepath=$depth.$this->savepath;
      if (empty($path)) {
          $path = $this->savepath.'/'.date('Ym').'/';
          $water = $this->savepath.'/'.date('Ym').'/watermark/';
          $thumb = $this->savepath.'/'.date('Ym').'/thumb/';
		  $thumb_dis = $this->savepath.'/'.date('Ym').'/thumb_dis/';
          if (!file_exists($water)){
              if(!$this->make_dir($water)){ return $this->halt($lang_upfileFail);}
          }
          if (!file_exists($thumb)) {
              if(!$this->make_dir($thumb)){ return $this->halt($lang_upfileFail1);}
          }
		  if (!file_exists($thumb_dis)) {
              if(!$this->make_dir($thumb_dis)){ return $this->halt($lang_upfileFail1);}
          }
          $this->waterpath = $water;
      } else {
      $this->savepath = substr($path,  - 1) == "/" ? $path : $path."/";
    }
    if (!file_exists($path)) {
      if (!$this->make_dir($path)) {
        return $this->halt($lang_upfileFail2);
      }
    }
    $this->savepath = $path;
    $this->overwrite = $over; //Whether it covers the same name file
    $this->maxsize = $maxsize?$maxsize:$this->maxsize; //Maximum file size bytes
    $this->format = $format;
	$this->separator = $separator;
  }

  /*
   * Functions: to detect and organize your files
   * $form file name
   * $file fave file name
   */
  function upload($form, $file = "") {
	global $lang_upfileFail3,$met_img_rename;
    if (is_array($form)) {
      $filear = $form;
    } else {
      $filear = $_FILES[$form];
    }
    if (!is_writable($this->savepath)) {
      return $this->halt($lang_upfileFail3);
    }
      $this->getext($filear["name"]); //Get extension
	  if($met_img_rename||$file){
		$this->set_savename($file); //Save the settings file name
	  }else{
		$this->savename = str_replace(array(":", "*", "?", "|", "/" ,"——"," "),'_',$filear["name"]);
	  }
	  if(stristr(PHP_OS,"WIN")){
		$this->savename = @iconv("utf-8","GBK",$this->savename);
	  }
	  $paths=explode("../",$this->savepath);
	  $paths='../'.$paths[2];
	 if(!$file){
		  $i=1;
		  $savename_temp=str_replace('.'.$this->ext,'',$this->savename);
		  while(file_exists('../'.$paths.$this->savename)){
			$this->savename=$savename_temp.'('.$i.')'.'.'.$this->ext;
			$i++;
			}
		}
		$this->copyfile($filear);
	  if(stristr(PHP_OS,"WIN")){
		$this->savename = @iconv("GBK","utf-8",$this->savename);
	  }
	  return $paths.$this->savename;
  }

  /*
   * Function: To detect and copy uploaded file
   * $filear Upload an array of documents
   */
 
 
  function copyfile($filear) {
	global $lang_upfileFile,$lang_upfileMax,$lang_upfileByte,$lang_upfileTip1,$lang_upfileTip2,$lang_upfileTip3,
			$lang_upfileOK,$lang_upfileOver,$lang_upfileOver1,$lang_upfileOver2,$lang_upfileOver3,$lang_upfileOver4,$lang_upfileOver5;
    if ($filear["size"] > $this->maxsize) {
      return $this->halt("$lang_upfileFile ".$filear["name"]." $lang_upfileMax [".$this->maxsize." $lang_upfileByte] $lang_upfileTip1");
    }

    if (!$this->overwrite && file_exists($this->savename)) {
      return $this->halt($this->savename." $lang_upfileTip2");
    }
	if(!$this->format)$this->format.='|';
    if ($this->format != "" && !in_array(strtolower($this->ext), explode($this->separator,
        strtolower($this->format)))) {  	
       return $this->halt($this->ext." $lang_upfileTip3");
    }
	if(strtolower($this->ext)=='php'||strtolower($this->ext)=='aspx'||strtolower($this->ext)=='asp'||strtolower($this->ext)=='jsp'||strtolower($this->ext)=='js'||strtolower($this->ext)=='asa'){
		return $this->halt($this->ext." $lang_upfileTip3");
	}
	$upfileok=0;
	$file_tmp=$filear["tmp_name"];
	$file_name=$this->savepath.$this->savename;
	if (function_exists("move_uploaded_file")){
        if (move_uploaded_file($file_tmp, $file_name)){
            $upfileok=1;
        }
        else if (copy($file_tmp, $file_name)){
            $upfileok=1;
        }
    }
    elseif (copy($file_tmp, $file_name)){
        $upfileok=1;
    }
    if (!$upfileok) {
		if(file_put_contents($this->savepath.'test.txt','metinfo')){
			$lang_upfileOver4=$lang_upfileOver5;
		}
		unlink($this->savepath.'test.txt');
		$errors = array(0 => $lang_upfileOver4, 1 =>$lang_upfileOver, 2 => $lang_upfileOver1, 3 => $lang_upfileOver2, 4 => $lang_upfileOver3);
		$filear["error"]=$filear["error"]?$filear["error"]:0;
		return $this->halt($errors[$filear["error"]]);
    } else {
		@unlink($filear["tmp_name"]); //Delete temporary files
    }
  }

  /*
   * Function: get the file extension
   * $filename file name
   */
  function getext($filename) {
    if ($filename == "") {
      return ;
    }

    $ext = explode(".", $filename);
    return $this->ext = $ext[count($ext)-1];

  }

  /*
   * Function: Set the file name
   * $savename Save the name, if it is empty, then the system automatically generates a random file name
   */
  function set_savename($savename = "") {
    if ($savename == "")
     { // If you do not set the file name, then generate a random file name
      srand((double)microtime() * 1000000);
      $rnd = rand(100, 999);
      $name = date('U') + $rnd;
      $name = $name.".".$this->ext;
    } else {
      $name = $savename.".".$this->ext;
    }
    return $this->savename = $name;
  }

  /*
   * Function: error
   * $msg output
   */
  function halt($msg) {
	global $lang_upfileNotice;
	if($this->errnotype==0){
		//错误信息
		return $lang_upfileNotice.$msg;
		exit;
	}
	else{
		$this->error=1;
		$this->errorcode=$lang_upfileNotice.$msg;
		return $lang_upfileNotice.$msg;
	}
	
    
  }
  /**Thumbnail image width and height to determine treatment**/
  function setWidthHeight($width, $height, $maxwidth, $maxheight) {
    if ($width > $height) {
      if ($width > $maxwidth) {
        $difinwidth = $width / $maxwidth;
        $height = intval($height / $difinwidth);
        $width = $maxwidth;
        if ($height > $maxheight) {
          $difinheight = $height / $maxheight;
          $width = intval($width / $difinheight);
          $height = $maxheight;

        }
      } else {
        if ($height > $maxheight) {
          $difinheight = $height / $maxheight;
          $width = intval($width / $difinheight);
          $height = $maxheight;

        }
      }
    } else {
      if ($height > $maxheight) {
        $difinheight = $height / $maxheight;

        $width = intval($width / $difinheight);

        $height = $maxheight;
        if ($width > $maxwidth) {
          $difinwidth = $width / $maxwidth;

          $height = intval($height / $difinwidth);

          $width = $maxwidth;

        }
      } else {
        if ($width > $maxwidth) {
          //Rescale it.
          $difinwidth = $width / $maxwidth;

          $height = intval($height / $difinwidth);
          $width = $maxwidth;
        }
      }
    }
    $widthheightarr = array("$width", "$height");
    return $widthheightarr;
  }
    /**
  
     *
     * @access  public
     * @param   string      $img    Path
     * @param   int         $thumb_width  
     * @param   int         $thumb_height 
     * @param   strint      $path         
     * @return  mix        
     */
    function createthumb($img, $thumb_width = 0, $thumb_height = 0, $path = '', $bgcolor='')
    {
	     global $met_img_x,$met_img_y,$met_productimg_x,$lang_upfileFail4,$lang_upfileFail5,$lang_upfileFail6,$met_thumb_kind,$lang_upfileFail7,$lang_upfileFail8,$lang_upfileFail9,$lang_upfileFail10,$lang_upfileFail11;
		if(stristr(PHP_OS,"WIN")){
			$img = @iconv("utf-8","GBK",$img);
		}
		 $thumb_width=$thumb_width?$thumb_width:$met_img_x;
		 $thumb_height=$thumb_height?$thumb_height:$met_img_y;
         $gd = $this->gd_version(); 
        /* Check the original file exists and get the original file information */
        $org_info = @getimagesize($img);//返回图片大小
		//$this->halt($org_info[2]);
        if ($org_info[mime]=='image/bmp')//bmp图无法压缩
        {   
		   return $this->halt($lang_upfileFail5);
        }

        if (!$this->check_img_function($org_info[2]))
        {
		   return $this->halt($lang_upfileFail6);
        }

        $img_org = $this->img_resource($img, $org_info[2]);

        /* The original image and the thumbnail size ratio */
        $scale_org      = $org_info[0] / $org_info[1];
		$scale_tumnb    = $thumb_width / $thumb_height;
        /* Processing only the thumbnail width and height have a case for the 0, then as large as the background and thumbnail */
        if ($thumb_width == 0)
        {
            $thumb_width = $thumb_height * $scale_org;
        }
        if ($thumb_height == 0)
        {
            $thumb_height = $thumb_width / $scale_org;
        }

        /* Identifier to create thumbnails */
        if ($gd == 2)//创建一张缩略图（黑色）
        {
            $img_thumb  = imagecreatetruecolor($thumb_width, $thumb_height);
        }
        else
        {
            $img_thumb  = imagecreate($thumb_width, $thumb_height);
        }

        /* Background Color */
        if (empty($bgcolor))$bgcolor = "#FFFFFF";
        $bgcolor = trim($bgcolor,"#");
        sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
        $clr = imagecolorallocate($img_thumb, $red, $green, $blue);
        imagefilledrectangle($img_thumb, 0, 0, $thumb_width, $thumb_height, $clr);//创建背景色，默认为白色
		/*
		$img_org_size = getimagesize($img); 
		$img_org_scale=$img_org_size[1]/$img_org_size[0];
		$thumb_scale=$thumb_height/$thumb_width;
		$scale=$thumb_scale/$img_org_scale;
		$met_thumb_kind_liubai=0.75;
		$met_thumb_kind_lashen=0.9;
		if($scale>=$met_thumb_kind_lashen&&$scale<=(1/$met_thumb_kind_lashen)){
			$met_thumb_kind=1;
		}else if($scale<=$met_thumb_kind_liubai||$scale>=(1/$met_thumb_kind_liubai)){
			$met_thumb_kind=2;
		}else{
			$met_thumb_kind=3;
		}
		*/
		switch($met_thumb_kind){
			case 1:
				$dst_x=0;
				$dst_y=0;
				$lessen_width=$thumb_width;
				$lessen_height=$thumb_height;
				$scr_x=0;
				$scr_y=0;
				$scr_w=$org_info[0];
				$scr_h=$org_info[1];
			break;
			case 2:
			  if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height)
				{//上下留白
					$lessen_width  = $thumb_width;
					$lessen_height  = $thumb_width / $scale_org;
				}
				else
				{//左右留白
					/* Original image is relatively high, with a high degree of subject */
					$lessen_width  = $thumb_height * $scale_org;
					$lessen_height = $thumb_height;
				}
				$dst_x = ($thumb_width  - $lessen_width)  / 2;
				$dst_y = ($thumb_height - $lessen_height) / 2;
				$scr_x=0;
				$scr_y=0;
				$scr_w=$org_info[0];
				$scr_h=$org_info[1];
			break;
			case 3:
				$dst_x=0;
				$dst_y=0;
				$lessen_width=$thumb_width;
				$lessen_height=$thumb_height;
				  if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height)
				{//上下留白,截左右
					$scr_w  = $org_info[1] * $scale_tumnb;
					$scr_h = $org_info[1];

				}
				else
				{//左右留白,截上下
					/* Original image is relatively high, with a high degree of subject */
					$scr_w  = $org_info[0];
					$scr_h  = $org_info[0] / $scale_tumnb;
				}
				$scr_x = ($org_info[0]  - $scr_w)  / 2;
				$scr_y = ($org_info[1] - $scr_h) / 2;
			break;			
      
}
        /* Processing the original image to zoom */
        if ($gd == 2)
        {
            imagecopyresampled($img_thumb, $img_org, $dst_x, $dst_y, $scr_x, $scr_y, $lessen_width, $lessen_height, $scr_w, $scr_h);
        }
        else
        {
            imagecopyresized($img_thumb, $img_org, $dst_x, $dst_y, $scr_x, $scr_y, $lessen_width, $lessen_height, $scr_w, $scr_h);
        }
		
		$path = $path?$this->savepath.$path:$this->savepath.'thumb/';
        if (!file_exists($path)) {
        if (!$this->make_dir($path)) {
         return $this->halt($lang_upfileFail4);
         }
        }
		if(stristr(PHP_OS,"WIN")){
			$this->savename = @iconv("utf-8","GBK",$this->savename);
		}
        $thumbname = $path.$this->savename;
        /* Create */
		switch ($org_info[mime])
        {
            case 'image/gif':
				if(function_exists('imagegif')){
					$re=imagegif($img_thumb, $thumbname);
				}else{
					return $this->halt($lang_upfileFail9);
				}
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
				if(function_exists('imagejpeg')){
					$re=imagejpeg($img_thumb, $thumbname,100);
				}else{
					return $this->halt($lang_upfileFail10);
				}
                break;
            case 'image/x-png':
            case 'image/png':
				if(function_exists('imagejpeg')){
					$re=imagepng($img_thumb, $thumbname);
				}else{
					return $this->halt($lang_upfileFail11);
				}
                break;
            default:
               return $this->halt($lang_upfileFail7);
        }
		if(!$re){
			return $this->halt($lang_upfileFail8);
		}
		if(stristr(PHP_OS,"WIN")){
			$this->savename = @iconv("GBK","utf-8",$this->savename);
		}
		$thumbname=$path.$this->savename;
        imagedestroy($img_thumb);
        imagedestroy($img_org);

        return $thumbname;
    }

     /**
     * According to the source file type identifier to create an image manipulation
     *
     * @access  public
     * @param   string      $img_file   path of images
     * @param   string      $mime_type  tyoe of images
     * @return  resource    
     */
    function img_resource($img_file, $mime_type)
    {
        switch ($mime_type)
        {
            case 1:
            case 'image/gif':
                $res = imagecreatefromgif($img_file);
                break;

            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
                $res = imagecreatefromjpeg($img_file);
                break;

            case 3:
            case 'image/x-png':
            case 'image/png':
                $res = imagecreatefrompng($img_file);
                break;

            default:
                return false;
        }

        return $res;
    }	
 /**
     * Get the server version of GD
     *
     * @access      public
     * @return      int         Value may be 0，1，2
     */
    function gd_version()
    {
        static $version = -1;

        if ($version >= 0)
        {
            return $version;
        }

        if (!extension_loaded('gd'))
        {
            $version = 0;
        }
        else
        {
            // Try gd_info function
            if (PHP_VERSION >= '4.3')
            {
                if (function_exists('gd_info'))
                {
                    $ver_info = gd_info();
                    preg_match('/\d/', $ver_info['GD Version'], $match);
                    $version = $match[0];
                }
                else
                {
                    if (function_exists('imagecreatetruecolor'))
                    {
                        $version = 2;
                    }
                    elseif (function_exists('imagecreate'))
                    {
                        $version = 1;
                    }
                }
            }
            else
            {
                if (preg_match('/phpinfo/', ini_get('disable_functions')))
                {
        
                    $version = 1;
                }
                else
                {
                  // use phpinfo function
                   ob_start();
                   phpinfo(8);
                   $info = ob_get_contents();
                   ob_end_clean();
                   $info = stristr($info, 'gd version');
                   preg_match('/\d/', $info, $match);
                   $version = $match[0];
                }
             }
        }

        return $version;
     }
    /**
     * Check image processing
     *
     * @access  public
     * @param   string  $img_type   type of image
     * @return  void
     */
    function check_img_function($img_type)
    {
        switch ($img_type)
        {
            case 'image/gif':
            case 1:

                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromgif');
                }
                else
                {
                    return (imagetypes() & IMG_GIF) > 0;
                }
            break;

            case 'image/pjpeg':
            case 'image/jpeg':
            case 2:
                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromjpeg');
                }
                else
                {
                    return (imagetypes() & IMG_JPG) > 0;
                }
            break;

            case 'image/x-png':
            case 'image/png':
            case 3:
                if (PHP_VERSION >= '4.3')
                {
                     return function_exists('imagecreatefrompng');
                }
                else
                {
                    return (imagetypes() & IMG_PNG) > 0;
                }
            break;

            default:
                return false;
        }
    }	 
  
  function make_dir($folder) {
    $reval = false;
    if (!file_exists($folder)) {
      @umask(0);
      preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);
      $base = ($atmp[0][0] == '/') ? '/' : '';
      foreach($atmp[1]AS $val) {
        if ('' != $val) {
          $base .= $val;

          if ('..' == $val || '.' == $val) {
            $base .= '/';

            continue;
          }
        } else {
          continue;
        }

        $base .= '/';

        if (!file_exists($base)) {
          if (@mkdir($base, 0777)) {
            //@chmod($base, 0777);
            $reval = true;
          }
        }
      }
    } else {
      $reval = is_dir($folder);
    }
    clearstatcache();
    return $reval;
  }
  function get_error(){
	$geterror=$this->error;
	$this->error=0;
	return $geterror;
  }
  function get_errorcode(){
	return $this->errorcode;
  }

}

?>