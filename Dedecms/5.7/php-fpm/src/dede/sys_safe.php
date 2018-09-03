<?php
/**
 * 系统密码提示问
 *
 * @version        $Id: sys_safe.php 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Safe');
$safeconfigfile = DEDEDATA."/safe/inc_safe_config.php";
if(empty($dopost)) $dopost = "";

if($dopost == "save")
{
    $configstr = $shortname = "";
    $gdopen = empty($gdopen)? "" : $gdopen;
    $codetype = empty($codetype)? 1 : $codetype;
    $gdtype = empty($gdtype)? 1 : $gdtype;
    $gdstyle = empty($gdstyle)? "" : $gdstyle;
    $gd_wwidth = empty($gd_wwidth)? 0 : $gd_wwidth;
    $gd_wheight = empty($gd_wheight)? 0 : $gd_wheight;
    $codelen = empty($codelen)? 4 : $codelen;
    $gdfaq_reg = empty($gdfaq_reg)? 0 : $gdfaq_reg;
    $gdfaq_send = empty($gdfaq_send)? 0 : $gdfaq_send;
    $gdfaq_msg = empty($gdfaq_msg)? 0 : $gdfaq_msg;

    if(is_array($gdopen))
    {
        $configstr = "\$safe_gdopen = '".implode(",",$gdopen)."';\r\n";
    } else {
        $configstr = "\$safe_gdopen = '';\r\n";
    }
    $configstr .= "\$safe_codetype = '".$codetype."';\r\n";
    $configstr .= "\$safe_gdtype = '".$gdtype."';\r\n";
    if(is_array($gdstyle))
    {
        $configstr .= "\$safe_gdstyle = '".implode(",",$gdstyle)."';\r\n";
    } else {
        $configstr .= "\$safe_gdstyle = '';\r\n";
    }
    $configstr .= "\$safe_wwidth = '".$gd_wwidth."';\r\n";
    $configstr .= "\$safe_wheight = '".$gd_wheight."';\r\n";
    $configstr .= "\$safe_codelen = '".$codelen."';\r\n";
    $configstr .= "\$safe_faq_reg = '".$gdfaq_reg."';\r\n";
    $configstr .= "\$safe_faq_send = '".$gdfaq_send."';\r\n";
    $configstr .= "\$safe_faq_msg = '".$gdfaq_msg."';\r\n";
    
    //保存问答数组
    $faqs = array();
    for ($i = 1; $i <= count($question)-1; $i++) 
    {
        $val = trim($question[$i]);
        if($val)
        {
            $faqs[$i]['question'] = str_replace("'","\"",stripslashes($val));
            $faqs[$i]['answer'] = stripslashes(trim($answer[$i]));
        }
    }
    
    //print_r($question);exit();
    $configstr .= "\$safe_faqs = '".serialize($faqs)."';\r\n";
    $configstr = "<"."?php\r\n".$configstr."?".">\r\n";
    
    $fp = fopen($safeconfigfile, "w") or die("写入文件 $safeconfigfile 失败，请检查权限！");
    fwrite($fp, $configstr);
    fclose($fp);
    ShowMsg("修改配置成功！","sys_safe.php");
    exit;
}

require_once($safeconfigfile);
$safefaqs = unserialize($safe_faqs);
include DedeInclude('templets/sys_safe.htm');