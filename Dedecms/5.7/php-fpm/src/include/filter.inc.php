<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 过滤核心处理文件
 *
 * @version        $Id: filter.inc.php 1 15:59 2010年7月5日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  过滤不相关内容
 *
 * @access    public
 * @param     string  $fk 过滤键
 * @param     string  $svar 过滤值
 * @return    string
 */
function _FilterAll($fk, &$svar)
{
    global $cfg_notallowstr,$cfg_replacestr;
    if( is_array($svar) )
    {
        foreach($svar as $_k => $_v)
        {
            $svar[$_k] = _FilterAll($fk,$_v);
        }
    }
    else
    {
        if($cfg_notallowstr!='' && preg_match("#".$cfg_notallowstr."#i", $svar))
        {
            ShowMsg(" $fk has not allow words!",'-1');
            exit();
        }
        if($cfg_replacestr!='')
        {
            $svar = preg_replace('/'.$cfg_replacestr.'/i', "***", $svar);
        }
    }
    return $svar;
}

/* 对_GET,_POST,_COOKIE进行过滤 */
foreach(Array('_GET','_POST','_COOKIE') as $_request)
{
    foreach($$_request as $_k => $_v)
    {
        ${$_k} = _FilterAll($_k,$_v);
    }
}