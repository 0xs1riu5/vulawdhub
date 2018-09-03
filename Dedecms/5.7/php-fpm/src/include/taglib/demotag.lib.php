<?php
if(!defined('DEDEINC')){
    exit("Request Error!");
}
/**
 * 这仅是一个演示标签
 *
 * @version        $Id: demotag.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>演示标签</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>这仅是一个演示标签</description>
<demo>
{dede:demotag /}
</demo>
<attributes>
</attributes> 
>>dede>>*/
 
function lib_demotag(&$ctag,&$refObj)
{
    global $dsql,$envs;
    
    //属性处理
    $attlist="row|12,titlelen|24";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    $revalue = '';
    
    //你需编写的代码，不能用echo之类语法，把最终返回值传给$revalue
    //------------------------------------------------------
    
    $revalue = 'Hello Word!';
    
    //------------------------------------------------------
    return $revalue;
}