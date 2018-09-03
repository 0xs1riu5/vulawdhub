<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 下载说明标签
 *
 * @version        $Id: softmsg.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>下载说明</name>
<type>软件内容模板</type>
<for>V55,V56,V57</for>
<description>下载说明标签</description>
<demo>
{dede:softmsg /}
</demo>
<attributes>
</attributes> 
>>dede>>*/
 
function lib_softmsg(&$ctag,&$refObj)
{
    global $dsql;
    //$attlist="type|textall,row|24,titlelen|24,linktype|1";
    //FillAttsDefault($ctag->CAttribute->Items,$attlist);
    //extract($ctag->CAttribute->Items, EXTR_SKIP);
    $revalue = '';
    $row = $dsql->GetOne(" SELECT * FROM `#@__softconfig` ");
    if(is_array($row)) $revalue = $row['downmsg'];
    return $revalue;
}