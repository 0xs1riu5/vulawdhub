<?php
/**
 * 采集指定页面作为文章发布源
 *
 * @version        $Id: inc_coonepage.php 1 10:32 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEINC.'/charset.func.php');

/**
 *  获取一个页面
 *
 * @access    public
 * @param     string  $gurl  操作地址
 * @return    string
 */
function CoOnePage($gurl)
{
    global $dsql,$cfg_auot_description, $cfg_soft_lang;
    $redatas = array('title' => '','body' => '','source' => '','writer' => '','description' => '','keywords' => '');
    $redatas['source'] = preg_replace("/http:\/\//i","",$gurl);
    $redatas['source'] = preg_replace("/\/(.*)$/i","",$redatas['source']);
    $row = $dsql->GetOne("SELECT * FROM `#@__co_onepage` WHERE url LIKE '".$redatas['source']."' ");
    $s = $e = '';
    if(is_array($row))
    {
        list($s,$e) = explode('{@body}',$row['rule']);
        $s = trim($s);
        $e = trim($e);
        if($row['issource']==1)
        {
            $redatas['source'] = $row['title'];
        }
    }
    $htd = new DedeHttpDown();
    $htd->OpenUrl($gurl);
    $body = $htd->GetHtml();
    if($body!='')
    {
        //编码自动转换
        if($cfg_soft_lang=='utf-8')
        {
            if($row['lang']=='gb2312')
            {
                $body = gb2utf8($body);
            }
        }
        else if($cfg_soft_lang=='gb2312')
        {
            if($row['lang']=='utf-8')
            {
                $body = utf82gb($body);
            }
        }

        //获取标题
        $inarr = array();
        preg_match("/<title>(.*)<\/title>/isU",$body,$inarr);
        if(isset($inarr[1]))
        {
            $redatas['title'] = $inarr[1];
        }

        //获取关键词
        $inarr = array();
        preg_match("/<meta[\s]+name=['\"]keywords['\"] content=['\"](.*)['\"]/isU",$body,$inarr);
        if(isset($inarr[1]))
        {
            $redatas['keywords'] = cn_substr(html2text($inarr[1]),30);
        }

        //获取摘要
        $inarr = array();
        preg_match("/<meta[\s]+name=['\"]description['\"] content=['\"](.*)['\"]/isU",$body,$inarr);
        if(isset($inarr[1]))
        {
            $redatas['description'] = cn_substr(html2text($inarr[1]),$cfg_auot_description);
        }

        //获取内容
        if($s!='' && $e!='')
        {
            $redatas['body'] = GetHtmlAreaA($s,$e,$body);
            if($redatas['body']!='' && $redatas['description']=='')
            {
                $redatas['description'] = cn_substr(html2text($redatas['body']),$GLOBALS['cfg_auot_description']);
            }
        }
    }
    return $redatas;
}

/**
 *  获取特定区域的HTML
 *
 * @access    public
 * @param     string  $s  开始标识符
 * @param     string  $e  末尾标识符
 * @param     string  $html  文档信息
 * @return    string
 */
function GetHtmlAreaA($s, $e, &$html)
{
    if($html==""||$s=="")
    {
        return "";
    }
    $posstart = @strpos($html,$s);
    if($posstart === FALSE)
    {
        return "";
    }
    $posend = strpos($html, $e, $posstart);
    if($posend > $posstart && $posend !== FALSE)
    {
        return substr($html, $posstart+strlen($s), $posend-$posstart-strlen($s));
    }else
    {
        return '';
    }
}