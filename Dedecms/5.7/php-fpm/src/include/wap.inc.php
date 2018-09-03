<?php
if(!defined('DEDEINC')) exit('Request Error!');

require_once(DEDEINC.'/channelunit.class.php');

//---------------------------------------
// Html 标记WAP语言
//----------------------------------------
function html2wml($content)
{
     //保留图片
     preg_match_all("/<img([^>]*)>/isU", $content, $imgarr);
     if(isset($imgarr[0]) && count($imgarr[0])>0 )
     {
         foreach($imgarr[0] as $k=>$v) $content = str_replace($v, "WAP-IMG::{$k}", $content);
     }
     // 过滤掉样式表和脚本
     $content = preg_replace("/<style .*?<\/style>/is", "", $content);
     $content = preg_replace("/<script .*?<\/script>/is", "", $content);
     // 首先将各种可以引起换行的标签（如<br />、<p> 之类）替换成换行符"\n"
     $content = preg_replace("/<br \s*\/?\/>/i", "\n", $content);
     $content = preg_replace("/<\/?p>/i", "\n", $content);
     $content = preg_replace("/<\/?td>/i", "\n", $content);
     $content = preg_replace("/<\/?div>/i", "\n", $content);
     $content = preg_replace("/<\/?blockquote>/i", "\n", $content);
     $content = preg_replace("/<\/?li>/i", "\n", $content);
     // 将"&nbsp;"替换为空格
     $content = preg_replace("/\&nbsp\;/i", " ", $content);
     $content = preg_replace("/\&nbsp/i", " ", $content);
     // 过滤掉剩下的 HTML 标签
     $content = strip_tags($content);
     // 将 HTML 中的实体（entity）转化为它所对应的字符
     $content = html_entity_decode($content, ENT_QUOTES, "GB2312");
     // 过滤掉不能转化的实体（entity）
     $content = preg_replace('/\&\#.*?\;/i', '', $content);
     // 上面是将 HTML 网页内容转化为带换行的纯文本，下面是将这些纯文本转化为 WML。
     $content = str_replace('$', '$$', $content);
     $content = str_replace("\r\n", "\n", htmlspecialchars($content));
     $content = explode("\n", $content);
     for ($i = 0; $i < count($content); $i++)
     {
        $content[$i] = trim($content[$i]);
        // 如果去掉全角空格为空行，则设为空行，否则不对全角空格过滤。
        if (str_replace('　', '', $content[$i]) == '') $content[$i] = '';
     }
     $content = str_replace("<p><br /></p>\n", "", '<p>'.implode("<br /></p>\n<p>", $content)."<br /></p>\n");
     
     //还原图片
     if(isset($imgarr[0]) && count($imgarr[0])>0 )
     {
                foreach($imgarr[0] as $k=>$v)
                {
                    $attstr = (preg_match('#/$#', $imgarr[1][$k])) ? '<img '.$imgarr[1][$k].'>' : '<img '.$imgarr[1][$k].' />';
                    $content = str_replace("WAP-IMG::{$k}", $attstr, $content);
                }
     }
     
     $content = preg_replace("/&amp;[a-z]{3,10};/isU", ' ', $content);
     
     return $content;
}

function text2wml($content)
{
     $content = str_replace('$', '$$', $content);
     $content = str_replace("\r\n", "\n", htmlspecialchars($content));
     $content = explode("\n", $content);
     for ($i = 0; $i < count($content); $i++)
     {
        // 过滤首尾空格
        $content[$i] = trim($content[$i]);
        // 如果去掉全角空格为空行，则设为空行，否则不对全角空格过滤。
        if (str_replace("　", "", $content[$i]) == "") $content[$i] = "";
     }
     //合并各行，转化为 WML，并过滤掉空行
     $content = str_replace("<p><br /></p>\n", "", "<p>".implode("<br /></p>\n<p>", $content)."<br /></p>\n");
     return $content;
}

//----------------------
//把GBK字符转换成UTF8
//----------------------
function ConvertCharset($varlist)
{
    global $cfg_soft_lang;
    if(preg_match('#utf#i',$cfg_soft_lang)) return 0;
    $varlists = explode(',',$varlist);
    $numargs=count($varlists);
    for($i = 0; $i < $numargs; $i++)
    {   
        if(isset($GLOBALS[$varlists[$i]]))
        {
            $GLOBALS[$varlists[$i]] = gb2utf8($GLOBALS[$varlists[$i]]);
        }
    } 
    return 1;
}

//----------------------
//处理特殊字符
//----------------------
function ConvertStr($str)
{
    $str = str_replace("&amp;","##amp;",$str);
    $str = str_replace("&","&amp;",$str);
    $str = preg_replace("#[\"><']#","",$str);
    $str = str_replace("##amp;","&amp;",$str);
    return $str;
}

?>
