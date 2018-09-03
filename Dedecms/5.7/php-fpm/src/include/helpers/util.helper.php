<?php  if(!defined('DEDEINC')) exit('dedecms');
/**
 * 核心小助手
 *
 * @version        $Id: util.helper.php 4 19:20 2010年7月6日Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  获得当前的脚本网址
 *
 * @return    string
 */
if ( ! function_exists('GetCurUrl'))
{
    function GetCurUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $scriptName = $_SERVER["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $_SERVER["PHP_SELF"];
            if(empty($_SERVER["QUERY_STRING"]))
            {
                $nowurl = $scriptName;
            }
            else
            {
                $nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
            }
        }
        return $nowurl;
    }
}

/**
 *  获取用户真实地址
 *
 * @return    string  返回用户ip
 */
if ( ! function_exists('GetIP'))
{
    function GetIP()
    {
        static $realip = NULL;
        if ($realip !== NULL)
        {
            return $realip;
        }
        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第x个非unknown的有效IP字符? */
                foreach ($arr as $ip)
                {
                    $ip = trim($ip);
                    if ($ip != 'unknown')
                    {
                        $realip = $ip;
                        break;
                    }
                }
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                if (isset($_SERVER['REMOTE_ADDR']))
                {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
                else
                {
                    $realip = '0.0.0.0';
                }
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $realip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }
}

/**
 *  获取编辑器
 *
 * @param     string  $fname  表单名称
 * @param     string  $fvalue 如果表单中有默认值,则填入默认值
 * @param     string  $nheight 高度
 * @param     string  $etype   编辑器类型
 * @param     string  $gtype   获取类型
 * @param     string  $isfullpage   是否全屏
 * @return    string
 */
if ( ! function_exists('GetEditor'))
{
    function GetEditor($fname, $fvalue, $nheight="350", $etype="Basic", $gtype="print", $isfullpage="FALSE")
    {
        if(!function_exists('SpGetEditor'))
        {
            require_once(DEDEINC."/inc/inc_fun_funAdmin.php");
        }
        return SpGetEditor($fname, $fvalue, $nheight, $etype, $gtype, $isfullpage);
    }
}

/**
 *  获取模板
 *
 * @param     string  $filename 文件名称
 * @return    string
 */
if ( ! function_exists('GetTemplets'))
{
    function GetTemplets($filename)
    {
        if(file_exists($filename))
        {
            $fp = fopen($filename,"r");
            $rstr = fread($fp,filesize($filename));
            fclose($fp);
            return $rstr;
        }
        else
        {
            return '';
        }
    }
}

/**
 *  获取系统模板
 *
 * @param     $filename  模板文件
 * @return    string
 */
if ( ! function_exists('GetSysTemplets'))
{
    function GetSysTemplets($filename)
    {
        return GetTemplets($GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'].'/system/'.$filename);
    }
}

/**
 *  获取新闻提示
 *
 * @return    void
 */
if ( ! function_exists('GetNewInfo'))
{
    function GetNewInfo()
    {
        if(!function_exists('SpGetNewInfo'))
        {
            require_once(DEDEINC."/inc/inc_fun_funAdmin.php");
        }
        return SpGetNewInfo();
    }
}

/**
 *  生成一个随机字符
 *
 * @access    public
 * @param     string  $ddnum
 * @return    string
 */
if ( ! function_exists('dd2char'))
{
    function dd2char($ddnum)
    {
        $ddnum = strval($ddnum);
        $slen = strlen($ddnum);
        $okdd = '';
        $nn = '';
        for($i=0;$i<$slen;$i++)
        {
            if(isset($ddnum[$i+1]))
            {
                $n = $ddnum[$i].$ddnum[$i+1];
                if( ($n>96 && $n<123) || ($n>64 && $n<91) )
                {
                    $okdd .= chr($n);
                    $i++;
                }
                else
                {
                    $okdd .= $ddnum[$i];
                }
            }
            else
            {
                $okdd .= $ddnum[$i];
            }
        }
        return $okdd;
    }
}

/**
 *  json_encode兼容函数
 *
 * @access    public
 * @param     string  $data
 * @return    string
 */
if (!function_exists('json_encode')) {
     function format_json_value(&$value)
    {
        if(is_bool($value)) {
            $value = $value?'TRUE':'FALSE';
        } else if (is_int($value)) {
            $value = intval($value);
        } else if (is_float($value)) {
            $value = floatval($value);
        } else if (defined($value) && $value === NULL) {
            $value = strval(constant($value));
        } else if (is_string($value)) {
            $value = '"'.addslashes($value).'"';
        }
        return $value;
    }

    function json_encode($data)
    {
        if(is_object($data)) {
            //对象转换成数组
            $data = get_object_vars($data);
        }else if(!is_array($data)) {
            // 普通格式直接输出
            return format_json_value($data);
        }
        // 判断是否关联数组
        if(empty($data) || is_numeric(implode('',array_keys($data)))) {
            $assoc  =  FALSE;
        }else {
            $assoc  =  TRUE;
        }
        // 组装 Json字符串
        $json = $assoc ? '{' : '[' ;
        foreach($data as $key=>$val) {
            if(!is_NULL($val)) {
                if($assoc) {
                    $json .= "\"$key\":".json_encode($val).",";
                }else {
                    $json .= json_encode($val).",";
                }
            }
        }
        if(strlen($json)>1) {// 加上判断 防止空数组
            $json  = substr($json,0,-1);
        }
        $json .= $assoc ? '}' : ']' ;
        return $json;
    }
}

/**
 *  json_decode兼容函数
 *
 * @access    public
 * @param     string  $json  json数据
 * @param     string  $assoc  当该参数为 TRUE 时，将返回 array 而非 object
 * @return    string
 */
if (!function_exists('json_decode')) {
    function json_decode($json, $assoc=FALSE)
    {
        // 目前不支持二维数组或对象
        $begin  =  substr($json,0,1) ;
        if(!in_array($begin,array('{','[')))
            // 不是对象或者数组直接返回
            return $json;
        $parse = substr($json,1,-1);
        $data  = explode(',',$parse);
        if($flag = $begin =='{' ) {
            // 转换成PHP对象
            $result   = new stdClass();
            foreach($data as $val) {
                $item    = explode(':',$val);
                $key =  substr($item[0],1,-1);
                $result->$key = json_decode($item[1],$assoc);
            }
            if($assoc)
                $result   = get_object_vars($result);
        }else {
            // 转换成PHP数组
            $result   = array();
            foreach($data as $val)
                $result[]  =  json_decode($val,$assoc);
        }
        return $result;
    }
}
