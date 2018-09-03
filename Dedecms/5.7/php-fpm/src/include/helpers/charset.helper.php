<?php  if(!defined('DEDEINC')) exit('dedecms');
/**
 * DedeCMS中用到的字符编码转换的小助手函数
 *
 * @version        $Id: charset.helper.php 1 2010-07-05 11:43:09Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */


$UC2GBTABLE = $CODETABLE = $BIG5_DATA = $GB_DATA = '';
$GbkUniDic = null;

/**
 *  UTF-8 转GB编码
 *
 * @access    public
 * @param     string  $utfstr  需要转换的字符串
 * @return    string
 */
if ( ! function_exists('utf82gb'))
{
    function utf82gb($utfstr)
    {
        if(function_exists('iconv'))
        {
            return iconv('utf-8','gbk//ignore',$utfstr);
        }
        global $UC2GBTABLE;
        $okstr = "";
        if(trim($utfstr)=="")
        {
            return $utfstr;
        }
        if(empty($UC2GBTABLE))
        {
            $filename = DEDEINC."/data/gb2312-utf8.dat";
            $fp = fopen($filename,"r");
            while($l = fgets($fp,15))
            {
                $UC2GBTABLE[hexdec(substr($l, 7, 6))] = hexdec(substr($l, 0, 6));
            }
            fclose($fp);
        }
        $okstr = "";
        $ulen = strlen($utfstr);
        for($i=0;$i<$ulen;$i++)
        {
            $c = $utfstr[$i];
            $cb = decbin(ord($utfstr[$i]));
            if(strlen($cb)==8)
            {
                $csize = strpos(decbin(ord($cb)),"0");
                for($j=0;$j < $csize;$j++)
                {
                    $i++; $c .= $utfstr[$i];
                }
                $c = utf82u($c);
                if(isset($UC2GBTABLE[$c]))
                {
                    $c = dechex($UC2GBTABLE[$c]+0x8080);
                    $okstr .= chr(hexdec($c[0].$c[1])).chr(hexdec($c[2].$c[3]));
                }
                else
                {
                    $okstr .= "&#".$c.";";
                }
            }
            else
            {
                $okstr .= $c;
            }
        }
        $okstr = trim($okstr);
        return $okstr;
    }
}

/**
 *  GB转UTF-8编码
 *
 * @access    public
 * @param     string  $gbstr  gbk的字符串
 * @return    string
 */
if ( ! function_exists('gb2utf8'))
{
    function gb2utf8($gbstr)
    {
        if(function_exists('iconv'))
        {
            return iconv('gbk','utf-8//ignore',$gbstr);
        }
        global $CODETABLE;
        if(trim($gbstr)=="")
        {
            return $gbstr;
        }
        if(empty($CODETABLE))
        {
            $filename = DEDEINC."/data/gb2312-utf8.dat";
            $fp = fopen($filename,"r");
            while ($l = fgets($fp,15))
            {
                $CODETABLE[hexdec(substr($l, 0, 6))] = substr($l, 7, 6);
            }
            fclose($fp);
        }
        $ret = "";
        $utf8 = "";
        while ($gbstr != '')
        {
            if (ord(substr($gbstr, 0, 1)) > 0x80)
            {
                $thisW = substr($gbstr, 0, 2);
                $gbstr = substr($gbstr, 2, strlen($gbstr));
                $utf8 = "";
                @$utf8 = u2utf8(hexdec($CODETABLE[hexdec(bin2hex($thisW)) - 0x8080]));
                if($utf8!="")
                {
                    for ($i = 0;$i < strlen($utf8);$i += 3)
                    $ret .= chr(substr($utf8, $i, 3));
                }
            }
            else
            {
                $ret .= substr($gbstr, 0, 1);
                $gbstr = substr($gbstr, 1, strlen($gbstr));
            }
        }
        return $ret;
    }
}

/**
 *  Unicode转utf8
 *
 * @access    public
 * @param     string  $c  Unicode的字符串内容
 * @return    string
 */
if ( ! function_exists('u2utf8'))
{
    function u2utf8($c)
    {
        for ($i = 0;$i < count($c);$i++)
        {
            $str = "";
        }
        if ($c < 0x80)
        {
            $str .= $c;
        }
        else if ($c < 0x800)
        {
            $str .= (0xC0 | $c >> 6);
            $str .= (0x80 | $c & 0x3F);
        }
        else if ($c < 0x10000)
        {
            $str .= (0xE0 | $c >> 12);
            $str .= (0x80 | $c >> 6 & 0x3F);
            $str .= (0x80 | $c & 0x3F);
        }
        else if ($c < 0x200000)
        {
            $str .= (0xF0 | $c >> 18);
            $str .= (0x80 | $c >> 12 & 0x3F);
            $str .= (0x80 | $c >> 6 & 0x3F);
            $str .= (0x80 | $c & 0x3F);
        }
        return $str;
    }
}

/**
 *  utf8转Unicode
 *
 * @access    public
 * @param     string  $c  UTF-8的字符串信息
 * @return    string
 */
if ( ! function_exists('utf82u'))
{
    function utf82u($c)
    {
        switch(strlen($c))
        {
            case 1:
                return ord($c);
            case 2:
                $n = (ord($c[0]) & 0x3f) << 6;
                $n += ord($c[1]) & 0x3f;
                return $n;
            case 3:
                $n = (ord($c[0]) & 0x1f) << 12;
                $n += (ord($c[1]) & 0x3f) << 6;
                $n += ord($c[2]) & 0x3f;
                return $n;
            case 4:
                $n = (ord($c[0]) & 0x0f) << 18;
                $n += (ord($c[1]) & 0x3f) << 12;
                $n += (ord($c[2]) & 0x3f) << 6;
                $n += ord($c[3]) & 0x3f;
                return $n;
        }
    }
}

/**
 *  Big5码转换成GB码
 *
 * @access    public
 * @param     string   $Text  字符串内容
 * @return    string
 */
if ( ! function_exists('big52gb'))
{
    function big52gb($Text)
    {
        if(function_exists('iconv'))
        {
            return iconv('big5','gbk//ignore',$Text);
        }
        global $BIG5_DATA;
        if(empty($BIG5_DATA))
        {
            $filename = DEDEINC."/data/big5-gb.dat";
            $fp = fopen($filename, "rb");
            $BIG5_DATA = fread($fp,filesize($filename));
            fclose($fp);
        }
        $max = strlen($Text)-1;
        for($i=0;$i<$max;$i++)
        {
            $h = ord($Text[$i]);
            if($h>=0x80)
            {
                $l = ord($Text[$i+1]);
                if($h==161 && $l==64)
                {
                    $gbstr = "　";
                }
                else
                {
                    $p = ($h-160)*510+($l-1)*2;
                    $gbstr = $BIG5_DATA[$p].$BIG5_DATA[$p+1];
                }
                $Text[$i] = $gbstr[0];
                $Text[$i+1] = $gbstr[1];
                $i++;
            }
        }
        return $Text;
    }
}

/**
 *  GB码转换成Big5码
 *
 * @access    public
 * @param     string  $Text 字符串内容
 * @return    string
 */
if ( ! function_exists('gb2big5'))
{
    function gb2big5($Text)
    {
        if(function_exists('iconv'))
        {
            return iconv('gbk','big5//ignore',$Text);
        }
        global $GB_DATA;
        if(empty($GB_DATA))
        {
            $filename = DEDEINC."/data/gb-big5.dat";
            $fp = fopen($filename, "rb");
            $gb = fread($fp,filesize($filename));
            fclose($fp);
        }
        $max = strlen($Text)-1;
        for($i=0;$i<$max;$i++)
        {
            $h = ord($Text[$i]);
            if($h>=0x80)
            {
                $l = ord($Text[$i+1]);
                if($h==161 && $l==64)
                {
                    $big = "　";
                }
                else
                {
                    $p = ($h-160)*510+($l-1)*2;
                    $big = $GB_DATA[$p].$GB_DATA[$p+1];
                }
                $Text[$i] = $big[0];
                $Text[$i+1] = $big[1];
                $i++;
            }
        }
        return $Text;
    }
}

/**
 *  unicode url编码转gbk编码函数
 *
 * @access    public
 * @param     string  $str  转换的内容
 * @return    string
 */
if ( ! function_exists('UnicodeUrl2Gbk'))
{
    function UnicodeUrl2Gbk($str)
    {
        //载入对照词典
        if(!isset($GLOBALS['GbkUniDic']))
        {
            $fp = fopen(DEDEINC.'/data/gbk-unicode.dat','rb');
            while(!feof($fp))
            {
                $GLOBALS['GbkUniDic'][bin2hex(fread($fp,2))] = fread($fp,2);
            }
            fclose($fp);
        }

        //处理字符串
        $str = str_replace('$#$','+',$str);
        $glen = strlen($str);
        $okstr = "";
        for($i=0; $i < $glen; $i++)
        {
            if($glen-$i > 4)
            {
                if($str[$i]=='%' && $str[$i+1]=='u')
                {
                    $uni = strtolower(substr($str,$i+2,4));
                    $i = $i+5;
                    if(isset($GLOBALS['GbkUniDic'][$uni]))
                    {
                        $okstr .= $GLOBALS['GbkUniDic'][$uni];
                    }
                    else
                    {
                        $okstr .= "&#".hexdec('0x'.$uni).";";
                    }
                }
                else
                {
                    $okstr .= $str[$i];
                }
            }
            else
            {
                $okstr .= $str[$i];
            }
        }
        return $okstr;
    }
}

/**
 *  自动转换字符集 支持数组转换
 *
 * @access    public
 * @param     string  $str  转换的内容
 * @return    string
 */
if ( ! function_exists('AutoCharset'))
{
    function AutoCharset($fContents, $from='gbk', $to='utf-8')
    {
        $from   =  strtoupper($from)=='UTF8'? 'utf-8' : $from;
        $to       =  strtoupper($to)=='UTF8'? 'utf-8' : $to;
        if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
            //如果编码相同或者非字符串标量则不转换
            return $fContents;
        }
        if(is_string($fContents) ) 
        {
            if(function_exists('mb_convert_encoding'))
            {
                return mb_convert_encoding ($fContents, $to, $from);
            } elseif (function_exists('iconv'))
            {
                return iconv($from, $to, $fContents);
            } else {
                return $fContents;
            }
        }
        elseif(is_array($fContents))
        {
            foreach ( $fContents as $key => $val ) 
            {
                $_key =     AutoCharset($key,$from,$to);
                $fContents[$_key] = AutoCharset($val,$from,$to);
                if($key != $_key )
                    unset($fContents[$key]);
            }
            return $fContents;
        }
        else{
            return $fContents;
        }
    }
}
