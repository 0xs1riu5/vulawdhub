<?php  if(!defined('DEDEINC')) exit('dedecms');
/**
 * 文件处理小助手
 *
 * @version        $Id: file.helper.php 1 2010-07-05 11:43:09Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

$g_ftpLink = false;

/**
 *  使用FTP方法创建文件夹目录
 *
 * @param     string  $truepath  真实目标地址
 * @param     string  $mmode  创建模式
 * @param     string  $isMkdir  是否创建目录
 * @return    bool
 */
if ( ! function_exists('FtpMkdir'))
{
    function FtpMkdir($truepath,$mmode,$isMkdir=true)
    {
        global $cfg_basedir,$cfg_ftp_root,$g_ftpLink;
        OpenFtp();
        $ftproot = preg_replace('/'.$cfg_ftp_root.'$/', '', $cfg_basedir);
        $mdir = preg_replace('/^'.$ftproot.'/', '', $truepath);
        if($isMkdir)
        {
            ftp_mkdir($g_ftpLink, $mdir);
        }
        return ftp_site($g_ftpLink, "chmod $mmode $mdir");
    }
}

/**
 *  改变目录模式
 *
 * @param     string  $truepath  真实地址
 * @param     string  $mmode   模式
 * @return    bool
 */
if ( ! function_exists('FtpChmod'))
{
    function FtpChmod($truepath, $mmode)
    {
        return FtpMkdir($truepath, $mmode, false);
    }
}


/**
 *  打开FTP链接,打开之前确保已经设置好了FTP相关的配置信息
 *
 * @return    void
 */
if ( ! function_exists('OpenFtp'))
{
    function OpenFtp()
    {
        global $cfg_basedir,$cfg_ftp_host,$cfg_ftp_port, $cfg_ftp_user,$cfg_ftp_pwd,$cfg_ftp_root,$g_ftpLink;
        if(!$g_ftpLink)
        {
            if($cfg_ftp_host=='')
            {
                echo "由于你的站点的PHP配置存在限制，程序尝试用FTP进行目录操作，你必须在后台指定FTP相关的变量！";
                exit();
            }
            $g_ftpLink = ftp_connect($cfg_ftp_host,$cfg_ftp_port);
            if(!$g_ftpLink)
            {
                echo "连接FTP失败！";
                exit();
            }
            if(!ftp_login($g_ftpLink,$cfg_ftp_user,$cfg_ftp_pwd))
            {
                echo "登陆FTP失败！";
                exit();
            }
        }
    }
}


/**
 *  关闭FTP链接
 *
 * @return    void
 */
if ( ! function_exists('CloseFtp'))
{
    function CloseFtp()
    {
        global $g_ftpLink;
        if($g_ftpLink)
        {
            @ftp_quit($g_ftpLink);
        }
    }
}


/**
 *  创建所有目录
 *
 * @param     string  $truepath  真实地址
 * @param     string  $mmode   模式
 * @return    bool
 */
if ( ! function_exists('MkdirAll'))
{
    function MkdirAll($truepath,$mmode)
    {
        global $cfg_ftp_mkdir,$isSafeMode,$cfg_dir_purview;
        if( $isSafeMode || $cfg_ftp_mkdir=='Y' )
        {
            return FtpMkdir($truepath, $mmode);
        }
        else
        {
            if(!file_exists($truepath))
            {
                mkdir($truepath, $cfg_dir_purview);
                chmod($truepath, $cfg_dir_purview);
                return true;
            }
            else
            {
                return true;
            }
        }
    }
}

/**
 *  更改所有模式
 *
 * @access    public
 * @param     string  $truepath  文件路径
 * @param     string  $mmode   模式
 * @return    string
 */
if ( ! function_exists('ChmodAll'))
{
    function ChmodAll($truepath,$mmode)
    {
        global $cfg_ftp_mkdir,$isSafeMode;
        if( $isSafeMode || $cfg_ftp_mkdir=='Y' )
        {
            return FtpChmod($truepath, $mmode);
        }
        else
        {
            return chmod($truepath, '0'.$mmode);
        }
    }
}


/**
 *  创建目录
 *
 * @param     string  $spath  创建的文件夹
 * @return    bool
 */
if ( ! function_exists('CreateDir'))
{
    function CreateDir($spath)
    {
        if(!function_exists('SpCreateDir'))
        {
            require_once(DEDEINC.'/inc/inc_fun_funAdmin.php');
        }
        return SpCreateDir($spath);
    }
}

/**
 *  写文件
 *
 * @access    public
 * @param     string  $file  文件名
 * @param     string  $content  内容
 * @param     int  $flag   标识
 * @return    string
 */
if ( ! function_exists('PutFile'))
{
    function PutFile($file, $content, $flag = 0)
    {
        $pathinfo = pathinfo ( $file );
        if (! empty ( $pathinfo ['dirname'] ))
        {
            if (file_exists ( $pathinfo ['dirname'] ) === FALSE)
            {
                if (@mkdir ( $pathinfo ['dirname'], 0777, TRUE ) === FALSE)
                {
                    return FALSE;
                }
            }
        }
        if ($flag === FILE_APPEND)
        {
            return @file_put_contents ( $file, $content, FILE_APPEND );
        }
        else
        {
            return @file_put_contents ( $file, $content, LOCK_EX );
        }
    }
}

/**
 *  用递归方式删除目录
 *
 * @access    public
 * @param     string    $file   目录文件
 * @return    string
 */
if ( ! function_exists('RmRecurse'))
{
    function RmRecurse($file)
    {
        if (is_dir($file) && !is_link($file))
        {
            foreach(glob($file . '/*') as $sf)
            {
                if (!RmRecurse($sf))
                {
                    return false;
                }
            }
            return @rmdir($file);
        } else {
            return @unlink($file);
        }
    }
}


