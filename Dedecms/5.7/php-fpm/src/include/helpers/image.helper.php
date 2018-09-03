<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 图像处理相关函数
 *
 * @version        $Id: image.func.php 1 15:59 2010年7月5日Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
// ------------------------------------------------------------------------

/**
 *  缩图片自动生成函数，来源支持bmp、gif、jpg、png
 *  但生成的小图只用jpg或png格式
 *
 * @access    public
 * @param     string  $srcFile  图片路径
 * @param     string  $toW  转换到的宽度
 * @param     string  $toH  转换到的高度
 * @param     string  $toFile  输出文件到
 * @return    string
 */
if ( ! function_exists('ImageResize'))
{
    function ImageResize($srcFile, $toW, $toH, $toFile="")
    {
        global $cfg_photo_type;
        if($toFile=='') $toFile = $srcFile;
        $info = '';
        $srcInfo = GetImageSize($srcFile,$info);
        switch ($srcInfo[2])
        {
            case 1:
                if(!$cfg_photo_type['gif']) return FALSE;
                $im = imagecreatefromgif($srcFile);
                break;
            case 2:
                if(!$cfg_photo_type['jpeg']) return FALSE;
                $im = imagecreatefromjpeg($srcFile);
                break;
            case 3:
                if(!$cfg_photo_type['png']) return FALSE;
                $im = imagecreatefrompng($srcFile);
                break;
            case 6:
                if(!$cfg_photo_type['bmp']) return FALSE;
                $im = imagecreatefromwbmp($srcFile);
                break;
        }
        $srcW=ImageSX($im);
        $srcH=ImageSY($im);
        if($srcW<=$toW && $srcH<=$toH ) return TRUE;
        $toWH=$toW/$toH;
        $srcWH=$srcW/$srcH;
        if($toWH<=$srcWH)
        {
            $ftoW=$toW;
            $ftoH=$ftoW*($srcH/$srcW);
        }
        else
        {
            $ftoH=$toH;
            $ftoW=$ftoH*($srcW/$srcH);
        }
        if($srcW>$toW||$srcH>$toH)
        {
            if(function_exists("imagecreateTRUEcolor"))
            {
                @$ni = imagecreateTRUEcolor($ftoW,$ftoH);
                if($ni)
                {
                    imagecopyresampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
                }
                else
                {
                    $ni=imagecreate($ftoW,$ftoH);
                    imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
                }
            }
            else
            {
                $ni=imagecreate($ftoW,$ftoH);
                imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
            }
            switch ($srcInfo[2])
            {
                case 1:
                    imagegif($ni,$toFile);
                    break;
                case 2:
                    imagejpeg($ni,$toFile,85);
                    break;
                case 3:
                    imagepng($ni,$toFile);
                    break;
                case 6:
                    imagebmp($ni,$toFile);
                    break;
                default:
                    return FALSE;
            }
            imagedestroy($ni);
        }
        imagedestroy($im);
        return TRUE;
    }
}
 


/**
 *  获得GD的版本
 *
 * @access    public
 * @return    int
 */
if ( ! function_exists('gdversion'))
{
    function gdversion()
    {
        //没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
        if(!function_exists('phpinfo'))
        {
            if(function_exists('imagecreate'))
            {
                return '2.0';
            }
            else
            {
                return 0;
            }
        }
        else
        {
            ob_start();
            phpinfo(8);
            $module_info = ob_get_contents();
            ob_end_clean();
            if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches))
            {
                $gdversion_h = $matches[1];
            }
            else
            {
                $gdversion_h = 0;
            }
            return $gdversion_h;
        }
    }
}


/**
 *  图片自动加水印函数
 *
 * @access    public
 * @param     string  $srcFile  图片源文件
 * @param     string  $fromGo  位置
 * @return    string
 */
if ( ! function_exists('WaterImg'))
{
    function WaterImg($srcFile, $fromGo='up')
    {
        include(DEDEDATA.'/mark/inc_photowatermark_config.php');
        require_once(DEDEINC.'/image.class.php');
        if( isset($GLOBALS['needwatermark']) )
        {
            $photo_markup = $photo_markdown = empty($GLOBALS['needwatermark']) ? '0' : '1';
        }
        if($photo_markup != '1' || ($fromGo=='collect' && $photo_markdown!='1') )
        {
            return;
        }
        $info = '';
        $srcInfo = @getimagesize($srcFile,$info);
        $srcFile_w    = $srcInfo[0];
        $srcFile_h    = $srcInfo[1];
            
        if($srcFile_w < $photo_wwidth || $srcFile_h < $photo_wheight)
        {
            return;
        }
        if($fromGo=='up' && $photo_markup=='0')
        {
            return;
        }
        if($fromGo=='down' && $photo_markdown=='0')
        {
            return;
        }
         $TRUEMarkimg = DEDEDATA.'/mark/'.$photo_markimg;
        if(!file_exists($TRUEMarkimg) || empty($photo_markimg))
        {
            $TRUEMarkimg = "";
        }
        if($photo_waterpos == 0)
        {
            $photo_waterpos = rand(1, 9);
        }
        $cfg_watermarktext = array();
        if($photo_marktype == '2')
        {
            if(file_exists(DEDEDATA.'/mark/simhei.ttf'))
            {
                $cfg_watermarktext['fontpath'] =  DEDEDATA.'/mark/simhei.ttf';
            }
            else
            {
                return ;
            }
        }
        $cfg_watermarktext['text'] = $photo_watertext;
        $cfg_watermarktext['size'] = $photo_fontsize;
        $cfg_watermarktext['angle'] = '0';
        $cfg_watermarktext['color'] = '255,255,255';
        $cfg_watermarktext['shadowx'] = '0';
        $cfg_watermarktext['shadowy'] = '0';
        $cfg_watermarktext['shadowcolor'] = '0,0,0';
        $photo_marktrans = 85;
        $img = new image($srcFile,0, $cfg_watermarktext, $photo_waterpos, $photo_diaphaneity, $photo_wheight, $photo_wwidth, $photo_marktype, $photo_marktrans,$TRUEMarkimg);
        $img->watermark(0);
    }
}

/**
 *  会对空白地方填充满
 *
 * @access    public
 * @param     string  $srcFile  图片路径
 * @param     string  $toW  转换到的宽度
 * @param     string  $toH  转换到的高度
 * @param     string  $toFile  输出文件到
 * @param     string  $issave  是否保存
 * @return    bool
 */
if ( ! function_exists('ImageResizeNew'))
{
    function ImageResizeNew($srcFile, $toW, $toH, $toFile='', $issave=TRUE)
    {
        global $cfg_photo_type, $cfg_ddimg_bgcolor;
        if($toFile=='') $toFile = $srcFile;
        $info = '';
        $srcInfo = GetImageSize($srcFile,$info);
        switch ($srcInfo[2])
        {
            case 1:
                if(!$cfg_photo_type['gif']) return FALSE;
                $img = imagecreatefromgif($srcFile);
                break;
            case 2:
                if(!$cfg_photo_type['jpeg']) return FALSE;
                $img = imagecreatefromjpeg($srcFile);
                break;
            case 3:
                if(!$cfg_photo_type['png']) return FALSE;
                $img = imagecreatefrompng($srcFile);
                break;
            case 6:
                if(!$cfg_photo_type['bmp']) return FALSE;
                $img = imagecreatefromwbmp($srcFile);
                break;
        }

        $width = imageSX($img);
        $height = imageSY($img);

        if (!$width || !$height) {
            return FALSE;
        }

        $target_width = $toW;
        $target_height = $toH;
        $target_ratio = $target_width / $target_height;

        $img_ratio = $width / $height;

        if ($target_ratio > $img_ratio) {
            $new_height = $target_height;
            $new_width = $img_ratio * $target_height;
        } else {
            $new_height = $target_width / $img_ratio;
            $new_width = $target_width;
        }

        if ($new_height > $target_height) {
            $new_height = $target_height;
        }
        if ($new_width > $target_width) {
            $new_height = $target_width;
        }

        $new_img = ImageCreateTrueColor($target_width, $target_height);
        
        if($cfg_ddimg_bgcolor==0) $bgcolor = ImageColorAllocate($new_img, 0xff, 0xff, 0xff);
        else $bgcolor = 0;
        
        if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, $bgcolor))
        {
            return FALSE;
        }

        if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height))
        {
            return FALSE;
        }
        
        //保存为目标文件
        if($issave)
        {
            switch ($srcInfo[2])
            {
                case 1:
                    imagegif($new_img, $toFile);
                    break;
                case 2:
                    imagejpeg($new_img, $toFile,100);
                    break;
                case 3:
                    imagepng($new_img, $toFile);
                    break;
                case 6:
                    imagebmp($new_img, $toFile);
                    break;
                default:
                    return FALSE;
            }
        }
        //不保存
        else
        {
            switch ($srcInfo[2])
            {
                case 1:
                    imagegif($new_img);
                    break;
                case 2:
                    imagejpeg($new_img);
                    break;
                case 3:
                    imagepng($new_img);
                    break;
                case 6:
                    imagebmp($new_img);
                    break;
                default:
                    return FALSE;
            }
        }
        imagedestroy($new_img);
        imagedestroy($img);
        return TRUE;
    }
}
