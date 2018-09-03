<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 单页文档调用标签
 *
 * @version        $Id: likesgpage.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>单页文档调用标签</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>单页文档调用标签</description>
<demo>
{dede:likespage row=''/}
</demo>
<attributes>
    <iterm>row:调用条数</iterm> 
</attributes> 
>>dede>>*/
 
function lib_likesgpage(&$ctag,&$refObj)
{
    global $dsql;

    //把属性转为变量，如果不想进行此步骤，也可以直接从 $ctag->CAttribute->Items 获得，这样也可以支持中文名
    $attlist="row|8";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    $innertext = trim($ctag->GetInnerText());

    $aid = (isset($refObj->Fields['aid']) ? $refObj->Fields['aid'] : 0);

    $revalue = '';
    if($innertext=='') $innertext = GetSysTemplets("part_likesgpage.htm");

    $likeid = (empty($refObj->Fields['likeid']) ?  'all' : $refObj->Fields['likeid']);

    $dsql->SetQuery("SELECT aid,title,filename FROM `#@__sgpage` WHERE likeid LIKE '$likeid' LIMIT 0,$row");
    $dsql->Execute();
    $ctp = new DedeTagParse();
    $ctp->SetNameSpace('field','[',']');
    $ctp->LoadSource($innertext);
    while($row = $dsql->GetArray())
    {
        if($aid != $row['aid'])
        {
            $row['url'] = $GLOBALS['cfg_cmsurl'].'/'.$row['filename'];
            foreach($ctp->CTags as $tagid=>$ctag) {
                if(!empty($row[$ctag->GetName()])) $ctp->Assign($tagid,$row[$ctag->GetName()]);
            }
            $revalue .= $ctp->GetResult();
        }
        else
        {
            $revalue .= '<dd class="cur"><span>'.$row['title'].'</span></dd>';
        }
    }
    return $revalue;
}