<?php  if(!defined('DEDEINC')) exit('dedecms');
/**
 * 过滤小助手
 *
 * @version        $Id: time.filter.php 1 2010-07-05 11:43:09Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  去除html中不规则内容字符

 *
 * @access    public
 * @param     string  $str  需要处理的字符串
 * @param     string  $rptype  返回类型
 *            $rptype = 0 表示仅替换 html标记
 *            $rptype = 1 表示替换 html标记同时去除连续空白字符
 *            $rptype = 2 表示替换 html标记同时去除所有空白字符
 *            $rptype = -1 表示仅替换 html危险的标记 
 * @return    string
 */
if ( ! function_exists('HtmlReplace'))
{
    function HtmlReplace($str,$rptype=0)
    {
        $str = stripslashes($str);
		$str = preg_replace("/<[\/]{0,1}style([^>]*)>(.*)<\/style>/i", '', $str);//2011-06-30 禁止会员投稿添加css样式 (by:织梦的鱼)
        if($rptype==0)
        {
            $str = htmlspecialchars($str);
        }
        else if($rptype==1)
        {
            $str = htmlspecialchars($str);
            $str = str_replace("　", ' ', $str);
            $str = preg_replace("/[\r\n\t ]{1,}/", ' ', $str);
        }
        else if($rptype==2)
        {
            $str = htmlspecialchars($str);
            $str = str_replace("　", '', $str);
            $str = preg_replace("/[\r\n\t ]/", '', $str);
        }
        else
        {
            $str = preg_replace("/[\r\n\t ]{1,}/", ' ', $str);
            $str = preg_replace('/script/i', 'ｓｃｒｉｐｔ', $str);
            $str = preg_replace("/<[\/]{0,1}(link|meta|ifr|fra)[^>]*>/i", '', $str);
        }
        return addslashes($str);
    }
}

 
 
/**
 *  修复浏览器XSS hack的函数
 *
 * @param     string   $val  需要处理的内容
 * @return    string
 */
if ( ! function_exists('RemoveXSS'))
{
    function RemoveXSS($val) {
       $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
       $search = 'abcdefghijklmnopqrstuvwxyz';
       $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $search .= '1234567890!@#$%^&*()';
       $search .= '~`";:?+/={}[]-_|\'\\';
       for ($i = 0; $i < strlen($search); $i++) {
          $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
          $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
       }

       $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
       $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
       $ra = array_merge($ra1, $ra2);

       $found = true; 
       while ($found == true) {
          $val_before = $val;
          for ($i = 0; $i < sizeof($ra); $i++) {
             $pattern = '/';
             for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                   $pattern .= '(';
                   $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                   $pattern .= '|';
                   $pattern .= '|(&#0{0,8}([9|10|13]);)';
                   $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
             }
             $pattern .= '/i';
             $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
             $val = preg_replace($pattern, $replacement, $val); 
             if ($val_before == $val) {
                $found = false;
             }
          }
       }
       return $val;
    }
}

/**
 *  处理禁用HTML但允许换行的内容
 *
 * @access    public
 * @param     string  $msg  需要过滤的内容
 * @return    string
 */
if ( ! function_exists('TrimMsg'))
{
    function TrimMsg($msg)
    {
        $msg = trim(stripslashes($msg));
        $msg = nl2br(htmlspecialchars($msg));
        $msg = str_replace("  ","&nbsp;&nbsp;",$msg);
        return addslashes($msg);
    }
}

/**
 *  过滤用于搜索的字符串
 *
 * @param     string  $keyword  关键词
 * @return    string
 */
if ( ! function_exists('FilterSearch'))
{
    function FilterSearch($keyword)
    {
        global $cfg_soft_lang;
        if($cfg_soft_lang=='utf-8')
        {
            $keyword = preg_replace("/[\"\r\n\t\$\\><']/", '', $keyword);
            if($keyword != stripslashes($keyword))
            {
                return '';
            }
            else
            {
                return $keyword;
            }
        }
        else
        {
            $restr = '';
            for($i=0;isset($keyword[$i]);$i++)
            {
                if(ord($keyword[$i]) > 0x80)
                {
                    if(isset($keyword[$i+1]) && ord($keyword[$i+1]) > 0x40)
                    {
                        $restr .= $keyword[$i].$keyword[$i+1];
                        $i++;
                    }
                    else
                    {
                        $restr .= ' ';
                    }
                }
                else
                {
                    if(preg_match("/[^0-9a-z@#\.]/",$keyword[$i]))
                    {
                        $restr .= ' ';
                    }
                    else
                    {
                        $restr .= $keyword[$i];
                    }
                }
            }
        }
        return $restr;
    }
}

