<?php
/**
 * 处理外部请求变量的类
 *
 * 禁止此文件以外的文件出现 $_POST、$_GET、$_FILES变量及eval函数(用request::myeval )
 * 以便于对主要黑客攻击进行防范
 *
 * @version        $Id: request.class.php 1 12:03 2010-10-28 tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
// REQUEST常量,用于判断是否启用REQUEST类
define('DEDEREQUEST', TRUE);

//简化 cls_request::item() 函数
function Request($key, $df='')
{
    $GLOBALS['request'] = isset($GLOBALS['request'])? $GLOBALS['request'] : new Request;
    if (!$GLOBALS['request']->isinit)
    {
        $GLOBALS['request']->Init();
    }
    return $GLOBALS['request']->Item($key, $df);
}
class Request
{

    var $isinit = false;
    //用户的cookie
    var $cookies = array();

    //把GET、POST的变量合并一块，相当于 _REQUEST
    var $forms = array();
    
    //_GET 变量
    var $gets = array();

    //_POST 变量
    var $posts = array();

    //用户的请求模式 GET 或 POST
    var $request_type = 'GET';

    //文件变量
    var $files = array();
    
    //严禁保存的文件名
    var $filter_filename = '/\.(php|pl|sh|js)$/i';

   /**
    * 初始化用户请求
    * 对于 post、get 的数据，会转到 selfforms 数组， 并删除原来数组
    * 对于 cookie 的数据，会转到 cookies 数组，但不删除原来数组
    */
    function Init()
    {
        global $_POST,$_GET;
        //处理post、get
        $formarr = array('p' => $_POST, 'g' => $_GET);
        foreach($formarr as $_k => $_r)
        {
            if( count($_r) > 0 )
            {
                foreach($_r as $k=>$v)
                {
                    if( preg_match('/^cfg_(.*?)/i', $k) )
                    {
                        continue;
                    }
                    $this->forms[$k] = $v;
                    if( $_k=='p' )
                    {
                        $this->posts[$k] = $v;
                    } else {
                        $this->gets[$k] = $v;
                    }
                }
            }
        }
        unset($_POST);
        unset($_GET);
        unset($_REQUEST);
        
        //处理cookie
        if( count($_COOKIE) > 0 )
        {
            foreach($_COOKIE as $k=>$v)
            {
                if( preg_match('/^config/i', $k) )
                {
                    continue;
                }
                $this->cookies[$k] = $v;
            }
        }
        //unset($_POST, $_GET);
        
        //上传的文件处理
        if( isset($_FILES) && count($_FILES) > 0 )
        {
            $this->filter_files($_FILES);
        }
        $this->isinit = TRUE;
        
        //global变量
        //self::$forms['_global'] = $GLOBALS;
    }

   /**
    * 把 eval 重命名为 myeval
    */
    function MyEval( $phpcode )
    {
        return eval( $phpcode );
    }

   /**
    * 获得指定表单值
    */
    function Item( $formname, $defaultvalue = '' )
    {
        return isset($this->forms[$formname]) ? $this->forms[$formname] :  $defaultvalue;
    }

   /**
    * 获得指定临时文件名值
    */
    function Upfile( $formname, $defaultvalue = '' )
    {
        return isset($this->files[$formname]['tmp_name']) ? $this->files[$formname]['tmp_name'] :  $defaultvalue;
    }

   /**
    * 过滤文件相关
    */
    function FilterFiles( &$files )
    {
        foreach($files as $k=>$v)
        {
            $this->$files[$k] = $v;
        }
        unset($_FILES);
    }

   /**
    * 移动上传的文件
    */
    function MoveUploadFile( $formname, $filename, $filetype = '' )
    {
        if( $this->is_upload_file( $formname ) )
        {
            if( preg_match($this->filter_filename, $filename) )
            {
                return FALSE;
            }
            else
            {
                return move_uploaded_file($this->files[$formname]['tmp_name'], $filename);
            }
        }
    }

   /**
    * 获得文件的扩展名
    */
    function GetShortname( $formname )
    {
        $filetype = strtolower(isset($this->files[$formname]['type']) ? $this->files[$formname]['type'] : '');
        $shortname = '';
        switch($filetype)
        {
            case 'image/jpeg':
                $shortname = 'jpg';
                break;
            case 'image/pjpeg':
                $shortname = 'jpg';
                break;
            case 'image/gif':
                $shortname = 'gif';
                break;
            case 'image/png':
                $shortname = 'png';
                break;
            case 'image/xpng':
                $shortname = 'png';
                break;
            case 'image/wbmp':
                $shortname = 'bmp';
                break;
            default:
                $filename = isset($this->files[$formname]['name']) ? $this->files[$formname]['name'] : '';
                if( preg_match("/\./", $filename) )
                {
                    $fs = explode('.', $filename);
                    $shortname = strtolower($fs[ count($fs)-1 ]);
                }
                break;
        }
        return $shortname;
    }

   /**
    * 获得指定文件表单的文件详细信息
    */
    function GetFileInfo( $formname, $item = '' )
    {
        if( !isset( $this->files[$formname]['tmp_name'] ) )
        {
            return FALSE;
        }
        else
        {
            if($item=='')
            {
                return $this->files[$formname];
            }
            else
            {
                return (isset($this->files[$formname][$item]) ? $this->files[$formname][$item] : '');
            }
        }
    }

   /**
    * 判断是否存在上传的文件
    */
    function IsUploadFile( $formname )
    {
        if( !isset( $this->files[$formname]['tmp_name'] ) )
        {
            return FALSE;
        }
        else
        {
            return is_uploaded_file( $this->files[$formname]['tmp_name'] );
        }
    }
    
    /**
     * 检查文件后缀是否为指定值
     *
     * @param  string  $subfix
     * @return boolean
     */
     function CheckSubfix($formname, $subfix = 'csv')
    {
        if( $this->get_shortname( $formname ) != $subfix)
        {
            return FALSE;
        }
        return TRUE;
    }
}