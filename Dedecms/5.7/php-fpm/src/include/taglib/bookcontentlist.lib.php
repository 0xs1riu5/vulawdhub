<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 连载图书最新内容调用
 *
 * @version        $Id: bookcontentlist.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>连载内容</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>连载图书最新内容调用</description>
<demo>
{dede:bookcontentlist row='12' booktype='-1' orderby='lastpost' author='' keyword=''}
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
<td width='40%'>
[[field:cataloglink/]] [field:booklink/]</td>
<td width='40%'>[field:contentlink/]</td>
<td width='20%'>[field:lastpost function="GetDateMk(@me)"/]</td>
</tr>
</table>
{/dede:bookcontentlist} 
</demo>
<attributes>
    <iterm>row:调用记录条数</iterm> 
    <iterm>booktype:图书类型，0 图书、1 漫画，默认全部</iterm>
    <iterm>orderby:排序类型，当按排序类型为 commend 表示推荐图书</iterm>
    <iterm>author:作者</iterm>
    <iterm>keyword:关键字</iterm>
</attributes> 
>>dede>>*/
 
require_once(DEDEINC.'/taglib/booklist.lib.php');

function lib_bookcontentlist(&$ctag, &$refObj)
{
    global $dsql, $envs, $cfg_dbprefix, $cfg_cmsurl;

    $attlist="row|12,booktype|-1,titlelen|30,orderby|lastpost,author|,keyword|";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    if( !$dsql->IsTable("{$cfg_dbprefix}story_books") ) return '没安装连载模块';
    
    return lib_booklist($ctag, $refObj, 1);
    
}